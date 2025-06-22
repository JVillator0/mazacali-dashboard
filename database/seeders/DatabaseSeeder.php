<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShieldSeeder::class,
            UserSeeder::class,

            CategorySeeder::class,

            TableSeeder::class,
        ]);

        $this->defaultProductImage();
    }

    private function defaultProductImage(): void
    {
        $defaultImage = base_path('database/seeders/assets/images/default.png');
        $uniqueName = 'default.png';
        $destinationDir = public_path('storage/products');
        if (! file_exists($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }
        $destinationPath = $destinationDir.'/'.$uniqueName;
        if (file_exists($defaultImage)) {
            copy($defaultImage, $destinationPath);
        }
    }
}
