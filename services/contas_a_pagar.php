<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getPagar($conexao, $id)) . ']}';
	echo $lista;
}

function getPagar($conexao, $id) {
	$retorno = array();

	$sql = "select * from pagar_receber where academia={$id} and deletado='N' and pagar_receber= 'P'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'documento' => $row['documento'], 
			'pagar_receber' => utf8_encode($row['pagar_receber']), 
			'parc' => utf8_encode($row['parc']), 
			'aluno_fornecedor' => $row['aluno_fornecedor'], 
			'colaborador' => $row['colaborador'], 
			'emissao' => $row['emissao'], 
			'vencimento' => $row['vencimento'], 
			'liberadoate' => $row['liberadoate'], 
			'valor' => $row['valor'], 
			'canc' => utf8_encode($row['canc']), 
			'tipodoc' => $row['tipodoc'], 
			'bloq' => utf8_encode($row['bloq']), 
			'historico' => utf8_encode($row['historico']), 
			'obs' => utf8_encode($row['obs']), 
			'sel' => utf8_encode($row['sel']), 
			'bloq_banco' => $row['bloq_banco'], 
			'val_origem' => $row['val_origem'], 
			'val_pago' => $row['val_pago'], 
			'desconto' => $row['desconto'], 
			'pagamento' => $row['pagamento'], 
			'pago' => $row['pago'], 
			'academia' => $row['academia'], 
			'ocorrencia' => $row['ocorrencia'], 
			'historico_model' => $row['historico_model'], 
			'venda' => $row['venda'], 
			'idassinatura_iugu' => utf8_encode($row['idassinatura_iugu'])));
	}

	return $retorno;
}