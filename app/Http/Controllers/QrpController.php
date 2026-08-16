<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QrpController extends Controller
{
    /**
     * Muestra la pantalla principal del analizador QRP.
     */
    public function index()
    {
        return view('qrp.analizar');
    }

    /**
     * Recibe el archivo QRP y lo procesa mediante Python.
     */
    public function analizar(Request $request)
    {
        // ============================================
        // 1. VALIDAR ARCHIVO
        // ============================================

        $request->validate([
            'archivo_qrp' => [
                'required',
                'file',
                'max:51200', // 50 MB
            ],
        ], [
            'archivo_qrp.required' => 'Debes seleccionar un archivo QRP.',
            'archivo_qrp.file' => 'El archivo seleccionado no es válido.',
            'archivo_qrp.max' => 'El archivo no puede superar los 50 MB.',
        ]);


        // ============================================
        // 2. OBTENER ARCHIVO
        // ============================================

        $archivo = $request->file('archivo_qrp');


        // ============================================
        // 3. VALIDAR EXTENSIÓN
        // ============================================

        $extension = strtolower(
            $archivo->getClientOriginalExtension()
        );

        if ($extension !== 'qrp') {

            return back()
                ->withErrors([
                    'archivo_qrp' => 'El archivo debe tener extensión .QRP.'
                ])
                ->withInput();

        }


        // ============================================
        // 4. GUARDAR TEMPORALMENTE EL QRP
        // ============================================

        $nombreArchivo = uniqid('qrp_') . '.qrp';

        $rutaArchivo = $archivo->storeAs(
            'qrp_temp',
            $nombreArchivo,
            'local'
        );

        $rutaCompleta = storage_path(
            'app/private/' . $rutaArchivo
        );


        // ============================================
        // 5. UBICACIÓN DEL SCRIPT PYTHON
        // ============================================

        $scriptPython = base_path(
            'python/qrp_parser.py'
        );


        // ============================================
        // 6. VERIFICAR QUE EXISTA PYTHON
        // ============================================

        if (!file_exists($scriptPython)) {

            return back()
                ->withErrors([
                    'archivo_qrp' =>
                        'No se encontró el archivo Python: '
                        . $scriptPython
                ]);

        }


        // ============================================
        // 7. VERIFICAR QUE EXISTA EL QRP
        // ============================================

        if (!file_exists($rutaCompleta)) {

            return back()
                ->withErrors([
                    'archivo_qrp' =>
                        'No se pudo encontrar el archivo QRP temporal.'
                ]);

        }


        // ============================================
        // 8. EJECUTAR PYTHON
        // ============================================

        try {

            /*
             * En Windows normalmente funciona:
             *
             *     python
             *
             * Si posteriormente Laravel no encuentra Python,
             * podremos colocar aquí la ruta completa de python.exe.
             */

            $python = 'python';


            /*
             * Escapamos las rutas para evitar problemas
             * con espacios en nombres de carpetas.
             */

            $comando =
                $python
                . ' '
                . escapeshellarg($scriptPython)
                . ' '
                . escapeshellarg($rutaCompleta);


            // Ejecutar Python

            $salida = [];

            $codigoSalida = 0;

            exec(
                $comando . ' 2>&1',
                $salida,
                $codigoSalida
            );


            // Convertir salida de consola a texto

            $resultadoPython = implode(
                PHP_EOL,
                $salida
            );


            // ========================================
            // 9. REGISTRAR RESPUESTA DE PYTHON
            // ========================================

            Log::info(
                'Respuesta del parser QRP',
                [
                    'codigo' => $codigoSalida,
                    'respuesta' => $resultadoPython
                ]
            );


            // ========================================
            // 10. VERIFICAR ERROR DE PYTHON
            // ========================================

            if ($codigoSalida !== 0) {

                return back()
                    ->withErrors([
                        'archivo_qrp' =>
                            'Python devolvió un error: '
                            . $resultadoPython
                    ]);

            }


            // ========================================
            // 11. CONVERTIR JSON
            // ========================================

            $resultado = json_decode(
                $resultadoPython,
                true
            );


            // ========================================
            // 12. VALIDAR JSON
            // ========================================

            if (json_last_error() !== JSON_ERROR_NONE) {

                return back()
                    ->withErrors([
                        'archivo_qrp' =>
                            'Python no devolvió un JSON válido.'
                            . PHP_EOL
                            . 'Respuesta recibida: '
                            . $resultadoPython
                    ]);

            }


            // ========================================
            // 13. VALIDAR SUCCESS
            // ========================================

            if (
                !isset($resultado['success'])
                || $resultado['success'] !== true
            ) {

                $mensaje = $resultado['message']
                    ?? 'No se pudo procesar el archivo QRP.';

                return back()
                    ->withErrors([
                        'archivo_qrp' => $mensaje
                    ]);

            }


            // ========================================
            // 14. OBTENER DATOS
            // ========================================

            $movimientos = $resultado['movimientos']
                ?? [];

            $resumen = $resultado['resumen']
                ?? [];


            // ========================================
            // 15. TOTAL DE MOVIMIENTOS
            // ========================================

            $totalMovimientos = $resultado[
                'total_movimientos'
            ] ?? count($movimientos);


            // ========================================
            // 16. ELIMINAR ARCHIVO TEMPORAL
            // ========================================

            if (file_exists($rutaCompleta)) {

                unlink($rutaCompleta);

            }


            // ========================================
            // 17. REGRESAR A LA VISTA
            // ========================================

            return view(
                'qrp.analizar',
                [
                    'movimientos' => $movimientos,

                    'resumen' => $resumen,

                    'totalMovimientos' =>
                        $totalMovimientos,

                    'archivoProcesado' =>
                        $archivo->getClientOriginalName(),

                    'procesado' => true,
                ]
            );


        } catch (\Throwable $e) {

            // ========================================
            // ELIMINAR TEMPORAL SI OCURRIÓ ERROR
            // ========================================

            if (
                isset($rutaCompleta)
                && file_exists($rutaCompleta)
            ) {

                unlink($rutaCompleta);

            }


            // ========================================
            // REGISTRAR ERROR
            // ========================================

            Log::error(
                'Error procesando archivo QRP',
                [
                    'error' => $e->getMessage(),
                    'archivo' =>
                        $archivo->getClientOriginalName()
                ]
            );


            // ========================================
            // MOSTRAR ERROR
            // ========================================

            return back()
                ->withErrors([
                    'archivo_qrp' =>
                        'Ocurrió un error al procesar el QRP: '
                        . $e->getMessage()
                ]);

        }
    }
}