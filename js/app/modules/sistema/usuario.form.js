/*! usuario.form.js
 * ================
 *
 * @Author Alain TM
 * @Email  <alaintm79@gmail.com>
 * @Update 21062001
 */

import Inputmask from "inputmask";

(function () {

    /*** Vars ***/
    const hasAccount = document.getElementById('hasAccount');

    /*** Methods & Functions ***/
    const userAccount = function(){
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

	/*** Inits & Event Listeners ***/
    Inputmask().mask(document.querySelectorAll("input"));

    if(hasAccount.checked){
        userAccount();
    }

    hasAccount.addEventListener('click', function(){
        userAccount();
    });

})();
