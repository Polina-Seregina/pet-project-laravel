<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Enums\ProductsStatus;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * Проверяет отображение страницы магазина.
     */
    public function test_products_market_page_showed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);

    }

    /**
     * Проверяет отображение страницы с продуктами, принадлежащими пользователю.
     */
    public function test_my_products_page_showed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/my/products');
        $response->assertStatus(200);

    }

    /**
     * Проверяет, что страница конкретного арта отображается.
     */
    public function test_product_page_showed(): void
    {
        $product = Product::factory()->create();
        Profile::factory()->create(['user_id' => $product->user->id]);

        $response = $this->actingAs($product->user)->get("/products/{$product->id}");
        $response->assertStatus(200);
    }

    /**
     * Проверяет отображение формы создания продукта.
     */
    public function test_create_form_showed(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products/create');
        $response->assertStatus(200);
    }

    /**
     * Проверяет, что Пользователь может создать арт.
     */
    public function test_user_can_create_art(): void
    {
        $user = User::factory()->create();

        $name = fake()->name();
        $image = UploadedFile::fake()->create('image.jpg', 100);

        $response = $this->actingAs($user)->post('/products', [
            'name' => $name,
            'description' => fake()->realTextBetween(),
            'price' => fake()->numberBetween(0, 100000),
            'status' => ProductsStatus::FORSALE->label(),
            'image' => $image,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => $name,
        ]);
    }
}
