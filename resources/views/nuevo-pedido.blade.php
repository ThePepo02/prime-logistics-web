@extends('layouts.main')


@section('title', 'Nuevo Pedido')


@section('content')
    <client-new-order-component></client-new-order-component>
@endsection


@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');


.form-shell {
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


.top-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
    padding: 0.9rem;
}


.stepper {
    background: #0a2243;
    border-radius: 8px;
    padding: 0.65rem 0.8rem;
    display: flex;
    align-items: center;
}


.step {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    color: #8ea8cb;
    min-width: 0;
}


.step strong {
    display: block;
    font-size: 0.66rem;
    color: #edf3ff;
}


.step small {
    display: block;
    font-size: 0.56rem;
}


.bubble {
    width: 21px;
    height: 21px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.62rem;
    font-weight: 800;
    background: #f8fbff;
    color: #13253e;
}


.step.active .bubble {
    background: #ff7e26;
    color: #fff;
}


.step.done .bubble {
    background: #2ac86f;
    color: #fff;
}


.line {
    height: 1px;
    background: #264d7b;
    flex: 1;
    margin: 0 0.45rem;
}


.line.on {
    background: #2ac86f;
}


.step-body {
    padding: 0.8rem 0.2rem 0;
}


.step-body h2,
.step-body h3 {
    margin: 0.35rem 0 0.7rem;
    font-size: 0.76rem;
    font-weight: 800;
}


.grid {
    display: grid;
    gap: 0.65rem;
    margin-bottom: 0.65rem;
}


.grid.two {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}


.grid.three {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}


label {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    font-size: 0.62rem;
    color: #6c7f98;
    font-weight: 700;
}


input,
textarea {
    border: 1px solid #d6dee8;
    border-radius: 8px;
    background: #f6f8fb;
    font-size: 0.78rem;
    color: #1b2f4c;
    padding: 0.56rem 0.66rem;
}


textarea {
    min-height: 72px;
    resize: vertical;
}


label small {
    font-size: 0.56rem;
    color: #8ea0b8;
    font-weight: 600;
}


.transport-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.6rem;
    margin-bottom: 0.75rem;
}


.transport-card {
    border: 1px solid #d6dee8;
    background: #f6f8fb;
    border-radius: 9px;
    padding: 0.72rem;
    text-align: left;
}


.transport-card.selected {
    border-color: #ff7e26;
    box-shadow: 0 0 0 1px #ff7e26 inset;
    background: #fff4eb;
}


.transport-card strong {
    display: block;
    font-size: 0.73rem;
    color: #1c3150;
}


.transport-card small {
    font-size: 0.58rem;
    color: #6f829c;
}


.segmented {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.55rem;
}


.segmented button {
    border: 1px solid #d6dee8;
    background: #f6f8fb;
    border-radius: 8px;
    padding: 0.56rem;
    font-size: 0.68rem;
    color: #1f3556;
    text-align: left;
    font-weight: 700;
}


.segmented button.selected {
    border-color: #2f69bd;
    box-shadow: 0 0 0 1px #2f69bd inset;
}


.warning-box {
    margin-top: 0.45rem;
    border: 1px solid #ff934f;
    border-radius: 8px;
    padding: 0.62rem;
    background: #fff8f2;
}


.warning-box p {
    margin: 0 0 0.35rem;
    font-size: 0.63rem;
    color: #df6f2a;
    font-weight: 700;
}


.wizard-footer {
    margin-top: 0.8rem;
    padding-top: 0.7rem;
    border-top: 1px solid #dee6f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.7rem;
}


.progress-info span {
    display: block;
    font-size: 0.62rem;
    color: #7d8fa8;
    margin-bottom: 0.2rem;
    font-weight: 700;
}


.progress-track {
    width: 120px;
    height: 4px;
    background: #d3dce8;
    border-radius: 999px;
    overflow: hidden;
}


.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #2f69bd 0%, #ff7e26 100%);
}


.footer-actions {
    display: flex;
    gap: 0.45rem;
}


.btn {
    border-radius: 8px;
    font-size: 0.68rem;
    font-weight: 700;
    border: 1px solid #d2dae6;
    padding: 0.45rem 0.8rem;
    background: #f9fbff;
    color: #1f3556;
}


.btn.primary {
    background: #ff7e26;
    color: #fff;
    border-color: #ff7e26;
}


.submit-message {
    margin: 0.65rem 0 0;
    font-size: 0.7rem;
    font-weight: 700;
    color: #2b7a4e;
}


.submit-message.error {
    color: #be4d43;
}


@media (max-width: 1100px) {
    .form-shell {
        grid-template-columns: 1fr;
    }


    .transport-grid,
    .grid.two,
    .grid.three {
        grid-template-columns: 1fr;
    }


    .wizard-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endpush



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


