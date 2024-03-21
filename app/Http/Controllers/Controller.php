<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected static function response($status, $data, $message = null)
    {
        return response()->json([
            'success'   =>  boolval($status == 200 || $status == 201 || $status == 202),
            'status'    =>  $status,
            'message'   =>  $message,
            'data'  =>  $data,
        ], $status);
    }

    protected function uploadImage($image)
    {
        $path = Storage::disk('public')->put('images', $image);
        $name = basename($path);

        return 'storage/images/' . $name;
    }

    protected function uploadDocument($doc)
    {
        $fileName = 'profile-'.time().'.'.$doc->getClientOriginalExtension();
        $path = $doc->storeAs('public/files', $fileName);

        return 'storage/files/' . $fileName;
    }

}
