/*! backup.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062201
 */
import swal from 'sweetalert';

(function () {

    /*** Vars ***/
    const action = document.getElementById("action");
    const btnAction = document.getElementById("btnAction");

    /*** Methods & Functions ***/

    /*** Inits & Event Listeners ***/
    btnAction.addEventListener('click', (event) => {
        let selected = action.options[action.selectedIndex].value;

        if(selected === 'eliminar'){
            swal({
                text: "Esta seguro de eliminar permanentemente las copias de respaldo seleccionadas",
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
            'click .btn-modal-restore': function (e, value, row, index) {
                swal({
                    text: "Esta seguro de restaurar la copia de respaldo " + row.archivo,
                    buttons: true,
                    dangerMode: true,
                    with: '350px'
                })
                .then((willRestore) => {
                    if (willRestore) {
                        window.location.href = '/sistema/backup/' + row.archivo + '/restore';
                    }
                });
            },
            'click .btn-modal-eliminar': function (e, value, row, index) {
                swal({
                    text: "Esta seguro de eliminar la copia de respaldo " + row.archivo,
                    buttons: true,
                    dangerMode: true,
                    width: '800px'
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = '/sistema/backup/' + row.archivo + '/delete';
                    }
                });
            }
        }
})();
