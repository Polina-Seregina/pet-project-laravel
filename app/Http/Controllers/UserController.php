<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Возвращает список всех зарегистрированных пользователей.
     */
    public function index(): View
    {
        return view('admin.index', [
            'users' => User::paginate(),
            ]);
    }

    /**
     * Возвращает страницу конкретного Пользователя с формой для изменения роли.
     */

    public function show(User $user): View
    {
        $avatar = $user->profile->avatar
            ? Storage::disk('s3')->url($user->profile->avatar)
            : asset(config('filesystems.default_avatar'));

        return view('admin.show', [
            'user' => $user,
            'avatar' => $avatar,
        ]);
    }

    /**
     * Обновление пользователя.
     */

    public function update(UserUpdateRequest $request, User $user)
    {
        $validData = $request->validated();

        if ($user->email !== config('app.admin-email')) {
            $user->removeRole($user->getRoleNames());
            $user->assignRole($validData['role']);
            return Redirect::route('admin.show', ['user' => $user])->with('status', 'Роль пользователя изменена успешно.');
        }

        return Redirect::route('admin.show', ['user' => $user])->with('status', "Роль пользователя не может быть изменена.");
    }

}
