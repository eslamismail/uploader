<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $appends = [
        'name',
        'file_size',
        'file_path'
    ];

    public function getNameAttribute()
    {
        $file = $this->attributes['file_name'];

        return explode('/', $file)[count(explode('/', $file)) - 1];
    }

    public function getFilePathAttribute()
    {
        $file = $this->attributes['file_name'];

        return $file;
    }

    public function getFileNameAttribute()
    {
        $file = $this->attributes['file_name'];


        if (Storage::disk('public')->exists($file))
            return Storage::disk('public')->url($file);
    }


    public function getFileSizeAttribute()
    {
        // Get the file name from the attributes array
        $file = $this->attributes['file_name'];

        // Check if the file exists in storage
        if (Storage::disk('public')->exists($file)) {
            // Return the size of the file in bytes
            return $this->formatSize(Storage::disk('public')->size($file));
        }
    }

    function formatSize($sizeInBytes)
    {
        // Define units for size conversion
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        // Initialize unit index
        $unitIndex = 0;

        // Convert the size while it's larger than or equal to 1024
        while ($sizeInBytes >= 1024 && $unitIndex < count($units) - 1) {
            $sizeInBytes /= 1024;
            $unitIndex++;
        }

        // Return the formatted size with two decimal places and the appropriate unit
        return round($sizeInBytes, 2) . ' ' . $units[$unitIndex];
    }
}
