@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Gestión de Pacientes</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Administración de base de datos de pacientes.</p>
        </div>
        <a href="{{ route('home') }}" class="btn">← Volver al Panel Principal</a>
    </div>

    <div class="table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Lista de Pacientes</h3>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                
                <form action="{{ route('pacientes.index') }}" method="GET" style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-color); overflow: hidden; margin: 0;">
                <button type="submit" style="background: none; border: none; padding: 8px 12px; color: var(--secondary); font-size: 14px; cursor: pointer;">🔍</button>
                <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar paciente..." style="border: none; padding: 10px 10px 10px 0; outline: none; background: transparent; font-family: var(--font-main); font-size: 14px; width: 220px; color: var(--primary);">
                </form>

                <button type="button" class="btn btn-primary" onclick="openModal('modal-create')">Agregar Nuevo Paciente</button>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Tipo Sangre</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacientes as $paciente)
                <tr>
                    <td>{{ $paciente->id }}</td>
                    <td>{{ $paciente->first_name }}</td>
                    <td>{{ $paciente->last_name }}</td>
                    <td>{{ $paciente->date_of_birth }}</td>
                    <td>{{ $paciente->gender }}</td>
                    <td>{{ $paciente->phone }}</td>
                    <td>{{ $paciente->address }}</td>
                    <td>{{ $paciente->blood_type }}</td>
                    <td style="display: flex; gap: 8px; justify-content: center;">
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px;"
                            data-id="{{ $paciente->id }}"
                            data-first="{{ $paciente->first_name }}"
                            data-last="{{ $paciente->last_name }}"
                            data-dob="{{ $paciente->date_of_birth }}"
                            data-gender="{{ $paciente->gender }}"
                            data-phone="{{ $paciente->phone }}"
                            data-address="{{ $paciente->address }}"
                            data-blood="{{ $paciente->blood_type }}"
                            onclick="openEditPatientsModal(this)">
                            Editar
                        </button>
                        
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px; color: #D35400; border-color: #D35400; background: #FDEDEC;" 
                            onclick="openDeletePatientsModal({{ $paciente->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px 20px; color: var(--secondary);">
                        @if(isset($buscar) && $buscar != '')
                        <div style="font-size: 16px; font-weight: 600;">No se encontró ningún paciente con "{{ $buscar }}"</div>
                        <div style="font-size: 14px; margin-top: 5px;">Revisa la ortografía.</div>
                        <a href="{{ route('pacientes.index') }}" class="btn" style="margin-top: 15px; display: inline-block;">Ver todos los pacientes</a>
                        @else
                        <div style="font-size: 16px; font-weight: 600;">No hay pacientes registrados</div>
                        <div style="font-size: 14px; margin-top: 5px;">Haz clic en "Agregar Nuevo Paciente" para comenzar.</div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="modal-create">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Nuevo Paciente</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('pacientes.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group"><label>Nombre</label><input type="text" name="first_name" required></div>
                <div class="form-group"><label>Apellido</label><input type="text" name="last_name" required></div>
                <div class="form-group"><label>Fecha Nac.</label><input type="date" name="date_of_birth" required></div>
                <div class="form-group">
                    <label>Género</label>
                    <select name="gender" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div class="form-group"><label>Teléfono</label><input type="text" name="phone" required></div>
                <div class="form-group">
                    <label>Tipo Sangre</label>
                    <select name="blood_type" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="O+">O+</option><option value="A+">A+</option><option value="B+">B+</option><option value="AB+">AB+</option>
                        <option value="O-">O-</option><option value="A-">A-</option><option value="B-">B-</option><option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;"><label>Dirección</label><input type="text" name="address" required></div>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-edit">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Paciente</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group"><label>Nombre</label><input type="text" name="first_name" id="edit_first" required></div>
                <div class="form-group"><label>Apellido</label><input type="text" name="last_name" id="edit_last" required></div>
                <div class="form-group"><label>Fecha Nac.</label><input type="date" name="date_of_birth" id="edit_dob" required></div>
                <div class="form-group">
                    <label>Género</label>
                    <select name="gender" id="edit_gender" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="Masculino">Masculino</option><option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div class="form-group"><label>Teléfono</label><input type="text" name="phone" id="edit_phone" required></div>
                <div class="form-group">
                    <label>Tipo Sangre</label>
                    <select name="blood_type" id="edit_blood" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                        <option value="O+">O+</option><option value="A+">A+</option><option value="B+">B+</option><option value="AB+">AB+</option>
                        <option value="O-">O-</option><option value="A-">A-</option><option value="B-">B-</option><option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;"><label>Dirección</label><input type="text" name="address" id="edit_address" required></div>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 style="color: #D35400; margin-top: 0;">¿Eliminar Paciente?</h3>
        <p>Esta acción no se puede deshacer.</p>
        <form id="form-delete" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn" onclick="closeModal('modal-delete')">Cancelar</button>
            <button type="submit" class="btn" style="background-color: #D35400; color: white; border: none;">Sí, Eliminar</button>
        </form>
    </div>
</div>

@endsection