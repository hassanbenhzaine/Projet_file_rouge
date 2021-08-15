<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
 {
        return [
            'title' => $this->faker->sentence(6, true),
            'description' => $this->faker->paragraph(6, true) . '<br><br><br></br><ul><li><span>NVIDIA GeForce RTX 30 Series Graphics for Stunning Visuals: Built on NVIDIA’s award-winning 2nd-gen RTX architecture, these GPUs provide the most realistic ray-traced graphics and cutting-edge AI features for the most powerful graphics in a gaming laptop</span></li><li><span>Whether gaming or creating, AMD Ryzen processors offer ultimate performance: AMD Ryzen 5000 Series processors power the next generation of demanding games, providing one of a kind immersive experiences and dominate any multithreaded task like 3D and video rendering3, and software compiling</span></li><li><span>Next-gen Displays to Meet Your Needs: 165Hz QHD display for the best of both worlds in fast gaming and ultra clear display</span></li><li><span>Vapor Chamber Cooling for Maximized Thermal Performance: The laptop quietly and efficiently dissipates heat through the evaporation and condensation of an internal fluid, keeping it running smoothly and coolly even under intense loads</span></li><li><span>Customizable RGB Individual Key Lighting: Illuminates in sync with Razer Chroma-supported peripherals and popular games with multiple lighting effects</span></li></ul>',
            'price' => $this->faker->randomFloat(2, 0, 500),
            'category_id' => Category::inRandomOrder()->first()->id,
            'condition' => $this->faker->randomElement(['used', 'new', 'refurbished']),
            'photos' => json_encode(['a.jpg','b.jpg','c.jpg','d.jpg','e.jpg'])
        ];
    }
}
