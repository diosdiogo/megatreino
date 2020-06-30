<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$idaluno = $_GET["idaluno"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getPagarReceber($conexao, $id, $idaluno)) . ']}';
	echo $lista;
}

function getPagarReceber($conexao, $id, $idaluno) {
	$retorno = array();

	$sql = "select *,if(canc='CA', 'CT',if(canc='BL','PZ',if(canc='DN/CT','DN/CA',canc))) as sigla from pagar_receber where academia={$id} and aluno_fornecedor = {$idaluno}  order by pagamento desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'id' => $row['id'],
			'documento' => $row['documento'],
			'pagar_receber' => $row['pagar_receber'],
			'parc' => utf8_encode($row['parc']),
			'aluno_fornecedor' => $row['aluno_fornecedor'],
			'colaborador' => $row['colaborador'],
			'emissao' => $row['emissao'],
			'vencimento' => $row['vencimento'],
			'liberadoate' => $row['liberadoate'],
			'valor' => $row['valor'],
			'canc' => utf8_encode($row['canc']),
			'tipodoc' => utf8_encode($row['tipodoc']),
			'bloq' => utf8_encode($row['bloq']),
			'historico' => utf8_encode($row['historico']),
			'obs' => utf8_encode($row['obs']),
			'parc' => utf8_encode($row['parc']),
			'sel' => utf8_encode($row['sel']),
			'bloq_banco' => $row['bloq_banco'],
			'val_origem' => $row['val_origem'],
			'val_pago' => $row['val_pago'],
			'pagamento' => $row['pagamento'],
			'pago' => $row['pago'],
			'academia' => $row['academia'],
			'ocorrencia' => $row['ocorrencia'],
			'historico_model' => $row['historico_model'],
			'venda' => $row['venda'],
			'idassinatura_iugu' => utf8_encode($row['idassinatura_iugu']),
			'sigla' => utf8_encode($row['sigla']),
			'deletado' => utf8_encode($row['deletado'])));
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