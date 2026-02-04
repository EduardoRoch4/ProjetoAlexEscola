-- Criação do banco de dados
CREATE DATABASE Escola;
USE Escola;

-- Tabela de Cursos
CREATE TABLE Cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

-- Tabela de Professores
CREATE TABLE Professores (
    id_professor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20)
);

-- Tabela de Funcionários
CREATE TABLE Funcionarios (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(50),
    email VARCHAR(100),
    telefone VARCHAR(20)
);

-- Tabela de Alunos
CREATE TABLE Alunos (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    data_nascimento DATE,
    email VARCHAR(100),
    telefone VARCHAR(20),
    id_curso INT,
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso)
);

-- Tabela de Matérias
CREATE TABLE Materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    id_curso INT,
    id_professor INT,
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso),
    FOREIGN KEY (id_professor) REFERENCES Professores(id_professor)
);

-- Tabela de vínculo entre Alunos e Matérias (matrícula)
CREATE TABLE Alunos_Materias (
    id_aluno INT,
    id_materia INT,
    PRIMARY KEY (id_aluno, id_materia),
    FOREIGN KEY (id_aluno) REFERENCES Alunos(id_aluno),
    FOREIGN KEY (id_materia) REFERENCES Materias(id_materia)
);

INSERT INTO Cursos (nome, descricao) VALUES
('Informática', 'Curso técnico de informática com foco em programação e redes'),
('Administração', 'Curso técnico de administração com foco em gestão empresarial');

INSERT INTO Alunos (nome, data_nascimento, email, telefone, id_curso) VALUES
('Ana Souza', '2006-03-15', 'ana.souza@email.com', '11999990001', 1),
('Carlos Lima', '2005-07-22', 'carlos.lima@email.com', '11999990002', 2),
('Beatriz Mendes', '2006-11-05', 'beatriz.m@email.com', '11999990003', 1);

INSERT INTO Professores (nome, email, telefone) VALUES
('Marcos Pereira', 'marcos.pereira@escola.com', '11988880001'),
('Fernanda Costa', 'fernanda.costa@escola.com', '11988880002'),
('João Oliveira', 'joao.oliveira@escola.com', '11988880003');

INSERT INTO Funcionarios (nome, cargo, email, telefone) VALUES
('Luciana Silva', 'Secretária', 'luciana.silva@escola.com', '11977770001'),
('Roberto Nunes', 'Coordenador', 'roberto.nunes@escola.com', '11977770002');

INSERT INTO Materias (nome, id_curso, id_professor) VALUES
('Lógica de Programação', 1, 1),
('Redes de Computadores', 1, 3),
('Gestão de Pessoas', 2, 2),
('Matemática Financeira', 2, 2);

INSERT INTO Alunos_Materias (id_aluno, id_materia) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 1);

-- Remover tabela antiga
DROP TABLE IF EXISTS Alunos_Materias;

-- Criar tabela Alunos_Cursos
CREATE TABLE Alunos_Cursos (
    id_aluno INT,
    id_curso INT,
    PRIMARY KEY (id_aluno, id_curso),
    FOREIGN KEY (id_aluno) REFERENCES Alunos(id_aluno),
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso)
);


select * from alunos



