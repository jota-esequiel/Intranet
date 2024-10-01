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

/**
 * Formata um valor numérico como preço no formato brasileiro (R$).
 * A função converte o valor numérico passado como parâmetro em uma string formatada com o prefixo "R$",
 * usando a vírgula (`,`) como separador decimal e o ponto (`.`) como separador de milhar.
 * 
 * @param float $formPrice O valor numérico a ser formatado. Deve ser um número positivo ou zero.
 * 
 * @return string O valor formatado como uma string no formato de preço brasileiro.
 * @author Gabrielli
 */
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

/**
 * Retorna um link HTML ou um script com base na chave fornecida.
 *
 * Esta função busca um array de links e scripts pré-definidos e retorna o valor correspondente
 * à chave fornecida. Se a chave não for encontrada, a função retorna `null`.
 *
 * @param string $keyLinks A chave que identifica o link ou script desejado.
 * 
 * @return string|null O link HTML ou o script correspondente à chave fornecida. Se a chave
 *                     não for encontrada, retorna `null`.
 * @author Gabrielli
 * 
 * */
function getLink($keyLinks) {
    $links = array(

        //Links HTML 
        'link'   => '<link href="../fontawesome/css/all.css" rel="stylesheet">',
        'mask'   => '<script src = "../templates/JS/mask.js"></script>',
        'main'   => '<script src="../templates/JS/main.js"></script>',
        'cart'   => '<a href = "../View/shoppingCart.php">Carrinho de compras</a>',
        'show'   => '<script src="../templates/JS/showToast.js"></script>',

        //Ícones FontAwesome
        'email'  => '<i class="fa-solid fa-envelope"></i>',
        'user'   => '<i class="fa-solid fa-user"></i>',
        'logout' => '<i class="fa-solid fa-right-from-bracket"></i>',
        'edit'   => '<i class="fa-solid fa-pen-to-square"></i>',
        'save'   => '<i class="fa-solid fa-floppy-disk"></i>',
        'excel'  => '<i class="fa-sharp fa-solid fa-file-excel"></i>',
        'pdf'    => '<i class="fa-solid fa-file-pdf"></i>',
        'box'    => '<i class="fa-solid fa-box-open"></i>',
    );

    if (array_key_exists($keyLinks, $links)) {
        return $links[$keyLinks];
    } else {
        return null;
    }
}

/**
 * Converte uma data no formato brasileiro para o formato americano.
 *
 * Esta função recebe uma data no formato brasileiro (dd/mm/aaaa) e a converte para o formato americano (aaaa-mm-dd).
 * Se a data fornecida não estiver no formato esperado, a função retorna a data original sem alterações.
 *
 * @param string $dateBrazilian A data no formato brasileiro (dd/mm/aaaa).
 * 
 * @return string A data convertida para o formato americano (aaaa-mm-dd) se a entrada estiver no formato esperado,
 *                ou a data original se o formato não for reconhecido.
 * 
 * @author Gabrielli
 * */
function convertDateSQL($dateBrazilian) {
    if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $dateBrazilian)) {
        $dateAmerican = date('Y-m-d', strtotime(str_replace('/', '-', $dateBrazilian)));
        return $dateAmerican;
    } else {
        return $dateBrazilian;
    }
}


/**
 * Remove a formatação de um CPF e retorna apenas os números.
 *
 * @param string $cpfFormatado O CPF formatado (ex: 123.456.789-00).
 * @return string O CPF sem formatação (ex: 12345678900).
 */
function formatarCPFSQL($cpfFormatado) {
    return preg_replace('/\D/', '', $cpfFormatado);
}

/**
 * Remove a formatação de um número de telefone e retorna apenas os números.
 *
 * @param string $telefoneFormatado O telefone formatado (ex: (12) 34567-8901).
 * @return string O telefone sem formatação (ex: 12345678901).
 */
function formatarTelefoneSQL($telefoneFormatado) {
    return preg_replace('/\D/', '', $telefoneFormatado);
}


/**
 * Remove a formatação de um CEP e retorna apenas os números.
 *
 * @param string $cepFormatado O CEP formatado (ex: 12345-678).
 * @return string O CEP sem formatação (ex: 12345678).
 */
function formatarCEPSQL($cepFormatado) {
    return preg_replace('/\D/', '', $cepFormatado);
}



/**
 * Verifica se o usuário na sessão é do tipo especificado.
 *
 * @param string $userType O tipo de usuário esperado ('A' para administrador, 'C' para cliente).
 * @return bool Retorna true se o usuário for do tipo especificado, caso contrário, retorna false.
 * @author Gabrielli
 */
function checkUserType($userType) {

    if (session_status() === PHP_SESSION_NONE) {
        session_start(); 
    }

    if (isset($_SESSION['usuario']['tipo'])) {
        return $_SESSION['usuario']['tipo'] === $userType;
    }
    return false;
}


/**
 * Esta função encerra a sessão atual do usuário, limpando todas as variáveis de sessão
 * e destruindo a sessão. Após a destruição da sessão, redireciona o usuário para a página
 * de login.
 *
 * @param string $redirectURL URL para redirecionar após a destruição da sessão (padrão: '../View/loginUser.php')
 * @author Gabrielli
 */
function destroySession($redirectURL = '../View/loginUser.php') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = array();

    session_destroy();

    header("Location: $redirectURL");
    exit();
}


/**
 * Monta o cabeçalho HTML básico com links dinâmicos para CSS e JS.
 *
 * Esta função gera o início padrão de um documento HTML e os links para arquivos CSS e JavaScript especificados pelos parâmetros.
 *
 * @param string $linkCSS O caminho ou URL do arquivo CSS a ser incluído.
 * @param string $linkJS  O caminho ou URL do arquivo JavaScript a ser incluído.
 * 
 * @return string O cabeçalho HTML completo como uma string.
 * @author Gabrielli
 */

function montarCabecalhoHTML($linkCSS, $linkJS) {
    ob_start();

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo $linkCSS; ?>">
        <script src="<?php echo $linkJS; ?>"></script>
        <title>Document</title>
    </head>
    <body>
    <?php

    $html = ob_get_clean();

    return $html;
}


/**
 * Redireciona o usuário para uma URL especificada após 3 segundos.
 *
 * @param string $url A URL para a qual o usuário será redirecionado.
 */
function redirect($url) {
    echo '<script>
            setTimeout(function() {
                window.location.href = "' . $url . '";
            }, 3000);
          </script>';
    exit();
}



/**
 *
 * Esta função retorna o caminho para uma imagem específica do sistema com as dimensões ajustadas. Suporta imagens da logo ou do diretório 'Produtos'.
 *
 * @param string $type Tipo de imagem
 * @param int $width Largura da imagem 
 * @param int $height Altura da imagem 
 * @param string|null $filename Nome do arquivo da imagem
 * 
 * @author Gabrielli
 */

function getImgPath($type, $width = 100, $height = 100, $filename = null) {
    $basePath = "C:/xampp/htdocs/Intranet/imagens";
    $urlBasePath = "http://localhost/Intranet/imagens";
    
    switch($type) {
        case 'logo':
            $filePath = $basePath . "/Logo/logo.png";
            $urlFilePath = $urlBasePath . "/Logo/logo.png";
            break;

            case 'mais':   
            $filePath = $basePath . "/Mais/FundoLogin.jpeg";
            $urlFilePath = $urlBasePath . "/Mais/FundoLogin.jpeg";
            break;

        
        case 'produtos':
            if ($filename === null) {
                $productFiles = glob($basePath . "/Produtos/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                
                if (empty($productFiles)) {
                    return "Nenhuma imagem disponível no diretório de produtos!";
                }
                
                $filePath = $productFiles[0];
                $urlFilePath = $urlBasePath . "/Produtos/" . basename($filePath);
            } else {
                $productFiles = glob($basePath . "/Produtos/{$filename}.{jpg,jpeg,png,gif}", GLOB_BRACE);
                
                if (empty($productFiles)) {
                    return "Imagem '{$filename}' não encontrada!";
                }

                $filePath = $productFiles[0];
                $urlFilePath = $urlBasePath . "/Produtos/" . basename($filePath);
            }
            break;
        
        default:
            return $arrayAlert = [
                "Tipo de imagem não suportado! Verifique a extensão da imagem!",
                "Suportamos apenas .jpg .jpeg .png .gif"
        ];
    }

    if (file_exists($filePath)) {
        return "<img src='{$urlFilePath}' width='{$width}' height='{$height}' />";
    } else {
        return "Imagem indisponível!";
    }
}

?>