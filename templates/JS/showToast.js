/**
 * Exibe um toast na parte superior da tela com uma mensagem de sucesso ou erro.
 * O toast é removido automaticamente após 5 segundos.
 *
 * @param {string} type - O tipo do toast. Pode ser 'success' para mensagem de sucesso ou 'error' para mensagem de erro.
 * @param {string} message - A mensagem que será exibida no toast.
 * @author Gabrielli
 */

function showToast(type, message) {
    var toastContainer = document.getElementById('toast-container');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.classList.add('toast-container');
        document.body.appendChild(toastContainer);
    }

    var toast = document.createElement('div');
    toast.className = 'toast show toast-top'; 

    if (type === 'success') {
        toast.classList.add('toast-success');
    } else if (type === 'error') {
        toast.classList.add('toast-error');
    }

    var toastBody = document.createElement('div');
    toastBody.className = 'toast-body';
    toastBody.textContent = message;

    toast.appendChild(toastBody);

    toastContainer.appendChild(toast);

    setTimeout(function() {
        toast.classList.add('hide'); 

    setTimeout(function() {
        toast.parentNode.removeChild(toast); 
    }, 300); 
}, 5000); 
} 

