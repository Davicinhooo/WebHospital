@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Gestión de Citas</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Administración y programación de citas médicas.</p>
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
            <h3 style="margin: 0;">Lista de Citas</h3>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                <form action="{{ route('citas.index') }}" method="GET" style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-color); overflow: hidden; margin: 0;">
                    <button type="submit" style="background: none; border: none; padding: 8px 12px; color: var(--secondary); font-size: 14px; cursor: pointer;">🔍</button>
                    <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar por paciente o médico..." style="border: none; padding: 10px 10px 10px 0; outline: none; background: transparent; font-family: var(--font-main); font-size: 14px; width: 250px; color: var(--primary);">
                </form>
                <button type="button" class="btn btn-primary" onclick="openModal('modal-create')">Programar Nueva Cita</button>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha y Hora</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Motivo</th>
                    <th>Consultorio</th>
                    <th>Observaciones</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($citas as $cita)
                <tr>
                    <td>{{ $cita->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y H:i') }}</td>
                    
                    <td>{{ $cita->patient->first_name ?? 'N/A' }} {{ $cita->patient->last_name ?? '' }}</td>
                    <td>Dr(a). {{ $cita->doctor->last_name ?? 'N/A' }}</td>
                    
                    <td>{{ $cita->reason }}</td>
                    <td>{{ $cita->room }}</td>
                    <td>{{ $cita->observations}}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;
                            {{ $cita->status == 'Pendiente' ? 'background: #FEF9E7; color: #F1C40F;' : '' }}
                            {{ $cita->status == 'Completada' ? 'background: #EAFAF1; color: #2ECC71;' : '' }}
                            {{ $cita->status == 'Cancelada' ? 'background: #FDEDEC; color: #E74C3C;' : '' }}
                        ">
                            {{ $cita->status }}
                        </span>
                    </td>
                    <td style="display: flex; gap: 8px; justify-content: center;">
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px;"
                            data-id="{{ $cita->id }}"
                            data-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d\TH:i') }}"
                            data-patient="{{ $cita->patient_id }}"
                            data-doctor="{{ $cita->doctor_id }}"
                            data-reason="{{ $cita->reason }}"
                            data-room="{{ $cita->room }}"
                            data-status="{{ $cita->status }}"
                            data-observations="{{ $cita->observations }}"
                            onclick="openEditCitaModal(this)">
                            Editar
                        </button>
                        
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px; color: #D35400; border-color: #D35400; background: #FDEDEC;" 
                            onclick="openDeleteCitaModal({{ $cita->id }})">
                            Cancelar Cita
                        </button>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px 20px; color: var(--secondary);">
                        @if(isset($buscar) && $buscar != '')
                            <div style="font-size: 16px; font-weight: 600;">No se encontró ninguna cita para "{{ $buscar }}"</div>
                            <a href="{{ route('citas.index') }}" class="btn" style="margin-top: 15px; display: inline-block;">Ver todas las citas</a>
                        @else
                            <div style="font-size: 40px; margin-bottom: 10px;">📅</div>
                            <div style="font-size: 16px; font-weight: 600;">No hay citas programadas</div>
                            <div style="font-size: 14px; margin-top: 5px;">Haz clic en "Programar Nueva Cita" para comenzar.</div>
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
            <h3>Programar Nueva Cita</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('citas.store') }}" method="POST">
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

                <div class="form-group"><label>Fecha y Hora</label><input type="datetime-local" name="date" required></div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="form-group"><label>Motivo de la cita</label><input type="text" name="reason" required></div>
                <div class="form-group"><label>Consultorio (Room)</label><input type="text" name="room" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Observaciones (Opcional)</label>
                    <textarea name="observations" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Agendar Cita</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-edit">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Editar Cita</h3>
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

                <div class="form-group"><label>Fecha y Hora</label><input type="datetime-local" name="date" id="edit_date" required></div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" id="edit_status" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="form-group"><label>Motivo</label><input type="text" name="reason" id="edit_reason" required></div>
                <div class="form-group"><label>Consultorio</label><input type="text" name="room" id="edit_room" required></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Observaciones</label>
                    <textarea name="observations" id="edit_observations" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-main);" rows="3"></textarea>
                </div>

            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Actualizar Cita</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 style="color: #D35400; margin-top: 0;">¿Cancelar y Eliminar Cita?</h3>
        <p>Esta acción no se puede deshacer.</p>
        <form id="form-delete" action="" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn" onclick="closeModal('modal-delete')">Volver</button>
            <button type="submit" class="btn" style="background-color: #D35400; color: white; border: none;">Sí, Eliminar</button>
        </form>
    </div>
</div>

@endsection