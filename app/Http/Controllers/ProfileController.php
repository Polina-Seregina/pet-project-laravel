<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Возвращает страницу с отображением профиля пользователя.
     */

    public function show(Request $request): View
    {
        $avatar = $request->user()->profile->avatar
            ? Storage::disk('s3')->url($request->user()->profile->avatar)
            : asset(config('filesystems.default_avatar'));

        return view('profile.show', [
            'user' => $request->user(),
            'avatar' => $avatar,
        ]);
    }

    /**
     * Отображает форму редактирования профиля.
     */
    public function edit(Request $request): View
    {
        $avatar = $request->user()->profile->avatar
            ? Storage::disk('s3')->url($request->user()->profile->avatar)
            : asset(config('filesystems.default_avatar'));

        return view('profile.edit', [
            'user' => $request->user(),
            'avatar' => $avatar,
        ]);
    }

    /**
     * Обновление информации о пользователе в профиле.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $request->user()->profile;

        $userValidData = Arr::only($request->validated(), $user->getFillable());
        $profileValidData = Arr::only($request->validated(), $profile->getFillable());

        $user->fill($userValidData);
        $profile->fill($profileValidData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $name = $file->getClientOriginalName();
            $path = Storage::disk('s3')->putFile("avatars/{$request->user()->id}/{$name}", $file);
            $request->user()->profile->avatar = $path;
        }

        $request->user()->save();
        $request->user()->profile->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Удаление профиля.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
