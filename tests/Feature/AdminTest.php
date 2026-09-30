<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * Просмотр admin.show пользователем с заполненным аватаром.
     */

    public function test_view_admin_show_page_by_the_user_with_avatar(): void
    {
        Storage::fake('s3');

        $avatar = UploadedFile::fake()->create('avatar.jpg');
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();

        $path = Storage::disk('s3')->putFile("{$user->id}", $avatar);
        $user->profile->avatar = $path;
        $user->profile->save();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.show', $user));
        $response->assertOk();
    }

    /**
     * Проверяет возможность пользователя без административных прав получить доступ к административным страницам.
     */

    public function test_that_not_admin_try_to_get_access_to_admin_pages(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.index'));
        $response->assertRedirect(route('profile.show'))
            ->assertSessionHas('status', 'Не хватает прав для выполнения предыдущих действий.');

        $response = $this->actingAs($user)->get(route('admin.show', $user));
        $response->assertRedirect(route('profile.show'))
            ->assertSessionHas('status', 'Не хватает прав для выполнения предыдущих действий.');

        $response = $this->actingAs($user)->patch(route('admin.update', $user));
        $response->assertRedirect(route('profile.show'))
            ->assertSessionHas('status', 'Не хватает прав для выполнения предыдущих действий.');
    }

    /**
     * Попытка изменить роль главного администратора.
     */

    public function test_change_the_role_of_the_main_admin(): void
    {
        $user = User::factory()->create(['email' => config('app.admin-email')]);
        $user->assignRole('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->patch(route('admin.update', $user));
        $response->assertRedirect(route('admin.show', $user))->assertSessionHas('status', "Роль пользователя не может быть изменена.");
        $this->assertEquals(collect(['admin']), $user->getRoleNames());
    }

    /**
     * Проверяет назначение несуществующей роли пользователю.
     **/

    public function test_assigning_an_invalid_role(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->patch(route('admin.update', $user), ['role' => 'manager']);
        $response->assertInvalid(['role']);
    }
    
}
