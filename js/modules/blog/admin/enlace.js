// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Enlace', '/blog/admin/enlaces/' + row.id + '/edit', 'modal-lg')
    }
};

// Formato para valores booleanos
function boolFormatter(value, row, index){
    return (value) ? 'ACTIVO' : 'INACTIVO';
}

// Formato para los valores de tipo
function tipoFormatter(value, row) {
    if(value == 'APP'){
        return 'APLICACIÓN'
    } else {
        return 'SITIO'
    }
}

const $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});
