SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS tb_compras_itens;
DROP TABLE IF EXISTS tb_compras;
DROP TABLE IF EXISTS tb_produtos;
DROP TABLE IF EXISTS tb_imagens;
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
    cpf BIGINT(11) ZEROFILL NOT NULL,
    fone BIGINT(11) NOT NULL,
    email VARCHAR(50) NOT NULL,
    senha VARCHAR(32) NOT NULL, -- Para criptografia md5
    dtnasc DATE NOT NULL,
    rua VARCHAR(40),
    complemento VARCHAR(30),
    ncasa INT NOT NULL,
    cep BIGINT(8) NOT NULL,
    tipo CHAR(1) DEFAULT 'C' NOT NULL, 
    ativo CHAR(1) DEFAULT 'S' NOT NULL, 
    codcid INT,
    FOREIGN KEY (codcid) REFERENCES tb_cidades(codcid) ON DELETE CASCADE,
    CHECK (tipo IN ('C', 'A')), -- Verificação para apenas inserir se for A ou C
    CHECK (ativo IN ('S', 'N')) -- Verificação para apenas inserir se for S ou N
);

CREATE TABLE IF NOT EXISTS tb_categorias (
    codcategoria INT PRIMARY KEY AUTO_INCREMENT,
    nomecategoria VARCHAR(30) NOT NULL,
    ativo CHAR(1) DEFAULT 'S' NOT NULL,
    CHECK (ativo IN ('S', 'N')) -- Verificação para apenas inserir se for S ou N
);

CREATE TABLE IF NOT EXISTS tb_imagens (
    codimg INT PRIMARY KEY AUTO_INCREMENT,
    img VARCHAR(250) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_produtos (
    codproduto INT PRIMARY KEY AUTO_INCREMENT,
    nomeproduto VARCHAR(50) NOT NULL,
    precoproduto DECIMAL(5, 2) NOT NULL,
    ativo CHAR(1) DEFAULT 'S' NOT NULL,
    cor INT NOT NULL DEFAULT 1,
    tamanho CHAR(1) DEFAULT 'P' NOT NULL,
    codimg INT,
    codcategoria INT,
    FOREIGN KEY (codcategoria) REFERENCES tb_categorias(codcategoria) ON DELETE CASCADE,
    FOREIGN KEY (codimg) REFERENCES tb_imagens(codimg) ON DELETE CASCADE,
    CHECK (ativo IN ('S', 'N')),
    CHECK (cor IN (1, 2, 3)),
    CHECK (tamanho IN ('P', 'M', 'G'))
);

CREATE TABLE IF NOT EXISTS tb_compras (
    codencomenda INT PRIMARY KEY AUTO_INCREMENT,
    codrecibo VARCHAR(15) NOT NULL,
    codcliente INT,
    ddcompra DATE NOT NULL,
    pagamento CHAR(1) DEFAULT 'P' NOT NULL,
    entrega CHAR(1) DEFAULT 'S' NOT NULL,
    taxaentrega DECIMAL(5, 2) NOT NULL,
    FOREIGN KEY (codcliente) REFERENCES tb_clientes(codcliente) ON DELETE CASCADE,
    CHECK (pagamento = 'P'), -- Verificação para apenas inserir se for P
    CHECK (entrega IN ('S', 'E')) -- Verificação para apenas inserir se for S ou E
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

INSERT INTO tb_cidades (nomecidade, uf) VALUES ('Cascavel', 'PR');
INSERT INTO tb_cidades (nomecidade, uf) VALUES ('Toledo', 'PR');
INSERT INTO tb_cidades (nomecidade, uf) VALUES ('Foz do Iguaçu', 'PR');

INSERT INTO tb_clientes (nome, cpf, fone, email, senha, dtnasc, rua, complemento, ncasa, cep, tipo, codcid)
VALUES ('João Silva', 12345678901, 11987654321, 'joao@example.com', MD5('senha123'), '1985-01-01', 'Rua A', 'Apto 1', 100, '12345-678', 'A', 1);

INSERT INTO tb_clientes (nome, cpf, fone, email, senha, dtnasc, rua, complemento, ncasa, cep, tipo, codcid)
VALUES ('Maria Oliveira', 10987654321, 21987654321, 'maria@example.com', MD5('senha456'), '1990-02-02', 'Rua B', 'Casa', 200, '98765-432', 'A', 2);

INSERT INTO tb_clientes (nome, cpf, fone, email, senha, dtnasc, rua, complemento, ncasa, cep, tipo, codcid)
VALUES ('José Junior', 11111111111, 22222222222, 'josé@example.com', MD5('senha678'), '1990-03-03', 'Rua C', 'Casa', 300, '12345-678', 'C', 3);

INSERT INTO tb_categorias (nomecategoria,codcategoria) VALUES ('Flores', '1');
INSERT INTO tb_categorias (nomecategoria, codcategoria) VALUES ('Árvores', '2');
INSERT INTO tb_categorias (nomecategoria, codcategoria) VALUES ('Ferramentas', '3');

INSERT INTO tb_produtos (nomeproduto, precoproduto, codcategoria) VALUES ('Rosa', 10.50, 1);
INSERT INTO tb_produtos (nomeproduto, precoproduto, codcategoria) VALUES ('Pinheiro', 15.00, 2);
INSERT INTO tb_produtos (nomeproduto, precoproduto, codcategoria) VALUES ('Pá', 150.00, 3);

INSERT INTO tb_compras (codrecibo, codcliente, ddcompra, pagamento, entrega, taxaentrega)
VALUES ('REC001', 1, '2023-05-15', 'P', 'S', 5.00);
INSERT INTO tb_compras (codrecibo, codcliente, ddcompra, pagamento, entrega, taxaentrega)
VALUES ('REC002', 2, '2023-05-16', 'P', 'E', 10.00);
INSERT INTO tb_compras (codrecibo, codcliente, ddcompra, pagamento, entrega, taxaentrega)
VALUES ('REC003', 3, '2023-05-15', 'P', 'E', 5.00);

INSERT INTO tb_compras_itens (codcompra, codproduto, qtd, valor)
VALUES (1, 1, 2, 21.00);
INSERT INTO tb_compras_itens (codcompra, codproduto, qtd, valor)
VALUES (2, 2, 1, 150.00);
INSERT INTO tb_compras_itens (codcompra, codproduto, qtd, valor)
VALUES (3, 3, 2, 21.00);

