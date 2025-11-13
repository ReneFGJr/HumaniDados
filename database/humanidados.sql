-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2025 at 10:08 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `humanidados`
--

-- --------------------------------------------------------

--
-- Table structure for table `instituicoes_lattes`
--

DROP TABLE IF EXISTS `instituicoes_lattes`;
CREATE TABLE IF NOT EXISTS `instituicoes_lattes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_instituicao_empresa` varchar(20) NOT NULL,
  `nome_instituicao_empresa` varchar(255) NOT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `uf` varchar(5) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `instituicoes_lattes`
--

INSERT INTO `instituicoes_lattes` (`id`, `codigo_instituicao_empresa`, `nome_instituicao_empresa`, `pais`, `uf`, `cidade`, `created_at`, `updated_at`) VALUES
(1, '024000000008', 'Universidade de Brasília', 'Brasil', 'DF', 'Brasília', '2025-11-13 07:37:29', '2025-11-13 07:37:29'),
(2, 'K45D00000006', 'Instituto Federal Sul-Rio-Grandense / Câmpus Lajeado', 'Brasil', 'RS', 'Lajeado', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(3, '004500000002', 'Universidade Federal de Pelotas', 'Brasil', 'RS', 'Pelotas', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(4, '016700000000', 'Universidade Federal do Rio Grande', 'Brasil', 'RS', 'Rio Grande', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(5, '', '', '', '', '', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(6, '169700000007', 'Universidade Federal do Estado do Rio de Janeiro', 'Brasil', 'RJ', 'Rio de Janeiro', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(7, '121000000000', 'Instituto Nacional de Educação de Surdos', 'Brasil', 'RJ', 'Rio de Janeiro', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(8, '020200000009', 'Universidade Federal do Rio de Janeiro', 'Brasil', 'RJ', 'Rio de Janeiro', '2025-11-13 07:42:09', '2025-11-13 07:42:09'),
(9, '006700000002', 'Universidade de São Paulo', 'Brasil', 'SP', 'Sao Paulo', '2025-11-13 07:42:09', '2025-11-13 07:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `lattes_formacao`
--

DROP TABLE IF EXISTS `lattes_formacao`;
CREATE TABLE IF NOT EXISTS `lattes_formacao` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sequencia_formacao` int NOT NULL,
  `idlattes` varchar(16) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `nivel` varchar(10) DEFAULT NULL,
  `titulo_tcc` varchar(500) DEFAULT NULL,
  `orientador` varchar(255) DEFAULT NULL,
  `codigo_instituicao` varchar(20) DEFAULT NULL,
  `nome_instituicao` varchar(255) DEFAULT NULL,
  `codigo_orgao` varchar(20) DEFAULT NULL,
  `nome_orgao` varchar(255) DEFAULT NULL,
  `codigo_curso` varchar(20) DEFAULT NULL,
  `nome_curso` varchar(255) DEFAULT NULL,
  `codigo_area_curso` varchar(20) DEFAULT NULL,
  `status_curso` varchar(30) DEFAULT NULL,
  `ano_inicio` int DEFAULT NULL,
  `ano_conclusao` int DEFAULT NULL,
  `flag_bolsa` varchar(5) DEFAULT NULL,
  `codigo_agencia` varchar(50) DEFAULT NULL,
  `nome_agencia` varchar(255) DEFAULT NULL,
  `id_orientador` varchar(20) DEFAULT NULL,
  `codigo_curso_capes` varchar(50) DEFAULT NULL,
  `titulo_tcc_ing` varchar(500) DEFAULT NULL,
  `nome_curso_ing` varchar(255) DEFAULT NULL,
  `formacao_academica_titulacao` varchar(255) DEFAULT NULL,
  `tipo_graduacao` varchar(255) DEFAULT NULL,
  `codigo_instituicao_grad` varchar(20) DEFAULT NULL,
  `nome_instituicao_grad` varchar(255) DEFAULT NULL,
  `codigo_instituicao_outra_grad` varchar(20) DEFAULT NULL,
  `nome_instituicao_outra_grad` varchar(255) DEFAULT NULL,
  `orientador_grad` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idlattes` (`idlattes`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lattes_formacao`
--

INSERT INTO `lattes_formacao` (`id`, `sequencia_formacao`, `idlattes`, `tipo`, `nivel`, `titulo_tcc`, `orientador`, `codigo_instituicao`, `nome_instituicao`, `codigo_orgao`, `nome_orgao`, `codigo_curso`, `nome_curso`, `codigo_area_curso`, `status_curso`, `ano_inicio`, `ano_conclusao`, `flag_bolsa`, `codigo_agencia`, `nome_agencia`, `id_orientador`, `codigo_curso_capes`, `titulo_tcc_ing`, `nome_curso_ing`, `formacao_academica_titulacao`, `tipo_graduacao`, `codigo_instituicao_grad`, `nome_instituicao_grad`, `codigo_instituicao_outra_grad`, `nome_instituicao_outra_grad`, `orientador_grad`, `created_at`, `updated_at`) VALUES
(1, 1, '0004072613292475', 'GRADUACAO', '1', 'O Texto ideográfico da arte conceitual', 'Elisa de Souza Martinez', '024000000008', 'Universidade de Brasília', '', '', '90000013', 'Licenciatura em Artes Visuais', '90000013', 'CONCLUIDO', 2001, 2004, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:07:55', '2025-11-13 10:06:10'),
(2, 5, '0004072613292475', 'MESTRADO', '3', NULL, NULL, '119300000003', 'Universidade do Estado de Santa Catarina', '', '', '90000012', 'História e Teoria da Arte', '90000012', 'CONCLUIDO', 2006, 2008, 'SIM', '045000000000', 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:10'),
(3, 11, '0004072613292475', 'DOUTORADO', '4', NULL, NULL, '024000000008', 'Universidade de Brasília', '', '', '60002522', 'Artes', '80300006', 'CONCLUIDO', 2015, 2017, 'NAO', '', '', '4040818825968308', '53001010040P5', NULL, 'Arts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:10'),
(4, 13, '0004072613292475', 'POS-DOUTORADO', '5', NULL, NULL, '033300000002', 'Universidade Federal de Minas Gerais', NULL, NULL, NULL, NULL, NULL, 'CONCLUIDO', 2018, 2019, 'NAO', '', '', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:10'),
(5, 1, '0004706603300740', 'GRADUACAO', '1', 'As Funções da Fotografia nos Procedimentos Artísticos Contemporâneos', 'Carmem Lúcia Abadie Biasoli', '004500000002', 'Universidade Federal de Pelotas', '', '', '90000000', 'Lic. Artes Visuais - Hab Desenho e Comp. Gráfica', '90000000', 'CONCLUIDO', 2003, 2006, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(6, 3, '0004706603300740', 'MESTRADO', '3', NULL, NULL, '004500000002', 'Universidade Federal de Pelotas', '', '', '60039086', 'Memória Social e Patrimônio Cultural', '99900009', 'CONCLUIDO', 2008, 2010, 'SIM', '045000000000', 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', '4451406034191031', '42003016027P7', NULL, 'Multidisciplinary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(7, 37, '0004706603300740', 'DOUTORADO', '4', NULL, NULL, '119300000003', 'Universidade do Estado de Santa Catarina', '', '', '60024615', 'Artes Visuais', '80300006', 'CONCLUIDO', 2016, 2020, 'NAO', '', '', '9781556928615419', '41002016010P8', NULL, 'Arts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(8, 2, '0004706603300740', 'CURSO-TECNICO-PROFISSIONALIZANTE', '7', NULL, NULL, '047500000006', 'Instituto Federal Sul-Rio-Grandense', '', '', '90000001', 'Edificações', NULL, 'INCOMPLETO', 2004, 2004, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(9, 1, '0007278260015680', 'GRADUACAO', '1', '', '', '000100000991', 'Escola de Música e Belas Artes do Paraná', '', '', '90000000', 'Superior de Instrumento', '90000000', 'CONCLUIDO', 2007, 2010, 'SIM', '000300000995', 'Fundação Araucária de apoio ao Desenvolvimento Científico e Técnológico', '', '', '', '', '', 'N', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(10, 9, '0007278260015680', 'MESTRADO', '3', NULL, NULL, '010600000009', 'Universidade Federal de Goiás', '', '', '60009756', 'Música', '80303005', 'CONCLUIDO', 2011, 2013, 'SIM', '045000000000', 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', '0771092244377551', '52001016013P1', NULL, 'Music', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(11, 10, '0007278260015680', 'DOUTORADO', '4', NULL, NULL, '134500000000', 'Université de Montreal', '', '', '90000039', 'Musique - Interprétation', '90000039', 'CONCLUIDO', 2013, 2017, 'SIM', '045000000000', 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:06:22'),
(12, 1, '0008743559477412', 'GRADUACAO', '1', '', '', '000100000991', 'Universidade Federal de Pelotas', '', '', '90000007', 'Música (Bacharelado)', '90000007', 'CONCLUIDO', 2000, 2004, 'NAO', '', '', '', '', '', '', '', 'N', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(13, 10, '0008743559477412', 'MESTRADO', '3', NULL, NULL, '029100000000', 'Universidade Federal da Bahia', '', '', '24000264', 'Música', '80303005', 'CONCLUIDO', 2005, 2007, 'SIM', '045000000000', 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', '', '28001010026P1', NULL, 'Music', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(14, 35, '0008743559477412', 'DOUTORADO', '4', NULL, NULL, '007900000004', 'Universidade Estadual de Campinas', '', '', '60010871', 'Música', '80300006', 'CONCLUIDO', 2013, 2017, 'SIM', '037700000002', 'Fundação de Amparo à Pesquisa do Estado de São Paulo', '6940068973325101', '33003017077P9', NULL, 'Arts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(15, 3, '0009674810581809', 'GRADUACAO', '1', 'Avaliação da Fragilidade ambiental, em Função das condições de interface oceano-continentais, para o planejamento urbano da Planície do Perequê, Ilha de São Sebastião - São Paulo.', 'Aílton Luchiari', '006700000002', 'Universidade de São Paulo', '', '', '60307170', 'Geografia', '', 'CONCLUIDO', 2000, 2003, 'NAO', '', '', '', '345903', '', '', '', 'N', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(16, 4, '0009674810581809', 'GRADUACAO', '1', '', '', '006700000002', 'Universidade de São Paulo', '', '', '90000001', 'Licenciatura', '90000001', 'CONCLUIDO', 2004, 2005, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(17, 8, '0009674810581809', 'MESTRADO', '3', NULL, NULL, '006700000002', 'Universidade de São Paulo', '', '', '60057602', 'MEIOS E PROCESSOS AUDIOVISUAIS', '60900008', 'CONCLUIDO', 2012, 2013, 'NAO', '', '', '3725098495803766', '33002010212P7', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(18, 10, '0009674810581809', 'DOUTORADO', '4', NULL, NULL, '006700000002', 'Universidade de São Paulo', '', '', '60057602', 'MEIOS E PROCESSOS AUDIOVISUAIS', '60900008', 'CONCLUIDO', 2014, 2017, 'NAO', '', '', '7984648859899240', '33002010212P7', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(19, 2, '0009674810581809', 'ENSINO-MEDIO-SEGUNDO-GRAU', 'C', NULL, NULL, '000200000993', 'Colégio Meninópolis', NULL, NULL, NULL, NULL, NULL, 'CONCLUIDO', 1996, 1998, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:34'),
(20, 3, '0009784707188018', 'GRADUACAO', '1', '', '', 'J28R00000000', 'Universidade de Paris-Sorbonne IV', NULL, NULL, '', 'Musicologia', '', 'CONCLUIDO', 2000, 2003, 'NAO', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 10:04:35'),
(21, 12, '0009784707188018', 'ESPECIALIZACAO', '2', NULL, 'Alain Louvier', '', 'Conservatoire National Supérieur de Musique et de Danse de Paris', NULL, NULL, '', 'Analise musical', NULL, 'CONCLUIDO', 2008, 2010, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:35'),
(22, 2, '0009784707188018', 'MESTRADO', '3', NULL, NULL, 'J28R00000000', 'Universidade de Paris-Sorbonne IV', NULL, NULL, '', 'Musicologia', '', 'CONCLUIDO', 2005, 2006, 'NAO', '', '', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:35'),
(23, 10, '0009784707188018', 'MESTRADO', '3', NULL, NULL, '', 'Conservatoire National Supérieur de Musique et de Danse de Paris', NULL, NULL, '', 'Cultura musical', '', 'CONCLUIDO', 2005, 2007, 'NAO', '', '', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:35'),
(24, 11, '0009784707188018', 'MESTRADO', '3', NULL, NULL, '', 'Conservatoire National Supérieur de Musique et de Danse de Paris', NULL, NULL, '', 'Estética', '', 'CONCLUIDO', 2005, 2007, 'NAO', '', '', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:35'),
(25, 1, '0009784707188018', 'DOUTORADO', '4', NULL, NULL, 'J28R00000000', 'Universidade de Paris-Sorbonne IV', NULL, NULL, '', 'Musicologia', '', 'CONCLUIDO', 2006, 2011, 'SIM', '000100000991', 'Université Paris-Sorbonne', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 10:04:35'),
(26, 2, '0010019430133988', 'GRADUACAO', '1', '', '', '005300000996', 'Escola de Belas Artes da Universidade Federal do Rio de Janeiro', '', '', '90000074', 'Bacharel em Escultura', '90000074', 'CONCLUIDO', 1980, 1984, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(27, 50, '0010019430133988', 'GRADUACAO', '1', '', '', '003900000990', 'Faculdade de Arquitetura e Urbanismo UFRJ', '', '', '90000054', 'Arquitetura', '90000054', 'INCOMPLETO', 1977, 1981, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(28, 3, '0010019430133988', 'ESPECIALIZACAO', '2', NULL, 'José Thomaz Brum', '011100000008', 'Pontifícia Universidade Católica do Rio de Janeiro', '', '', '90000001', 'História da Arte e da Arquitetura no Brasil', NULL, 'CONCLUIDO', 1994, 1995, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(29, 71, '0010019430133988', 'MESTRADO', '3', NULL, NULL, '032600000000', 'Universidade do Estado do Rio de Janeiro', '', '', '31030165', 'Filosofia', '70100004', 'CONCLUIDO', 1998, 2000, 'SIM', '045000000000', 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', '', '31004016016P0', NULL, 'Philosophy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(30, 73, '0010019430133988', 'MESTRADO', '3', NULL, NULL, '586600000000', 'Universidade Veiga de Almeida', '', '', '90000091', 'Psicanálise, Saúde e Sociedade', '90000091', 'CONCLUIDO', 2010, 2012, 'SIM', '005800000995', 'Universidade Federal do Estado do Rio de Janeiro - PRIQ', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(31, 81, '0010019430133988', 'MESTRADO', '3', NULL, NULL, '011100000008', 'Pontifícia Universidade Católica do Rio de Janeiro', '', '', '31040063', 'Filosofia', '70100004', 'CONCLUIDO', 1998, 2000, 'SIM', '002200000000', 'Conselho Nacional de Desenvolvimento Científico e Tecnológico', '1660892331457355', '31005012006P1', NULL, 'Philosophy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(32, 59, '0010019430133988', 'DOUTORADO', '4', NULL, NULL, '007900000004', 'Universidade Estadual de Campinas', '', '', '33070660', 'Filosofia', '70100004', 'INCOMPLETO', 2000, 2006, 'NAO', '', '', '', '33003017066P7', NULL, 'Philosophy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(33, 80, '0010019430133988', 'DOUTORADO', '4', NULL, NULL, '586600000000', 'Universidade Veiga de Almeida', '', '', '90000100', 'Doutorado em Psicanálise, saúde e Sociedade', '90000100', 'CONCLUIDO', 2013, 2017, 'NAO', '', '', '9225842201574214', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(34, 27, '0010019430133988', 'CURSO-TECNICO-PROFISSIONALIZANTE', '7', NULL, NULL, '001300000993', 'Colégio Sâo Paulo', '', '', '90000025', 'Curso técnico de Análises Clínicas', NULL, 'CONCLUIDO', 1970, 1974, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(35, 24, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, 'Heloisa Pires Ferreira', '001000000998', 'Oficina de Gravura Em Metal Sesc Tijuca', '', '', '90000010', 'Gravura Em Metal Calcografia', '90000010', 'CONCLUIDO', 1990, 1991, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(36, 23, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '000500000999', 'Atelier do Escultor e Restaurador Joaquim Lemos', '', '', '90000009', 'Escultura em Madeira / Restauração', '90000009', 'CONCLUIDO', 1987, 1987, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(37, 25, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '000900000996', 'Atelier de Xilogravura da Gravadora Anna Carolina Albernaz', '', '', '90000011', 'Xilogravura', '90000011', 'CONCLUIDO', 1993, 1994, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(38, 26, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '001100000990', 'Centro de Informação Arqueológica Fundação Getúlio Vargas', '', '', '90000012', 'Arqueologia', '90000012', 'CONCLUIDO', 1974, 1974, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(39, 35, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '003300000990', 'Atelier do escultor Humberto Cozzo', '', '', '', '', '', 'CONCLUIDO', 1979, 1980, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(40, 34, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '003200000998', 'Atelier de Serigrafia Paulo Pereira', '', '', '90000031', 'Serigrafia', '90000031', 'CONCLUIDO', 1987, 1988, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(41, 22, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '000800000994', 'Atelier do Escultor Armando Schnoor', '', '', '90000008', 'Escultura /Modelagem', '90000008', 'CONCLUIDO', 1982, 1985, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(42, 19, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '000700000992', 'Oficina do Espaço Roberto Moriconi', '', '', '90000005', 'Técnicas Mistas de representação Espacial', '90000005', 'CONCLUIDO', 1985, 1985, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(43, 18, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '000600000990', 'Atelier do Escultor Getulio Starling', '', '', '90000004', 'Escultura em Metal', '90000004', 'CONCLUIDO', 1982, 1984, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(44, 28, '0010019430133988', 'APERFEICOAMENTO', 'X', NULL, '', '002900000992', 'Atelier Mauricio Salgueiro', '', '', '90000026', 'Escultura Contemporânea/ Arte Cinética', '90000026', 'CONCLUIDO', 1983, 1985, 'NAO', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(45, 2, '0011219817945756', 'GRADUACAO', '1', 'Explorando a Arte Contemporânea', 'Vivian Busnardo', '000100000991', 'Escola de Música e Belas Artes do Paraná', '', '', '90000001', 'Licenciatura em Desenho', '90000001', 'CONCLUIDO', 2007, 2010, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(46, 1, '0011219817945756', 'GRADUACAO', '1', 'Animação: Pintura Digital', 'Ingo Moosburger e Maria José Justino', '000100000991', 'Escola de Música e Belas Artes do Paraná', '', '', '90000000', 'Bacharelado em Pintura', '90000000', 'CONCLUIDO', 2006, 2009, 'NAO', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(47, 5, '0011219817945756', 'ESPECIALIZACAO', '2', NULL, 'Prof.ª Paula Vizaco Rigo Cuéllar Tramujas', '000300000995', 'Escola de Música e Belas Artes do Paraná', '', '', '90000005', 'História da Arte Moderna e Contemporânea', NULL, 'CONCLUIDO', 2010, 2012, 'SIM', '000300000995', 'Escola de Música e Belas Artes do Paraná', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(48, 10, '0011219817945756', 'MESTRADO', '3', NULL, NULL, '119300000003', 'Universidade do Estado de Santa Catarina', '', '', '60024615', 'Artes Visuais', '80300006', 'CONCLUIDO', 2013, 2015, 'SIM', '002000000996', 'Programa de Bolsas de Monitoria de Pós-Graduação da UDESC, PROMOP, Brasil.', '6634786429422199', '41002016010P8', NULL, 'Arts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(49, 12, '0011219817945756', 'DOUTORADO', '4', NULL, NULL, '119300000003', 'Universidade do Estado de Santa Catarina', '', '', '60024615', 'Artes Visuais', '80300006', 'CONCLUIDO', 2015, 2019, 'SIM', '001900000994', 'Programa de Bolsas de Monitoria de Pós-Graduação da UDESC', '6634786429422199', '41002016010P8', NULL, 'Arts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(50, 1, '0003919386536713', 'DOUTORADO', '4', NULL, NULL, '236000000004', 'Universidad de Santiago de Compostela - Campus Santiago', '', '', '90000003', 'Teoria da Literatura e Literatura Comparada', '90000003', 'CONCLUIDO', 2010, 2019, 'SIM', '000200000993', 'Ministerio de Educación y Ciencia', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(51, 2, '0004022586110481', 'MESTRADO', '3', NULL, NULL, '006700000002', 'Universidade de São Paulo', '', '', '33020965', 'Ciências da Comunicação', '60900008', 'CONCLUIDO', 1990, 1994, 'NAO', '', '', '', '33002010096P7', NULL, 'Comunication', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26'),
(52, 1, '0004022586110481', 'DOUTORADO', '4', NULL, NULL, '006700000002', 'Universidade de São Paulo', '', '', '33021147', 'Artes', '80300006', 'CONCLUIDO', 1995, 2000, 'NAO', '', '', '', '33002010114P5', NULL, 'Arts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 08:08:26', '2025-11-13 08:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `lattes_researchers`
--

DROP TABLE IF EXISTS `lattes_researchers`;
CREATE TABLE IF NOT EXISTS `lattes_researchers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `idlattes` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador do pesquisador na Plataforma Lattes',
  `orcID` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_completo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano_doutorado` year DEFAULT NULL,
  `ano_posdoutorado` year DEFAULT NULL,
  `ano_mestrado` year DEFAULT NULL,
  `ano_graduacao` year DEFAULT NULL,
  `data_atualizacao` date DEFAULT NULL COMMENT 'Data da última atualização do currículo Lattes',
  `situacao_coleta` enum('pendente','em_coleta','coletado','erro') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente' COMMENT 'Situação do processo de coleta de dados',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nascimento_pais` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vinculo_instituicao` int NOT NULL DEFAULT '0',
  `nascimento_cidade` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idlattes` (`idlattes`),
  KEY `idx_nome` (`nome_completo`),
  KEY `idx_situacao` (`situacao_coleta`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lattes_researchers`
--

INSERT INTO `lattes_researchers` (`id`, `idlattes`, `orcID`, `nome_completo`, `nacionalidade`, `ano_doutorado`, `ano_posdoutorado`, `ano_mestrado`, `ano_graduacao`, `data_atualizacao`, `situacao_coleta`, `created_at`, `updated_at`, `nascimento_pais`, `vinculo_instituicao`, `nascimento_cidade`) VALUES
(3, '0004072613292475', '', 'Atila Ribeiro Regiani', 'Brasil', '2017', NULL, '2008', '2004', '2024-08-23', 'coletado', '2025-11-13 09:50:24', '2025-11-13 13:06:22', 'Peru', 1, 'Lima'),
(4, '0004706603300740', 'https://orcid.org/00', 'Janaina Schvambach', 'Brasil', '2020', NULL, '2010', '2006', '2024-07-11', '', '2025-11-13 09:50:24', '2025-11-13 13:06:22', 'Brasil', 2, 'São Miguel do Oeste'),
(5, '0007278260015680', '', 'Luciana Elisa Lozada Tenório', 'Brasil', '2017', NULL, '2013', '2010', '2024-03-08', 'coletado', '2025-11-13 09:50:24', '2025-11-13 13:04:35', 'Brasil', 3, 'Maringá'),
(6, '0008743559477412', 'https://orcid.org/00', 'Luciano da Costa Nazario', 'Brasil', '2017', NULL, '2007', '2004', '2025-03-22', 'coletado', '2025-11-13 09:50:24', '2025-11-13 13:04:35', 'Brasil', 4, 'Rio Grande'),
(7, '0009674810581809', '', 'Francisco Tupy Gomes Correa', 'Brasil', '2017', NULL, '2013', '2005', '2023-04-26', 'coletado', '2025-11-13 09:50:24', '2025-11-13 13:04:35', 'Brasil', 5, 'São Paulo'),
(8, '0009784707188018', '', 'Mathias Roger', '', '2011', NULL, '2007', '2003', '2013-09-04', 'coletado', '2025-11-13 09:50:24', '2025-11-13 11:22:37', 'França', 5, 'Nice'),
(9, '0010019430133988', 'http://orcid.org/000', 'Patricia Vivian von Benko Horvat', 'Brasil', '2017', NULL, '2000', '1981', '2024-05-23', 'coletado', '2025-11-13 09:50:24', '2025-11-13 11:22:37', 'Brasil', 6, 'Rio de Janeiro'),
(10, '0011219817945756', '', 'Rafael Schultz Myczkowski', 'Brasil', '2019', NULL, '2015', '2009', '2025-02-02', 'coletado', '2025-11-13 09:50:24', '2025-11-13 11:22:37', 'Brasil', 7, 'Itaiópolis'),
(11, '0014692223687736', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(12, '0014987556460591', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(13, '0016615895456187', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(14, '0016633385742336', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(15, '0016757428256557', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(16, '0021270141793953', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(17, '0022455027640104', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(18, '0022922708196026', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(19, '0024311302653987', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(20, '0024366223698692', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(21, '0024697241449516', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(22, '0024977948247395', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(23, '0025737955160334', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(24, '0026506533871929', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(25, '0031308428259339', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(26, '0031669201092957', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(27, '0033345213407184', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(28, '0033590915691960', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(29, '0038246950643543', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(30, '0039779933104797', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(31, '0041400444364626', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(32, '0042351447296584', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(33, '0042926780326467', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(34, '0043759595854301', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(35, '0044553371898324', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(36, '0044776599924662', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(37, '0048490191207720', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(38, '0051943663700839', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(39, '0054310935050478', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(40, '0055086110667463', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(41, '0055964600569667', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(42, '0056137103805108', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(43, '0059449047020903', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(44, '0061536860336610', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(45, '0064644440999586', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(46, '0064848908794311', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(47, '0065457360543420', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(48, '0066198607480458', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(49, '0073480063679881', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(50, '0075321687481406', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(51, '0077295633943448', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(52, '0077830707011397', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(53, '0078265561442268', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(54, '0078908653872214', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(55, '0080717401701387', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(56, '0080806155449987', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(57, '0082489998988349', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(58, '0084587484397779', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(59, '0085464740753410', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(60, '0085910433103746', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(61, '0087710881975586', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(62, '0088148939420174', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(63, '0090264123376586', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(64, '0090487072535815', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(65, '0093501591730029', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(66, '0095674903266269', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(67, '0095785210350166', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(68, '0096246030057998', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(69, '0096908516252005', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(70, '0097225405601898', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(71, '0099839210424757', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(72, '0103670479374870', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(73, '0105103630232330', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(74, '0105169821549649', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(75, '0107833031325109', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(76, '0115290332424686', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(77, '0116868370405159', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(78, '0121492505345809', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(79, '0122570064924211', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(80, '0123565049834202', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(81, '0129810966268826', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(82, '0134613809884885', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(83, '0135514084578874', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(84, '0136019251243788', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(85, '0137042082983485', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(86, '0137525443525215', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(87, '0137944963846547', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(88, '0139950052938367', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(89, '0141389002346917', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(90, '0144065626783174', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(91, '0144255593679291', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(92, '0145317590210668', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(93, '0146892410251360', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(94, '0148310591809616', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(95, '0148840984724085', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(96, '0149114925951052', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(97, '0152406572004828', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(98, '0157280353796356', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(99, '0166203467582187', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(100, '0167585419146104', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(101, '0168506847437120', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(102, '0169275269797691', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(103, '0172503460046947', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(104, '0173402410647199', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(105, '0175768759753693', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(106, '0175914492530439', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(107, '0180031783903467', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(108, '0180561132480429', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(109, '0181238591339993', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(110, '0183785831384933', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(111, '0184416940270273', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(112, '0184584802331353', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(113, '0185617848060730', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(114, '0187965704879377', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(115, '0189444951687068', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(116, '0191659852270195', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(117, '0197389281039103', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(118, '0197656843094994', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(119, '0198608985380472', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(120, '0199098726098947', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(121, '0201945304099855', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(122, '0202517834827018', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(123, '0205035296600439', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(124, '0205821659629755', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(125, '0206022223969980', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2025-11-13 09:50:24', '2025-11-13 09:50:24', '', 0, ''),
(126, '0003919386536713', '', 'Lucía María Montenegro Pico', 'Espanha', '2019', NULL, NULL, NULL, '2020-09-09', 'coletado', '2025-11-13 09:50:56', '2025-11-13 11:22:37', 'Espanha', 8, ''),
(127, '0004022586110481', '', 'Eduardo Simões dos Santos Mendes', 'Brasil', '2000', NULL, '1994', NULL, '2024-11-27', 'coletado', '2025-11-13 09:50:56', '2025-11-13 11:22:37', 'Brasil', 9, 'São Paulo');

-- --------------------------------------------------------

--
-- Table structure for table `producao_artistica`
--

DROP TABLE IF EXISTS `producao_artistica`;
CREATE TABLE IF NOT EXISTS `producao_artistica` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lattes` varchar(20) NOT NULL,
  `sequencia_producao` int DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `natureza` varchar(150) DEFAULT NULL,
  `titulo` text,
  `ano` int DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `idioma` varchar(50) DEFAULT NULL,
  `flag_relevancia` varchar(5) DEFAULT NULL,
  `titulo_ingles` text,
  `meio_divulgacao` varchar(100) DEFAULT NULL,
  `home_page` text,
  `flag_divulgacao_cientifica` varchar(5) DEFAULT NULL,
  `premiacao` text,
  `atividade_autores` text,
  `instituicao_evento` text,
  `local_evento` text,
  `cidade_evento` varchar(150) DEFAULT NULL,
  `temporada` varchar(150) DEFAULT NULL,
  `informacoes_adicionais` text,
  `tipo_evento` varchar(150) DEFAULT NULL,
  `data_estreia` varchar(50) DEFAULT NULL,
  `data_encerramento` varchar(50) DEFAULT NULL,
  `local_estreia` varchar(255) DEFAULT NULL,
  `instituicao_promotora_premio` varchar(255) DEFAULT NULL,
  `obra_referencia` text,
  `autor_obra_referencia` varchar(255) DEFAULT NULL,
  `ano_obra_referencia` varchar(10) DEFAULT NULL,
  `duracao` varchar(100) DEFAULT NULL,
  `flag_itinerante` varchar(5) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Rene Faustino Gabriel Junior', 'renefgj@gmail.com', '$2y$10$RQCJL/4KzaCD6m34gpYYs.RCm56wPJaq9OEK95XOcwXYf8wancaUq', 'admin', '2025-11-12 18:35:33', '2025-11-12 18:35:33');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
