<?php
/**
 * Confirma uma ação exibindo uma mensagem JavaScript de confirmação.
 *
 * @param string $message Mensagem a ser exibida na caixa de diálogo de confirmação.
 * @return bool Retorna true se o usuário confirmar, false se cancelar.
 * @author Gabrielli
 */
function confirm($message) {
    echo "<script>";
    echo "if(confirm('$message')) {";
    echo "return true;";
    echo "} else {";
    echo "return false;";
    echo "}";
    echo "</script>";
}

/**
 * Formata uma data para o padrão brasileiro (dd/mm/aaaa).
 *
 * @param string $date Data no formato americano (aaaa-mm-dd).
 * @return string Data formatada no padrão brasileiro (dd/mm/aaaa).
 * @author Gabrielli
 */
function formatDateToBrazilian($date) {
    if (strpos($date, '-') !== false) {
        $dateArray = explode('-', $date);
        if (count($dateArray) == 3) {
            return $dateArray[2] . '/' . $dateArray[1] . '/' . $dateArray[0];
        }
    }
    return $date;
}

/**
 * Formata um número de CPF para o padrão brasileiro.
 *
 * @param string $cpf Número de CPF.
 * @return string CPF formatado no padrão brasileiro.
 * @author Gabrielli
 */
function formatCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

/**
 * Formata um número de telefone para o padrão brasileiro.
 *
 * @param string $phoneNumber Número de telefone.
 * @return string Número de telefone formatado no padrão brasileiro.
 * @author Gabrielli
 */
function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    return '(' . substr($phoneNumber, 0, 2) . ') ' . substr($phoneNumber, 2, 5) . '-' . substr($phoneNumber, 7);
}

/**
 * Formata um número de CEP para o padrão brasileiro.
 *
 * @param string $cep Número de CEP.
 * @return string CEP formatado no padrão brasileiro.
 * @author Gabrielli
 */
function formatCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    return substr($cep, 0, 5) . '-' . substr($cep, 5);
}

/**
 * Formata uma data e hora para o padrão brasileiro (dd/mm/aaaa H:i:s).
 *
 * @param string $dateTime Data e hora no formato americano (aaaa-mm-dd H:i:s).
 * @return string Data e hora formatadas no padrão brasileiro (dd/mm/aaaa H:i:s).
 * @author Gabrielli
 */

function formatDateTimeToBrazilian($dateTime) {
    $dateTimeArray = explode(' ', $dateTime);

    $date = formatDateToBrazilian($dateTimeArray[0]);

    if (isset($dateTimeArray[1])) {
        return $date . ' ' . $dateTimeArray[1];
    } else {
        return $date;
    }
}

function formatarPrice($formPrice) {
    return 'R$ ' . number_format($formPrice, 2, ',', '.');
}

/**
 * Converte uma data no formato AAAAMMDD para DD/MM/AAAA.
 *
 * @param string $date Data no formato AAAAMMDD.
 * @return string Data formatada no padrão DD/MM/AAAA.
 * @author Gabrielli
 */
function convertDate($date) {
    if (strlen($date) != 8) {
        return "Formato de data inválido";
    }

    $ano = substr($date, 0, 4);
    $mes = substr($date, 4, 2);
    $dia = substr($date, 6, 2);

    return "$dia/$mes/$ano";
}

/**
 * Gera um hash para um texto ou número usando o algoritmo SHA-256.
 * Poderá ser utilizado posteriormente na criptografia de QRCode
 *
 * @param string|int $data Texto ou número a ser criptografado.
 * @return string Hash gerado em formato hexadecimal.
 * @author Gabrielli
 */
function generateHash($data) {
    return hash('sha256', $data);
}


/**
 * Converte uma string para evitar problemas com caracteres especiais em HTML.
 *
 * @param string $str String a ser convertida.
 * @return string String convertida para evitar problemas com caracteres especiais em HTML.
 */
function tratarCaracteresEspeciais($str) {
    return utf8_decode($str);
}


/**
 * Formata um número como porcentagem.
 *
 * Esta função recebe um número inteiro ou float e formata-o como uma porcentagem.
 *
 * @param float $number Número a ser formatado como porcentagem.
 * @return string Número formatado como porcentagem, com duas casas decimais seguidas do símbolo '%'.
 * @author Gabrielli
 */
function formatPercentage($number) {
    $percentage = $number * 100;
    
    $formatted = number_format($percentage, 2);
    return $formatted . '%';
}

/**
 * Exibe uma mensagem de ajuda com um ícone selecionado.
 *
 * Esta função gera um ícone de ajuda e uma mensagem de ajuda que pode ser exibida
 * em qualquer parte da página. A mensagem e o tipo de ícone devem ser passados como parâmetros.
 * O link dos ícones pode ser fornecido ou a função usará um link padrão.
 *
 * @param string $message Mensagem a ser exibida ao lado do ícone de ajuda.
 * @param string $iconType Tipo de ícone a ser exibido. Aceita os valores: 'alerta', 'exclamacao', 'interrogacao'.
 * @param string|null $fontLink Link para o arquivo CSS do FontAwesome. Se 'link', usa o link padrão. Se não fornecido ou se contiver o texto 'href', usa o link padrão.
 *                               Se o valor for um link real, deve ser um URL válido para o arquivo CSS dos ícones.
 * @return void
 * @author Gabrielli
 */
function displayHelp($message, $iconType, $fontLink = null) {
    $defaultFontLink = '<link href="../fontawesome/css/all.css" rel="stylesheet">';

    if ($fontLink === 'link') {
        $fontLink = $defaultFontLink;
    } elseif ($fontLink !== null && strpos($fontLink, 'href') === false) {
        $fontLink = '<link href="' . htmlspecialchars($fontLink) . '" rel="stylesheet">';
    } else {
        $fontLink = $defaultFontLink;
    }

    $icons = [
        'alerta'       => 'fa-triangle-exclamation',
        'exclamacao'   => 'fa-exclamation',
        'interrogacao' => 'fa-question'
    ];

    $iconClass = isset($icons[$iconType]) ? $icons[$iconType] : $icons['interrogacao'];

    echo $fontLink . "\n";
    
    echo '<i class="fa-solid ' . htmlspecialchars($iconClass) . '"></i> ';
    echo '<span>' . htmlspecialchars($message) . '</span>';
}

/**
 * Exibe um ícone de logout e processa o logout do usuário.
 *
 * @param string $iconKey A chave do ícone a ser exibido (por exemplo, 'logout').
 * 
 * @return void
 */
function logoutUser($iconKey) {
    $icons = array(
        'logout' => '<i class="fa-solid fa-right-to-bracket"></i>'
    );

    if (!array_key_exists($iconKey, $icons)) {
        return 'Ícone não encontrado';
    }

    $icon = $icons[$iconKey];
    echo '<a href="?logout=true">' . $icon . '</a>';

    if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
        session_unset();
        session_destroy();

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Location: ../View/loginUser.php');
        exit();
    }
}

function checkUserStatusAndLogout($pdo, $rotinaAcessada) {
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['tipo'] !== 'A') {
            capturarEEnviarEmailSuporte($pdo, $rotinaAcessada);

            session_unset();
            session_destroy();

            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');

            header('Location: ../Controller/logoutNotificationController.php');
            exit();
        }
    } else {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Location: ../View/loginUser.php');
        exit();
    }
}









//Função em testes
// function gerarCodigoVerificacao($tamanho = 8) {
//     $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
//     $codigo = '';
//     for ($i = 0; $i < $tamanho; $i++) {
//         $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
//     }
//     return $codigo;
// }
?>