@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>Crear Cuenta</h2>
    @if ($errors->any())
    <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px; border-radius: 5px;">
        <strong>¡Ups! Hubo problema:</strong>
        <ul style="margin-top: 5px; margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label>Nombre Completo</label>
            <input type="text" name="name" placeholder="Ingresa tu nombre" required>
        </div>
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" placeholder="ejemplo@correo.com" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" id="password" placeholder="Crea una contraseña" required>
            
            <div class="password-strength-container">
                <div class="strength-bar-bg">
                    <div id="strength-bar" class="strength-bar"></div>
                </div>
                <span id="strength-text" class="strength-text"></span>
            </div>
        </div>
        <div class="form-group">
            <label>Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" placeholder="Repite tu contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Registrarme</button>
    </form>
</div>
@endsection