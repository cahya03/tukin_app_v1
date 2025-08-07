<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TunkinUploadStatus extends Component
{
    public $uploadStatus;
    public $year;

    public function __construct($uploadStatus, $year)
    {
        $this->uploadStatus = $uploadStatus;
        $this->year = $year;
    }

    public function render()
    {
        return view('components.tunkin-upload-status');
    }
}

