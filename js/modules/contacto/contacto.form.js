Inputmask().mask(document.querySelectorAll("input"));

let content = document.getElementById('content');

// Validación si ha cambiado la información en el formulario
let form = document.getElementById("form");
let btnSubmit = document.getElementById("btnSubmit");

// Asignación de CUBA como país por defecto.
if(content.dataset.action == 'create'){
    $('#pais').selectpicker('val', '53');

}

btnSubmit.addEventListener('click', function() {
let ext = document.getElementById("extension"),
    telTrabajo = document.getElementById("telefonoFijoTrabajo");

if(ext.value != ''){
    telTrabajo.setAttribute('required', 'required')
} else {
    telTrabajo.removeAttribute('required')
}

}, false);
