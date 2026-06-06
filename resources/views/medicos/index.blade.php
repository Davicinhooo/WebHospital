@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2 style="margin: 0;">Gestión de Medicos</h2>
            <p style="color: var(--secondary); margin-top: 5px;">Administración de base de datos de los medicos.</p>
        </div>
        <a href="{{ route('home') }}" class="btn">← Volver al Panel Principal</a>
        @if ($errors->any())
    <div style="background-color: #FDEDEC; color: #D35400; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #F5B7B1;">
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
            <h3 style="margin: 0;">Lista de Medicos</h3>
            
            <div style="display: flex; gap: 15px; align-items: center;">
                <form action="{{ route('medicos.index') }}" method="GET" style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-color); overflow: hidden; margin: 0;">
                <button type="submit" style="background: none; border: none; padding: 8px 12px; color: var(--secondary); font-size: 14px; cursor: pointer;">🔍</button>
                <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar médico..." style="border: none; padding: 10px 10px 10px 0; outline: none; background: transparent; font-family: var(--font-main); font-size: 14px; width: 220px; color: var(--primary);">
                </form>
                <button type="button" class="btn btn-primary" onclick="openModal('modal-create')">Agregar Nuevo Medico</button>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Especialidad</th>
                    <th>Teléfono</th>
                    <th>Correo Electronico</th>
                    <th>Licencia</th>
                    <th>Años de experiencia</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicos as $medico)
                <tr>
                    <td>{{ $medico->id }}</td>
                    <td>{{ $medico->first_name }}</td>
                    <td>{{ $medico->last_name }}</td>
                    <td>{{ $medico->specialty }}</td>
                    <td>{{ $medico->phone }}</td>
                    <td>{{ $medico->email }}</td>
                    <td>{{ $medico->license }}</td>
                    <td>{{ $medico->years_of_experience }}</td>
                    <td style="display: flex; gap: 8px; justify-content: center;">
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px;"
                            data-id="{{ $medico->id }}"
                            data-first="{{ $medico->first_name }}"
                            data-last="{{ $medico->last_name }}"
                            data-specialty="{{ $medico->specialty }}"
                            data-phone="{{ $medico->phone }}"
                            data-email="{{ $medico->email }}"
                            data-license="{{ $medico->license }}"
                            data-years_of_experiences="{{ $medico->years_of_experience }}"
                            onclick="openEditDoctorsModal(this)">
                            Editar
                        </button>
                        
                        <button type="button" class="btn" style="padding: 5px 10px; font-size: 12px; color: #D35400; border-color: #D35400; background: #FDEDEC;" 
                            onclick="openDeleteDoctorsModal({{ $medico->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px 20px; color: var(--secondary);">
                        @if(isset($buscar) && $buscar != '')
                        <div style="font-size: 16px; font-weight: 600;">No se encontró ningún médico con "{{ $buscar }}"</div>
                        <div style="font-size: 14px; margin-top: 5px;">Revisa la ortografía.</div>
                        <a href="{{ route('medicos.index') }}" class="btn" style="margin-top: 15px; display: inline-block;">Ver todos los médicos</a>
                        @else
                        <div style="font-size: 16px; font-weight: 600;">No hay medicos registrados</div>
                        <div style="font-size: 14px; margin-top: 5px;">Haz clic en "Agregar Nuevo Medico" para comenzar.</div>
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
            <h3>Agregar Nuevo Medico</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('medicos.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group"><label>Nombre</label><input type="text" name="first_name" required></div>
                <div class="form-group"><label>Apellido</label><input type="text" name="last_name" required></div>
                <div class="form-group"><label>Especialidad</label><input type="text" name="specialty" required></div>
                <div class="form-group"><label>Teléfono</label><input type="text" name="phone" required></div>
                <div class="form-group"><label>Correo Electronico</label><input type="text" name="email" required></div>
                
                <div class="form-group">
                    <label>Licencia</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="license" id="licencia_input" required readonly style="background-color: #f8f9fa; cursor: not-allowed; width: 100%; border: 1px solid var(--border); border-radius: 6px; padding: 12px;">
                        <button type="button" class="btn" id="btn_generar_licencia" style="background-color: #34495E; color: white; white-space: nowrap;">🎲 Generar</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Años de experiencia</label>
                    <input type="number" name="years_of_experience" required min="0" max="60" step="1" style="width: 120px; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                </div>
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
            <h3>Editar Medico</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group"><label>Nombre</label><input type="text" name="first_name" id="edit_first" required></div>
                <div class="form-group"><label>Apellido</label><input type="text" name="last_name" id="edit_last" required></div>
                <div class="form-group"><label>Especialidad</label><input type="text" name="specialty" id="edit_specialty" required></div>
                <div class="form-group"><label>Teléfono</label><input type="text" name="phone" id="edit_phone" required></div>
                <div class="form-group"><label>Correo Electronico</label><input type="text" name="email" id="edit_email" required></div>
                
                <div class="form-group">
                    <label>Licencia</label>
                    <input type="text" name="license" id="edit_license" required readonly style="background-color: #f8f9fa; cursor: not-allowed; width: 100%; border: 1px solid var(--border); padding: 12px; border-radius: 6px;">
                </div>
                
                <div class="form-group">
                    <label>Años de experiencia</label>
                    <input type="number" name="years_of_experience" id="edit_years_of_experiences" required min="0" max="60" step="1" style="width: 120px; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
                </div>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background-color: #006064;">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 style="color: #D35400; margin-top: 0;">¿Eliminar Medico?</h3>
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