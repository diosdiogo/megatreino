<?php

include 'conecta.php';
setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];
if (isset($_GET["dataI"])) {
	$dataI = $_GET["dataI"];
}
if (isset($_GET["dataF"])) {
	$dataF = $_GET["dataF"];
}

$hoje=date('Y-m-d');


if ($state == "C") {
	if (isset($_GET['dataI']) && isset($_GET['dataF'])) {
		$lista = '{"result":[' . json_encode(getPagarReceberTotal($conexao, $id, $dataI, $dataF)) . ']}';
	echo $lista;
	}else{
		$lista = '{"result":[' . json_encode(getPagarReceberTotal($conexao, $id, $hoje, $hoje)) . ']}';
		echo $lista;
	}
}

function getPagarReceberTotal($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select sum(pagar_receber.val_pago) as total, 
				   if (pagar_receber.canc = 'CA', 'CT', pagar_receber.canc) as canc
			from pagar_receber inner join aluno 
			on (pagar_receber.aluno_fornecedor=aluno.idaluno)
			where pagar_receber.academia={$id} 
				and pagar_receber.pago = 1 
				and pagar_receber.pagamento >= '{$dataI}' 
				and pagar_receber.pagamento <= '{$dataF}' 
			group by canc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'total' => $row['total'],
			'canc' => $row['canc'],
		));
	}
	//echo $sql;

	return $retorno;
}
