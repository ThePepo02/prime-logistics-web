<?php

namespace Database\Seeders;

use App\Models\Incoterm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * IncotermSeeder - Carga datos de ejemplo de Incoterms
 * 
 * Este seeder carga los incoterms más comunes en el comercio internacional.
 * Los incoterms son términos estandarizados que definen las responsabilidades
 * de comprador y vendedor en una transacción internacional.
 * 
 * Para ejecutar: php artisan db:seed --class=IncotermSeeder
 */
class IncotermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array con los incoterms más comunes
        $incoterms = [
            [
                'codi' => 'EXW',
                'nom' => 'Ex Works',
                'descripcio' => 'Ex Works - El comprador se encarga de todos los costos y riesgos desde las instalaciones del vendedor.'
            ],
            [
                'codi' => 'FCA',
                'nom' => 'Free Carrier',
                'descripcio' => 'Free Carrier - El vendedor entrega la mercancía a un transportista elegido por el comprador.'
            ],
            [
                'codi' => 'CPT',
                'nom' => 'Carriage Paid To',
                'descripcio' => 'Carriage Paid To - El vendedor paga el flete hasta el lugar de destino.'
            ],
            [
                'codi' => 'CIP',
                'nom' => 'Carriage and Insurance Paid To',
                'descripcio' => 'Carriage and Insurance Paid To - El vendedor paga flete y seguro.'
            ],
            [
                'codi' => 'DAP',
                'nom' => 'Delivered at Place',
                'descripcio' => 'Delivered at Place - El vendedor entrega en lugar designado, el comprador asume riesgos.'
            ],
            [
                'codi' => 'DPU',
                'nom' => 'Delivered at Place Unloaded',
                'descripcio' => 'Delivered at Place Unloaded - El vendedor entrega descargado en lugar designado.'
            ],
            [
                'codi' => 'DDP',
                'nom' => 'Delivered Duty Paid',
                'descripcio' => 'Delivered Duty Paid - El vendedor entrega pagados todos los derechos, el comprador solo recibe.'
            ],
            [
                'codi' => 'FOB',
                'nom' => 'Free on Board',
                'descripcio' => 'Free on Board - El vendedor carga la mercancía en el buque, los riesgos pasan al comprador.'
            ],
            [
                'codi' => 'CFR',
                'nom' => 'Cost and Freight',
                'descripcio' => 'Cost and Freight - El vendedor cubre el costo y flete, riesgos pasan al comprador.'
            ],
            [
                'codi' => 'CIF',
                'nom' => 'Cost, Insurance and Freight',
                'descripcio' => 'Cost, Insurance and Freight - El vendedor cubre costo, flete y seguro hasta puerto de destino.'
            ],
        ];

        // Insertar cada incoterm en la BD
        foreach ($incoterms as $incoterm) {
            Incoterm::create($incoterm);
        }

        $this->command->info('✅ ' . count($incoterms) . ' incoterms cargados correctamente.');
    }
}
