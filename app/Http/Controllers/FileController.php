<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $tableName = 'files';
        $rowCount = DB::table($tableName)->count();

        // If the table is empty, reset AUTO_INCREMENT value
        if ($rowCount == 0) {
            // Use a raw query to alter the table and set AUTO_INCREMENT to 1
            DB::statement("ALTER TABLE `$tableName` AUTO_INCREMENT = 1;");
        }

        return response()->json([
            'message' => 'file has been deleted successfully'
        ]);
    }
}
