<?php

namespace App\Traits;
use Illuminate\Support\Facades\Log;

trait SlugTrait
{
    public function log($message)
    {
        Log::info($message);  // Menulis log ke file Laravel
    }
}
