<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function store(User $user)
    {
        abort_if($user->id === Auth::id(), 403);

        $user->followers()->syncWithoutDetaching([Auth::id()]);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $user->followers()->detach(Auth::id());

        return redirect()->back();
    }
}
