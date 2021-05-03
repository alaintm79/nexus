// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Módulo', '/blog/admin/opciones/' + row.id + '/edit', 'modal-lg')
    }
};

// Formato para valores booleanos
function boolFormatter(value, row, index){
    return (value) ? 'ACTIVO' : 'INACTIVO';
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});

// Opciones con Filemanager
const splash = document.getElementById('splash');
const fileManager = document.getElementById("fileManager");

splash.addEventListener('click', event => {

    $('#modalFile').modal('show');

    fileManager.addEventListener("load", function () {
        setInterval(function(){
            fileManager.contentWindow.document.querySelectorAll('.select').forEach(el => el.addEventListener('click', event => {
                splash.value = event.target.getAttribute("data-path");
                $('#modalFile').modal('hide');
            }));
        }, 10);
    });
});
