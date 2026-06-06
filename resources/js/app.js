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
// LÓGICA DE MODALES GLOBALES
// ==========================================

window.openModal = function(id) { 
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('active'); 
};

window.closeModal = function(id) { 
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('active'); 
};

// ==========================================
// MODALES DE ACTUALIZAR - ELIMINAR (PACIENTES)
// ==========================================

window.openEditPatientsModal = function(button) {
    const id = button.getAttribute('data-id');
    
    document.getElementById('edit_first').value = button.getAttribute('data-first');
    document.getElementById('edit_last').value = button.getAttribute('data-last');
    document.getElementById('edit_dob').value = button.getAttribute('data-dob');
    document.getElementById('edit_gender').value = button.getAttribute('data-gender');
    document.getElementById('edit_phone').value = button.getAttribute('data-phone');
    document.getElementById('edit_address').value = button.getAttribute('data-address');
    document.getElementById('edit_blood').value = button.getAttribute('data-blood');

    document.getElementById('form-edit').action = '/pacientes/' + id;

    window.openModal('modal-edit');
};

window.openDeletePatientsModal = function(id) {
    document.getElementById('form-delete').action = '/pacientes/' + id;
    window.openModal('modal-delete');
};


// ==========================================
// MODALES DE ACTUALIZAR - ELIMINAR (MEDICOS)
// ==========================================

window.openEditDoctorsModal = function(button) {

    document.getElementById('edit_first').value = button.getAttribute('data-first');
    document.getElementById('edit_last').value = button.getAttribute('data-last');
    document.getElementById('edit_specialty').value = button.getAttribute('data-specialty');
    document.getElementById('edit_phone').value = button.getAttribute('data-phone');
    document.getElementById('edit_email').value = button.getAttribute('data-email');
    document.getElementById('edit_license').value = button.getAttribute('data-license');
    document.getElementById('edit_years_of_experiences').value = button.getAttribute('data-years_of_experiences');

    let id = button.getAttribute('data-id');
    document.getElementById('form-edit').action = '/medicos/' + id;

    openModal('modal-edit');
};

window.openDeleteDoctorsModal = function(id) {
    document.getElementById('form-delete').action = '/medicos/' + id;
    openModal('modal-delete');
};

// ==========================================
// MODALES DE ACTUALIZAR - ELIMINAR (CITAS)
// ==========================================

window.openEditCitaModal = function(button) {
    const id = button.getAttribute('data-id');

    document.getElementById('edit_date').value = button.getAttribute('data-date');
    document.getElementById('edit_patient').value = button.getAttribute('data-patient');
    document.getElementById('edit_doctor').value = button.getAttribute('data-doctor');
    document.getElementById('edit_reason').value = button.getAttribute('data-reason');
    document.getElementById('edit_status').value = button.getAttribute('data-status');
    document.getElementById('edit_room').value = button.getAttribute('data-room');
    document.getElementById('edit_observations').value = button.getAttribute('data-observations');

    document.getElementById('form-edit').action = '/citas/' + id;
    window.openModal('modal-edit');
};

window.openDeleteCitaModal = function(id) {
    document.getElementById('form-delete').action = '/citas/' + id;
    window.openModal('modal-delete');
};

// ==========================================
// MODALES DE ACTUALIZAR - ELIMINAR (DIAGNOSTICOS)
// ==========================================

window.openEditDiagnosticModal = function(button) {
    const id = button.getAttribute('data-id');

    document.getElementById('edit_date').value = button.getAttribute('data-date');
    document.getElementById('edit_patient').value = button.getAttribute('data-patient');
    document.getElementById('edit_doctor').value = button.getAttribute('data-doctor');
    document.getElementById('edit_severity').value = button.getAttribute('data-severity');
    document.getElementById('edit_description').value = button.getAttribute('data-description');
    document.getElementById('edit_type_diagnosis').value = button.getAttribute('data-type_diagnosis');
    document.getElementById('edit_recommendations').value = button.getAttribute('data-recommendations');

    document.getElementById('form-edit').action = '/diagnosticos/' + id; 
    window.openModal('modal-edit');
};

window.openDeleteDiagnosticModal = function(id) {
    document.getElementById('form-delete').action = '/diagnosticos/' + id;
    window.openModal('modal-delete');
};

// ==========================================
// MODALES DE ACTUALIZAR - ELIMINAR (TRATAMIENTOS)
// ==========================================
window.openEditTratamientoModal = function(button) {
    const id = button.getAttribute('data-id');

    document.getElementById('edit_name').value = button.getAttribute('data-name');
    document.getElementById('edit_diagnostic').value = button.getAttribute('data-diagnostic');
    document.getElementById('edit_doctor').value = button.getAttribute('data-doctor');
    document.getElementById('edit_duration').value = button.getAttribute('data-duration');
    document.getElementById('edit_status').value = button.getAttribute('data-status');
    document.getElementById('edit_frequency').value = button.getAttribute('data-frequency');
    document.getElementById('edit_description').value = button.getAttribute('data-description');

    document.getElementById('form-edit').action = '/tratamientos/' + id;
    window.openModal('modal-edit');
};

window.openDeleteTratamientoModal = function(id) {
    document.getElementById('form-delete').action = '/tratamientos/' + id;
    window.openModal('modal-delete');
};


// ==========================================
// MODALES DE ACTUALIZAR - ELIMINAR (MEDICAMENTOS)
// ==========================================
window.openEditMedicacionModal = function(button) {
    const id = button.getAttribute('data-id');

    document.getElementById('edit_name').value = button.getAttribute('data-name');
    document.getElementById('edit_treatment').value = button.getAttribute('data-treatment');
    document.getElementById('edit_dose').value = button.getAttribute('data-dose');
    document.getElementById('edit_frequency').value = button.getAttribute('data-frequency');
    document.getElementById('edit_duration').value = button.getAttribute('data-duration');
    document.getElementById('edit_supplier').value = button.getAttribute('data-supplier');
    document.getElementById('edit_side').value = button.getAttribute('data-side');

    document.getElementById('form-edit').action = '/medicaciones/' + id;
    window.openModal('modal-edit');
};

window.openDeleteMedicacionModal = function(id) {
    document.getElementById('form-delete').action = '/medicaciones/' + id;
    window.openModal('modal-delete');
};

// ==========================================
// GENERADOR DE LICENCIAS - MEDICOS
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    // Buscamos el botón y el input por sus IDs
    const btnGenerar = document.getElementById('btn_generar_licencia');
    const inputLicencia = document.getElementById('licencia_input');

    if (btnGenerar && inputLicencia) {
        btnGenerar.addEventListener('click', async function() {
            try {
                // Petición al backend
                const response = await fetch('/medicos/generar-licencia');
                const data = await response.json();
                
                // Pintamos el resultado en el input
                inputLicencia.value = data.licencia;
            } catch (error) {
                console.error("Error al generar la licencia:", error);
                alert("Hubo un error al generar la licencia.");
            }
        });
    }
});

// La función del botón generar licencia se queda igual (dentro del DOMContentLoaded)
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerar = document.getElementById('btn_generar_licencia');
    const inputLicencia = document.getElementById('licencia_input');

    if (btnGenerar && inputLicencia) {
        btnGenerar.addEventListener('click', async function() {
            try {
                const response = await fetch('/medicos/generar-licencia');
                const data = await response.json();
                inputLicencia.value = data.licencia;
            } catch (error) {
                console.error("Error al generar la licencia:", error);
                alert("Hubo un error al generar la licencia.");
            }
        });
    }
});

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

