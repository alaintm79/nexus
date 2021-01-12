/*! app.js
 * ================
 *
 * @Author  Alain TM
 * @Email   <alaintm79@gmail.com>
 * @version 0.3
 */

"use strict";

(function() {
    'use strict';
    window.addEventListener('load', function() {
        init();
    }, false);
})();

// Función de Inicio
function init(){
    // Obtener los formularios a los que aplicar estilos personalizados de validación Bootstrap
    let forms = document.getElementsByClassName('needs-validation');

    if (forms.length > 0){ // Validación si existe en el DOM

        // Bucle para recorrer los formularios para aplicar estilo y evitar el envio
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    }

    // Obtener los botones con clase .btn-modal a los que aplicar evento click para activar ventana modal
    let btnModals = document.getElementsByClassName("btn-modal");

    if (btnModals){ // Validación si existe en el DOM

        // Bucle para recorrer los botones estableciendo el evento click
        Array.prototype.filter.call(btnModals, function(btn){
            btn.addEventListener('click', function(){
                let title = this.getAttribute("data-title");
                let url = this.getAttribute("data-url");
                let size = this.getAttribute("data-size");
                let height = this.getAttribute("data-height");

                modalShow(title, url, size, height);

            }, false);
        });
    }

    // Permitir cerrar el modal de acciones desde el iframe
    let modal = document.getElementById("modal");

    if (modal){
        window.closeModal = function(){
            $('#modal').modal('hide');
        };
    }

    // Chequeo de si existe un error de validación
    let error = document.querySelectorAll('.form-error-message');
    if(error.length) document.querySelector('.card').classList.add('has-form-error');

    // Sidebar Toggle

    const toggle = document.querySelector('.sidebar-toggle');

    if(toggle){
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            let body = document.getElementsByTagName('body')[0];
            body.className = body.className + ' sidebar-collapse';
        }

        toggle.addEventListener('click', function(event) {
            event.preventDefault();

            if (Boolean(sessionStorage.getItem("sidebar-toggle-collapsed"))) {
                sessionStorage.setItem("sidebar-toggle-collapsed", "");
            } else {
                sessionStorage.setItem("sidebar-toggle-collapsed", "1");
            }
        });

    }
}

// Asignar atributos src y heigh al iframe y mostrar el la ventana modal
function modalShow(title, url, size, height){

    //Limpieza de iframe basado en https://www.iteramos.com/pregunta/81248/descargaeliminar-contenido-de-un-iframe
    //generador de id para la carga de iframe basado en https://support.google.com/dcm/answer/2837459?hl=es

    let modalDialog = document.getElementById('modalDialog');
    let modalTitle = document.getElementById('modalTitle');
    let ifm = document.getElementById('ifmContent');
    let ifmContent = ifm.contentDocument || el.contentWindow.document;
    let rand = Math.random() + "";

    // Establecer título
    modalTitle.innerHTML = title;

    // Comprobar y eliminar clases de tamaño
    if(modalDialog.classList.contains('modal-lg')){
        modalDialog.classList.remove('modal-lg');
    } else if(modalDialog.classList.contains('modal-xl')){
        modalDialog.classList.remove('modal-xl');
    }

    // Establecer clase de tamaño
    if(size === null){
        modalDialog.classList.add('modal-lg');
    } else {
        modalDialog.classList.add(size);
    }

    // Reiniciar contenido del iframe
    ifmContent.documentElement.innerHTML = "";

    // Establecer atributos del iframe
    ifm.setAttribute('src', url);
    ifm.style.height = height + 'px';

    $('#modal').modal('show');
}

// Funciones Boostrap Table --------------------------------

// Unir nombre y apellidos en una sola celda
function nameFormatter(value, row) {
    return row.nombre + ' ' + row.apellidos;
}

// Formato para valores booleanos
function boolFormatter(value, row, index){
    return (value) ? 'SI' : 'NO';
}

// Formato para valores null
function nullFormatter(value, row, index){
    return (value) ? value : '';
}

// Formato para arrays
function arrayFormatter(value, row){

    let str = [];

    if (value.length > 0 && value !== null){
        str = value;
    }

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
    if(value !== null){
        // return moment(value.date).format('DD-MM-YYYY');
        return moment(value.date).format('YYYY-MM-DD');
    } else {
        return '';
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

function currencyFormatter(value, row, index){
    return (value) ? value : '0.00';
}

function buttons () {
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

if(document.getElementById("ifmContent")){
    iFrameResize({
        checkOrigin: false,
        warningTimeout: 0,
    }, '#ifmContent');
}

/**
 * Dependent Dropdown Menu setItemActivesDM
*/
function DDMSetItemActives(elp, els) {
    let selectedId = (elp.value || elp.options[elp.selectedIndex].value);
    let items = els.querySelectorAll('[data-id]');

    Array.prototype.filter.call(items, function(el){
        if(el.getAttribute('data-id') != selectedId){
            el.style.display = 'none';
        } else {
            el.style.display = 'inline-block';
        }
    });
}

let btnCancel = document.getElementById('btnCancel');

if(btnCancel){
    btnCancel.addEventListener('click', function () {
        window.parent.closeModal();
    }, false);
}

