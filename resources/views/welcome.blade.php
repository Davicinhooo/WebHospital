@extends('layouts.app')

@section('content')

<!-- SECCIÓN 1: HERO (Portada principal) -->
<div class="hero-section">
    <div class="hero-content animate-fade-up">
        <h1>Bienvenido a <br>Clínica Pe Farma</h1>
        <p>Tu salud, gestionada con elegancia y eficiencia. Descubre una plataforma diseñada para centralizar y optimizar la atención médica y farmacéutica en un entorno moderno y seguro.</p>
        
        <div style="margin-top: 30px;">
            @auth
                <a href="{{ route('home') }}" class="btn btn-primary" style="font-size: 16px; padding: 12px 30px; background-color: #006064; border-color: #006064; text-decoration: none;">
                    Ir al Panel de Gestión →
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="font-size: 16px; padding: 12px 30px; background-color: #006064; border-color: #006064; text-decoration: none;">
                    Ingresar al Sistema
                </a>
            @endauth
        </div>
    </div>
    
    <div class="hero-image animate-fade-up delay-1">
        <img src="{{ asset('images/clinica.jpg') }}" alt="Clínica Pe Farma">
    </div>
</div>

<!-- SECCIÓN 2: SERVICIOS Y CARACTERÍSTICAS -->
<div class="services-section">
    <div class="animate-fade-up delay-2">
        <h2 style="color: #006064; font-size: 32px; margin-bottom: 10px; margin-top: 0;">Nuestros Servicios</h2>
        <p style="color: var(--secondary); max-width: 600px; margin: 0 auto;">Ofrecemos soluciones integrales para el bienestar de nuestros pacientes, combinando tecnología de punta con la mejor atención humana.</p>
    </div>

    <div class="services-grid">
        <!-- Tarjeta 1 -->
        <div class="service-card animate-fade-up delay-1">
            <div class="service-icon">👨‍⚕️</div>
            <h3>Atención Médica</h3>
            <p style="color: var(--secondary); font-size: 15px; margin: 0;">Contamos con los mejores especialistas dispuestos a brindarte una atención personalizada y de la más alta calidad.</p>
        </div>
        
        <!-- Tarjeta 2 -->
        <div class="service-card animate-fade-up delay-2">
            <div class="service-icon">💊</div>
            <h3>Farmacia Integrada</h3>
            <p style="color: var(--secondary); font-size: 15px; margin: 0;">Amplio stock de medicamentos, tratamientos y productos de cuidado personal con disponibilidad inmediata.</p>
        </div>
        
        <!-- Tarjeta 3 -->
        <div class="service-card animate-fade-up delay-3">
            <div class="service-icon">📊</div>
            <h3>Historial Clínico Digital</h3>
            <p style="color: var(--secondary); font-size: 15px; margin: 0;">Acceso centralizado, seguro y rápido a tus diagnósticos, citas y recetas desde cualquier dispositivo.</p>
        </div>
    </div>
</div>

<!-- SECCIÓN 3: FOOTER -->
<footer class="footer animate-fade-up delay-3">
    <h3 style="margin-top: 0;">PE FARMA</h3>
    <p style="margin: 0; font-size: 14px; color: #B2EBF2;">&copy; {{ date('Y') }} Sistema de Gestión Clínica y Farmacéutica. Todos los derechos reservados.</p>
</footer>

@endsection