/*
|--------------------------------------------------------------------------
| Projeto: Sistema de Agendamento Médico
| Arquivo: schema.sql
| Versão: 1.0
|--------------------------------------------------------------------------
*/

CREATE DATABASE IF NOT EXISTS projeto_agendamento_medico
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE projeto_agendamento_medico;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('Administrador','Recepcionista','Medico') NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX idx_usuario_email ON usuarios(email);

CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE medicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    crm_numero VARCHAR(20) NOT NULL,
    crm_uf CHAR(2) NOT NULL,
    telefone VARCHAR(20),
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_medico_usuario
      FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT uk_crm UNIQUE (crm_numero, crm_uf)
);

CREATE TABLE medicos_especialidades (
    medico_id INT NOT NULL,
    especialidade_id INT NOT NULL,
    PRIMARY KEY (medico_id, especialidade_id),
    CONSTRAINT fk_me_medico
      FOREIGN KEY (medico_id) REFERENCES medicos(id)
      ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_me_especialidade
      FOREIGN KEY (especialidade_id) REFERENCES especialidades(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    sexo ENUM('Masculino','Feminino','Outro') NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(150),
    endereco VARCHAR(255),
    tipo_sanguineo VARCHAR(5),
    alergias TEXT,
    observacoes TEXT,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX idx_paciente_nome ON pacientes(nome);
CREATE INDEX idx_paciente_cpf ON pacientes(cpf);

CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_consulta DATE NOT NULL,
    horario TIME NOT NULL,
    hora_checkin DATETIME NULL,
    motivo_consulta TEXT,
    status ENUM('Agendada','Em Andamento','Finalizada','Cancelada')
      NOT NULL DEFAULT 'Agendada',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_consulta_paciente
      FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_consulta_medico
      FOREIGN KEY (medico_id) REFERENCES medicos(id)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_consulta_usuario
      FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
      ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX idx_consulta_data ON consultas(data_consulta);
CREATE INDEX idx_consulta_status ON consultas(status);

CREATE TABLE atendimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT NOT NULL UNIQUE,
    sintomas TEXT,
    diagnostico TEXT,
    observacoes TEXT,
    data_atendimento DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_atendimento_consulta
      FOREIGN KEY (consulta_id) REFERENCES consultas(id)
      ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    atendimento_id INT NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_receita_atendimento
      FOREIGN KEY (atendimento_id) REFERENCES atendimentos(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE receita_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receita_id INT NOT NULL,
    medicamento VARCHAR(150) NOT NULL,
    dosagem VARCHAR(100),
    frequencia VARCHAR(100),
    duracao VARCHAR(100),
    instrucoes TEXT,
    CONSTRAINT fk_item_receita
      FOREIGN KEY (receita_id) REFERENCES receitas(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE atestados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    atendimento_id INT NOT NULL,
    dias_afastamento INT NOT NULL,
    cid VARCHAR(10),
    observacoes TEXT,
    data_emissao DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_atestado_atendimento
      FOREIGN KEY (atendimento_id) REFERENCES atendimentos(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

/* Inserir administrador após gerar um hash com password_hash().
Exemplo:
INSERT INTO usuarios(nome,email,senha,perfil)
VALUES('Administrador','admin@admin.com','HASH_AQUI','Administrador');
*/
