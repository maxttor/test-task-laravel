<?php

namespace App\Http\Controllers;

use App\File;
use App\Helpers\Result;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        return view('list', ['files' => File::all()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        $this->validate($request, ['url' => 'required|url']);

        File::create($request->only('url'));

        if ($request->wantsJson()) {
            return response()->json(Result::success());
        } else {
            return redirect()->route('index');
        }
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws FileNotFoundException
     */
    public function download($id)
    {
        $file = File::findOrFail($id);
        if (
            $file->status != File::STATUS_COMPLETE ||
            !Storage::exists($id)
        ) {
            throw new FileNotFoundException();
        }

        return response()->download(storage_path().'/app/files/'.$file->id, $file->name);
    }
}
