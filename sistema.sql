CREATE TABLE usuarios (
  id_usuario INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  usuario VARCHAR(25) NOT NULL,
  email VARCHAR(50) NOT NULL,
  senha VARCHAR(75) NOT NULL,
  tipo_usuario VARCHAR(20) NOT NULL DEFAULT 'aluno',
  data_criacao_usuario DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Exemplo de cadastro de coordenador
INSERT INTO usuarios (nome, usuario, email, senha, tipo_usuario)
VALUES ('Coordenador', 'coordenador', 'coordenador@exemplo.com', '123', 'coordenador');

-- Exemplo de cadastro de aluno
INSERT INTO usuarios (nome, usuario, email, senha, tipo_usuario)
VALUES ('Aluno', 'aluno', 'aluno@exemplo.com', '123', 'aluno');


CREATE TABLE inscricoes (
  id_inscricao INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_evento INT(11) NOT NULL,
  id_usuario INT(11) NOT NULL,
  compareceu TINYINT(1) NOT NULL DEFAULT 0,
  data_inscricao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_evento) REFERENCES eventos(id_evento)
);

CREATE TABLE eventos (
  id_evento INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome_evento VARCHAR(100) NOT NULL,
  descricao_evento TEXT,
  data_hora_inicio DATETIME,
  data_hora_fim DATETIME,
  data_criacao_evento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
