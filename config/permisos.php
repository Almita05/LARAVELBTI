<?php

/*
| Catálogo único de permisos de la matriz de "Gestión de personal".
| Lo usan las dos tablas de permisos (alta y edición). Cada módulo admite las acciones:
| ver · crear (crear/editar) · eliminar · consultar. Las rutas lo validan con el middleware "permiso".
| Al agregar un módulo nuevo: añadirlo aquí y proteger sus rutas con ->middleware('permiso:<modulo>').
*/

return [
    'acciones' => [
        'ver' => 'Ver',
        'crear' => 'Crear/Editar',
        'eliminar' => 'Eliminar',
        'consultar' => 'Consultar',
    ],

    'grupos' => [
        [
            'titulo' => 'GENERAL',
            'icono' => 'fa-solid fa-gear me-1',
            'modulos' => [
                'inicio' => 'Inicio (Dashboard)',
                'notificaciones' => 'Avisos y Pendientes',
                'busqueda_grupos' => 'Búsqueda de Grupo',
            ],
        ],
        [
            'titulo' => 'ALUMNOS',
            'icono' => 'fa-solid fa-graduation-cap me-1 text-success',
            'modulos' => [
                'alumnos_list' => 'Listado de Alumnos',
                'alumnos_calificaciones' => 'Captura de Calificaciones',
                'alumnos_asistencias' => 'Asistencia Alumnos',
                'alumnos_reporte_asistencias' => 'Reporte de Asistencias',
            ],
        ],
        [
            'titulo' => 'DOCENTES',
            'icono' => 'fa-solid fa-person-chalkboard me-1 text-info',
            'modulos' => [
                'docentes_list' => 'Listado de Docentes',
                'docentes_asistencias' => 'Asistencia Docente',
                'docentes_horarios' => 'Horario Docente',
                'docentes_permisos_captura' => 'Permisos de Captura',
            ],
        ],
        [
            'titulo' => 'GRUPOS',
            'icono' => 'fa-solid fa-users me-1 text-secondary',
            'modulos' => [
                'grupos_list' => 'Inicio Grupos',
                'grupos_calificaciones' => 'Captura Calificaciones',
                'grupos_horarios' => 'Horarios',
            ],
        ],
        [
            'titulo' => 'MATERIAS',
            'icono' => 'fa-solid fa-book-bookmark me-1 text-danger',
            'modulos' => [
                'materias' => 'Materias',
            ],
        ],
        [
            'titulo' => 'PLANES DE ESTUDIO',
            'icono' => 'fa-solid fa-briefcase me-1',
            'estilo' => 'color: #6f42c1;',
            'modulos' => [
                'planes_bti' => 'Planes BTI',
                'planes_bgne' => 'Planes BGNE',
            ],
        ],
        [
            'titulo' => 'IMPRIMIR FORMATOS',
            'icono' => 'fa-solid fa-print me-1 text-danger',
            'modulos' => [
                'formatos_boletas_bti' => 'Boletas BTI',
                'formatos_kardex_bgne' => 'Kardex BGNE',
                'formatos_actas_extraordinarios' => 'Actas Extraordinarios',
                'formatos_listas_asistencia' => 'Listas de Asistencia',
                'formatos_actas_ordinarios' => 'Actas Calificaciones O.',
            ],
        ],
        [
            'titulo' => 'GENERACIONES',
            'icono' => 'fa-solid fa-graduation-cap me-1 text-dark',
            'modulos' => [
                'generaciones' => 'Generaciones',
            ],
        ],
    ],
];
