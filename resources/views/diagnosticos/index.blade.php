@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Gestión de Diagnosticos</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Visualización de diagnósticos registrados.</p>
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
            <h3 style="margin: 0;">Lista de Diagnósticos</h3>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                <form action="{{ route('diagnosticos.index') }}" method="GET" style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-color); overflow: hidden; margin: 0;">
                    <button type="submit" style="background: none; border: none; padding: 8px 12px; color: var(--secondary); font-size: 14px; cursor: pointer;">🔍</button>
                    <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar por paciente o médico..." style="border: none; padding: 10px 10px 10px 0; outline: none; background: transparent; font-family: var(--font-main); font-size: 14px; width: 250px; color: var(--primary);">
                </form>
                <button type="button" class="btn btn-primary" onclick="openModal('modal-create')">Registrar nuevo diagnóstico</button>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Medico</th>
                    <th>Gravedad</th>
                    <th>Recomendaciones</th>
                    <th>Tipo de diagnostico</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diagnosticos as $diagnostico)
                <tr>
                    <td>{{ $diagnostico->id }}</td>
                    <td>{{ $diagnostico->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($diagnostico->date)->format('d/m/Y') }}</td>
                    
                    <td>{{ $diagnostico->patient->first_name ?? 'N/A' }} {{ $diagnostico->patient->last_name ?? '' }}</td>
                    <td>Dr(a). {{ $diagnostico->doctor->last_name ?? 'N/A' }}</td>
                    
                    <td>{{ $diagnostico->severity }}</td>
                    <td>{{ $diagnostico->recommendations }}</td>
                    <td>{{ $diagnostico->type_diagnosis}}</td>
                    <td style="display: flex; gap: 8px; justify-content: center;">
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px;"
                            data-id="{{ $diagnostico->id }}"
                            data-date="{{ \Carbon\Carbon::parse($diagnostico->date)->format('Y-m-d') }}"
                            data-patient="{{ $diagnostico->patient_id }}"
                            data-doctor="{{ $diagnostico->doctor_id }}"
                            data-severity="{{ $diagnostico->severity }}"
                            data-recommendations="{{ $diagnostico->recommendations }}"
                            data-type_diagnosis="{{ $diagnostico->type_diagnosis }}"
                            data-description="{{ $diagnostico->description }}"
                            onclick="openEditDiagnosticModal(this)">
                            Editar
                        </button>
                        
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px; color: #D35400; border-color: #D35400; background: #FDEDEC;" 
                            onclick="openDeleteDiagnosticModal({{ $diagnostico->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px 20px; color: var(--secondary);">
                        @if(isset($buscar) && $buscar != '')
                            <div style="font-size: 16px; font-weight: 600;">No se encontró ningún diagnostico para "{{ $buscar }}"</div>
                            <a href="{{ route('diagnosticos.index') }}" class="btn" style="margin-top: 15px; display: inline-block;">Ver todos los diagnósticos</a>
                        @else
                            <div style="font-size: 40px; margin-bottom: 10px;">📝</div>
                            <div style="font-size: 16px; font-weight: 600;">No hay diagnósticos registrados</div>
                            <div style="font-size: 14px; margin-top: 5px;">Haz clic en "Registrar nuevo diagnóstico" para comenzar.</div>
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
            <h3>Registrar nuevo Diagnóstico</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('diagnosticos.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group">
                    <label>Paciente</label>
                    <select name="patient_id" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="">Seleccione un paciente...</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id }}">{{ $paciente->first_name }} {{ $paciente->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Médico Asignado</label>
                    <select name="doctor_id" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="">Seleccione un médico...</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->first_name }} {{ $medico->last_name }} - {{ $medico->specialty }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"><label>Fecha</label><input type="date" name="date" required></div>
                
                <div class="form-group">
                    <label>Gravedad</label>
                    <select name="severity" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>

                <div class="form-group"><label>Descripcion</label><input type="text" name="description" required></div>
                <div class="form-group"><label>Tipo de diagnóstico</label><input type="text" name="type_diagnosis" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Recomendaciones</label>
                    <textarea name="recommendations" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Guardar Diagnóstico</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-edit">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Editar Diagnóstico</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group">
                    <label>Paciente</label>
                    <select name="patient_id" id="edit_patient" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id }}">{{ $paciente->first_name }} {{ $paciente->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Médico Asignado</label>
                    <select name="doctor_id" id="edit_doctor" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->first_name }} {{ $medico->last_name }} - {{ $medico->specialty }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"><label>Fecha</label><input type="date" name="date" id="edit_date" required></div>
                
                <div class="form-group">
                    <label>Gravedad</label>
                    <select name="severity" id="edit_severity" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>

                <div class="form-group"><label>Descripcion</label><input type="text" name="description" id="edit_description" required></div>
                <div class="form-group"><label>Tipo de diagnóstico</label><input type="text" name="type_diagnosis" id="edit_type_diagnosis" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Recomendaciones</label>
                    <textarea name="recommendations" id="edit_recommendations" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Actualizar Diagnóstico</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 style="color: #D35400; margin-top: 0;">¿Eliminar Diagnóstico?</h3>
        <p>Esta acción no se puede deshacer.</p>
        <form id="form-delete" action="" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn" onclick="closeModal('modal-delete')">Cancelar</button>
            <button type="submit" class="btn" style="background-color: #D35400; color: white; border: none;">Sí, Eliminar</button>
        </form>
    </div>
</div>

@endsection