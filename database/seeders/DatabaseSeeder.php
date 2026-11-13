<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * (Se quitó el "Test User" de ejemplo: con db:seed en producción habría creado
     *  una cuenta test@example.com con contraseña conocida.)
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
