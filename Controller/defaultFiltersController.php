<?php
/**
 * @author Gabrielli
 * @param - Função padrão para filtragem de dados
 */

include_once '../bdConnection.php';

function getCidades() {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT codcid, nomecidade FROM tb_cidades");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAtivo() {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT DISTINCT ativo FROM tb_clientes");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTipoUsuario() {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT DISTINCT tipo FROM tb_clientes");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAtivoProdutos() {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT DISTINCT ativo FROM tb_produtos");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getColorProduct() {
    $pdo = conectar();
    $stmt = $pdo->prepare("
        SELECT DISTINCT 
            cor,
            CASE cor
                WHEN '1' THEN 'Vermelho'
                WHEN '2' THEN 'Azul'
                WHEN '3' THEN 'Amarelo'
                ELSE 'Desconhecido'
            END AS corProd
        FROM tb_produtos
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getSizeProduct() {
    $pdo = conectar();
    $stmt = $pdo->prepare("
        SELECT DISTINCT 
            tamanho,
            CASE tamanho
                WHEN 'P' THEN 'Pequeno'
                WHEN 'M' THEN 'Médio'
                WHEN 'G' THEN 'Grande'
                ELSE 'Desconhecido'
            END AS tamanhoDesc
        FROM tb_produtos
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getCategoriasProdutos() {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT codcategoria, nomecategoria FROM tb_categorias");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAtivoCategory() {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT DISTINCT ativo FROM tb_categorias");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function filterUser() {
    $cidades = getCidades();
    $ativos = getAtivo();
    $tipos = getTipoUsuario();
    echo '
    <form id="filterUserForm" style="display: none;" method="POST" action="../View/consultUser.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        
        <label for="email">E-mail:</label>
        <input type="text" id="email" name="email">

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" class="mask-cpf">

        <label for="fone">Contato:</label>
        <input type="text" id="fone" name="fone" class="mask-phone">
        
        <label for="dtnasc">Data de nascimento:</label>
        <input type="date" id="dtnasc" name="dtnasc" class="mask-date">
        
        <label for="rua">Rua:</label>
        <input type="text" id="rua" name="rua">
        
        <label for="complemento">Complemento:</label>
        <input type="text" id="complemento" name="complemento">

        <label for="ncasa">Nº Casa:</label>
        <input type="text" id="ncasa" name="ncasa">
        
        <label for="cep">CEP:</label>
        <input type="text" id="cep" name="cep" class="mask-cep">
        
        <label for="cidade">Cidade:</label>
        <select id="cidade" name="cidade">
            <option value="">- Selecione -</option>';
            foreach ($cidades as $cidade) {
                echo '<option value="' . htmlspecialchars($cidade['codcid']) . '">' . htmlspecialchars($cidade['nomecidade']) . '</option>';
            }
    echo '
        </select>

        <label for="ativo">Status:</label>
        <select id="ativo" name="ativo">
            <option value="">- Selecione -</option>';
            foreach ($ativos as $status) {
                $statusActivity = $status['ativo'] == 'S' ? 'Ativo' : 'Inativo';
                echo '<option value="' . htmlspecialchars($status['ativo']) . '">' . htmlspecialchars($statusActivity) . '</option>';
            }
    echo '
        </select>

        <label for="tipo">Tipo de Usuário:</label>
        <select id="tipo" name="tipo">
            <option value="">- Selecione -</option>';
            foreach ($tipos as $tipo) {
                $tipoActivity = $tipo['tipo'] == 'A' ? 'Administrador' : 'Cliente';
                echo '<option value="' . htmlspecialchars($tipo['tipo']) . '">' . htmlspecialchars($tipoActivity) . '</option>';
            }
    echo '
        </select>

        <button type="submit">Filtrar</button>
    </form>';
}

function filterCategory() {
    $ativoCategory = getAtivoCategory();
    echo '
    <form id="filterCategoryForm" style="display: none;" method="POST" action="../View/consultCategory.php">
        <label for="nomecategoria">Nome da Categoria:</label>
        <input type="text" id="nomecategoria" name="nomecategoria">

        <label for="ativo">Status:</label>
        <select id="ativo" name="ativo">
            <option value="">- Selecione -</option>';
            foreach ($ativoCategory as $status) {
                $statusActivity = $status['ativo'] == 'S' ? 'Ativo' : 'Inativo';
                echo '<option value="' . htmlspecialchars($status['ativo']) . '">' . htmlspecialchars($statusActivity) . '</option>';
            }
    echo '
        </select>
        
        <button type="submit">Filtrar</button>
    </form>';
}

function filterProduct() {
    $ativosProdutos = getAtivoProdutos();
    $categoriasProdutos = getCategoriasProdutos();
    $colors = getColorProduct();
    $sizes = getSizeProduct();   
    
    echo '
    <form id="filterProductForm" style="display: none;" method="POST" action="../View/consultProduct.php">
        <label for="nomeproduto">Nome do Produto:</label>
        <input type="text" id="nomeproduto" name="nomeproduto">
        
        <label for="precoproduto">Preço do Produto:</label>
        <input type="text" id="precoproduto" name="precoproduto">
        
        <label for="ativo">Status do Produto:</label>
        <select id="ativo" name="ativo">
            <option value="">- Selecione -</option>';
            foreach ($ativosProdutos as $ativoProduto) {
                $statusProduto = $ativoProduto['ativo'] == 'S' ? 'Ativo' : 'Inativo';
                echo '<option value="' . htmlspecialchars($ativoProduto['ativo']) . '">' . htmlspecialchars($statusProduto) . '</option>';
            }
    echo '
        </select>
        
        <label for="codcategoria">Categoria do Produto:</label>
        <select id="codcategoria" name="codcategoria">
            <option value="">- Selecione -</option>';
            foreach ($categoriasProdutos as $categoriaProduto) {
                echo '<option value="' . htmlspecialchars($categoriaProduto['codcategoria']) . '">' . htmlspecialchars($categoriaProduto['nomecategoria']) . '</option>';
            }
    echo '
        </select>

        <label for="cor">Cor do Produto:</label>
        <select id="cor" name="cor">
            <option value="">- Selecione -</option>';
            foreach ($colors as $color) {
                echo '<option value="' . htmlspecialchars($color['cor']) . '">' . htmlspecialchars($color['corProd']) . '</option>';
            }
    echo '
        </select>
        
        <label for="tamanho">Tamanho do Produto:</label>
        <select id="tamanho" name="tamanho">
            <option value="">- Selecione -</option>';
            foreach ($sizes as $size) {
                echo '<option value="' . htmlspecialchars($size['tamanho']) . '">' . htmlspecialchars($size['tamanhoDesc']) . '</option>';
            }
    echo '
        </select>
        
        <button type="submit">Filtrar</button>
    </form>';
}


echo '<script src="../templates/JS/mask.js"></script>';
?>