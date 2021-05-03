// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Directorio', '/blog/admin/directorios/' + row.id + '/edit', 'modal-lg')
    },
    'click .btn-modal-detail': function (e, value, row, index) {
        modalShow('Detalles Directorio', '/files/?conf=directorio&route=/' + row.ruta, 'modal-xl', '640', true)
    },
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
