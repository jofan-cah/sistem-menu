<?php

namespace Tests\Unit\Controllers;

use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class IngredientControllerTest extends TestCase
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
    public function it_can_display_ingredient_index_page()
    {
        // Create some ingredients
        Ingredient::factory()->count(3)->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('ingredients.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.ingredients.index');
        $response->assertViewHas('ingredients');
    }

    /** @test */
    public function it_can_show_create_ingredient_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('ingredients.create'));

        $response->assertStatus(200);
        $response->assertViewIs('master.ingredients.create');
    }

    /** @test */
    public function it_can_create_a_new_ingredient()
    {
        // Authenticate the user
        $this->actingAs($this->adminUser);

        $ingredientData = [
            'name' => 'Salt',
            'unit' => 'gram'
        ];

        $response = $this->post(route('ingredients.store'), $ingredientData);

        $response->assertRedirect(route('ingredients.index'));
        $response->assertSessionHas('success', 'Ingredient created successfully.');

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Salt',
            'unit' => 'gram',
            'created_by' => $this->adminUser->id
        ]);
    }

    /** @test */
    public function it_validates_ingredient_creation_with_invalid_data()
    {
        $this->actingAs($this->adminUser);

        $invalidIngredientData = [
            'name' => '', // Empty name
            'unit' => '' // Empty unit
        ];

        $response = $this->post(route('ingredients.store'), $invalidIngredientData);

        $response->assertSessionHasErrors(['name', 'unit']);
    }

    /** @test */
    public function it_can_show_edit_ingredient_page()
    {
        $ingredient = Ingredient::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('ingredients.edit', $ingredient));

        $response->assertStatus(200);
        $response->assertViewIs('master.ingredients.edit');
        $response->assertViewHas('ingredient', $ingredient);
    }

    /** @test */
    public function it_can_update_ingredient_information()
    {
        $ingredient = Ingredient::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $updatedData = [
            'name' => 'Updated Ingredient Name',
            'unit' => 'kilogram'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('ingredients.update', $ingredient), $updatedData);

        $response->assertRedirect(route('ingredients.index'));
        $response->assertSessionHas('success', 'Ingredient updated successfully.');

        $ingredient->refresh();
        $this->assertEquals('Updated Ingredient Name', $ingredient->name);
        $this->assertEquals('kilogram', $ingredient->unit);
        $this->assertEquals($this->adminUser->id, $ingredient->updated_by);
    }

    /** @test */
    public function it_validates_ingredient_update_with_invalid_data()
    {
        $ingredient = Ingredient::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $invalidData = [
            'name' => '', // Empty name
            'unit' => '' // Empty unit
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('ingredients.update', $ingredient), $invalidData);

        $response->assertSessionHasErrors(['name', 'unit']);
    }

    /** @test */
    public function it_can_delete_an_ingredient()
    {
        $ingredient = Ingredient::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('ingredients.destroy', $ingredient));

        $response->assertRedirect(route('ingredients.index'));
        $response->assertSessionHas('success', 'Ingredient deleted successfully.');
        $this->assertDatabaseMissing('ingredients', ['id' => $ingredient->id]);
    }

    /** @test */
    public function it_prevents_creating_ingredient_with_long_name()
    {
        $this->actingAs($this->adminUser);

        $longNameIngredient = [
            'name' => str_repeat('a', 256), // Exceeds max length
            'unit' => 'gram'
        ];

        $response = $this->post(route('ingredients.store'), $longNameIngredient);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_prevents_creating_ingredient_with_long_unit()
    {
        $this->actingAs($this->adminUser);

        $longUnitIngredient = [
            'name' => 'Test Ingredient',
            'unit' => str_repeat('a', 51) // Exceeds max length
        ];

        $response = $this->post(route('ingredients.store'), $longUnitIngredient);

        $response->assertSessionHasErrors(['unit']);
    }
}
