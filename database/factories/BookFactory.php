<?php
namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'author' => $this->faker->name(),
            'year' => $this->faker->numberBetween(1995, date('Y')),
            'pages' => $this->faker->numberBetween(120, 800),
            'description' => $this->faker->text(10),
        ];
    }
}
