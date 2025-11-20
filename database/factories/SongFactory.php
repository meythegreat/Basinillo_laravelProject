<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Song;
use App\Models\Genre;

class SongFactory extends Factory {
    protected $model = Song::class;
    public function definition() {
        return [
            'title' => $this->faker->sentence(3),
            'artist' => $this->faker->name(),
            'genre_id' => Genre::inRandomOrder()->first()?->id,
            'duration_seconds' => $this->faker->numberBetween(90,420),
            'release_year' => $this->faker->year(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}