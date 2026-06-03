<?php

namespace Database\Seeders;

use App\Models\TrackingStep;
use App\Models\Incoterm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * TrackingStepSeeder - Carga datos de ejemplo de pasos de tracking
 * 
 * Este seeder carga los pasos típicos del tracking de una oferta logística.
 * Estos pasos pueden variar según el incoterm y tipo de transporte.
 * 
 * Para ejecutar: php artisan db:seed --class=TrackingStepSeeder
 */
class TrackingStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primero, obtener un incoterm de ejemplo (FOB)
        $incoterm = Incoterm::where('codi', 'FOB')->first();

        if (!$incoterm) {
            $this->command->warn('⚠️ No se encontró el incoterm FOB. Ejecuta primero: php artisan db:seed --class=IncotermSeeder');
            return;
        }

        // Pasos típicos de un tracking de transporte marítimo
        $pasos = [
            [
                'nom' => 'Recolección',
                'descripcio' => 'Recogida de la mercancía en el almacén del vendedor',
                'ordre' => 1,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Inspección y Documentación',
                'descripcio' => 'Verificación de la carga y preparación de documentos aduanales',
                'ordre' => 2,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Transporte a Puerto',
                'descripcio' => 'Envío desde el almacén hasta el puerto de origen',
                'ordre' => 3,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Carga en Buque',
                'descripcio' => 'Carga de la mercancía en el barco',
                'ordre' => 4,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Tránsito Marítimo',
                'descripcio' => 'La mercancía se encuentra en tránsito por el océano',
                'ordre' => 5,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Atraque en Puerto Destino',
                'descripcio' => 'Llegada del buque al puerto de destino',
                'ordre' => 6,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Trámites Aduanales',
                'descripcio' => 'Presentación de documentos aduanales en el puerto destino',
                'ordre' => 7,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Descarga',
                'descripcio' => 'Descarga de la mercancía del buque',
                'ordre' => 8,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Transporte Local',
                'descripcio' => 'Transporte desde el puerto al lugar de destino final',
                'ordre' => 9,
                'estat' => 'pendiente',
            ],
            [
                'nom' => 'Entrega Final',
                'descripcio' => 'Entrega de la mercancía al comprador',
                'ordre' => 10,
                'estat' => 'pendiente',
            ],
        ];

        // Insertar pasos (sin oferta_id por ahora, se asociarán dinámicamente)
        foreach ($pasos as $paso) {
            TrackingStep::create(array_merge($paso, [
                'incoterm_id' => $incoterm->id,
            ]));
        }

        $this->command->info('✅ ' . count($pasos) . ' pasos de tracking cargados correctamente.');
        $this->command->info('Nota: Los pasos estarán disponibles cuando se asocien a una oferta.');
    }
}
