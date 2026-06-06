@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Panel de Gestión Pe Farma</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Selecciona un módulo para administrar el sistema.</p>
        </div>
    </div>

    <div class="grid-modules">
        <a href="/pacientes" class="module-card card-pacientes">
            <div class="module-icon">👥</div>
            <h3>Pacientes</h3>
            <p>Gestiona el registro, historial y datos de los pacientes.</p>
        </a>

        <a href="/medicos" class="module-card card-medicos">
            <div class="module-icon">👨‍⚕️</div>
            <h3>Médicos</h3>
            <p>Administra el personal médico y sus especialidades.</p>
        </a>

        <a href="/citas" class="module-card card-citas">
            <div class="module-icon">📅</div>
            <h3>Citas</h3>
            <p>Programa, modifica y cancela las citas médicas.</p>
        </a>

        <a href="/diagnosticos" class="module-card card-diagnosticos">
            <div class="module-icon">📋</div>
            <h3>Diagnósticos</h3>
            <p>Registra las evaluaciones y diagnósticos de los pacientes.</p>
        </a>

        <a href="/tratamientos" class="module-card card-tratamientos">
            <div class="module-icon">❤️‍🩹</div>
            <h3>Tratamientos</h3>
            <p>Controla los planes de recuperación y terapias.</p>
        </a>

        <a href="/medicaciones" class="module-card card-medicamentos">
            <div class="module-icon">💊</div>
            <h3>Medicamentos</h3>
            <p>Inventario y control de fármacos recetados.</p>
        </a>
    </div>
</div>
@endsection