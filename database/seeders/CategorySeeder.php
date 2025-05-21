<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->delete();
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

        $defaultCategories = [
            ['name'=> 'Technique', 'description'=> 'Problèmes techniques'],
            ['name'=> 'Facturation', 'description'=> 'Problèmes de facturation'],
            ['name'=> 'Comptes', 'description'=> 'Problèmes de comptes utilisateur'],
            ['name'=> 'Fonctionnalité', 'description'=> 'Problèmes de fonctionnalité'],
        ];

        
        foreach($defaultCategories as $category) {
            Category::create($category);
        }

        Category::factory()->count(10)->create();
    }
}
