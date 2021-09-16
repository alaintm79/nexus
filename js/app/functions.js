// Valiadación fomularios
function formValidation() {
    let forms = document.getElementsByClassName('needs-validation');

    if (forms.length > 0) { // Validación si existe en el DOM

        // Bucle para recorrer los formularios para aplicar estilo y evitar el envio
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    }
}

// Asignar atributos src y heigh al iframe y mostrar el la ventana modal
function modalShow(title, url, size, height, scroll) {

    //Limpieza de iframe basado en https://www.iteramos.com/pregunta/81248/descargaeliminar-contenido-de-un-iframe
    //generador de id para la carga de iframe basado en https://support.google.com/dcm/answer/2837459?hl=es

    let modalDialog = document.getElementById('modalDialog');
    let modalTitle = document.getElementById('modalTitle');
    let ifm = document.getElementById('ifmContent');
    let ifmContent = ifm.contentDocument || el.contentWindow.document;

    // Establecer título
    modalTitle.innerHTML = title;

    // Comprobar y eliminar clases de tamaño
    if (modalDialog.classList.contains('modal-lg')) {
        modalDialog.classList.remove('modal-lg');
    } else if (modalDialog.classList.contains('modal-xl')) {
        modalDialog.classList.remove('modal-xl');
    }

    // Establecer clase de tamaño
    if (size === null) {
        modalDialog.classList.add('modal-lg');
    } else {
        modalDialog.classList.add(size);
    }

    // Reiniciar contenido del iframe
    ifmContent.documentElement.innerHTML = "";

    // Establecer atributos del iframe
    ifm.setAttribute('src', url);

    if (scroll) {
        ifm.setAttribute('scrolling', "yes");
    } else {
        ifm.setAttribute('scrolling', "no");
    }

    ifm.style.height = height + 'px';

    $('#modal').modal('show');
}

// Funciones Boostrap Table --------------------------------

// Unir nombre y apellidos en una sola celda
function nameFormatter(value, row) {
    return row.nombre + ' ' + row.apellidos;
}

// Formato para valores booleanos
function boolFormatter(value, row, index) {
    return (value) ? 'SI' : 'NO';
}

// Formato para valores null
function nullFormatter(value, row, index) {
    return (value) ? value : '';
}

// Botones de acción editar y vista previa
function operateFormatter(value, row, index) {
    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>',
        '<button class="btn btn-secondary btn-modal-edit" title="Editar" data-toggle="tooltip" data-placement="top" title="Editar">',
        '<i class="fas fa-pencil-alt fa-sm"></i>',
        '</button>',
        '</div>'
    ].join('')
}

function operateFormatterView(value, row, index) {
    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        '<button class="btn btn-secondary btn-modal-detail" title="Detalles" data-toggle="tooltip" data-placement="top" title="Detalle">',
        '<i class="fas fa-eye fa-sm"></i>',
        '</button>',
    ].join('')
}

function operateFormatterEdit(value, row, index) {
    return [
        '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
        '<button class="btn btn-secondary btn-modal-edit" title="Modificar" data-toggle="tooltip" data-placement="top" title="Modificar">',
        '<i class="fas fa-pencil-alt fa-sm"></i>',
        '</button>',
        '</div>'
    ].join('')
}

function dateFormatter(value, row, index) {
    if (value !== null) {
        return moment(value.date).format('YYYY-MM-DD');
    } else {
        return '';
    }
}

function tipoFormatter(value, row, index) {
    if (value !== 'p') {
        return 'PROVEEDOR';
    } else {
        return 'CLIENTE';
    }
}

function dateSorter(a, b) {
    if (new Date(a) > new Date(b)) return 1;
    if (new Date(a) < new Date(b)) return -1;
    return 0;
}

function indexFormatter(value, row, index) {
    return index + 1;
}

function currencyFormatter(value, row, index) {
    return (value) ? value : '0.00';
}

function buttons() {
    return {
        btnClear: {
            text: 'Limpiar',
            icon: 'fa-eraser',
            event: function () {
                $table.bootstrapTable('clearFilterControl');
            },
            attributes: {
                title: 'Limpiar filtros activos'
            }
        },
    }
}
