SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS tb_compras_itens;
DROP TABLE IF EXISTS tb_compras;
DROP TABLE IF EXISTS tb_imagens;
DROP TABLE IF EXISTS tb_produtos;
DROP TABLE IF EXISTS tb_categorias;
DROP TABLE IF EXISTS tb_clientes;
DROP TABLE IF EXISTS tb_cidades;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS tb_cidades (
    codcid INT PRIMARY KEY AUTO_INCREMENT,
    nomecidade VARCHAR(50) NOT NULL,
    uf CHAR(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_clientes (
    codcliente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    cpf BIGINT NOT NULL,
    fone BIGINT NOT NULL,
    email VARCHAR(50) NOT NULL,
    senha VARCHAR(32) NOT NULL, -- Para criptografia md5
    dtnasc DATE NOT NULL,
    rua VARCHAR(40),
    complemento VARCHAR(30),
    ncasa INT NOT NULL,
    cep VARCHAR(10) NOT NULL,
    tipo CHAR(1) NOT NULL, -- Tipo de Usuário (A = Administrador, C = Cliente)
    ativo CHAR(1) NOT NULL DEFAULT 'S', -- Status do Usuário (S = Ativo, N = Inativo)
    codcid INT,
    FOREIGN KEY (codcid) REFERENCES tb_cidades(codcid) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tb_categorias (
    codcategoria INT PRIMARY KEY AUTO_INCREMENT,
    nomecategoria VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_produtos (
    codproduto INT PRIMARY KEY AUTO_INCREMENT,
    nomeproduto VARCHAR(50) NOT NULL,
    precoproduto DECIMAL(5, 2) NOT NULL,
    ativo CHAR(1) NOT NULL DEFAULT 'S',
    qtdprod INT NOT NULL, -- Quantidade do produto disponível para compra, ou seja, em "estoque".
    cor CHAR(1), -- Armazena a cor vindo de um array (1 = Vermelho, 2 = Azul, 3 = Amarelo)
    tamanho CHAR(1), -- Armazena o tamanho vindo de um array (P = Pequeno, M = Médio, G = Grande)
    codcategoria INT,
    FOREIGN KEY (codcategoria) REFERENCES tb_categorias(codcategoria) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tb_imagens (
    codimg INT PRIMARY KEY AUTO_INCREMENT,
    img LONGBLOB NOT NULL,
    codproduto INT,
    FOREIGN KEY (codproduto) REFERENCES tb_produtos(codproduto) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tb_compras (
    codencomenda INT PRIMARY KEY AUTO_INCREMENT,
    codrecibo VARCHAR(15) NOT NULL,
    codcliente INT,
    ddcompra DATE NOT NULL,
    pagamento CHAR(1) NOT NULL, -- Tipo de Pagamento (P = Pix)
    entrega CHAR(1) NOT NULL, -- Status da entrega (1 = Entregue, 2 = Separação... Vocês que decidem nesse campo)
    taxaentrega DECIMAL(5, 2) NOT NULL,
    FOREIGN KEY (codcliente) REFERENCES tb_clientes(codcliente) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tb_compras_itens (
    codcompraitem INT PRIMARY KEY AUTO_INCREMENT,
    codcompra INT NOT NULL,
    codproduto INT NOT NULL,
    qtd INT NOT NULL,
    valor DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (codcompra) REFERENCES tb_compras(codencomenda) ON DELETE CASCADE,
    FOREIGN KEY (codproduto) REFERENCES tb_produtos(codproduto) ON DELETE CASCADE
);

-- Fim do SQL


-- Inserts

INSERT INTO tb_cidades (nomecidade, uf) VALUES ('São Paulo', 'SP');
INSERT INTO tb_cidades (nomecidade, uf) VALUES ('Rio de Janeiro', 'RJ');

INSERT INTO tb_clientes (nome, cpf, fone, email, senha, dtnasc, rua, complemento, ncasa, cep, codcid)
VALUES ('João Silva', 12345678901, 11987654321, 'joao@example.com', MD5('senha123'), '1985-01-01', 'Rua A', 'Apto 1', 100, '12345-678', 1);

INSERT INTO tb_clientes (nome, cpf, fone, email, senha, dtnasc, rua, complemento, ncasa, cep, codcid)
VALUES ('Maria Oliveira', 10987654321, 21987654321, 'maria@example.com', MD5('senha456'), '1990-02-02', 'Rua B', 'Casa', 200, '98765-432', 2);

INSERT INTO tb_categorias (nomecategoria) VALUES ('Ferramenta');
INSERT INTO tb_categorias (nomecategoria) VALUES ('Planta');

INSERT INTO tb_produtos (nomeproduto, precoproduto, qtdprod, codcategoria) VALUES ('Rosa', 10.50, 10, 1);
INSERT INTO tb_produtos (nomeproduto, precoproduto, qtdprod, codcategoria) VALUES ('Pinheiro', 150.00, 10, 2);

INSERT INTO tb_imagens (img, codproduto) VALUES (LOAD_FILE('C:/xampp/htdocs/Intranet/img/bcaa.jpg'), 1);
INSERT INTO tb_imagens (img, codproduto) VALUES (LOAD_FILE('C:/xampp/htdocs/Intranet/img/bcaa.jpg'), 2);

INSERT INTO tb_compras (codrecibo, codcliente, ddcompra, pagamento, entrega, taxaentrega)
VALUES ('REC001', 1, '2023-05-15', 'P', 1, 5.00);

INSERT INTO tb_compras (codrecibo, codcliente, ddcompra, pagamento, entrega, taxaentrega)
VALUES ('REC002', 2, '2023-05-16', 'P', 1, 10.00);

INSERT INTO tb_compras_itens (codcompra, codproduto, qtd, valor)
VALUES (1, 1, 2, 21.00);

INSERT INTO tb_compras_itens (codcompra, codproduto, qtd, valor)
VALUES (2, 2, 1, 150.00);


-- Alterações no SQL

-- Foi necessário, pois, BIGINT leva em consideração que todo 0 a esquerda não é um caracter "usável" e ignora ele na inserção.

ALTER TABLE tb_clientes MODIFY cpf VARCHAR(11);