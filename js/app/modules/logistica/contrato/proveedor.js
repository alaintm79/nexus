window.operateEvents = {
    'click .btn-modal-detail': function (e, value, row, index) {
        modalShow('Detalle Contrato', '/logistica/contrato/proveedor/' + row.id + '/show', 'modal-xl')
    },
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Contrato', '/logistica/contrato/proveedor/' + row.id + '/edit', 'modal-xl')
    },
    'click .btn-modal-suplemento': function (e, value, row, index) {
        modalShow('Suplemento Contrato', '/logistica/suplemento/contrato/' + row.id, 'modal-xl');
    },
    'click .btn-modal-ejecucion': function (e, value, row, index) {
        modalShow('Ejecución Contrato', '/logistica/contrato/ejecucion/' + row.id, 'modal-xl');
    }
}

let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
