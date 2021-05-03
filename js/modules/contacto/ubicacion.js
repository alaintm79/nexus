// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Ubicación', '/contactos/ubicacion/' + row.id + '/edit', 'modal-lg')
    }
}

const $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
