<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Stock;
use App\Models\Adress;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \DB::beginTransaction();
        Adress::factory(30)->create();

        User::factory()->create([
            'name' => "Erwan SORIA",
            'birthday' => "1998-01-26",
            'email' => "erwan.soria@gmail.com",
            'adresse_livraison_id' => 1,
            'adresse_facturation_id' => 1,
            'phone' => "+33 652501448",
            'password' => Hash::make('Bossdu76*'),
        ]);
        User::factory(10)->create();
        
        for ($i=1;$i<=10;$i++) {
            Article::factory()->create([
                'img_path' => '/storage/img/articles/article' . $i . '.jpg', // ou le chemin approprié pour vos images
            ]);
        }

        Stock::factory(10)->create();

        

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
