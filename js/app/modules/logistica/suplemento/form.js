let vigencia = document.getElementById('vigencia');

function suplementoVigencia(){
    let vigenciaEstado = vigencia.selectedOptions[0].label;
    let vigenciaFecha = document.getElementById('vigenciaFecha');
    let fecha = document.getElementById('fechaVigencia');

    vigenciaFecha.classList.toggle('d-none', vigenciaEstado !== "CUMPLIMIENTO FECHA");

    if(vigenciaEstado === "CUMPLIMIENTO FECHA"){
        fecha.disabled = false;
        fecha.required = true;
    } else {
        fecha.disabled = true;
        fecha.required = false;
    }

}

window.addEventListener('load', function() {
    Inputmask({removeMaskOnSubmit: true}).mask(document.querySelectorAll("input"));

    suplementoVigencia();
});

vigencia.addEventListener('change', function(event) {
    suplementoVigencia();
}, false);
