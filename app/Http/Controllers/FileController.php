<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $files = File::get();

        return response()->json([
            'files' => $files
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_name' => 'required|file'
        ]);

        $data = [
            'file_name' => Storage::disk('public')->put('files', $request->file_name)
        ];

        File::create($data);

        return response()->json([
            'message' => 'file has been uploaded successfully'
        ]);
    }


    public function destroy($id)
    {
        $file = File::find($id);
        Storage::disk('public')->delete($file->file_path);
        $file->delete();
        return response()->json([
            'message' => 'file has been deleted successfully'
        ]);
    }
}
