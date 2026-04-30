<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_welcome_page_exsist(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
