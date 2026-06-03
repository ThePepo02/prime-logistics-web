@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Columna 1: Mantenimiento de Incoterms -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">📋 Gestión de Incoterms</h1>
            <p class="text-gray-600 mb-6">
                Crea, actualiza y elimina los tipos de incoterm disponibles en el sistema.
                Los incoterms son términos comerciales internacionales que definen las 
                responsabilidades de comprador y vendedor.
            </p>
            
            <!-- Componente Vue -->
            <incoterm-maintenance-component></incoterm-maintenance-component>
        </div>

        <!-- Columna 2: Tracking de Ofertas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">🚀 Seguimiento de Ofertas</h1>
            <p class="text-gray-600 mb-6">
                Visualiza el progreso de tus ofertas logísticas. Puedes ver en qué paso 
                se encuentra cada envío y cambiar a un paso diferente cuando sea necesario.
            </p>
            
            <!-- Componente Vue -->
            <tracking-oferta-component></tracking-oferta-component>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Importar componentes
    import IncotermMaintenanceComponent from '@/components/IncotermMaintenanceComponent.vue';
    import TrackingOfertaComponent from '@/components/TrackingOfertaComponent.vue';
    
    export default {
        components: {
            IncotermMaintenanceComponent,
            TrackingOfertaComponent
        }
    };
</script>
@endsection
