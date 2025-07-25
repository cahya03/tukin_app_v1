<?php

namespace app\Http\Controllers;

use App\Models\User;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\ActivityLogService;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        try {
            $users = User::with('satker')->get();
            $satkers = Satker::pluck(DB::raw("CONCAT(kode_satker, ' - ', nama_satker)"), 'kode_satker');
            
            // Log activity untuk akses halaman users
            ActivityLogService::log(
                ActivityLogService::VIEW_USER,
                'Mengakses halaman daftar users',
                request(),
                ['total_users' => $users->count()]
            );
            
            return view('admin.users.index', compact('users', 'satkers'));
        } catch (\Exception $e) {
            ActivityLogService::log(
                ActivityLogService::VIEW_USER,
                'Gagal mengakses halaman daftar users: ' . $e->getMessage(),
                request(),
                ['error' => $e->getMessage()]
            );
            
            return redirect()->back()->with('error', 'Gagal memuat data users.');
        }
    }
    
    public function create()
    {
        try {
            $satkers = Satker::pluck('kode_satkers', 'id');
            
            // Log activity untuk akses halaman create user
            ActivityLogService::log(
                ActivityLogService::CREATE_USER,
                'Mengakses halaman tambah user baru',
                request(),
                ['available_satkers' => $satkers->count()]
            );
            
            return view('admin.users.create', compact('satkers'));
        } catch (\Exception $e) {
            ActivityLogService::log(
                ActivityLogService::CREATE_USER,
                'Gagal mengakses halaman tambah user: ' . $e->getMessage(),
                request(),
                ['error' => $e->getMessage()]
            );
            
            return redirect()->back()->with('error', 'Gagal memuat halaman tambah user.');
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:juru_bayar,admin',
                'kode_satker' => $request->role === 'juru_bayar'? 'required|exists:satkers,kode_satker' : 'nullable',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'kode_satker' => $request->role === 'juru_bayar' ? $request->kode_satker : null,
            ]);

            // Log activity untuk berhasil membuat user
            ActivityLogService::log(
                ActivityLogService::CREATE_USER,
                'Berhasil membuat user baru: ' . $user->name,
                $request,
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'kode_satker' => $user->kode_satker
                ]
            );

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log activity untuk validasi gagal
            ActivityLogService::log(
                ActivityLogService::CREATE_USER,
                'Validasi gagal saat membuat user: ' . json_encode($e->errors()),
                $request,
                [
                    'validation_errors' => $e->errors(),
                    'input_data' => $request->only(['name', 'email', 'role', 'kode_satker'])
                ]
            );
            
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // Log activity untuk error lainnya
            ActivityLogService::log(
                ActivityLogService::CREATE_USER,
                'Gagal membuat user: ' . $e->getMessage(),
                $request,
                [
                    'error' => $e->getMessage(),
                    'input_data' => $request->only(['name', 'email', 'role', 'kode_satker'])
                ]
            );
            
            return redirect()->back()->with('error', 'Gagal membuat user baru.')->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $userData = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'kode_satker' => $user->kode_satker
            ];
            
            $user->delete();
            
            // Log activity untuk berhasil menghapus user
            ActivityLogService::log(
                ActivityLogService::DELETE_USER,
                'Berhasil menghapus user: ' . $userData['user_name'],
                request(),
                $userData
            );
            
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
            
        } catch (\Exception $e) {
            // Log activity untuk gagal menghapus user
            ActivityLogService::log(
                ActivityLogService::DELETE_USER,
                'Gagal menghapus user ID: ' . $user->id . ' - ' . $e->getMessage(),
                request(),
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage()
                ]
            );
            
            return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus user.');
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role' => 'required|in:juru_bayar,admin',
                'kode_satker' => $request->role === 'juru_bayar' ? 'required|exists:satkers,kode_satker' : 'nullable',
            ]);

            if ($validator->fails()) {
                ActivityLogService::log(
                    ActivityLogService::UPDATE_USER,
                    'Gagal memperbarui user ID: ' . $user->id . ' - Validasi gagal',
                    $request,
                    [
                        'user_id' => $user->id,
                        'validation_errors' => $validator->errors(),
                        'input_data' => $request->only(['name', 'email', 'role', 'kode_satker'])
                    ]
                );
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $oldData = $user->only(['name', 'email', 'role', 'kode_satker']);
            
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'kode_satker' => $request->role === 'juru_bayar' ? $request->kode_satker : null,
            ]);
            
            $changes = [];
            $newData = $request->only(['name', 'email', 'role', 'kode_satker']);
            foreach ($newData as $key => $value) {
                if ($oldData[$key] !== $value) {
                    $changes[$key] = [
                        'old' => $oldData[$key],
                        'new' => $value,
                    ];
                }
            }
            
            // Log activity untuk berhasil update user
            ActivityLogService::log(
                ActivityLogService::UPDATE_USER,
                'Berhasil memperbarui user: ' . $user->name,
                $request,
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'changes' => $changes,
                    'old_data' => $oldData,
                    'new_data' => $newData
                ]
            );
            
            // Gunakan method yang sudah ada jika masih diperlukan
            ActivityLogService::logUpdateUser($user->id, $changes);
            
            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
            
        } catch (\Exception $e) {
            // Log activity untuk gagal update user
            ActivityLogService::log(
                ActivityLogService::UPDATE_USER,
                'Gagal memperbarui user ID: ' . $user->id . ' - ' . $e->getMessage(),
                $request,
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage(),
                    'input_data' => $request->only(['name', 'email', 'role', 'kode_satker'])
                ]
            );
            
            return redirect()->back()->with('error', 'Gagal memperbarui user.')->withInput();
        }
    }
}