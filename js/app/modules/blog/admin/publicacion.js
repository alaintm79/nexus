"use strict";
/*! publicacion.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */
import swal from 'sweetalert';

(function () {

    /*** Vars ***/
    const action = document.querySelector("#action");
    const btnAction = document.querySelector("#btnAction");
    const estado = document.querySelector("#table").dataset.estado;
    const form = document.querySelector("#form");

    /*** Methods & Functions ***/
    window.operateFormatter = function (value, row, index) {

            let btnEdit = [
                `<button class="btn btn-secondary btn-edit" title="Editar">`,
                `<i class="fas fa-pencil-alt fa-sm"></i>`,
                `</button>`,
            ].join('');

            let btnDelete = [
                `<button class="btn btn-secondary btn-delete" title="Borrar">`,
                `<i class="fas fa-trash fa-sm"></i>`,
                `</button>`,
            ].join('');

            let btnRemove = [
                `<button class="btn btn-secondary btn-delete" title="Eliminar">`,
                `<i class="fas fa-eraser fa-sm"></i>`,
                `</button>`,
            ].join('');

            let btnRestore = [
                `<button class="btn btn-secondary btn-restore" title="Restaurar">`,
                `<i class="fas fa-trash-restore fa-sm"></i>`,
                `</button>`,
            ].join('');

            return [
                '<div class="btn-group btn-group-sm" role="group" aria-label="Acciones">',
                row.isDelete ? btnRestore : btnEdit,
                row.isDelete ? btnRemove : btnDelete,
                '</div>'
            ].join('');
    }

    // Formato para valores de comentarios
    window.commentFormatter = function (value, row, index) {
        return '<a href="#" class="fa fa-comment-alt fa-comment-size" style="position:relative;color:grey;"><span class="fa-count-comment">' + value + '</span></a>';
    }

    /*** Inits & Event Listeners ***/

    btnAction.addEventListener('click', (event) => {
        let selected = action.options[action.selectedIndex].value;

        if(selected == 'eliminar'){
            swal({
                text: "Esta seguro de eliminar permanentemente las publicaciones seleccionadas",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if(willDelete){
                    form.submit();
                }
            });
        } else {
            form.submit();
        }

        event.preventDefault()
    });

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-edit': function (event, value, row, index) {
            window.location.href = '/blog/admin/publicaciones/' + row.id + '/edit';
            event.preventDefault();
        },
        'click .btn-delete': function (event, value, row, index) {
            switch (estado) {
                case 'publicado':
                case 'borrador':
                case 'programado':
                    swal({
                        text: "Esta seguro de eliminar la publicación " + row.titulo,
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if(willDelete){
                            window.location.href = '/blog/admin/publicaciones/' + row.id + '/delete';
                            event.preventDefault();
                        }
                    });

                    event.preventDefault();
                break;
                case 'eliminado':
                    window.location.href = '/blog/admin/publicaciones/' + row.id + '/remove';
                    event.preventDefault();
                break;
            }

            event.preventDefault();
        },
        'click .btn-restore': function (event, value, row, index) {
            window.location.href = '/blog/admin/publicaciones/' + row.id + '/restore';
            event.preventDefault();
        },
    }
})();
