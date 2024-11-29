<?php

namespace Tests\Unit\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user for authentication
        $this->adminUser = User::factory()->create([
            'role' => 'Master'
        ]);
    }

    /** @test */
    public function it_can_display_category_index_page()
    {
        // Create some categories
        Category::factory()->count(3)->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.categories.index');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function it_can_show_create_category_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('master.categories.create');
    }

    /** @test */
    public function it_can_show_specific_category_page()
    {
        $category = Category::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('categories.show', $category));

        $response->assertStatus(200);
        $response->assertViewIs('master.categories.show');
        $response->assertViewHas('category', $category);
    }

    /** @test */
    public function it_can_create_a_new_category()
    {
        // Authenticate the user
        $this->actingAs($this->adminUser);

        $categoryData = [
            'name' => 'New Category',
            'description' => 'A test category description'
        ];

        $response = $this->post(route('categories.store'), $categoryData);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category created successfully.');

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
            'description' => 'A test category description',
            'created_by' => $this->adminUser->id
        ]);
    }

    /** @test */
    public function it_validates_category_creation_with_invalid_data()
    {
        $this->actingAs($this->adminUser);

        $invalidCategoryData = [
            'name' => '', // Empty name
            'description' => str_repeat('a', 500) // Too long description
        ];

        $response = $this->post(route('categories.store'), $invalidCategoryData);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_can_show_edit_category_page()
    {
        $category = Category::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('master.categories.edit');
        $response->assertViewHas('category', $category);
    }

    /** @test */
    public function it_can_update_category_information()
    {
        $category = Category::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $updatedData = [
            'name' => 'Updated Category Name',
            'description' => 'Updated description'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('categories.update', $category), $updatedData);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category updated successfully.');

        $category->refresh();
        $this->assertEquals('Updated Category Name', $category->name);
        $this->assertEquals('Updated description', $category->description);
        $this->assertEquals($this->adminUser->id, $category->updated_by);
    }

    /** @test */
    public function it_validates_category_update_with_invalid_data()
    {
        $category = Category::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $invalidData = [
            'name' => '', // Empty name
            'description' => str_repeat('a', 500) // Potentially too long description
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('categories.update', $category), $invalidData);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Category::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category deleted successfully.');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
