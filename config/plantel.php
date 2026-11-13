<?php

/*
 * Datos institucionales que aparecen en los documentos oficiales (constancias, oficios…).
 * Se pueden sobrescribir desde .env sin tocar las vistas.
 */
return [
    'bti' => [
        'nombre'           => 'BACHILLERATO TECNOLÓGICO INTERAMERICANO',
        'encabezado'       => 'BACHILLERATO TECNOLOGICO INTERAMERICANO',
        'direccion'        => 'Avenida Benito Juárez # 901 Esq. León Guzmán, Col. Centro Teziutlán Pue. Tel. 231 - 3123979',
        'direccion_texto'  => 'AVENIDA JUÁREZ NO. 901 ESQUINA CON LEÓN GUZMÁN DE TEZIUTLÁN, PUEBLA',
        'clave_sep'        => '21PCT0073R',
        'rvoe'             => 'RVOE DGETI 200-2794',
        'clave_operativa'  => env('BTI_CLAVE_OPERATIVA', 'MSP21174.22'),
        'cct'              => '21PCT0073R',
        'ciudad'           => 'TEZIUTLÁN, PUEBLA',
        'director'         => env('BTI_DIRECTOR', 'ING. FAUSTO MAURO LEYVA FLORES'),
        'director_cargo'   => 'DIRECTOR DEL PLANTEL',
        'sistema'          => 'SISTEMA DE BACHILLERATO ESCOLARIZADO',
        'lema'             => '¡ Excelencia educativa a su servicio !',
        'logo'             => 'img/logo.png',
    ],
];
