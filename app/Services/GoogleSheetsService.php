<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $client = new Client();

        $client->setApplicationName('Sistema Escolar');

        $client->setScopes([
            Sheets::SPREADSHEETS
        ]);

        $client->setAuthConfig(base_path(env('GOOGLE_CREDENTIALS')));

        $this->service = new Sheets($client);

        $this->spreadsheetId = env('GOOGLE_SHEET_ID');
    }

    public function getRows(string $sheet): array
{
    $response = $this->service
        ->spreadsheets_values
        ->get($this->spreadsheetId, $sheet);

    $rows = $response->getValues();

    if (empty($rows)) {
        return [];
    }

    // Primera fila = encabezados
    $headers = array_shift($rows);

    $data = [];

    foreach ($rows as $row) {

        // Si faltan columnas las rellenamos con null
        $row = array_pad($row, count($headers), null);

        $data[] = array_combine($headers, $row);
    }

    return $data;
}
}