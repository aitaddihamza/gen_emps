<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SalleSeeder::class);
        $this->call(ClasseSeeder::class);
        $this->call(ModuleSeeder::class); // Doit être exécuté avant ProfesseurSeeder
        $this->call(ProfesseurSeeder::class);



        // create admin
        User::factory(1)->create([
            'name' => 'moufid',
            'email' => 'moufid@suptech.ma',
            'role' => 'admin',
        ]);
        // create 10 profs users
        // User::factory(10)->create();

    }
}
