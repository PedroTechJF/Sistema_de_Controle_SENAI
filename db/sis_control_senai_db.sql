SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE IF EXISTS `sis_control_senai`;
CREATE DATABASE IF NOT EXISTS `sis_control_senai` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sis_control_senai`;

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `num` varchar(10) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `vinculo` varchar(100) NOT NULL,
  `usuario_ativo` tinyint(1) NOT NULL,
  `escola_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `modalidade` varchar(100) NOT NULL,
  `duracao` varchar(100) NOT NULL,
  `escolas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `valor` float NOT NULL,
  `vinculo_aluno` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `num` varchar(10) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `usuario_ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `escolas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `num` varchar(10) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `usuario_ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `justificativas_faltas` (
  `id` int(11) NOT NULL,
  `dias` int(11) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `justificativa` varchar(100) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `local_arquivo` varchar(255) NOT NULL,
  `data_solicitacao` datetime NOT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `curso_escolas_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `relatorios` (
  `id` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `local_arquivo` varchar(100) NOT NULL,
  `data_criacao` date NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `solicitacoes` (
  `id` int(11) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_entrega_estimada` date NOT NULL,
  `data_entrega` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `data_solicitacao` datetime NOT NULL,
  `valor_solicitacao` float NOT NULL,
  `tipo_solicitacao` varchar(255) NOT NULL,
  `db_child` varchar(255) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `escola_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `solicitacoes_documentos` (
  `id` int(11) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_entrega_estimada` date NOT NULL,
  `data_entrega` date DEFAULT NULL,
  `observacao` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data_solicitacao` datetime NOT NULL,
  `valor_solicitacao` float NOT NULL,
  `local_boleto` varchar(255) DEFAULT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `documentos_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `curso_escolas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuarios_alunos` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `escola_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuarios_empresas` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuarios_escolas` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `escola_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`,`escola_id`,`empresa_id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_alunos_escola_idx` (`escola_id`),
  ADD KEY `fk_alunos_empresa_idx` (`empresa_id`);

ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`,`escolas_id`),
  ADD KEY `fk_cursos_escolas1_idx` (`escolas_id`);

ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `escolas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `justificativas_faltas`
  ADD PRIMARY KEY (`id`,`aluno_id`,`solicitacao_id`,`curso_id`,`curso_escolas_id`,`empresa_id`) USING BTREE,
  ADD KEY `fk_atestados_aluno_idx` (`aluno_id`),
  ADD KEY `fk_atestados_empresa_idx` (`empresa_id`),
  ADD KEY `fk_atestados_solicitacao_idx` (`solicitacao_id`),
  ADD KEY `fk_atestados_cursos` (`curso_id`,`curso_escolas_id`) USING BTREE;

ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`id`,`empresa_id`),
  ADD KEY `fk_relatorios_empresa_idx` (`empresa_id`);

ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`id`,`aluno_id`,`empresa_id`,`escola_id`) USING BTREE,
  ADD KEY `fk_solicitacoes_aluno_idx` (`aluno_id`) USING BTREE,
  ADD KEY `fk_solicitacoes_empresa_idx` (`empresa_id`) USING BTREE,
  ADD KEY `fk_solicitacoes_escola_idx` (`escola_id`) USING BTREE;

ALTER TABLE `solicitacoes_documentos`
  ADD PRIMARY KEY (`id`,`documentos_id`,`aluno_id`,`solicitacao_id`,`curso_id`,`curso_escolas_id`) USING BTREE,
  ADD KEY `fk_solicitacoes_documentos_solicitacao_idx` (`solicitacao_id`),
  ADD KEY `fk_solicitacoes_documentos_aluno_idx` (`aluno_id`),
  ADD KEY `fk_solicitacoes_documentos_documentos1_idx` (`documentos_id`),
  ADD KEY `fk_solicitacoes_documentos_curso` (`curso_id`,`curso_escolas_id`) USING BTREE;

ALTER TABLE `usuarios_alunos`
  ADD PRIMARY KEY (`id`,`escola_id`,`aluno_id`,`empresa_id`) USING BTREE,
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `fk_usuarios_alunos_aluno_idx` (`aluno_id`) USING BTREE,
  ADD KEY `fk_usuarios_alunos_escola_idx` (`escola_id`) USING BTREE,
  ADD KEY `fk_usuarios_alunos_empresa_idx` (`empresa_id`) USING BTREE;

ALTER TABLE `usuarios_empresas`
  ADD PRIMARY KEY (`id`,`empresa_id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `fk_usuarios_empresas_empresa_idx` (`empresa_id`);

ALTER TABLE `usuarios_escolas`
  ADD PRIMARY KEY (`id`,`escola_id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `fk_usuarios_escolas_escola_idx` (`escola_id`);


ALTER TABLE `alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `escolas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `justificativas_faltas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `relatorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `solicitacoes_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios_alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios_empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios_escolas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `alunos`
  ADD CONSTRAINT `fk_alunos_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alunos_escola` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_escolas1` FOREIGN KEY (`escolas_id`) REFERENCES `escolas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `justificativas_faltas`
  ADD CONSTRAINT `fk_atestados_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_atestados_cursos` FOREIGN KEY (`curso_id`,`curso_escolas_id`) REFERENCES `cursos` (`id`, `escolas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_atestados_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_atestados_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `relatorios`
  ADD CONSTRAINT `fk_relatorios_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `solicitacoes`
  ADD CONSTRAINT `fk_solicitacoes_aluno_idx` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacoes_empresa_idx` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacoes_escola_idx` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `solicitacoes_documentos`
  ADD CONSTRAINT `fk_solicitacoes_documentos_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacoes_documentos_cursos` FOREIGN KEY (`curso_id`,`curso_escolas_id`) REFERENCES `cursos` (`id`, `escolas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacoes_documentos_documentos1` FOREIGN KEY (`documentos_id`) REFERENCES `documentos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacoes_documentos_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `usuarios_alunos`
  ADD CONSTRAINT `fk_usuarios_alunos_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_alunos_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_alunos_escolas1` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `usuarios_empresas`
  ADD CONSTRAINT `fk_usuarios_empresas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `usuarios_escolas`
  ADD CONSTRAINT `fk_usuarios_escolas_escola` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

INSERT INTO `escolas` (`nome`, `cnpj`, `telefone`, `email`, `cep`, `logradouro`, `num`, `bairro`, `complemento`, `cidade`, `estado`) VALUES
('SENAI CFP JFN', '03773700000107', '3232392233', 'cfp-jfn@fiemg.com.br', '36035000', 'Av. Rio Branco', '1219', 'Centro', NULL, 'Juiz de Fora', 'MG');

INSERT INTO `usuarios_escolas` (`login`, `senha`, `nome`, `escola_id`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
