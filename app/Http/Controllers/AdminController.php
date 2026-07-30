<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Возвращает список всех зарегистрированных пользователей.
     */
    public function showUsersList(): View
    {
        return view('admin.index', [
            'users' => User::paginate(),
            ]);
    }

    /**
     * Возвращает страницу конкретного Пользователя с формой для изменения роли.
     */

    public function showUser(Request $request, User $user): View
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
     * Изменение Роли у пользователя. Замена 'admin' на 'user', и наоборот.
     */

    public function changeRole(Request $request, User $user)
    {
        if ($user->email !== config('app.admin-email')) {
            $user->removeRole($user->getRoleNames());
            $user->assignRole($request['role']);
            return Redirect::route('admin.show', ['user' => $user])->with('status', 'Роль пользователя изменена успешно.');
        }

        return Redirect::route('admin.show', ['user' => $user])->with('status', "Роль пользователя не может быть изменена.");
    }

}
