@extends('layouts.main')

@section('content')
    <dashboard-admin-component></dashboard-admin-component>
    <gestion-usuarios-component></gestion-usuarios-component>
    {{-- subcomponente de gestion de usuarios --}}
    <nuevo-usuario-componente></nuevo-usuario-componente>
@endsection
