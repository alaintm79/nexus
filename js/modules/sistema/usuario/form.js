const hasAccount = document.getElementById('hasAccount');

function userAcount(){
    let forms = document.querySelectorAll('.account');
    let isChecked = hasAccount.checked ? true : false;

    forms.forEach(function(form){
        if(isChecked){
            form.disabled = false;
        } else {
            form.value = '';
            form.disabled = true;
            $('.selectpicker').selectpicker('deselectAll');
        }
    });

    $('.selectpicker').selectpicker('refresh');
}

if(hasAccount.checked){
    userAcount();
}

hasAccount.addEventListener('click',function(){
    userAcount();
});
