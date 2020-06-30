<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getRelatorioAluno($conexao, $id)) . ']}';
	echo $lista;
}

function getRelatorioAluno($conexao, $id) {
	$retorno = array();

	$sql = "select count(aluno.idaluno) as soma 
from aluno inner join matricula inner join modalidade on
(aluno.idaluno = matricula.aluno and matricula.modalidade = modalidade.id)
where aluno.academia={$id} and aluno.data_nascimento ;
";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'soma' => $row['soma']
			));
	}

	return $retorno;
}

/*

 */