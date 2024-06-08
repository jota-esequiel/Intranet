/**
 * @param {*} formId
 * @author Gabi
 * @author Alex
 * @description - main.js - Funções JS usadas no BackEnd 
 */

function toggleFilterForm(formId) {
    var form = document.getElementById(formId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}