
<?php

include 'conecta.php';

$modalidade = $_GET['modalidade'];
$plano = $_GET['plano'];

insereMatricula($conexao, $modalidade, $plano);

function insereMatricula($conexao, $modalidade, $plano) {

	$query = "insert into teste (
				nome,
				texto
				)

				values (
				'{$modalidade}',
				'{$plano}'
				)";

	return mysqli_query($conexao, $query);
}

/*
id int(11) AI PK
aluno int(11)
modalidade int(11)
plano_pagamento varchar(45)
valor varchar(45)
data_inicio date
vencimento int(11)
desconto decimal(10,2)
ativo tinyint(1)
academia int(11)
data_fim date
colaborador int(11)
ocorrencia bigint(20)

 */
/*

function inserePagamento($conexao)
/*
id int(11) AI PK
documento int(11)
pagar_receber varchar(1)
parc varchar(5)
aluno_fornecedor int(11)
colaborador int(11)
emissao date
vencimento date
liberadoate date
valor decimal(10,2)
canc varchar(80)
tipodoc int(11)
bloq varchar(20)
historico varchar(80)
obs longtext
sel varchar(1)
bloq_banco int(11)
val_origem decimal(10,2)
val_pago decimal(10,2)
desconto decimal(10,2)
pagamento date
pago tinyint(1)
academia int(11)
ocorrencia bigint(20)
historico_model int(11)
venda tinyint(1)
idassinatura_iugu varchar(255)
deletado char(1)
 */
