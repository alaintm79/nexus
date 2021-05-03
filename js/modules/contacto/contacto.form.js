Inputmask().mask(document.querySelectorAll("input"));

const content = document.getElementById('content');

// Validación si ha cambiado la información en el formulario
const form = document.getElementById("form");
const btnSubmit = document.getElementById("btnSubmit");

// Asignación de CUBA como país por defecto.
if(content.dataset.action == 'create'){
    $('#pais').selectpicker('val', '53');
}

btnSubmit.addEventListener('click', function() {
const ext = document.getElementById("extension");
const telTrabajo = document.getElementById("telefonoFijoTrabajo");

if(ext.value != ''){
    telTrabajo.setAttribute('required', 'required')
} else {
    telTrabajo.removeAttribute('required')
}

}, false);
