<?php

namespace Tests\Unit\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $category;
    protected $ingredients;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user for authentication
        $this->adminUser = User::factory()->create([
            'role' => 'Master'
        ]);

        // Create a category for testing
        $this->category = Category::factory()->create([
            'created_by' => $this->adminUser->id
        ]);

        // Create some ingredients for testing
        $this->ingredients = Ingredient::factory()->count(3)->create([
            'created_by' => $this->adminUser->id
        ]);
    }

    /** @test */
    public function it_can_display_recipe_index_page()
    {
        // Create some recipes
        Recipe::factory()->count(3)->create([
            'category_id' => $this->category->id,
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('recipes.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.recipes.index');
        $response->assertViewHas('recipes');
    }

    /** @test */
    public function it_can_show_specific_recipe_page()
    {
        $recipe = Recipe::factory()->create([
            'category_id' => $this->category->id,
            'created_by' => $this->adminUser->id
        ]);

        // Attach ingredients to the recipe
        $ingredientsData = $this->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'quantity' => rand(1, 10)
            ];
        })->toArray();

        foreach ($ingredientsData as $ingredientData) {
            $recipe->ingredients()->attach(
                $ingredientData['id'],
                ['quantity' => $ingredientData['quantity']]
            );
        }

        $response = $this->actingAs($this->adminUser)
            ->get(route('recipes.show', $recipe));

        $response->assertStatus(200);
        $response->assertViewIs('master.recipes.show');
        $response->assertViewHas('recipe', $recipe);
    }

    /** @test */
    public function it_can_show_create_recipe_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('recipes.create'));

        $response->assertStatus(200);
        $response->assertViewIs('master.recipes.create');
        $response->assertViewHas('categories');
        $response->assertViewHas('ingredients');
    }

    /** @test */
    public function it_can_create_a_new_recipe()
    {
        $this->actingAs($this->adminUser);

        $recipeData = [
            'name' => 'Delicious Dish',
            'description' => 'A tasty recipe',
            'category_id' => $this->category->id,
            'ingredients' => $this->ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'quantity' => rand(1, 10)
                ];
            })->toArray()
        ];

        $response = $this->post(route('recipes.store'), $recipeData);

        $response->assertRedirect(route('recipes.index'));
        $response->assertSessionHas('success', 'Recipe created successfully.');

        // Check recipe was created in database
        $this->assertDatabaseHas('recipes', [
            'name' => 'Delicious Dish',
            'description' => 'A tasty recipe',
            'category_id' => $this->category->id,
            'created_by' => $this->adminUser->id
        ]);

        // Check recipe ingredients were created
        $recipe = Recipe::where('name', 'Delicious Dish')->first();
        foreach ($recipeData['ingredients'] as $ingredient) {
            $this->assertDatabaseHas('recipe_ingredients', [
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient['id'],
                'quantity' => $ingredient['quantity']
            ]);
        }
    }

    /** @test */
    public function it_validates_recipe_creation_with_invalid_data()
    {
        $this->actingAs($this->adminUser);

        $invalidRecipeData = [
            'name' => '', // Empty name
            'category_id' => 9999, // Non-existent category
            'ingredients' => [] // Empty ingredients
        ];

        $response = $this->post(route('recipes.store'), $invalidRecipeData);

        $response->assertSessionHasErrors([
            'name',
            'category_id',
            'ingredients'
        ]);
    }

    /** @test */
    public function it_can_show_edit_recipe_page()
    {
        $recipe = Recipe::factory()->create([
            'category_id' => $this->category->id,
            'created_by' => $this->adminUser->id
        ]);

        // Attach ingredients to the recipe
        $ingredientsData = $this->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'quantity' => rand(1, 10)
            ];
        })->toArray();

        foreach ($ingredientsData as $ingredientData) {
            $recipe->ingredients()->attach(
                $ingredientData['id'],
                ['quantity' => $ingredientData['quantity']]
            );
        }

        $response = $this->actingAs($this->adminUser)
            ->get(route('recipes.edit', $recipe));

        $response->assertStatus(200);
        $response->assertViewIs('master.recipes.edit');
        $response->assertViewHas('recipe', $recipe);
        $response->assertViewHas('categories');
        $response->assertViewHas('ingredients');
    }

    /** @test */
    public function it_can_update_recipe_information()
    {
        $recipe = Recipe::factory()->create([
            'category_id' => $this->category->id,
            'created_by' => $this->adminUser->id
        ]);

        $updatedData = [
            'name' => 'Updated Recipe Name',
            'description' => 'Updated description',
            'category_id' => $this->category->id,
            'ingredients' => $this->ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'quantity' => rand(1, 10)
                ];
            })->toArray()
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('recipes.update', $recipe), $updatedData);

        $response->assertRedirect(route('recipes.index'));
        $response->assertSessionHas('success', 'Recipe updated successfully.');

        $recipe->refresh();
        $this->assertEquals('Updated Recipe Name', $recipe->name);
        $this->assertEquals('Updated description', $recipe->description);
        $this->assertEquals($this->adminUser->id, $recipe->updated_by);

        // Check updated ingredients
        foreach ($updatedData['ingredients'] as $ingredient) {
            $this->assertDatabaseHas('recipe_ingredients', [
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient['id'],
                'quantity' => $ingredient['quantity']
            ]);
        }
    }

    /** @test */
    public function it_can_delete_a_recipe()
    {
        $recipe = Recipe::factory()->create([
            'category_id' => $this->category->id,
            'created_by' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('recipes.destroy', $recipe));

        $response->assertRedirect(route('recipes.index'));
        $response->assertSessionHas('success', 'Recipe deleted successfully.');
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }
}
