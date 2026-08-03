<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class MateriaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock session auth for testing
        $this->withSession([
            'usuario_id' => 1,
            'rol' => 'ADMIN'
        ]);
    }

    public function test_materia_index_page()
    {
        $response = $this->get('/materias');
        $response->assertStatus(200);
        $response->assertViewIs('materias.index');
    }

    public function test_materia_lista()
    {
        Http::fake([
            '*/materias' => Http::response([
                'success' => true,
                'data' => [
                    [
                        'id' => 2,
                        'nombreMateria' => 'Matemáticas',
                        'descripcionMateria' => 'Cálculo',
                        'estatusMateria' => 'ACTIVA',
                        'clave' => 'MAT-101',
                        'docentes' => []
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get('/materias/lista');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.nombreMateria', 'Matemáticas');
    }

    public function test_materia_store()
    {
        Http::fake([
            '*/createMateria' => Http::response(['success' => true, 'idMateria' => 3], 200)
        ]);

        $response = $this->postJson('/materias', [
            'nombreMateria' => 'Física I',
            'descripcionMateria' => 'Mecánica clásica',
            'estatusMateria' => 'ACTIVA',
            'clave' => 'FIS-101',
            'docentes' => [1, 2]
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_materia_show()
    {
        Http::fake([
            '*/materias?id_materia=2' => Http::response([
                'data' => [
                    [
                        'id' => 2,
                        'nombreMateria' => 'Matemáticas Avanzadas',
                        'descripcionMateria' => 'Cálculo',
                        'estatusMateria' => 'ACTIVA',
                        'clave' => 'MAT-201',
                        'docentes' => []
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get('/materias/2');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.nombreMateria', 'Matemáticas Avanzadas');
        $response->assertJsonPath('data.clave', 'MAT-201');
    }

    public function test_materia_update()
    {
        Http::fake([
            '*/updateMateria/2' => Http::response(['success' => true, 'mensaje' => 'Materia actualizada'], 200)
        ]);

        $response = $this->putJson('/materias/2', [
            'nombreMateria' => 'Matemáticas Avanzadas II',
            'descripcionMateria' => 'Cálculo diferencial',
            'estatusMateria' => 'ACTIVA',
            'clave' => 'MAT-202',
            'docentes' => [1, 3]
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_materia_destroy()
    {
        Http::fake([
            '*/deleteMateria/2' => Http::response(['success' => true, 'mensaje' => 'Materia eliminada'], 200)
        ]);

        $response = $this->deleteJson('/materias/2');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Materia eliminada correctamente');
    }
}
