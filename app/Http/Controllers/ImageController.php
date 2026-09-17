<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $image = $request->file('file');
        $imageName = Str::uuid().'.'.$image->extension();

        $manager = new ImageManager(new Driver);
        $imageServer = $manager->decode($image);
        $imageServer->cover(1000, 1000);

        $uploadsPath = public_path('uploads');
        if (! file_exists($uploadsPath)) {
            mkdir($uploadsPath, 0755, true);
        }

        $imagePath = $uploadsPath.'/'.$imageName;
        $imageServer->save($imagePath);

        return response()->json(['image' => $imageName]);
    }
}
