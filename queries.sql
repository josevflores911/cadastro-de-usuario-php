-- Criação da tabela de Tipos de Usuário

/*
CREATE TABLE tipos_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Elaborador', 'Validador', 'Validador +') NOT NULL UNIQUE
);

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    codigo_acesso VARCHAR(20) NOT NULL UNIQUE,
    senha VARCHAR(20) NOT NULL,
    tipo_usuario_id INT NOT NULL,
    FOREIGN KEY (tipo_usuario_id) REFERENCES tipos_usuario(id) -- Relacionamento com a tabela tipos_usuario
);

-- Tabela de Estados
CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

-- Tabela de Municípios
CREATE TABLE municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    estado_id INT NOT NULL,
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE
);

-- Tabela de Agências
CREATE TABLE agencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    municipio_id INT NOT NULL,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id) ON DELETE CASCADE
);

-- Tabela de Períodos de Vigência
CREATE TABLE periodos_vigencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CHECK (DATEDIFF(data_fim, data_inicio) <= 90) -- Garante que a diferença de dias entre as datas não seja maior que 90
);

-- Tabela de Relacionamento de Usuários, Municípios e Agências
CREATE TABLE usuario_municipio_agencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    municipio_id INT NOT NULL,
    agencia_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (agencia_id) REFERENCES agencias(id)
);
*/
-- Inserção de Tipos de Usuário
INSERT INTO tipos_usuario (tipo) VALUES 
('Elaborador'),
('Validador'),
('Validador +');

-- Inserção de Usuários com o tipo de usuário relacionado


-- Inserção de Estados
INSERT INTO estados (nome) VALUES 
('São Paulo'),
('Rio de Janeiro'),
('Minas Gerais'),
('Bahia');

-- Inserção de Municípios
INSERT INTO municipios (nome, estado_id) VALUES 
('São Paulo', 1), 
('Campinas', 1),
('Rio de Janeiro', 2), 
('Niterói', 2);

-- Inserção de Agências
INSERT INTO agencias (nome, municipio_id) VALUES 
('Agência Paulista', 1), 
('Agência Campinas', 2),
('Agência Carioca', 3), 
('Agência Niterói', 4);

/*
INSERT INTO usuarios (nome, codigo_acesso, senha, tipo_usuario_id) VALUES 
('João Silva', 'codigo1', 'senha123', 1),  -- 'Elaborador' tem id 1
('Maria Oliveira', 'codigo2', 'senha123', 2), -- 'Validador' tem id 2
('Pedro Costa', 'codigo3', 'senha123', 3), -- 'Validador +' tem id 3
('Ana Pereira', 'codigo4', 'senha123', 1); -- 'Elaborador' tem id 1

-- Inserção de Períodos de Vigência
INSERT INTO periodos_vigencia (usuario_id, data_inicio, data_fim) VALUES 
(1, '2025-03-01', '2025-05-30'), 
(2, '2025-01-01', '2025-03-31'),
(3, '2025-04-01', '2025-06-30'), 
(4, '2025-02-01', '2025-04-30');

-- Inserção de Relacionamentos Usuário-Município-Agência
INSERT INTO usuario_municipio_agencia (usuario_id, municipio_id, agencia_id) VALUES 
(1, 1, 1), 
(1, 2, 2),
(2, 3, 3),
(2, 4, 4);

*/

--queries 2

        SELECT 
            u.id AS id,
            u.nome AS nome,
            u.codigo_acesso AS codigo_acesso,
            tu.tipo AS tipo_usuario,
            e.nome AS estado,
            m.nome AS municipio,
            a.nome AS agencia,
            pv.data_inicio,
            pv.data_fim
        FROM 
            usuarios u
        JOIN 
            tipos_usuario tu ON u.tipo_usuario_id = tu.id
        JOIN 
            usuario_municipio_agencia uma ON u.id = uma.usuario_id
        JOIN 
            municipios m ON uma.municipio_id = m.id
        JOIN 
            agencias a ON uma.agencia_id = a.id
        JOIN 
            estados e ON m.estado_id = e.id
        JOIN 
            periodos_vigencia pv ON u.id = pv.usuario_id ;