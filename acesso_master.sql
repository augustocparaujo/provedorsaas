-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 11/10/2023 às 00:37
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `acesso_douglas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `caixa`
--

CREATE TABLE `caixa` (
  `id` int(255) NOT NULL,
  `banco` varchar(50) NOT NULL DEFAULT '',
  `titulo` varchar(50) NOT NULL DEFAULT '',
  `nrecibo` varchar(100) NOT NULL DEFAULT '',
  `idempresa` varchar(100) NOT NULL DEFAULT '',
  `nomecliente` varchar(150) NOT NULL DEFAULT '',
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valorpago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `dinheiro` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cartaocredito` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cartaodebito` decimal(10,2) NOT NULL DEFAULT 0.00,
  `boleto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pix` decimal(10,2) NOT NULL DEFAULT 0.00,
  `data` date NOT NULL,
  `datapagamento` date NOT NULL,
  `user` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chamado`
--

CREATE TABLE `chamado` (
  `id` int(255) NOT NULL,
  `nchamado` varchar(100) NOT NULL DEFAULT '',
  `idcliente` varchar(50) NOT NULL DEFAULT '',
  `idcontrato` varchar(255) NOT NULL DEFAULT '',
  `idtecnico` int(255) NOT NULL DEFAULT 0,
  `nometecnico` varchar(100) NOT NULL DEFAULT '',
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `nomecliente` varchar(100) NOT NULL DEFAULT '',
  `tipo` varchar(50) NOT NULL DEFAULT '',
  `usuariocad` varchar(100) NOT NULL DEFAULT '',
  `datacad` date NOT NULL DEFAULT '0000-00-00',
  `obs` longtext NOT NULL,
  `usuarioatendeu` varchar(100) NOT NULL DEFAULT '',
  `dataatendimento` date NOT NULL DEFAULT '0000-00-00',
  `situacao` varchar(30) NOT NULL DEFAULT '',
  `img1` varchar(100) NOT NULL DEFAULT '',
  `img2` varchar(100) NOT NULL DEFAULT '',
  `pdf` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `nome` varchar(100) NOT NULL DEFAULT '',
  `cpf` varchar(20) NOT NULL DEFAULT '',
  `cnpj` varchar(50) NOT NULL DEFAULT '',
  `fantasia` varchar(100) NOT NULL DEFAULT '',
  `ie` varchar(20) NOT NULL DEFAULT '',
  `rg` varchar(30) NOT NULL DEFAULT '',
  `rguf` varchar(2) NOT NULL DEFAULT '',
  `emissor` varchar(30) NOT NULL DEFAULT '',
  `nascimento` varchar(20) NOT NULL DEFAULT '',
  `contato` varchar(20) NOT NULL DEFAULT '' COMMENT 'contato whatsapp',
  `contato2` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `cep` varchar(20) NOT NULL DEFAULT '',
  `rua` varchar(100) NOT NULL DEFAULT '',
  `numero` varchar(10) NOT NULL DEFAULT '',
  `bairro` varchar(50) NOT NULL DEFAULT '',
  `municipio` varchar(50) NOT NULL DEFAULT '',
  `estado` varchar(50) NOT NULL DEFAULT '',
  `ibge` varchar(30) NOT NULL DEFAULT '',
  `complemento` varchar(255) NOT NULL DEFAULT '',
  `tipodecobranca` varchar(50) NOT NULL DEFAULT '',
  `vencimento` int(2) NOT NULL DEFAULT 0,
  `situacao` varchar(30) NOT NULL DEFAULT '',
  `usuariocad` varchar(50) NOT NULL DEFAULT '',
  `data` date NOT NULL DEFAULT '0000-00-00',
  `usuarioatualizou` varchar(255) NOT NULL,
  `atualizado` varchar(30) NOT NULL DEFAULT '',
  `coordenadas` varchar(100) NOT NULL DEFAULT '',
  `latitude` varchar(50) NOT NULL DEFAULT '',
  `longitude` varchar(50) NOT NULL DEFAULT '',
  `banco` varchar(50) NOT NULL DEFAULT '',
  `ativacao` date NOT NULL DEFAULT '0000-00-00',
  `obs` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cobranca`
--

CREATE TABLE `cobranca` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `idcliente` varchar(255) NOT NULL DEFAULT '',
  `idcobranca` varchar(255) NOT NULL DEFAULT '',
  `idcobrancaprincipal` varchar(255) NOT NULL DEFAULT '',
  `nparcela` varchar(255) NOT NULL DEFAULT '',
  `parcela` int(2) NOT NULL DEFAULT 0,
  `banco` varchar(50) NOT NULL DEFAULT '',
  `tipo` varchar(30) NOT NULL DEFAULT '',
  `tipocobranca` varchar(20) NOT NULL DEFAULT '',
  `modelodacobranca` varchar(100) NOT NULL DEFAULT '' COMMENT 'boleto,pix,porta a porta, etc',
  `code` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT 'se tiver link é boleto',
  `installmentLink` varchar(255) NOT NULL DEFAULT '',
  `linkmercado` varchar(255) NOT NULL DEFAULT '',
  `codigobarra` varchar(255) NOT NULL DEFAULT '',
  `codigodelinhadigitavel` varchar(255) NOT NULL DEFAULT '',
  `ncobranca` varchar(255) NOT NULL DEFAULT '',
  `custom_id` varchar(100) NOT NULL DEFAULT '',
  `cliente` varchar(100) NOT NULL DEFAULT '',
  `descricao` longtext DEFAULT NULL,
  `vencimento` date NOT NULL DEFAULT '0000-00-00',
  `databloqueio` date NOT NULL DEFAULT '0000-00-00',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valorpago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `datapagamento` date NOT NULL DEFAULT '0000-00-00',
  `situacao` varchar(20) NOT NULL DEFAULT '',
  `obs` longtext DEFAULT NULL,
  `usuarioatualizou` varchar(100) NOT NULL DEFAULT '',
  `atualizado` date NOT NULL DEFAULT '0000-00-00',
  `datagerado` date NOT NULL DEFAULT '0000-00-00',
  `nota` varchar(100) NOT NULL DEFAULT '',
  `pdf` varchar(255) NOT NULL DEFAULT '''',
  `qrcode` longtext DEFAULT NULL,
  `qrcode2` longtext DEFAULT NULL,
  `teste` int(1) DEFAULT NULL,
  `infobanco` varchar(50) NOT NULL DEFAULT '',
  `atualizadoauto` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `config_sms`
--

CREATE TABLE `config_sms` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `usuariosms` varchar(100) NOT NULL DEFAULT '',
  `senhasms` varchar(50) NOT NULL DEFAULT '',
  `antes` int(2) NOT NULL DEFAULT 0,
  `depois` int(2) NOT NULL DEFAULT 0,
  `texto` text NOT NULL,
  `api` varchar(20) NOT NULL DEFAULT '',
  `token` varchar(50) NOT NULL DEFAULT '',
  `instancia` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos`
--

CREATE TABLE `contratos` (
  `id` int(255) NOT NULL,
  `idcliente` int(255) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `cep` varchar(10) NOT NULL DEFAULT '',
  `rua` varchar(100) NOT NULL DEFAULT '',
  `numero` varchar(10) NOT NULL DEFAULT '',
  `bairro` varchar(50) NOT NULL DEFAULT '',
  `municipio` varchar(50) NOT NULL DEFAULT '',
  `estado` varchar(50) NOT NULL DEFAULT '',
  `ibge` varchar(30) NOT NULL DEFAULT '',
  `complemento` varchar(255) NOT NULL DEFAULT '',
  `login` varchar(50) NOT NULL DEFAULT '',
  `senha` varchar(50) NOT NULL DEFAULT '',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `porta` varchar(10) NOT NULL DEFAULT '',
  `mac` varchar(100) NOT NULL DEFAULT '',
  `plano` varchar(30) NOT NULL DEFAULT '',
  `nomeplano` varchar(100) NOT NULL DEFAULT '',
  `service` varchar(10) NOT NULL DEFAULT '',
  `ativacao` varchar(20) NOT NULL DEFAULT '0000-00-00',
  `situacao` varchar(30) NOT NULL DEFAULT '',
  `usuariocad` varchar(50) NOT NULL DEFAULT '',
  `data` date NOT NULL DEFAULT '0000-00-00',
  `usuarioatualizou` varchar(100) NOT NULL DEFAULT '',
  `atualizado` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(50) NOT NULL DEFAULT '',
  `longitude` varchar(50) NOT NULL DEFAULT '',
  `nsecomodato` varchar(100) NOT NULL DEFAULT '',
  `modelocomodato` varchar(100) NOT NULL DEFAULT '',
  `maccomodato` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `controle_cto`
--

CREATE TABLE `controle_cto` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(20) NOT NULL DEFAULT '',
  `empresa` varchar(20) NOT NULL DEFAULT '',
  `cto` varchar(50) NOT NULL DEFAULT '',
  `porta` varchar(20) NOT NULL DEFAULT '',
  `cliente` text NOT NULL,
  `longitude` varchar(50) NOT NULL DEFAULT '',
  `latitude` varchar(50) NOT NULL DEFAULT '',
  `estado` varchar(2) NOT NULL DEFAULT '',
  `localizacao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dadoscobranca`
--

CREATE TABLE `dadoscobranca` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `tokenprivado` varchar(255) NOT NULL DEFAULT '',
  `clienteid` longtext DEFAULT NULL,
  `clientesecret` varchar(255) NOT NULL DEFAULT '',
  `chavepixaleatoria` varchar(255) NOT NULL DEFAULT '',
  `chavepixsecundaria` varchar(100) NOT NULL DEFAULT '',
  `recebercom` varchar(20) NOT NULL DEFAULT '',
  `diagerar` varchar(2) NOT NULL DEFAULT '0',
  `aposvencimento` varchar(2) NOT NULL DEFAULT '0',
  `diasdesconto` varchar(4) NOT NULL DEFAULT '',
  `valordesconto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `multaapos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jurosapos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `diasbloqueio` varchar(2) NOT NULL DEFAULT '',
  `bloqueioautomatico` varchar(10) NOT NULL DEFAULT '',
  `data` datetime NOT NULL,
  `atualizado` date NOT NULL DEFAULT '0000-00-00',
  `user` varchar(255) NOT NULL DEFAULT '',
  `token_sms` varchar(100) NOT NULL DEFAULT '',
  `contasms` varchar(10) NOT NULL DEFAULT '''''',
  `smsamiversariante` varchar(255) NOT NULL DEFAULT '',
  `alertabaixa` varchar(20) NOT NULL DEFAULT '',
  `smsaniversario` varchar(10) NOT NULL DEFAULT '',
  `textosmsantes` varchar(160) NOT NULL DEFAULT '',
  `antesdovencimento` int(2) NOT NULL DEFAULT 0,
  `depoisdovencimento` int(2) NOT NULL DEFAULT 0,
  `textosmsdepois` varchar(160) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `convenio` varchar(20) NOT NULL DEFAULT '',
  `carteira` varchar(20) NOT NULL DEFAULT '',
  `variacaocarteira` varchar(20) NOT NULL DEFAULT '',
  `agencia` varchar(10) NOT NULL DEFAULT '',
  `conta` varchar(20) NOT NULL DEFAULT '',
  `codigocedente` varchar(20) NOT NULL DEFAULT '',
  `contrato` varchar(20) NOT NULL DEFAULT '',
  `keydev` varchar(255) NOT NULL DEFAULT '"',
  `usuariosms` varchar(100) NOT NULL DEFAULT '',
  `senhasms` varchar(100) NOT NULL DEFAULT '',
  `pix` varchar(10) NOT NULL DEFAULT '',
  `boleto` varchar(10) NOT NULL DEFAULT '',
  `cartao` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `idcliente` varchar(50) NOT NULL DEFAULT '',
  `obs` longtext NOT NULL,
  `usuariocad` varchar(100) NOT NULL DEFAULT '',
  `datacad` date NOT NULL DEFAULT '0000-00-00',
  `usuarioatualizou` longtext NOT NULL DEFAULT '',
  `dataatualizacao` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `j_categoria_estoque`
--

CREATE TABLE `j_categoria_estoque` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(100) NOT NULL DEFAULT '',
  `nome_cat` varchar(100) NOT NULL DEFAULT '',
  `usuariocad` varchar(100) NOT NULL DEFAULT '',
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `j_estoque`
--

CREATE TABLE `j_estoque` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(50) NOT NULL,
  `categoria` varchar(50) NOT NULL DEFAULT '',
  `fornecedor` varchar(100) NOT NULL DEFAULT '',
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `quantidade` int(255) NOT NULL,
  `usuariocad` varchar(100) NOT NULL DEFAULT '',
  `data` datetime NOT NULL,
  `situacao` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `j_estoque_saida`
--

CREATE TABLE `j_estoque_saida` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(100) NOT NULL DEFAULT '',
  `iditem` int(255) NOT NULL,
  `quantidade` int(255) NOT NULL,
  `usuariocad` varchar(100) NOT NULL,
  `data` datetime NOT NULL,
  `usuariosaida` varchar(100) NOT NULL DEFAULT '',
  `datasaida` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `j_fornecedor_equip`
--

CREATE TABLE `j_fornecedor_equip` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(100) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `usuariocad` varchar(100) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `j_gastos`
--

CREATE TABLE `j_gastos` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(100) NOT NULL DEFAULT '',
  `categoria` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL DEFAULT '',
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `vencimento` date NOT NULL DEFAULT '0000-00-00',
  `valor` decimal(10,2) NOT NULL,
  `data` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_chamado`
--

CREATE TABLE `log_chamado` (
  `id` int(255) NOT NULL,
  `nchamado` varchar(100) NOT NULL DEFAULT '',
  `usuariocad` varchar(100) NOT NULL DEFAULT '',
  `datacad` date NOT NULL,
  `obs` longtext NOT NULL,
  `imgRetorno` varchar(100) NOT NULL DEFAULT '',
  `docRetorno` varchar(100) NOT NULL DEFAULT '',
  `situacao` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_cobranca`
--

CREATE TABLE `log_cobranca` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `idcliente` varchar(255) NOT NULL DEFAULT '',
  `cliente` varchar(255) NOT NULL DEFAULT '',
  `data` datetime NOT NULL,
  `log` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_sms`
--

CREATE TABLE `log_sms` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `contato` varchar(20) NOT NULL DEFAULT '',
  `mensagem` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(100) NOT NULL DEFAULT '',
  `data` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacao_agendada`
--

CREATE TABLE `notificacao_agendada` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `idcobranca` varchar(255) NOT NULL DEFAULT '',
  `nome` varchar(100) NOT NULL DEFAULT '',
  `contato` varchar(20) NOT NULL DEFAULT '',
  `notificacao` longtext NOT NULL,
  `datadisparo` date NOT NULL DEFAULT '0000-00-00',
  `situacao` varchar(10) NOT NULL DEFAULT '',
  `erro` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissao`
--

CREATE TABLE `permissao` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(100) NOT NULL DEFAULT '',
  `usuario` varchar(100) NOT NULL DEFAULT '',
  `item` varchar(100) NOT NULL DEFAULT '',
  `valor` varchar(10) NOT NULL DEFAULT '',
  `datacad` date NOT NULL,
  `usuariocad` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano`
--

CREATE TABLE `plano` (
  `id` bigint(20) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `servidor` int(255) NOT NULL DEFAULT 0,
  `nomeservidor` varchar(100) NOT NULL DEFAULT '',
  `plano` varchar(50) NOT NULL DEFAULT '',
  `enderecolocal` varchar(50) NOT NULL DEFAULT '',
  `enderecoremoto` varchar(50) NOT NULL DEFAULT '',
  `velocidade` varchar(50) NOT NULL DEFAULT '',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `situacao` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servidor`
--

CREATE TABLE `servidor` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(255) NOT NULL DEFAULT '',
  `nome` varchar(50) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `datacadastro` date NOT NULL DEFAULT '0000-00-00',
  `situacao` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sms`
--

CREATE TABLE `sms` (
  `id` bigint(255) NOT NULL,
  `contato` varchar(20) NOT NULL,
  `textosms` varchar(255) NOT NULL,
  `data` datetime NOT NULL,
  `usuario` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `token_cli`
--

CREATE TABLE `token_cli` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `token` longtext NOT NULL,
  `data` datetime NOT NULL,
  `expira` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `sistema` varchar(10) NOT NULL DEFAULT '',
  `idempresa` varchar(50) NOT NULL DEFAULT '',
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `logomarca` varchar(255) NOT NULL DEFAULT '',
  `nome` varchar(100) NOT NULL DEFAULT '',
  `cargo` varchar(50) NOT NULL DEFAULT '',
  `fantasia` varchar(100) NOT NULL DEFAULT '',
  `cpf_cnpj` varchar(20) NOT NULL DEFAULT '',
  `isestadual` varchar(50) NOT NULL DEFAULT '',
  `ismunicipal` varchar(50) NOT NULL DEFAULT '',
  `codigoibge` varchar(50) NOT NULL DEFAULT '',
  `regime` varchar(100) NOT NULL DEFAULT '',
  `rg` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `nascimento` varchar(20) NOT NULL DEFAULT '',
  `contato` varchar(20) NOT NULL DEFAULT '' COMMENT 'campo whatsapp',
  `contato2` varchar(20) NOT NULL DEFAULT '',
  `cep` varchar(15) NOT NULL DEFAULT '',
  `rua` varchar(255) NOT NULL DEFAULT '',
  `bairro` varchar(100) NOT NULL DEFAULT '',
  `cidade` varchar(50) NOT NULL DEFAULT '',
  `estado` varchar(2) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `senha` varchar(50) NOT NULL DEFAULT '',
  `datacadastro` varchar(20) NOT NULL DEFAULT '',
  `usuariocad` varchar(100) NOT NULL DEFAULT '',
  `situacao` int(1) NOT NULL DEFAULT 0,
  `obs` varchar(255) NOT NULL DEFAULT '',
  `tema` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `user`
--

INSERT INTO `user` (`id`, `sistema`, `idempresa`, `tipo`, `logomarca`, `nome`, `cargo`, `fantasia`, `cpf_cnpj`, `isestadual`, `ismunicipal`, `codigoibge`, `regime`, `rg`, `email`, `nascimento`, `contato`, `contato2`, `cep`, `rua`, `bairro`, `cidade`, `estado`, `user`, `senha`, `datacadastro`, `usuariocad`, `situacao`, `obs`, `tema`, `ip`) VALUES
(1, 'master', '999', 'Admin', '', 'Admin', '', '', '', '', '', '', '', '', 'admin@admin.com', '', '', '', '', '', '', '', '', '21232f297a57a5a743894a0e4a801fc3', '21232f297a57a5a743894a0e4a801fc3', '', '', 1, '', '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_cobranca`
--

CREATE TABLE `user_cobranca` (
  `id` int(255) NOT NULL,
  `idcliente` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT 'se tiver link é boleto',
  `codigobarra` varchar(255) NOT NULL DEFAULT '',
  `ncobranca` varchar(255) NOT NULL DEFAULT '',
  `obs` varchar(255) NOT NULL DEFAULT '',
  `vencimento` date NOT NULL DEFAULT '0000-00-00',
  `diabloqueio` date NOT NULL DEFAULT '0000-00-00',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `datapagamento` date NOT NULL DEFAULT '0000-00-00',
  `situacao` varchar(20) NOT NULL DEFAULT '',
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(255) NOT NULL,
  `idempresa` varchar(100) NOT NULL DEFAULT '',
  `nome` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `cpf` varchar(11) NOT NULL DEFAULT '',
  `contato` varchar(20) NOT NULL DEFAULT '',
  `logintxt` varchar(100) NOT NULL DEFAULT '',
  `login` varchar(100) NOT NULL DEFAULT '',
  `senha` varchar(100) NOT NULL DEFAULT '',
  `logomarca` varchar(100) NOT NULL DEFAULT '',
  `situacao` int(1) NOT NULL DEFAULT 0,
  `datacad` datetime NOT NULL,
  `usuariocad` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vpn`
--

CREATE TABLE `vpn` (
  `id` int(255) NOT NULL,
  `login` varchar(30) NOT NULL,
  `senha` varchar(30) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `acesso.vpn_gisp` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `chamado`
--
ALTER TABLE `chamado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cobranca`
--
ALTER TABLE `cobranca`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `config_sms`
--
ALTER TABLE `config_sms`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `controle_cto`
--
ALTER TABLE `controle_cto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `dadoscobranca`
--
ALTER TABLE `dadoscobranca`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `j_categoria_estoque`
--
ALTER TABLE `j_categoria_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `j_estoque`
--
ALTER TABLE `j_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `j_estoque_saida`
--
ALTER TABLE `j_estoque_saida`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `j_fornecedor_equip`
--
ALTER TABLE `j_fornecedor_equip`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `j_gastos`
--
ALTER TABLE `j_gastos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log_chamado`
--
ALTER TABLE `log_chamado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log_cobranca`
--
ALTER TABLE `log_cobranca`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log_sms`
--
ALTER TABLE `log_sms`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `notificacao_agendada`
--
ALTER TABLE `notificacao_agendada`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `permissao`
--
ALTER TABLE `permissao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `plano`
--
ALTER TABLE `plano`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `servidor`
--
ALTER TABLE `servidor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `token_cli`
--
ALTER TABLE `token_cli`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `idempresa` (`idempresa`);

--
-- Índices de tabela `user_cobranca`
--
ALTER TABLE `user_cobranca`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vpn`
--
ALTER TABLE `vpn`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `chamado`
--
ALTER TABLE `chamado`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cobranca`
--
ALTER TABLE `cobranca`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `config_sms`
--
ALTER TABLE `config_sms`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `controle_cto`
--
ALTER TABLE `controle_cto`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dadoscobranca`
--
ALTER TABLE `dadoscobranca`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `j_categoria_estoque`
--
ALTER TABLE `j_categoria_estoque`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `j_estoque`
--
ALTER TABLE `j_estoque`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `j_estoque_saida`
--
ALTER TABLE `j_estoque_saida`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `j_fornecedor_equip`
--
ALTER TABLE `j_fornecedor_equip`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `j_gastos`
--
ALTER TABLE `j_gastos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_chamado`
--
ALTER TABLE `log_chamado`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_cobranca`
--
ALTER TABLE `log_cobranca`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_sms`
--
ALTER TABLE `log_sms`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacao_agendada`
--
ALTER TABLE `notificacao_agendada`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `permissao`
--
ALTER TABLE `permissao`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `plano`
--
ALTER TABLE `plano`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servidor`
--
ALTER TABLE `servidor`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sms`
--
ALTER TABLE `sms`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `token_cli`
--
ALTER TABLE `token_cli`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `user_cobranca`
--
ALTER TABLE `user_cobranca`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vpn`
--
ALTER TABLE `vpn`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
