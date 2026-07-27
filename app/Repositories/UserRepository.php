<?php

namespace App\Repositories;

use App\Services\GoogleSheetsService;

class UserRepository
{
    protected GoogleSheetsService $googleSheets;

    public function __construct(GoogleSheetsService $googleSheets)
    {
        $this->googleSheets = $googleSheets;
    }


    public function all()
    {
        return $this->googleSheets->getRows('Usuarios');
    }


    public function findByUsername(string $username)
    {
        $usuarios = $this->all();

        foreach ($usuarios as $usuario) {

            if ($usuario['Usuario'] === $username) {
                return $usuario;
            }

        }

        return null;
    }
}