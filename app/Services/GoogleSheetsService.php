<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $service;
protected $spreadsheetId;
protected $schoolSpreadsheetId;

   public function __construct()
{
    $client = new Client();

    $client->setApplicationName('Sistema Escolar');

    $client->setScopes([
        Sheets::SPREADSHEETS
    ]);

$client->setAuthConfig(
    base_path(config('services.google.credentials'))
);
    $this->service = new Sheets($client);

    // Hoja de accesos

    $this->spreadsheetId = config('services.google.sheet_id');

    // Hoja escolar (alumnos, materias, calificaciones y boletas)
    $this->schoolSpreadsheetId = config('services.google.school_sheet_id');
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

public function getSchoolRows(string $sheet): array
{
    $response = $this->service
        ->spreadsheets_values
        ->get($this->schoolSpreadsheetId, $sheet);

    $rows = $response->getValues();

    if (empty($rows)) {
        return [];
    }

    $headers = array_shift($rows);

    $data = [];

    foreach ($rows as $row) {

        $row = array_pad($row, count($headers), null);

        $data[] = array_combine($headers, $row);
    }

    return $data;
}

public function guardarCalificaciones($lista, $usuario)
{

    $sheet = "CALIFICACIONES";


    $data = $this->getSchoolRows($sheet);


    $usuarios = $this->getSchoolRows("USUARIOS");


    $esAdmin = false;


    foreach($usuarios as $user){

        if(
            strtolower(trim($user['USUARIO'])) ==
            strtolower(trim($usuario))
            &&
            strtoupper(trim($user['ROL'])) == "ADMIN"
        ){

            $esAdmin = true;
            break;

        }

    }



    foreach($lista as $dato){


        $fila = null;


        foreach($data as $index=>$registro){


            if(
    $registro['ID_ALUMNO'] == $dato['alumno_id']
    &&
    $registro['ID_MATERIA'] == $dato['materiaId']
){

                $fila = $index + 2;
                break;

            }

        }



        if(!$fila){
            continue;
        }



        $columnas = [
    'p1' => 3,
    'p2' => 4,
    'p3' => 5,
    'semestral' => 6,
    'extra' => 8,
];

if ($esAdmin) {

    foreach ($columnas as $campo => $col) {

        if (!array_key_exists($campo, $dato)) {
            continue;
        }

        $this->service->spreadsheets_values->update(
            $this->schoolSpreadsheetId,
            $sheet . "!" . $this->numeroColumna($col) . $fila,
            new \Google\Service\Sheets\ValueRange([
                'values' => [
                    [
                        $dato[$campo]
                    ]
                ]
            ]),
            [
                'valueInputOption' => 'USER_ENTERED'
            ]
        );
    }

} else {

    $columna = [
        "P1" => 3,
        "P2" => 4,
        "P3" => 5,
        "SEMESTRAL" => 6,
        "EXTRA" => 8
    ];

    $campo = $dato['parcial'];

    if (!isset($columna[$campo])) {
        continue;
    }

    $col = $columna[$campo];

    $actual = $this->service
        ->spreadsheets_values
        ->get(
            $this->schoolSpreadsheetId,
            $sheet . "!" . $this->numeroColumna($col) . $fila
        )
        ->getValues();

    $valorActual = $actual[0][0] ?? "";

    if ($valorActual !== "") {
        continue;
    }

    $this->service->spreadsheets_values->update(
        $this->schoolSpreadsheetId,
        $sheet . "!" . $this->numeroColumna($col) . $fila,
        new \Google\Service\Sheets\ValueRange([
            'values' => [
                [
                    $dato['calificacion']
                ]
            ]
        ]),
        [
            'valueInputOption' => 'USER_ENTERED'
        ]
    );
}
    }


    return "Calificaciones guardadas correctamente";

}

private function numeroColumna($numero)
{

    $letras = [
        1=>'A',
        2=>'B',
        3=>'C',
        4=>'D',
        5=>'E',
        6=>'F',
        7=>'G',
        8=>'H',
        9=>'I'
    ];


    return $letras[$numero];

}
}