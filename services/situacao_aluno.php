<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];
$status = $_GET['status'];
$situacao = $_GET['situacao'];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getAlunos($conexao, $id, $status, $situacao)) . ']}';
	echo $lista;
}

function getAlunos($conexao, $id, $status, $situacao) {

	$retorno = array();

	if ($status <> "T" and $situacao <> "T") {

	$sql = "select * 
			  from aluno 
			  where academia={$id} 
			  and status='{$status}' 
			  and ativo='{$situacao}'
			  and aluno_colaborador = 0";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'matricula' => $row['matricula'],
			'nome' => utf8_encode($row['nome']),
			'status' => $row['status'],
			'genero' => $row['genero'], 
			'celular' => $row['celular'], 
			'ativo' => $row['ativo'],
		));
		}
	}

	elseif ($status == "T" and $situacao <> "T") {

	$sql = "select * from aluno where academia={$id} and ativo='{$situacao}' and aluno_colaborador = 0";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'matricula' => $row['matricula'],
			'nome' => utf8_encode($row['nome']),
			'status' => $row['status'],
			'genero' => $row['genero'], 
			'celular' => $row['celular'], 
			'ativo' => $row['ativo'],
		));
		}
	}


	elseif ($status <> "T" and $situacao == "T") {

	$sql = "select * from aluno where academia={$id} and status='{$status}' and aluno_colaborador = 0";;

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'matricula' => $row['matricula'],
			'nome' => utf8_encode($row['nome']),
			'status' => $row['status'],
			'genero' => $row['genero'], 
			'celular' => $row['celular'], 
			'ativo' => $row['ativo'],
		));
		}
	}

	else {

	$sql = "select * from aluno where academia={$id} and aluno_colaborador = 0";;

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'matricula' => $row['matricula'],
			'nome' => utf8_encode($row['nome']),
			'status' => $row['status'],
			'genero' => $row['genero'], 
			'celular' => $row['celular'], 
			'ativo' => $row['ativo'],
		));
		}
	
	}
	return $retorno;
}
