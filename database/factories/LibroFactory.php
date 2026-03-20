<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'titulo' => $this->faker->title(),
            'autor' => $this->faker->name(),
            'anho_publicacion' => $this->faker->year(),
            'editorial' => $this->faker->company(),
            'paginas' => $this->faker->numberBetween(100, 1000),
            'calificacion' => $this->faker->numberBetween(1,5),
            'inicio_lectura' => $this->faker->date(),
            'fin_lectura' => $this->faker->date(),
            'user_id' => 1,
            'resenha' => $this->faker->text(200),
            'estado' => $this->faker->randomElement(['pendiente', 'leyendo', 'leido']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
