window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        modalShow('Detalle Pago', '/logistica/pagos/' + row.id + '/show', 'modal-xl', '473')
    },
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Pago', '/logistica/pagos/' + row.id + '/edit', 'modal-xl', '664')
    }
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
