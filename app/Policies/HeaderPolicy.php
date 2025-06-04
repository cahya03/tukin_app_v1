<?php

namespace App\Policies;

use App\Models\Header;
use App\Models\User;

class HeaderPolicy
{

    public function view(User $user, Header $header)
    {
        // Admin bisa lihat semua, juru bayar hanya header dari satker-nya
        return $user->role === 'admin' || $header->kode_satker === $user->kode_satker;
    }

    public function create(User $user)
    {
        // Admin bisa membuat header, juru bayar hanya jika memenuhi syarat tertentu
        return $user->role === 'admin' || $user->role === 'juru_bayar';
    }

    public function update(User $user, Header $header)
    {
        // Admin bisa edit semua, juru bayar hanya header dari satker-nya
        return $user->role === 'admin' || $header->kode_satker === $user->kode_satker;
    }

    public function delete(User $user, Header $header)
    {
        // Admin bisa hapus semua, juru bayar hanya header dari satker-nya
        return $user->role === 'admin' || $header->kode_satker === $user->kode_satker;
    }
}
