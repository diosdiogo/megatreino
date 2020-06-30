<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

if ($state == "C") {

	/*if ($dataI == 0 && $dataF == 0) {

	$lista = '{"result":[' . json_encode(getMatricula($conexao, $id)) . ']}';
	echo $lista;
	
	} else {*/

	$lista = '{"result":[' . json_encode(getMatriculaData($conexao, $id, $dataI, $dataF)) . ']}';
	echo $lista;
	
	}
	


/*function getMatricula($conexao, $id) {
	$retorno = array();

	$sql = "select count( matricula.id) as soma 
from matricula inner join aluno on (matricula.aluno  = aluno.idaluno) where matricula.academia={$id} ";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'soma' => $row['soma']));
	}

	return $retorno;
}*/

function getMatriculaData($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select count( matricula.id) as soma 
from matricula inner join aluno on (matricula.aluno  = aluno.idaluno) where matricula.academia={$id} and  data_inicio >= cast('{$dataI}' as date) and data_inicio <= cast('{$dataF}' as date) order by date(data_inicio) desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'soma' => $row['soma']));
	}

	return $retorno;
}