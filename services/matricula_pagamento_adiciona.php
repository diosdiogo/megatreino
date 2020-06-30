
<?php

include 'conecta.php';

$idaluno = $_GET["idaluno"];
$idacademia = $_GET["academia"];
$idcolaborador = $_GET["idcolaborador"];
$ocorrencia = $_GET["ocorrencia"];
$pagarreceber = $_GET["pagarreceber"];
$dataV = $_GET["dataV"];

print("ID aluno: " . $idaluno . "<br>");
print("ID Academia: " . $idacademia . "<br>");
print("ID Consultor: " . $idcolaborador . "<br>");
print("Ocorrencia: " . $ocorrencia . "<br>");
print("Pagar Receber: " . $pagarreceber . "<br>");
print("Pagar Receber: " . $dataV . "<br>");

inserePagamento($conexao, $pagarreceber, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataV);

function inserePagamento($conexao, $pagarreceber, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataV) {

	$query = "insert into pagar_receber (
					pagar_receber,
					aluno_fornecedor,
					colaborador,
					ocorrencia,
					academia,
					historico,
					deletado)

					values (
						'{$pagarreceber}',
						{$idaluno},
						{$idcolaborador},
						{$ocorrencia},
						{$idacademia},
						'{$dataV}',
						'N')";

	return mysqli_query($conexao, $query);
}

function alteraAluno($conexao, $idaluno) {
	$query = "update aluno set

			status='Liberado',

			where idaluno={$idaluno};";

	echo $query;

	return mysqli_query($conexao, $query);
}

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
