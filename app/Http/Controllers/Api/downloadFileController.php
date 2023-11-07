<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medias;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class downloadFileController extends Controller
{
    public function download(Request $request){
        $request->validate([
            "id"=>"required|".Rule::exists("medias",'id')
        ]);

        $media = Medias::find($request->id);
        $imagePath = asset($media->filename);

        return response()->download(public_path($media->filename));
    }
}
