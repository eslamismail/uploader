<?php

use App\Http\Controllers\FileController;
use App\Models\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // For generating random strings

Route::get('/', function () {
    $files = File::get();
    // return ;
    foreach ($files as $key => $file) {
        for ($i = 0; $i < 1; $i++) {
            $sourcePath = $file->file_path; // Path of the file to copy
            $randomFileName = Str::random(40); // Generates a 40-character random string
            $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
            $newFileName = $randomFileName . '.' . $extension;
            $destinationPath = 'files/' . $newFileName; // Destination path with random file name
            Storage::disk('public')->copy($sourcePath, $destinationPath);

            $data = [
                'file_name' => $destinationPath
            ];

            File::create($data);
        }

        // if ($key % 2 == 0) (new FileController())->destroy($file->id);
        // else continue;
    }

    return [
        File::count()
    ];
});
