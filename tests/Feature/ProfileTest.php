<?php

namespace Tests\Feature;

use App\Models\{User, Profile};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Проверяет успешный показ профиля пользователя.
     * @return void
     */

    public function test_users_profile_showed(): void 
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->get('/profile');

        $response->assertStatus(200);
    
    }

    /**
     * Проверяет возможность корректировать данные профиля пользователя.
     * @return void
     */

    public function test_users_profile_data_changable(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->patch('/profile', [
            'name' => fake()->name(),
            'nickname' => fake()->firstName(),
            'birthday' => fake()->date(),
            'email' => fake()->email(),
        ]);
        
        $response->assertStatus(302);

    }

    /**
     * Проверает отображение формы редактирования профиля пользователя.
     * @return void
     */

    public function test_user_profile_editing_page_showed(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->get('/profile/edit');

        $response->assertStatus(200);
    }

    /**
     * Проверяет результат удаления профиля пользователя.
     * @return void
     */

    public function test_deleting_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/profile');
        $response->assertRedirect();
    }
}
