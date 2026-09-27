<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Enums\ProductsStatus;
use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use App\Services\BuyProductService;
use TypeError;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Throwable;

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
            'status' => ProductsStatus::FORSALE->value,
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

    /**
     * Покупка арта, при недостаточном балансе кошелька у покупателя.
     */

    public function test_user_cant_buy_product_without_money(): void
    {
        $sellerWallet = Wallet::factory()->create(['balance' => 0]);
        $buyerWallet = Wallet::factory()->create(['balance' => rand(0, 500)]);
        $product = Product::factory()->create(['user_id' => $sellerWallet->user->id, 'price' => $buyerWallet->balance + 1]);

        $this->actingAs($buyerWallet->user)->post(route('products.buy', $product));

        $this->assertDatabaseMissing('orders', [
            'seller_id' => $sellerWallet->user->id,
            'buyer_id' => $buyerWallet->user->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals($sellerWallet->balance, 0);
        $this->assertEquals($product->status->value, ProductsStatus::FORSALE->value);
    }

    /**
     * Проверяет, что невозможно купить Товар, который не находится в статусе for_sale.
     */

    public function test_product_without_for_sale_status_cannot_be_purchased(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $product = Product::factory()->for($seller)->create(['price' => 10, 'user_id' => $seller->id, 'status' => ProductsStatus::DRAFT->value]);

        Wallet::factory()->create(['user_id' => $buyer->id, 'balance' => 100]);
        Wallet::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($buyer)->post(route('products.buy', $product));

        $this->assertEquals($buyer->wallet->balance, 100);
        $this->assertDatabaseMissing('orders', [
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Покупка собственного товара.
     */

    public function test_purchasing_art_that_user_already_owns(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('products.buy', $product));

        $response->assertRedirect(route('products.show', ['product' => $product]));
        $response->assertSessionHas('status', 'Этот арт уже принадлежит тебе.');
    }

    /**
     * Покупка, при отстутсвии кошелька у продавца или у покупателя.
     */

    public function test_purchasing_art_if_wallet_does_not_exist(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($buyer)->post(route('products.buy', $product));
        $response->assertInternalServerError();
    }

    /**
     * Покупка несуществующего или удалённого товара.
     */

    public function test_purchasing_non_existent_or_deleted_art(): void
    {
        $user = User::factory()->create();
        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer)->post('/products/0/buy');
        $response->assertNotFound();

        $product = Product::factory()->create(['user_id' => $user->id ]);
        $this->actingAs($user)->delete(route('products.destroy', $product));

        $response = $this->actingAs($buyer)->post(route('products.buy', $product));
        $response->assertNotFound();
    }

    /**
     * Одновременная покупка одного товара двумя покупателями.
     */

    public function test_synchronous_purchase_of_single_product_by_two_buyers(): void
    {
        $seller = User::factory()->create();
        $buyerOne = User::factory()->create();
        $buyerTwo = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $seller->id]);
        $productForOne = Product::find($product->id);
        $productForTwo = Product::find($product->id);

        $sellerWallet = Wallet::factory()->create(['user_id' => $seller->id]);
        Wallet::factory()->create(['user_id' => $buyerOne->id, 'balance' => $product->price + 100]);
        $buyerTwoWallet = Wallet::factory()->create(['user_id' => $buyerTwo->id, 'balance' => $product->price + 100]);

        $service = new BuyProductService();
        $service->purchase($productForOne, $buyerOne, $seller);
        
        $this->assertThrows(
            fn () => $service->purchase($productForTwo, $buyerTwo, $seller),
            TypeError::class
        );

        $this->assertDatabaseCount('orders', 1);

        $this->assertDatabaseMissing('products', [
            'user_id' => $buyerTwo->id,
            'status' => ProductsStatus::PURCHASED->value,
        ]);

        $this->assertEquals($buyerTwoWallet->refresh()->balance, $product->price + 100);
    }

}
