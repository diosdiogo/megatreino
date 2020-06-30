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
		$lista = '{"result":[' . json_encode(getPagarReceber($conexao, $id, $dataI, $dataF)) . ']}';
	echo $lista;
	}else{
		$lista = '{"result":[' . json_encode(getPagarReceber($conexao, $id, $hoje, $hoje)) . ']}';
		echo $lista;
	}
}

function getPagarReceber($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select distinct pagar_receber.id,
							pagar_receber.documento,
							pagar_receber.aluno_fornecedor,
							aluno.matricula,
							aluno.idaluno,
							aluno.professor,
							aluno.nome,
							pagar_receber.colaborador,
							pagar_receber.val_pago,
							if (pagar_receber.canc = 'CA', 'CT', pagar_receber.canc) as canc,
							pagar_receber.historico,
							pagar_receber.pagamento,
							pagar_receber.pago,
							pagar_receber.academia,
							pagar_receber.ocorrencia,
							pagar_receber.deletado
			from pagar_receber inner join aluno on (pagar_receber.aluno_fornecedor=aluno.idaluno) 
			where pagar_receber.academia={$id} 
				and pagar_receber.pago = 1 
				and pagar_receber.pagamento >= '{$dataI}' 
				and pagar_receber.pagamento <= '{$dataF}' 
			order by date(pagamento) desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'id' => $row['id'],
			'documento' => $row['documento'],
			'alunof' => $row['aluno_fornecedor'],
			'matricula' => $row['matricula'],
			'idaluno' => $row['idaluno'],
			'professor' => $row['professor'],
			'nome' => utf8_encode($row['nome']),
			'colaborador' => $row['colaborador'],
			'valpago' => $row['val_pago'],
			'canc' => $row['canc'],
			'historico' => utf8_encode($row['historico']),
			'pagamento' => $row['pagamento'],
			'pago' => number_format($row['pago'], 2, ',', '.'),
			'academia' => $row['academia'],
			'ocorrecia' => $row['ocorrencia'],
			'deletado' => $row['deletado'],
		));
	}
	//echo $sql;

	return $retorno;
}
