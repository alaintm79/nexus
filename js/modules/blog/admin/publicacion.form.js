const ckeContenido = CKEDITOR.instances['publicacion_contenido'];
const contenido = document.getElementById('publicacion_contenido');

ckeContenido.on('contentDom', function() {
    this.document.on('click', function(){
        contenido.classList.add('is-focus');

        if(contenido.classList.contains('is-validated') && !contenido.classList.contains('is-invalid')){
            contenido.classList.remove('is-focus');
        }

        if(contenido.classList.contains('is-validated') && contenido.classList.contains('is-invalid')){
            contenido.classList.remove('is-focus');
        }
    });

    this.on('blur', function(){
        contenido.classList.remove('is-focus');
    });

    this.on('change', function(){
        if(ckeContenido.getData().replace(/<[^>]*>/gi, '').length > 0 && contenido.classList.contains('is-invalid')){
            contenido.classList.remove('is-invalid');
        }

        if(ckeContenido.getData().replace(/<[^>]*>/gi, '').length == 0 && contenido.classList.contains('is-validated')){
            contenido.classList.add('is-invalid');
        }
    });
});

document.getElementById('btnSubmit').addEventListener('click', function (event) {
    contenido.classList.add('is-validated');
    if( ckeContenido.getData().replace(/<[^>]*>/gi, '').length == 0) {
        contenido.classList.add('is-invalid');
        formValidation(event);
    } else {
        contenido.classList.remove('is-invalid');
    }
}, false);
