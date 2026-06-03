<!-- Vista alternativa: Solo Tracking de Ofertas -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">🚀 Tracking de Ofertas</h1>
        <p class="text-gray-600 mt-2">Visualiza y controla el progreso de tus envíos</p>
    </div>

    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">Tracking</span>
    </nav>

    <!-- Información -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <span class="text-2xl">✅</span>
                <div class="ml-3">
                    <h3 class="font-semibold text-green-900">Completado</h3>
                    <p class="text-sm text-green-700">Pasos finalizados</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <span class="text-2xl">📍</span>
                <div class="ml-3">
                    <h3 class="font-semibold text-red-900">Actual</h3>
                    <p class="text-sm text-red-700">Paso en progreso</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-center">
                <span class="text-2xl">⭕</span>
                <div class="ml-3">
                    <h3 class="font-semibold text-gray-900">Pendiente</h3>
                    <p class="text-sm text-gray-700">Pasos por ejecutar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Componente Principal -->
    <div id="app">
        <tracking-oferta-component></tracking-oferta-component>
    </div>

</div>
@endsection

@section('scripts')
<script>
    import TrackingOfertaComponent from '@/components/TrackingOfertaComponent.vue';
    
    export default {
        components: {
            TrackingOfertaComponent
        }
    };
</script>
@endsection
