"use strict";
/*! comentario.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */

import swal from 'sweetalert';

(function () {

    /*** Vars ***/
    const form = document.getElementById("form");
    const action = document.getElementById("action");
    const btnAction = document.getElementById("btnAction");

    /*** Methods & Functions ***/
    window.operateFormatter = function (value, row, index) {

        let btnEdit = [
            `<button class="btn btn-secondary btn-edit" title="Editar">`,
            `<i class="fas fa-pencil-alt fa-sm"></i>`,
            `</button>`,
        ].join('');

        let btnApprove = [
            `<button class="btn btn-secondary btn-approve" title="Aprobar">`,
            `<i class="fas fa-check fa-sm"></i>`,
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
            (!row.isReview) && (!row.isDelete) ? btnApprove : ``,
            row.isDelete ? btnRemove : btnDelete,
            '</div>'
        ].join('');
    }

    /*** Inits & Event Listeners ***/

    btnAction.addEventListener('click', (event) => {
        let selected = action.options[action.selectedIndex].value;

        if(selected === 'eliminar'){
            swal({
                text: "Esta seguro de eliminar permanentemente los comentarios seleccionados",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        } else if (selected !== 'eliminar'){
            form.submit();
        }
        event.preventDefault()
    }, true);

    // BoostrapTable Acciones
    window.operateEvents = {
        'click .btn-edit': function (e, value, row, index) {
            window.location.href = '/blog/admin/comentarios/' + row.id + '/edit';
            e.preventDefault();
        },
        'click .btn-approve': function (e, value, row, index) {
            window.location.href = '/blog/admin/comentarios/' + row.id + '/approve';
            e.preventDefault();
        },
        'click .btn-delete': function (e, value, row, index) {
            if (row.isDelete) {
                swal({
                    text: "Esta seguro de eliminar el comentario",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location.href = '/blog/admin/comentarios/' + row.id + '/remove';
                            e.preventDefault();
                        }
                    });
            } else {
                window.location.href = '/blog/admin/comentarios/' + row.id + '/delete';
                e.preventDefault();
            }
            e.preventDefault();
        },
        'click .btn-restore': function (e, value, row, index) {
            window.location.href = '/blog/admin/comentarios/' + row.id + '/restore';
            e.preventDefault();
        },
    }
})();
