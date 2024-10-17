<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated user cannot access product routes.
     */
    public function test_unauthenticated_user_cannot_access_products()
    {
        $response = $this->getJson('/api/v1/products');
        
        $response->assertStatus(401);
    }

    /**
     * Test authenticated user can view products.
     */
    public function test_authenticated_user_can_view_products()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'description', 'price'],
                     ],
                 ]);
    }

    /**
     * Test authenticated user can create a product.
     */
    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $productData = [
            'name' => 'New Product',
            'description' => 'Product Description',
            'price' => 1000.00,
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'New Product']);

        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test authenticated user can view a single product.
     */
    public function test_authenticated_user_can_view_single_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => $product->name,
                     'description' => $product->description,
                 ]);
    }

    /**
     * Test authenticated user can update a product.
     */
    public function test_authenticated_user_can_update_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated Description',
            'price' => 1500.00,
        ];

        $response = $this->putJson("/api/v1/products/{$product->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Product Name']);

        $this->assertDatabaseHas('products', $updatedData);
    }

    /**
     * Test authenticated user can delete a product.
     */
    public function test_authenticated_user_can_delete_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
