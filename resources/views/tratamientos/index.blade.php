@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Gestión de Tratamientos</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Administración de planes de tratamiento para los pacientes.</p>
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
            <h3 style="margin: 0;">Lista de Tratamientos</h3>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                <form action="{{ route('tratamientos.index') }}" method="GET" style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-color); overflow: hidden; margin: 0;">
                    <button type="submit" style="background: none; border: none; padding: 8px 12px; color: var(--secondary); font-size: 14px; cursor: pointer;">🔍</button>
                    <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar tratamiento..." style="border: none; padding: 10px 10px 10px 0; outline: none; background: transparent; font-family: var(--font-main); font-size: 14px; width: 220px; color: var(--primary);">
                </form>
                <button type="button" class="btn btn-primary" onclick="window.openModal('modal-create')">Registrar Tratamiento</button>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Diagnóstico Asoc.</th>
                    <th>Médico</th>
                    <th>Duración</th>
                    <th>Frec. Administración</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tratamientos as $tratamiento)
                <tr>
                    <td>{{ $tratamiento->id }}</td>
                    <td style="font-weight: 600;">{{ $tratamiento->name }}</td>
                    
                    <td>{{ $tratamiento->diagnostic->type_diagnosis ?? 'N/A' }}</td>
                    <td>Dr(a). {{ $tratamiento->doctor->last_name ?? 'N/A' }}</td>
                    
                    <td>{{ $tratamiento->duration }}</td>
                    <td>{{ $tratamiento->administration_frequency }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;
                            {{ $tratamiento->status == 'En curso' ? 'background: #E8F8F5; color: #1ABC9C;' : '' }}
                            {{ $tratamiento->status == 'Completado' ? 'background: #EAF2F8; color: #3498DB;' : '' }}
                            {{ $tratamiento->status == 'Suspendido' ? 'background: #FDEDEC; color: #E74C3C;' : '' }}
                        ">
                            {{ $tratamiento->status }}
                        </span>
                    </td>
                    <td style="display: flex; gap: 8px; justify-content: center;">
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px;"
                            data-id="{{ $tratamiento->id }}"
                            data-name="{{ $tratamiento->name }}"
                            data-description="{{ $tratamiento->description }}"
                            data-duration="{{ $tratamiento->duration }}"
                            data-diagnostic="{{ $tratamiento->diagnostic_id }}"
                            data-doctor="{{ $tratamiento->doctor_id }}"
                            data-status="{{ $tratamiento->status }}"
                            data-frequency="{{ $tratamiento->administration_frequency }}"
                            onclick="window.openEditTratamientoModal(this)">
                            Editar
                        </button>
                        
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px; color: #D35400; border-color: #D35400; background: #FDEDEC;" 
                            onclick="window.openDeleteTratamientoModal({{ $tratamiento->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px 20px; color: var(--secondary);">
                        @if(isset($buscar) && $buscar != '')
                            <div style="font-size: 16px; font-weight: 600;">No se encontró ningún tratamiento para "{{ $buscar }}"</div>
                            <a href="{{ route('tratamientos.index') }}" class="btn" style="margin-top: 15px; display: inline-block;">Ver todos los tratamientos</a>
                        @else
                            <div style="font-size: 40px; margin-bottom: 10px;">🩺</div>
                            <div style="font-size: 16px; font-weight: 600;">No hay tratamientos registrados</div>
                            <div style="font-size: 14px; margin-top: 5px;">Haz clic en "Registrar Tratamiento" para comenzar.</div>
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
            <h3>Registrar Nuevo Tratamiento</h3>
            <button type="button" class="btn-close" onclick="window.closeModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('tratamientos.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group" style="grid-column: span 2;"><label>Nombre del Tratamiento</label><input type="text" name="name" required></div>
                
                <div class="form-group">
                    <label>Diagnóstico Asociado</label>
                    <select name="diagnostic_id" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="">Seleccione un diagnóstico...</option>
                        @foreach($diagnosticos as $diag)
                            <option value="{{ $diag->id }}">ID {{ $diag->id }} - {{ $diag->type_diagnosis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Médico Asignado</label>
                    <select name="doctor_id" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="">Seleccione un médico...</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}">Dr(a). {{ $medico->last_name }} - {{ $medico->specialty }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"><label>Duración (ej. 2 semanas)</label><input type="text" name="duration" required></div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="En curso">En curso</option>
                        <option value="Completado">Completado</option>
                        <option value="Suspendido">Suspendido</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;"><label>Frecuencia de Administración</label><input type="text" name="administration_frequency" placeholder="Ej. Diaria, Semanal..." required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Descripción detallada</label>
                    <textarea name="description" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Guardar Tratamiento</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-edit">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Editar Tratamiento</h3>
            <button type="button" class="btn-close" onclick="window.closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group" style="grid-column: span 2;"><label>Nombre del Tratamiento</label><input type="text" name="name" id="edit_name" required></div>
                
                <div class="form-group">
                    <label>Diagnóstico Asociado</label>
                    <select name="diagnostic_id" id="edit_diagnostic" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        @foreach($diagnosticos as $diag)
                            <option value="{{ $diag->id }}">ID {{ $diag->id }} - {{ $diag->type_diagnosis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Médico Asignado</label>
                    <select name="doctor_id" id="edit_doctor" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}">Dr(a). {{ $medico->last_name }} - {{ $medico->specialty }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"><label>Duración</label><input type="text" name="duration" id="edit_duration" required></div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" id="edit_status" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="En curso">En curso</option>
                        <option value="Completado">Completado</option>
                        <option value="Suspendido">Suspendido</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;"><label>Frecuencia de Administración</label><input type="text" name="administration_frequency" id="edit_frequency" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Descripción detallada</label>
                    <textarea name="description" id="edit_description" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Actualizar Tratamiento</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 style="color: #D35400; margin-top: 0;">¿Eliminar Tratamiento?</h3>
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