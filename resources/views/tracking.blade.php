@extends('layouts.main')


@section('title', 'Mis Pedidos')


@section('content')
    <client-orders-component></client-orders-component>
@endsection


@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');


.orders-shell {
    min-height: 100vh;
    background: #e9edf4;
    display: grid;
    grid-template-columns: 240px 1fr;
    font-family: 'Manrope', sans-serif;
    color: #152238;
}


.sidebar {
    background: linear-gradient(180deg, #0a2243 0%, #071a33 100%);
    color: #dbe7ff;
    padding: 1.2rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}


.brand {
    padding: 0.2rem 0.5rem 0.9rem;
}


.brand img {
    height: 30px;
    object-fit: contain;
}


.section-title {
    margin: 0.5rem 0 0.2rem;
    text-transform: uppercase;
    font-size: 0.67rem;
    letter-spacing: 0.08em;
    opacity: 0.8;
}


.section-title.muted {
    margin-top: 0.9rem;
    opacity: 0.58;
}


.menu {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.26rem;
}


.menu li {
    padding: 0.6rem 0.72rem;
    border-radius: 9px;
    font-size: 0.82rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #c6d8fb;
}


.menu li a {
    color: inherit;
    text-decoration: none;
}


.menu li.active {
    background: #ff7e26;
    color: #fff;
    font-weight: 700;
}


.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff7e26;
}


.user-card {
    margin-top: auto;
    padding-top: 0.9rem;
    border-top: 1px solid rgba(198, 216, 251, 0.17);
    display: flex;
    align-items: center;
    gap: 0.65rem;
}


.avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #2d65b0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
}


.user-card strong {
    font-size: 0.75rem;
    display: block;
}


.user-card small {
    opacity: 0.75;
    font-size: 0.68rem;
}


.content {
    padding: 1rem 1.1rem;
}


.topbar {
    background: #f3f5f8;
    border: 1px solid #d6dee8;
    border-radius: 12px;
    padding: 0.8rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}


h1 {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
}


.topbar p {
    margin: 0.12rem 0 0;
    font-size: 0.72rem;
    color: #607089;
}


.actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}


.new-order {
    border: 0;
    background: #ff7e26;
    color: #fff;
    padding: 0.45rem 0.8rem;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    text-decoration: none;
}


.icon-btn {
    border: 1px solid #d2dae6;
    background: #fff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
}


.mini-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #dce8fb;
    color: #2d65b0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 700;
}


.panel {
    background: #f7f9fc;
    border: 1px solid #d6dee8;
    border-radius: 9px;
    padding: 0.8rem;
}


.filters {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 0.6rem;
    align-items: center;
    margin-bottom: 0.7rem;
}


.filters input,
.filters select {
    border: 1px solid #d6dee8;
    border-radius: 8px;
    background: #f6f8fb;
    font-size: 0.74rem;
    color: #1b2f4c;
    padding: 0.55rem 0.66rem;
}


.filters small {
    font-size: 0.62rem;
    color: #6f819a;
    justify-self: end;
}


.table-wrap {
    overflow-x: auto;
}


table {
    width: 100%;
    border-collapse: collapse;
}


th,
 td {
    text-align: left;
    padding: 0.52rem 0.35rem;
    font-size: 0.67rem;
    border-top: 1px solid #e0e6ef;
    white-space: nowrap;
}


th {
    color: #7a8aa2;
    font-weight: 700;
    border-top: 0;
}


.id {
    color: #1f6fcc;
    font-weight: 700;
}


.pill {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.17rem 0.6rem;
    font-size: 0.56rem;
    font-weight: 800;
}


.in-transit {
    background: #dbe8ff;
    color: #2f69bd;
}


.accepted {
    background: #d8f3de;
    color: #257e4f;
}


.completed {
    background: #e9f5df;
    color: #3e7a3d;
}


.rejected {
    background: #ffe0dc;
    color: #ba4a46;
}


.tracking {
    border: 1px solid #d3dbe8;
    background: #fff;
    border-radius: 8px;
    font-size: 0.63rem;
    padding: 0.2rem 0.5rem;
    text-decoration: none;
    color: #1f3556;
    display: inline-flex;
}


.table-footer {
    margin-top: 0.65rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}


.table-footer small {
    font-size: 0.62rem;
    color: #73849d;
}


.pagination {
    display: flex;
    gap: 0.35rem;
}


.pagination button {
    width: 24px;
    height: 24px;
    border: 1px solid #d5deea;
    border-radius: 7px;
    background: #fff;
    font-size: 0.62rem;
}


.pagination button.active {
    background: #ff7e26;
    color: #fff;
    border-color: #ff7e26;
}


@media (max-width: 1100px) {
    .orders-shell {
        grid-template-columns: 1fr;
    }


    .filters {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
