<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Возвращает стартовую страницу панели Админа.
     */
    public function showBasePanel(): View
    {
        return view('admin.panel');
    }

    /**
     * Возвращает список всех зарегистрированных пользователей с ролью "user".
     */
    public function showUsersList(): View
    {
        return view('admin.usersList', [
            'users' => User::role('user')->paginate(),
            'role' => 'user',
            ]);
    }

    /**
     * Возвращает список пользователей с ролью "admin".
     */
    public function showAdminsList(): View
    {
        return view('admin.usersList', [
            'users' => User::role('admin')->paginate(),
            'role' => 'admin',
            ]);
    }

    /**
     * Возвращает страницу конкретного юзера с формой для изменения роли.
     */

    public function showUser(Request $request, User $user): View
    {
        $avatar = $user->profile->avatar
            ? Storage::disk('s3')->url($user->profile->avatar)
            : asset(config('filesystems.default_avatar'));

        return view('admin.showUser', [
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
            return Redirect::route('admin.showUser', ['user' => $user])->with('status', 'Роль пользователя изменена успешно.');
        }

        return Redirect::route('admin.showUser', ['user' => $user])->with('status', "Роль пользователя не может быть изменена.");
    }

}
