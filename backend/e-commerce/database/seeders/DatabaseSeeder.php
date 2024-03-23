<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \DB::beginTransaction();

        User::factory(10)->create();

        for ($i=1;$i<=10;$i++) {
            Article::factory()->create([
                'img_path' => '/storage/img/articles/article' . $i . '.jpg', // ou le chemin approprié pour vos images
            ]);
        }
        

        try {
            // Vos opérations de création de données
            
            \DB::commit();
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            \DB::rollBack();
            throw $e;
        }
        
    }
}
