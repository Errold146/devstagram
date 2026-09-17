<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('post.index', $user->username)->with('mensaje', 'Perfil actualizado correctamente.');
    }

    public function updateImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $image = $request->file('file');
        $imageName = Str::uuid().'.webp';
        $profilesPath = public_path('profiles');

        if (! File::isDirectory($profilesPath)) {
            File::makeDirectory($profilesPath, 0755, true);
        }

        $imageServer = new ImageManager(new Driver)->decode($image);
        $imageServer->cover(500, 500);
        $imageServer->save($profilesPath.'/'.$imageName);

        $oldImage = $user->profile_image;
        $user->update(['profile_image' => $imageName]);

        if ($oldImage) {
            File::delete($profilesPath.'/'.$oldImage);
        }

        return response()->json([
            'image' => $imageName,
            'url' => asset('profiles/'.$imageName),
        ]);
    }
}
