// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Contacto', '/contactos/ubicacion/' + row.id + '/edit', 'modal-lg')
    }
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
