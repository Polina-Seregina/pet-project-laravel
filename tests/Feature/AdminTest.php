<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * Просмотр admin.show пользователем с заполненным аватаром — проверить отображение страницы 
     * для пользователя, у которого установлен аватар. Этот тест должен выявить текущую ошибку с 
     * Storage::disk('s3'), связанную с отсутствующим use.
     */
    public function test_view_admin_show_page_by_the_user_with_avatar(): void
    {
        $avatar = UploadedFile::fake()->create('avatar.jpg', 100);
        $user = User::factory()->create();
        Profile::factory()
            ->for($user)
            ->create([
                'avatar' => 'avatars/avatar.jpg',
            ]);

        $user->refresh();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.show', $user));
        $response->assertOk();
    }
    
    /**
     * Не-админ пытается получить доступ к административным страницам — 
     * запросы к /admin/users, /admin/{user} и PATCH /admin/{user} должны 
     * быть перехвачены middleware admin (UserIsAdmin) и перенаправлены на profile.show. 
     * Сейчас административная функциональность вообще не покрыта тестами.
     */

    public function test_that_not_admin_try_to_get_access_to_admin_pages(): void
    {
        //
    }
}
