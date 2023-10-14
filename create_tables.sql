-- Criação da Tabela clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    numero VARCHAR(10),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado CHAR(2) NOT NULL,
    email VARCHAR(255),
    cpf_cnpj VARCHAR(20) UNIQUE,
    rg VARCHAR(20) UNIQUE,
    telefone VARCHAR(20),
    celular VARCHAR(20),
    data_nasc DATE
);

-- Criação da Tabela login_usuarios
CREATE TABLE login_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    id_cliente INT,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    UNIQUE KEY (login)
);

-- Criação da Tabela pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATE NOT NULL,
    id_cliente INT,
    observacao VARCHAR(255),
    cond_pagto VARCHAR(50),
    prazo_entrega VARCHAR(10),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    INDEX (id_cliente)
);

-- Criação da Tabela produto
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    qtde_estoque INT NOT NULL,
    valor_unitario DECIMAL(10, 2) NOT NULL,
    unidade_medida VARCHAR(20)
);

-- Criação da Tabela itens_pedido
CREATE TABLE itens_pedido (
    id_pedido INT,
    id_produto INT,
    qtde INT NOT NULL,
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id),
    INDEX (id_pedido, id_produto)
);
