<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Minuman (Hangat)',
            'Minuman (Dingin)',
            'Snacks',
        ];

        foreach ($genres as $genre) {
            Genre::firstOrCreate([
                'name' => $genre
            ]);
        }
    }
}