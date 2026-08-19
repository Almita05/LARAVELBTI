<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                'max:51200',
            ],
        ], [
            'archivo_qrp.required' =>
                'Debes seleccionar un archivo QRP.',

            'archivo_qrp.file' =>
                'El archivo seleccionado no es válido.',

            'archivo_qrp.max' =>
                'El archivo no puede superar los 50 MB.',
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
                    'archivo_qrp' =>
                        'El archivo debe tener extensión .QRP.'
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
        // 5. SCRIPT PYTHON
        // ============================================

        $scriptPython = base_path(
            'python/qrp_parser.py'
        );


        // ============================================
        // 6. VERIFICAR PYTHON
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
        // 7. VERIFICAR QRP
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

            $python = 'python3';

            $comando =
                $python
                . ' '
                . escapeshellarg($scriptPython)
                . ' '
                . escapeshellarg($rutaCompleta);


            $salida = [];

            $codigoSalida = 0;

            exec(
                $comando . ' 2>&1',
                $salida,
                $codigoSalida
            );


            // ========================================
            // 9. OBTENER RESPUESTA DE PYTHON
            // ========================================

            $resultadoPython = implode(
                PHP_EOL,
                $salida
            );


            Log::info(
                'Respuesta del parser QRP',
                [
                    'codigo' => $codigoSalida,
                    'respuesta' => $resultadoPython
                ]
            );


            // ========================================
            // 10. ERROR DE PYTHON
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
            // 11. LIMPIAR RESPUESTA
            // ========================================
            //
            // Python debe devolver solamente JSON.
            // Eliminamos espacios/BOM que puedan venir
            // al principio o final de la respuesta.
            //

            $resultadoPython = trim(
                $resultadoPython
            );

            $resultadoPython = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $resultadoPython
            );


            // ========================================
            // 12. CONVERTIR JSON
            // ========================================

            $resultado = json_decode(
                $resultadoPython,
                true
            );


            // ========================================
            // 13. VALIDAR JSON
            // ========================================

            if (
                json_last_error() !== JSON_ERROR_NONE
            ) {

                Log::error(
                    'JSON inválido recibido desde Python',
                    [
                        'error' =>
                            json_last_error_msg(),

                        'respuesta' =>
                            $resultadoPython
                    ]
                );

                return back()
                    ->withErrors([
                        'archivo_qrp' =>
                            'Python no devolvió un JSON válido. '
                            . 'Error JSON: '
                            . json_last_error_msg()
                    ]);
            }


            // ========================================
            // 14. VALIDAR SUCCESS
            // ========================================

            if (
                !isset($resultado['success'])
                || $resultado['success'] !== true
            ) {

                $mensaje =
                    $resultado['message']
                    ?? 'No se pudo procesar el archivo QRP.';

                return back()
                    ->withErrors([
                        'archivo_qrp' => $mensaje
                    ]);
            }


            // ========================================
            // 15. OBTENER DATOS
            // ========================================

            $movimientos =
                $resultado['movimientos']
                ?? [];

            $resumen =
                $resultado['resumen']
                ?? [];


            // ========================================
            // 16. TOTAL DE MOVIMIENTOS
            // ========================================

            $totalMovimientos =
                $resultado['total_movimientos']
                ?? count($movimientos);


            // ========================================
            // 17. ELIMINAR ARCHIVO TEMPORAL
            // ========================================

            if (file_exists($rutaCompleta)) {

                unlink($rutaCompleta);
            }


            // ========================================
            // 18. REGRESAR A LA VISTA
            // ========================================

            return view(
                'qrp.analizar',
                [
                    'movimientos' =>
                        $movimientos,

                    'resumen' =>
                        $resumen,

                    'totalMovimientos' =>
                        $totalMovimientos,

                    'archivoProcesado' =>
                        $archivo->getClientOriginalName(),

                    'procesado' =>
                        true,
                ]
            );


        } catch (\Throwable $e) {

            // ========================================
            // ELIMINAR TEMPORAL
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
                    'error' =>
                        $e->getMessage(),

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