<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pe Farma</title>
    <!-- Vinculando el CSS de Laravel Vite o Mix -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<header id="mainHeader">
        <!-- Logo a la izquierda redirigiendo al Welcome -->
        <div class="logo">
            <a href="{{ url('/') }}">PE FARMA</a>
        </div>

        <!-- Botones a la derecha -->
        <div class="header-right">
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn">Ingresar</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                @endif
            @else

                <span style="margin-right: 20px; font-weight: 600; color: var(--secondary);">
                    Hola, {{ Auth::user()->name }}
                </span>
                
                <a href="{{ route('logout') }}" class="btn" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
    </header>

    <main>
        <!-- Aquí se inyectará el contenido de las otras vistas -->
        @yield('content')
    </main>

</body>
</html> 