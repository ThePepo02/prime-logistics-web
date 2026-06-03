<?php

namespace Tests\Feature;

use App\Models\Incoterm;
use Tests\TestCase;

/**
 * Tests para TipusIncotermController
 * 
 * Este archivo contiene pruebas unitarias para verificar que el controlador
 * de gestión de incoterms funciona correctamente.
 * 
 * Para ejecutar: php artisan test tests/Feature/TipusIncotermControllerTest.php
 */
class TipusIncotermControllerTest extends TestCase
{
    /**
     * Test: Obtener lista de incoterms
     */
    public function test_index_returns_all_incoterms()
    {
        // Crear datos de prueba
        Incoterm::create([
            'codi' => 'CIF',
            'nom' => 'Cost, Insurance and Freight',
            'descripcio' => 'Test description'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/tipos-incoterm');

        // Verificaciones
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonCount(1, 'data');
    }

    /**
     * Test: Obtener un incoterm específico
     */
    public function test_show_returns_specific_incoterm()
    {
        // Crear incoterm
        $incoterm = Incoterm::create([
            'codi' => 'FOB',
            'nom' => 'Free on Board',
            'descripcio' => 'Vendor loads on ship'
        ]);

        // Hacer petición
        $response = $this->getJson("/api/tipos-incoterm/{$incoterm->id}");

        // Verificaciones
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'codi' => 'FOB',
                'nom' => 'Free on Board'
            ]
        ]);
    }

    /**
     * Test: Crear un nuevo incoterm
     */
    public function test_store_creates_new_incoterm()
    {
        // Datos para crear
        $data = [
            'codi' => 'DDP',
            'nom' => 'Delivered Duty Paid',
            'descripcio' => 'Seller pays everything'
        ];

        // Hacer petición
        $response = $this->postJson('/api/tipos-incoterm', $data);

        // Verificaciones
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);

        // Verificar en BD
        $this->assertDatabaseHas('incoterms', [
            'codi' => 'DDP'
        ]);
    }

    /**
     * Test: Validar que código debe ser único
     */
    public function test_store_validates_unique_codigo()
    {
        // Crear primer incoterm
        Incoterm::create([
            'codi' => 'CIF',
            'nom' => 'Cost, Insurance and Freight'
        ]);

        // Intentar crear otro con mismo código
        $data = [
            'codi' => 'CIF',
            'nom' => 'Another name'
        ];

        $response = $this->postJson('/api/tipos-incoterm', $data);

        // Verificaciones
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('codi');
    }

    /**
     * Test: Actualizar un incoterm
     */
    public function test_update_modifies_incoterm()
    {
        // Crear incoterm
        $incoterm = Incoterm::create([
            'codi' => 'FOB',
            'nom' => 'Free on Board',
            'descripcio' => 'Original description'
        ]);

        // Datos actualizados
        $data = [
            'codi' => 'FOB',
            'nom' => 'Free on Board - Updated',
            'descripcio' => 'Updated description'
        ];

        // Hacer petición
        $response = $this->putJson("/api/tipos-incoterm/{$incoterm->id}", $data);

        // Verificaciones
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);

        // Verificar en BD
        $this->assertDatabaseHas('incoterms', [
            'id' => $incoterm->id,
            'nom' => 'Free on Board - Updated'
        ]);
    }

    /**
     * Test: Eliminar un incoterm
     */
    public function test_destroy_deletes_incoterm()
    {
        // Crear incoterm
        $incoterm = Incoterm::create([
            'codi' => 'DDP',
            'nom' => 'Delivered Duty Paid'
        ]);

        // Verificar que existe
        $this->assertDatabaseHas('incoterms', ['id' => $incoterm->id]);

        // Hacer petición de eliminación
        $response = $this->deleteJson("/api/tipos-incoterm/{$incoterm->id}");

        // Verificaciones
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tipo de incoterm eliminado correctamente'
        ]);

        // Verificar que fue eliminado
        $this->assertDatabaseMissing('incoterms', ['id' => $incoterm->id]);
    }

    /**
     * Test: Devuelve error 404 si incoterm no existe
     */
    public function test_show_returns_404_if_not_found()
    {
        $response = $this->getJson('/api/tipos-incoterm/999');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Tipo de incoterm no encontrado'
        ]);
    }

    /**
     * Test: Código se guarda en mayúsculas
     */
    public function test_codigo_is_saved_uppercase()
    {
        $data = [
            'codi' => 'cif',  // en minúsculas
            'nom' => 'Cost, Insurance and Freight'
        ];

        $response = $this->postJson('/api/tipos-incoterm', $data);

        // Verificar que se guardó en mayúsculas
        $this->assertDatabaseHas('incoterms', [
            'codi' => 'CIF'
        ]);
    }
}
