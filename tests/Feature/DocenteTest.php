<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class DocenteTest extends TestCase
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

    public function test_docente_index_page()
    {
        $response = $this->get('/docentes');
        $response->assertStatus(200);
        $response->assertViewIs('docentes.index');
    }

    public function test_docente_lista()
    {
        Http::fake([
            '*/docentes' => Http::response([
                'data' => [
                    [
                        'idDocente' => 3,
                        'nombreDocente' => 'Juan',
                        'apPaternoDocente' => 'Pérez',
                        'apMaternoDocente' => 'García',
                        'correoDocente' => 'juan.perez@correo.com',
                        'telefonoDocente' => '2311234567',
                        'statusDocente' => 'ACTIVO',
                        'observacionesDocente' => 'Docente tiempo completo',
                        'nivelEstudios' => 'Licenciatura',
                        'fechaNacimiento' => '1990-05-15'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get('/docentes/lista');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.nombreDocente', 'Juan');
    }

    public function test_docente_store()
    {
        Http::fake([
            '*/createDocentes' => Http::response(['success' => true], 200)
        ]);

        $response = $this->postJson('/docentes', [
            'nombreDocente' => 'Juan',
            'apPaternoDocente' => 'Pérez',
            'apMaternoDocente' => 'García',
            'correoDocente' => 'juan.perez@correo.com',
            'telefonoDocente' => '2311234567',
            'statusDocente' => 'ACTIVO',
            'observacionesDocente' => 'Docente de tiempo completo.',
            'nivelEstudios' => 'Licenciatura',
            'fechaNacimiento' => '1990-05-15'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_docente_show()
    {
        Http::fake([
            '*/docentes' => Http::response([
                'data' => [
                    [
                        'idDocente' => 3,
                        'nombreDocente' => 'Juan',
                        'apPaternoDocente' => 'Pérez',
                        'apMaternoDocente' => 'García',
                        'correoDocente' => 'juan.perez@correo.com',
                        'telefonoDocente' => '2311234567',
                        'statusDocente' => 'ACTIVO',
                        'observacionesDocente' => 'Docente tiempo completo',
                        'nivelEstudios' => 'Licenciatura',
                        'fechaNacimiento' => '1990-05-15'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get('/docentes/3');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.nombreDocente', 'Juan');
        $response->assertJsonPath('data.idDocente', 3);
    }

    public function test_docente_update()
    {
        Http::fake([
            '*/updateDocente/3' => Http::response(['success' => true, 'mensaje' => 'Docente actualizado'], 200)
        ]);

        $response = $this->putJson('/docentes/3', [
            'nombreDocente' => 'Juan Pérez',
            'apPaternoDocente' => 'Pérez',
            'apMaternoDocente' => 'García',
            'correoDocente' => 'juan.perez@correo.com',
            'telefonoDocente' => '2311234567',
            'statusDocente' => 'ACTIVO',
            'observacionesDocente' => 'Docente de tiempo completo.',
            'nivelEstudios' => 'Licenciatura',
            'fechaNacimiento' => '1990-05-15'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_docente_destroy()
    {
        Http::fake([
            '*/deleteDocente/3' => Http::response(['success' => true, 'mensaje' => 'Docente eliminado'], 200)
        ]);

        $response = $this->deleteJson('/docentes/3');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Docente eliminado correctamente');
    }
}
