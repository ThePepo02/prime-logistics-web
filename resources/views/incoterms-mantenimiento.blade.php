<!-- Vista alternativa: Solo Mantenimiento de Incoterms -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">📋 Mantenimiento de Tipos de Incoterm</h1>
        <p class="text-gray-600 mt-2">Gestiona los términos comerciales internacionales</p>
    </div>

    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">Incoterms</span>
    </nav>

    <!-- Información -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-blue-900">ℹ️ ¿Qué es un Incoterm?</h3>
        <p class="text-blue-800 mt-2">
            Los Incoterms (International Commercial Terms) son términos estandarizados que 
            especifican las responsabilidades del comprador y vendedor en una transacción de 
            comercio internacional. Ejemplos: CIF, FOB, DDP, etc.
        </p>
    </div>

    <!-- Componente Principal -->
    <div id="app">
        <incoterm-maintenance-component></incoterm-maintenance-component>
    </div>

</div>
@endsection

@section('styles')
<style>
    /* Estilos adicionales si es necesario */
    body {
        background-color: #f5f5f5;
    }
</style>
@endsection

@section('scripts')
<script>
    import IncotermMaintenanceComponent from '@/components/IncotermMaintenanceComponent.vue';
    
    export default {
        components: {
            IncotermMaintenanceComponent
        }
    };
</script>
@endsection
