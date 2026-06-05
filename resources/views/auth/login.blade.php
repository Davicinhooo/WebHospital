@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" placeholder="ejemplo@correo.com" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Ingresar al Sistema</button>
    </form>
    
    <div class="divider">O CONTINUAR CON</div>
    <a href="{{ route('google.redirect') }}" class="btn-social btn-google">
        Google
    </a>
    <a href="{{ route('github.redirect') }}" class="btn-social btn-github">
        GitHub
    </a>
</div>
@endsection