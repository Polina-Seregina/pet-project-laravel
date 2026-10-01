<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Tests\TestCase;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class ProfileTest extends TestCase
{
    /**
     * Проверяет успешный показ профиля пользователя.
     */
    public function test_users_profile_showed(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->get('/profile');

        $response->assertStatus(200);

    }

    /**
     * Проверяет возможность корректировать данные профиля пользователя.
     */
    public function test_users_profile_data_changable(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->patch('/profile', [
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->firstName(),
            'birthday' => fake()->unique()->date(),
            'email' => fake()->unique()->email(),
        ]);

        $response->assertStatus(302);

    }

    /**
     * Проверает отображение формы редактирования профиля пользователя.
     */
    public function test_user_profile_editing_page_showed(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->get('/profile/edit');

        $response->assertStatus(200);
    }

    /**
     * Проверяет результат удаления профиля пользователя.
     */
    public function test_deleting_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/profile');
        $response->assertRedirect();
    }

    /**
     * Обновление профиля с занятым email. Обновление профиля текущим email.
     */

    public function test_update_profile_with_a_busy_email(): void
    {
        User::factory()->create(['email' => 'busy@gmail.com']);

        $user = User::factory()->create(['email' => 'base@gmail.com']);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->firstName(),
            'email' => 'busy@gmail.com',
        ]);

        $response->assertInvalid(['email']);
        $this->assertEquals('base@gmail.com', $user->fresh()->email);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->firstName(),
            'email' => 'base@gmail.com',
        ]);
        
        $response->assertRedirect(route('profile.edit'))->assertSessionHas('status', 'profile-updated');
    }
    /**
     * Обновление профиля с занятым nickname.
     */

    public function test_update_profile_with_a_busy_nickname(): void
    {
        $profile = Profile::factory()->create(['nickname' => 'firstNickname']);

        Profile::factory()->create(['nickname' => 'busyName']);

        $response = $this->actingAs($profile->user)->patch(route('profile.update'), [
            'name' => fake()->unique()->name(),
            'nickname' => 'busyName',
            'email' => fake()->unique()->email(),
        ]);

        $response->assertInvalid('nickname');
        $this->assertEquals('firstNickname', $profile->fresh()->nickname);
    }

    /**
     * Обновление профиля текущим nickname.
     */

    public function test_update_profile_with_current_nickname(): void
    {
        $profile = User::withoutEvents(function () {
            return Profile::factory()->create([
                'nickname' => 'firstNickname',
            ]);
        });
        
        $response = $this->actingAs($profile->user)->patch(route('profile.update'), [
            'name' => fake()->unique()->name(),
            'nickname' => 'firstNickname',
            'email' => fake()->unique()->email(),
        ]);

        $response->assertRedirect(route('profile.edit'))->assertSessionHas('status', 'profile-updated');
    }

    /**
     * Сброс email_verified_at при смене email.
     */

    public function test_reset_verification_when_changing_email(): void
    {
        $user = User::factory()->create([
            'email' => fake()->unique()->email(),
        ]);

        $this->assertNotNull($user->email_verified_at);

        $this->actingAs($user)->patch(route('profile.update'), [
            'email' => fake()->unique()->email(),
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->firstName(),
        ]);
        
        $this->assertNull($user->email_verified_at);
    }

    /**
     * Загрузка файла, не являющегося изображением, в качестве аватара.
     */

    public function test_upload_file_as_avatar(): void
    {
        $avatar = UploadedFile::fake()->create('avatar.pdf');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'email' => fake()->unique()->email(),
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->firstName(),
            'avatar' => $avatar,
        ]);
        
        $response->assertInvalid('avatar');
    }

    /**
     * У пользователя отсутствует Profile — граничный случай. В ProfileUpdateRequest::rules() 
     * вызывается $this->user()->profile()->first()->id, поэтому при отсутствии профиля произойдёт 
     * обращение к id у null. Необходимо либо зафиксировать текущее поведение тестом, либо 
     * предусмотреть корректную обработку.
     */

    public function test_update_user_without_profile(): void
    {
        $user = User::withoutEvents(function () {
            return User::factory()->create();
        });

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'email' => fake()->unique()->email(),
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->firstName(),
        ]);

        $response->assertInternalServerError();
    }
}
