//gkdfjgkldfjgkldf

@extends('layouts.main')

@section('content')
    <dashboard-admin-component></dashboard-admin-component>
    <gestion-usuarios-component></gestion-usuarios-component>
    {{-- subcomponentes de gestion de usuarios --}}
    <nuevo-usuario-componente></nuevo-usuario-componente>
    <editar-nuevo-usuario-componente></editar-nuevo-usuario-componente>
    <eliminar-nuevo-usuario-component></eliminar-nuevo-usuario-component>
    <dashboard-ofertas-admin-component></dashboard-ofertas-admin-component>
    <ofertas-activas-admin-component></ofertas-activas-admin-component>
    <datos-maestros-component></datos-maestros-component>
@endsection

