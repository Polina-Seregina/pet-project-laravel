<?php

namespace Tests\Feature;

use App\Models\{User, Profile};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_profile_showed() //метод show
    {
        $user = User::factory()->create(); 
        $profile = Profile::factory()->for($user)->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    
    }

    public function test_users_profile_name_changable() // дописать на метод update
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->for($user)->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'nickname' => 'tester',
            'birthday' => '11.11.1111',
            'email' => 'test@mail.ru',
        ]);
        
        $response->assertStatus(302);

    }

    public function test_user_profile_editing_page_chowed() // 
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->for($user)->create();

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertStatus(200);
    }

    public function test_deleting_user()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete('/profile');
        $response->assertRedirect();
    }
}
