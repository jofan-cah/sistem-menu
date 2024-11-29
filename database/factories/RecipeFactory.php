<?php
// database/factories/RecipeFactory.php
namespace Database\Factories;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'description' => $this->faker->paragraph,
            'category_id' => Category::factory(),
            'created_by' => User::factory(),
        ];
    }
}
