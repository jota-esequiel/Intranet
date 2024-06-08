// Autor: Gabi
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('dtnasc').addEventListener('input', function() {
        mascaraData(this);
    });

    document.getElementById('fone').addEventListener('input', function() {
        mascaraTelefone(this);
    });

    document.getElementById('cpf').addEventListener('input', function() {
        mascaraCPF(this);
    });

    document.getElementById('cep').addEventListener('input', function() {
        mascaraCEP(this);
    });
});

/**
 * Aplica máscara para formato de data (dd/mm/aaaa).
 * @param {*} input - O elemento de entrada onde a máscara será aplicada.
 * @author Gabrielli
 */
function mascaraData(input) {
    console.log("A função de data está sendo chamada");
    input.value = input.value.replace(/\D/g, '').replace(/(\d{2})(\d)/, '$1/$2').replace(/(\d{2})(\d)/, '$1/$2').replace(/(\d{4})\d+?$/, '$1');
}

/**
 * Aplica máscara para formato de CPF (xxx.xxx.xxx-xx).
 * @param {*} input - O elemento de entrada onde a máscara será aplicada.
 * @author Gabrielli
 */
function mascaraCPF(input) {
    input.value = input.value.replace(/\D/g, ''); 
    
    var primeiroDigito = input.value.charAt(0);
    if (primeiroDigito === '0') {
        input.value = '0' + input.value.substring(1); 
    }
    
    input.value = input.value.replace(/(\d{3})(\d)/, '$1.$2')
                             .replace(/(\d{3})(\d)/, '$1.$2')
                             .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
}

/**
 * Aplica máscara para formato de telefone ((xx) xxxxx-xxxx).
 * @param {*} input - O elemento de entrada onde a máscara será aplicada.
 * @author Gabrielli
 */
function mascaraTelefone(input) {
    input.value = input.value.replace(/\D/g, ''); 

    if (input.value.length > 2) {
        input.value = '(' + input.value.substring(0, 2) + ') ' + input.value.substring(2);
    }

    if (input.value.length > 10) {
        input.value = input.value.substring(0, 10) + '-' + input.value.substring(10);
    }

    input.value = input.value.substring(0, 15);
}

/**
 * Aplica máscara para formato de CEP (xxxxx-xxx).
 * @param {*} input - O elemento de entrada onde a máscara será aplicada.
 * @author Gabrielli
 */
function mascaraCEP(input) {
    input.value = input.value.replace(/\D/g, ''); 

    if (input.value.length > 5) {
        input.value = input.value.substring(0, 5) + '-' + input.value.substring(5);
    }

    input.value = input.value.substring(0, 9);
}