<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $sub_folder_id = $request->sub_folder_id;

        if ($sub_folder_id) {
            $files = auth()->user()->files()->where('parent_id', $sub_folder_id)->orderBy('is_folder','desc')->latest()->paginate(30);
        } else {
            $files = auth()->user()->files()->whereNull('parent_id')->orderBy('is_folder','desc')->latest()->paginate(30);
        }

        return view('panel.file-manager.index', compact('files','sub_folder_id'));
    }

    public function createFolder(Request $request)
    {
        if (File::where(['name' => $request->folder_name, 'parent_id' => $request->sub_folder_id, 'is_folder' => 1])->first()) {
            return response()->json([
                'error' => true,
                'message' => 'پوشه ای با همین نام موجود است'
            ]);
        }

        File::create([
            'user_id' => auth()->id(),
            'name' => $request->folder_name,
            'parent_id' => $request->sub_folder_id,
            'is_folder' => 1,
        ]);

        return back();
    }

    public function uploadFile(Request $request)
    {
        if ($request->duplicated_files_action) {
            $duplicated_files_names = array_unique(explode(',', $request->duplicated_files_names));

            if ($request->duplicated_files_action == 'override') {
                if (count($duplicated_files_names)) {
                    $files_path = File::whereIn('name', $duplicated_files_names)->pluck('path')->toArray();
                    foreach ($files_path as $path) {
                        unlink(public_path($path));
                    }

                    File::whereIn('name', $duplicated_files_names)->delete();
                }

                foreach ($request->files as $file) {
                    $this->createFile($file, $request->sub_folder_id);
                }
            } else {
                foreach ($request->files as $file) {
                    if (!in_array($file->getClientOriginalName(), $duplicated_files_names)) {
                        $this->createFile($file, $request->sub_folder_id);
                    }
                }
            }
        } else {
            foreach ($request->files as $file) {
                $this->createFile($file, $request->sub_folder_id);
            }
        }
    }

    private function createFile($file, $sub_folder_id)
    {
        File::create([
            'user_id' => auth()->id(),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'is_folder' => 0,
            'parent_id' => $sub_folder_id,
            'path' => upload_file($file, 'FileManager/'.auth()->id()),
        ]);
    }

    public function delete(Request $request)
    {
        if ($files = File::whereIn('id', $request->checked_files)->where('is_folder', 0)->get()) {
            foreach ($files as $file) {
                unlink(public_path($file->path));
            }
        }
        File::whereIn('id', $request->checked_files)->delete();

        return back();
    }
}
