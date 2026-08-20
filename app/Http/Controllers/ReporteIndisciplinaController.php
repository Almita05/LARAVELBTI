<?php

namespace App\Http\Controllers;

use App\Models\ReporteIndisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReporteIndisciplinaController extends Controller
{
    /**
     * Display a listing of disciplinary reports.
     */
    public function index(Request $request)
    {
        $query = ReporteIndisciplina::orderBy('created_at', 'desc');

        if ($request->filled('id_alumno')) {
            $query->where('id_alumno', $request->id_alumno);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%{$search}%")
                  ->orWhere('alumno_nombre', 'like', "%{$search}%")
                  ->orWhere('tutor_nombre', 'like', "%{$search}%")
                  ->orWhere('incidente', 'like', "%{$search}%");
            });
        }

        $reportes = $query->paginate($request->limit ?? 15);

        return response()->json([
            'success' => true,
            'data' => $reportes->items(),
            'total' => $reportes->total(),
            'current_page' => $reportes->currentPage(),
            'last_page' => $reportes->lastPage()
        ]);
    }

    /**
     * Store a newly created disciplinary report in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_alumno' => 'required|integer',
            'alumno_nombre' => 'required|string',
            'incidente' => 'required|string',
            'parcial' => 'required|integer|in:1,2,3',
            'tutor_nombre' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Autogenerate Folio: REP-YYYY-XXXX (e.g. REP-2026-0001)
        $year = date('Y');
        $prefix = "REP-{$year}-";

        $lastReport = ReporteIndisciplina::where('folio', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReport) {
            $lastNumber = intval(substr($lastReport->folio, strlen($prefix)));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $folio = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $reporte = ReporteIndisciplina::create([
            'folio' => $folio,
            'id_alumno' => $request->id_alumno,
            'alumno_nombre' => $request->alumno_nombre,
            'tutor_nombre' => $request->tutor_nombre ?: 'S/N',
            'fecha' => date('Y-m-d'),
            'incidente' => $request->incidente,
            'parcial' => $request->parcial
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte de indisciplina registrado correctamente.',
            'data' => $reporte
        ], 201);
    }

    /**
     * Remove the specified disciplinary report from storage.
     */
    public function destroy($id)
    {
        $reporte = ReporteIndisciplina::find($id);

        if (!$reporte) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte no encontrado.'
            ], 404);
        }

        $reporte->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reporte eliminado correctamente.'
        ]);
    }

    /**
     * Get the count of disciplinary reports for a student grouped by partial.
     */
    public function getStudentReportsCount($id_alumno)
    {
        $counts = ReporteIndisciplina::where('id_alumno', $id_alumno)
            ->selectRaw('parcial, count(*) as total')
            ->groupBy('parcial')
            ->pluck('total', 'parcial');

        return response()->json([
            'success' => true,
            'counts' => [
                1 => $counts->get(1, 0),
                2 => $counts->get(2, 0),
                3 => $counts->get(3, 0)
            ]
        ]);
    }

    /**
     * Render the view for BTI Disciplinary Reports.
     */
    public function indexView()
    {
        return view('reportes.indisciplina_bti');
    }
}
