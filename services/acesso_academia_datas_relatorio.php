<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = date('Y-m-d', strtotime('+ 1 days', strtotime($_GET['dataF'])));


if ($state == "C") {
	$lista = '{"result":[' . json_encode(getEntrada($conexao, $id, $dataI, $dataF)) . ']}';
	echo $lista;
}

function getEntrada($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select acesso.id, 
			acesso.academia, 
			acesso.status, 
			acesso.horario, 
			aluno.idaluno, 
			aluno.nome, 
			aluno.matricula, 
			aluno.celular 
		from acesso inner join aluno 
		on acesso.aluno = aluno.idaluno 
		where acesso.academia={$id}
			and acesso.horario >= cast('{$dataI}' as date) 
			and acesso.horario <= cast('{$dataF}' as date)
			order by date(acesso.horario) desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idaluno' => $row['id'], 
								   'nome' => utf8_encode($row['nome']), 
								   'horario' => $row['horario'], 
								   'idaluno' => $row['idaluno'],  
								   'celular' => $row['celular'], 
								   'matricula' => $row['matricula'],  
								   'status' => utf8_encode($row['status'])));
	}

	return $retorno;
}