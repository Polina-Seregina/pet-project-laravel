<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
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

    public function show(Request $request, User $user): View
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

    public function update(Request $request, User $user)
    {
        $rolesArray = DB::table('roles')->get('name')->toArray();
        $roles = array_map(fn ($role) => $role->name, $rolesArray);
        $request->validate(['role' => [Rule::in($roles)]]);

        if ($user->email !== config('app.admin-email')) {
            $user->removeRole($user->getRoleNames());
            $user->assignRole($request['role']);
            return Redirect::route('admin.show', ['user' => $user])->with('status', 'Роль пользователя изменена успешно.');
        }

        return Redirect::route('admin.show', ['user' => $user])->with('status', "Роль пользователя не может быть изменена.");
    }

}
