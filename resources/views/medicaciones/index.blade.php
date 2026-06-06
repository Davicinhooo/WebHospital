@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Gestión de Medicaciones</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Control de medicamentos prescritos en los tratamientos.</p>
        </div>
        <a href="{{ route('home') }}" class="btn">← Volver al Panel Principal</a>
        
        @if ($errors->any())
        <div style="background-color: #FDEDEC; color: #D35400; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #F5B7B1; margin-top: 15px;">
            <strong>¡Error al guardar!</strong>
            <ul style="margin: 5px 0 0 20px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Lista de Medicaciones</h3>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                <form action="{{ route('medicaciones.index') }}" method="GET" style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-color); overflow: hidden; margin: 0;">
                    <button type="submit" style="background: none; border: none; padding: 8px 12px; color: var(--secondary); font-size: 14px; cursor: pointer;">🔍</button>
                    <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar medicamento..." style="border: none; padding: 10px 10px 10px 0; outline: none; background: transparent; font-family: var(--font-main); font-size: 14px; width: 220px; color: var(--primary);">
                </form>
                <button type="button" class="btn btn-primary" onclick="window.openModal('modal-create')">Registrar Medicación</button>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Medicamento</th>
                    <th>Tratamiento Asoc.</th>
                    <th>Dosis</th>
                    <th>Frecuencia</th>
                    <th>Duración</th>
                    <th>Proveedor</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicaciones as $med)
                <tr>
                    <td>{{ $med->id }}</td>
                    <td style="font-weight: 600;">{{ $med->name }}</td>
                    
                    <td>{{ $med->treatment->name ?? 'N/A' }}</td>
                    
                    <td>{{ $med->dose }}</td>
                    <td>{{ $med->frequency }}</td>
                    <td>{{ $med->duration }}</td>
                    <td>{{ $med->supplier }}</td>
                    <td style="display: flex; gap: 8px; justify-content: center;">
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px;"
                            data-id="{{ $med->id }}"
                            data-name="{{ $med->name }}"
                            data-dose="{{ $med->dose }}"
                            data-frequency="{{ $med->frequency }}"
                            data-duration="{{ $med->duration }}"
                            data-treatment="{{ $med->treatment_id }}"
                            data-supplier="{{ $med->supplier }}"
                            data-side="{{ $med->side_effects }}"
                            onclick="window.openEditMedicacionModal(this)">
                            Editar
                        </button>
                        
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px; color: #D35400; border-color: #D35400; background: #FDEDEC;" 
                            onclick="window.openDeleteMedicacionModal({{ $med->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px 20px; color: var(--secondary);">
                        @if(isset($buscar) && $buscar != '')
                            <div style="font-size: 16px; font-weight: 600;">No se encontró medicación para "{{ $buscar }}"</div>
                            <a href="{{ route('medicaciones.index') }}" class="btn" style="margin-top: 15px; display: inline-block;">Ver todas</a>
                        @else
                            <div style="font-size: 40px; margin-bottom: 10px;">💊</div>
                            <div style="font-size: 16px; font-weight: 600;">No hay medicaciones registradas</div>
                            <div style="font-size: 14px; margin-top: 5px;">Haz clic en "Registrar Medicación" para comenzar.</div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="modal-create">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Registrar Nueva Medicación</h3>
            <button type="button" class="btn-close" onclick="window.closeModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('medicaciones.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group" style="grid-column: span 2;"><label>Nombre del Medicamento</label><input type="text" name="name" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Tratamiento Asociado</label>
                    <select name="treatment_id" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="">Seleccione un tratamiento...</option>
                        @foreach($tratamientos as $trat)
                            <option value="{{ $trat->id }}">{{ $trat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"><label>Dosis (ej. 500mg)</label><input type="text" name="dose" required></div>
                <div class="form-group"><label>Frecuencia (ej. Cada 8 horas)</label><input type="text" name="frequency" required></div>
                
                <div class="form-group"><label>Duración (ej. 7 días)</label><input type="text" name="duration" required></div>
                <div class="form-group"><label>Proveedor / Laboratorio</label><input type="text" name="supplier" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Efectos Secundarios (Opcional)</label>
                    <textarea name="side_effects" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Guardar Medicación</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-edit">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Editar Medicación</h3>
            <button type="button" class="btn-close" onclick="window.closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group" style="grid-column: span 2;"><label>Nombre del Medicamento</label><input type="text" name="name" id="edit_name" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Tratamiento Asociado</label>
                    <select name="treatment_id" id="edit_treatment" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        @foreach($tratamientos as $trat)
                            <option value="{{ $trat->id }}">{{ $trat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"><label>Dosis</label><input type="text" name="dose" id="edit_dose" required></div>
                <div class="form-group"><label>Frecuencia</label><input type="text" name="frequency" id="edit_frequency" required></div>
                
                <div class="form-group"><label>Duración</label><input type="text" name="duration" id="edit_duration" required></div>
                <div class="form-group"><label>Proveedor</label><input type="text" name="supplier" id="edit_supplier" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Efectos Secundarios</label>
                    <textarea name="side_effects" id="edit_side" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Actualizar Medicación</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 style="color: #D35400; margin-top: 0;">¿Eliminar Medicación?</h3>
        <p>Esta acción no se puede deshacer.</p>
        <form id="form-delete" action="" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn" onclick="window.closeModal('modal-delete')">Cancelar</button>
            <button type="submit" class="btn" style="background-color: #D35400; color: white; border: none;">Sí, Eliminar</button>
        </form>
    </div>
</div>
@endsection