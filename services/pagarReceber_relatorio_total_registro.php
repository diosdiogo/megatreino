<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

$professor = $_GET["professor"];
$modalidade = $_GET["mododalidade"];
$pagamento = $_GET["pagamento"];

$mod1 = ',matricula.aluno';
$mod2 = 'inner join matricula';
$mod3 = 'and aluno.idaluno=matricula.aluno';
$mod4 = 'modalidade=' . $modalidade . ' and';

$prof = 'aluno.professor=' . $professor . ' and';

$pg1 = 'canc=';
$pg2 = $pagamento;
$pg3 = ' and';

if ($professor == 1) {

	$prof = '';

}

if ($modalidade == 1) {

	$mod1 = '';
	$mod2 = '';
	$mod3 = '';
	$mod4 = '';

}

if ($state == "C") {

	if ($pagamento == 1) {
		$lista = '{"result":[' . json_encode(getPagarReceber($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4)) . ']}';
		echo $lista;
	} else if ($pagamento == "CT") {
		$lista = '{"result":[' . json_encode(getPagarReceberCA($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4)) . ']}';
		echo $lista;
	} else if ($pagamento == "DN" or $pagamento == "SV") {

		$lista = '{"result":[' . json_encode(getPagarReceberDN($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4)) . ']}';
		echo $lista;

	} else if ($pagamento == "CH") {

		$lista = '{"result":[' . json_encode(getPagarReceberCH($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4)) . ']}';
		echo $lista;

	} else if ($pagamento == "REC") {

		$lista = '{"result":[' . json_encode(getPagarReceberREC($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4)) . ']}';
		echo $lista;

	}
}

function getPagarReceber($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4) {
	$retorno = array();

	$sql = "select count(distinct pagar_receber.id) as rg from pagar_receber
inner join aluno {$mod2} on(pagar_receber.aluno_fornecedor=aluno.idaluno  {$mod3})
where pagar_receber.academia={$id} and {$prof} {$mod4}
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'
 order by date(pagamento) desc;";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'rg' => $row['rg']));
	}

	return $retorno;
}

function getPagarReceberCA($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4) {
	$retorno = array();

	$sql = "select count(distinct pagar_receber.id) as rg from pagar_receber
inner join aluno {$mod2} on(pagar_receber.aluno_fornecedor=aluno.idaluno  {$mod3})
where pagar_receber.academia={$id} and {$prof} {$mod4} (canc='CA' or canc='DN/CT' or canc='CT') and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'
 order by date(pagamento) desc;";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'rg' => $row['rg']));
	}

	return $retorno;
}

function getPagarReceberDN($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4) {
	$retorno = array();

	$sql = "select count(distinct pagar_receber.id) as rg from pagar_receber
inner join aluno {$mod2} on(pagar_receber.aluno_fornecedor=aluno.idaluno  {$mod3})
where pagar_receber.academia={$id} and {$prof} {$mod4} (canc='DN' or canc='DN/CT') and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'
 order by date(pagamento) desc;";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'rg' => $row['rg']));
	}

	return $retorno;
}

function getPagarReceberCH($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4) {
	$retorno = array();

	$sql = "select count(distinct pagar_receber.id) as rg from pagar_receber
inner join aluno {$mod2} on(pagar_receber.aluno_fornecedor=aluno.idaluno  {$mod3})
where pagar_receber.academia={$id} and {$prof} {$mod4} (canc='CH') and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'
 order by date(pagamento) desc;";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'rg' => $row['rg']));
	}

	return $retorno;
}

function getPagarReceberREC($conexao, $id, $dataI, $dataF, $prof, $mod2, $mod3, $mod4) {
	$retorno = array();

	$sql = "select count(distinct pagar_receber.id) as rg from pagar_receber
inner join aluno {$mod2} on(pagar_receber.aluno_fornecedor=aluno.idaluno  {$mod3})
where pagar_receber.academia={$id} and {$prof} {$mod4} canc='REC' and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'
 order by date(pagamento) desc;";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'rg' => $row['rg']));
	}

	return $retorno;
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