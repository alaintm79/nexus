let $table = $('#table');

$table.bootstrapTable({
    loadingFontSize: '16px'
});

$('#modal').on('hidden.bs.modal', function (e) {
    $table.bootstrapTable('refresh');
});

// Acciones
window.operateEvents = {
    'click .btn-modal-edit': function (e, value, row, index) {
        modalShow('Modificar Usuario', '/usuarios/' + row.id + '/edit', 'modal-lg', '796')
    }
};

// Formatos
function isActiveFormatter(value, row, index){
    if(row.usuario != ''){
        return (value) ? 'SI' : 'NO';
    }

    return ''
}

function rolFormatter(value, row){
    let rol = value

    if(value != null){
        rol = value.replace(/[\[\|&;\$%@"<>\(\)\]\+]/g, "");
        rol = rol.replace(/[,]/g, ", ");

        return rol
    }

    return ''
}

function syncFormatter(value, row, index){
    if(row.usuario != '' && row.isSyncPassword != null){
        return (value) ? 'SI' : 'NO';
    }

    return ''
}
