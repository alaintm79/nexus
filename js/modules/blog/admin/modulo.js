// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Módulo', '/blog/admin/modulos/' + row.id + '/edit', 'modal-lg')
    }
};

// Formato para valores booleanos
function boolFormatter(value, row, index){
    return (value) ? 'ACTIVO' : 'INACTIVO';
}

const $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
