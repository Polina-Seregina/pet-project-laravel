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
     * @return void
     */
    public function test_products_market_page_showed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);

    }

    /**
     * Проверяет отображение страницы с продуктами, принадлежащими пользователю.
     * @return void
     */
    public function test_my_products_page_showed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/my/products');
        $response->assertStatus(200);

    }

    /**
     * Проверяет, что страница конкретного арта отображается.
     * @return void
     */
    public function test_product_page_showed(): void
    {
        $product = Product::factory()->create();
        Profile::factory()->create(['user_id' => $product->user->id]);
        Profile::factory()->create(['user_id' => $product->author->id]);

        $response = $this->actingAs($product->user)->get(route("products.show", ['product' => $product]));
        $response->assertStatus(200);
    }

    /**
     * Проверяет отображение формы создания продукта.
     * @return void
     */
    public function test_create_form_showed(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products/create');
        $response->assertStatus(200);
    }

    /**
     * Проверяет, что Пользователь может создать арт.
     * @return void
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
            'status' => 'for_sale',
            'image' => $image,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => $name,
        ]);
    }
    /**
     * Проверяет, что Пользователь, являющийся автором и владелецем, может редактировать image,
     * а Пользователь владелец - нет.
     * @return void
     */

    public function test_author_can_edit_image(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id, 'author_id' => $user->id]);
        $newImage = UploadedFile::fake()->create('image.jpg');

        $response = $this->actingAs($user)->patch(route('products.update', ['product' => $product]), [
            'image' => $newImage,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'status' => ProductsStatus::FORSALE->value]);


        $product = Product::where(['id' => $product->id])->first();

        $this->assertEquals(basename($product->image), 'image.jpg');

    }

    /**
     * Проверяет, что Пользователь, не являющийся автором, не может редактировать image,
     * @return void
     */

    public function test_user_can_not_edit_image(): void
    {
        $user = User::factory()->create();
        $author = User::factory()->create();
        
        $product = Product::factory()->create(['user_id' => $user->id, 'author_id' => $author->id]);
        $oldImage = $product->image;
        $newImage = UploadedFile::fake()->create('image.jpg');

        $response = $this->actingAs($user)->patch(route('products.update', ['product' => $product]), [
            'image' => $newImage,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'status' => ProductsStatus::FORSALE->value]);

        $updatedProduct = Product::where(['id' => $product->id])->first();

        $this->assertEquals($oldImage, $updatedProduct->image);

    }

}
