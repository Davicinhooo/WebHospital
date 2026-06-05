/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');

// ==========================================
// LÓGICA DEL MEDIDOR DE CONTRASEÑA
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    // ¡Importante! Solo ejecutamos esto si estamos en la vista de registro (donde existen estos IDs)
    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            let strength = 0;

            if (val.length > 0) {
                // Sistema de puntos de seguridad
                if (val.length >= 8) strength += 1; // Longitud mínima
                if (val.match(/[a-z]+/)) strength += 1; // Contiene minúsculas
                if (val.match(/[A-Z]+/)) strength += 1; // Contiene mayúsculas
                if (val.match(/[0-9]+/)) strength += 1; // Contiene números
                if (val.match(/[$@#&!_*-]+/)) strength += 1; // Contiene símbolos

                // Resetear clases previas
                strengthBar.className = 'strength-bar'; 
                strengthText.className = 'strength-text'; 

                // Evaluar puntuación y actualizar UI
                if (strength <= 2) {
                    strengthBar.style.width = '33%';
                    strengthBar.classList.add('strength-weak');
                    strengthText.textContent = 'Débil';
                    strengthText.classList.add('text-weak');
                } else if (strength === 3 || strength === 4) {
                    strengthBar.style.width = '66%';
                    strengthBar.classList.add('strength-medium');
                    strengthText.textContent = 'Media';
                    strengthText.classList.add('text-medium');
                } else if (strength === 5) {
                    strengthBar.style.width = '100%';
                    strengthBar.classList.add('strength-strong');
                    strengthText.textContent = 'Fuerte';
                    strengthText.classList.add('text-strong');
                }
            } else {
                // Si el input está vacío
                strengthBar.style.width = '0';
                strengthText.textContent = '';
            }
        });
    }
});

// ==========================================
// LÓGICA DE MODALES - GESTIÓN DE PACIENTES
// ==========================================

// Funciones globales para abrir y cerrar
window.openModal = function(id) { 
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('active'); 
};

window.closeModal = function(id) { 
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('active'); 
};

// Llenar datos en el modal de Edición
window.openEditModal = function(button) {
    const id = button.getAttribute('data-id');
    
    document.getElementById('edit_first').value = button.getAttribute('data-first');
    document.getElementById('edit_last').value = button.getAttribute('data-last');
    document.getElementById('edit_dob').value = button.getAttribute('data-dob');
    document.getElementById('edit_gender').value = button.getAttribute('data-gender');
    document.getElementById('edit_phone').value = button.getAttribute('data-phone');
    document.getElementById('edit_address').value = button.getAttribute('data-address');
    document.getElementById('edit_blood').value = button.getAttribute('data-blood');

    // Construimos la ruta dinámica de actualización
    document.getElementById('form-edit').action = `/pacientes/${id}`;
    
    window.openModal('modal-edit');
};

// Preparar Modal de Eliminación
window.openDeleteModal = function(id) {
    document.getElementById('form-delete').action = `/pacientes/${id}`;
    window.openModal('modal-delete');
};
