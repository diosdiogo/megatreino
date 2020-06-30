<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
//$idaluno = $_GET["idaluno"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getEntrada($conexao, $id)) . ']}';
	echo $lista;
}

function getEntrada($conexao, $id) {
	$retorno = array();
	/*
	$sql = "select acesso.id, acesso.academia, DATEDIFF( now(), horario) as dias, acesso.status, acesso.horario, aluno.idaluno, aluno.nome, aluno.matricula, aluno.celular from acesso inner join aluno on acesso.aluno = aluno.idaluno where acesso.academia={$id}  ";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'academia' => $row['academia'],'dias' => $row['dias'], 'status' => utf8_encode($row['status']),'horario' => substr($row['horario'],8,2).'/'.substr($row['horario'],5,2).'/'.substr($row['horario'],0,4),  'matricula' => $row['matricula'], 'celular' => utf8_encode($row['celular']),  'nome' => utf8_encode($row['nome'])));
	}
	*/
	$sql = "select acesso.aluno, max(acesso.horario) as hora, DATEDIFF( now(), max(horario)) as dias, acesso.status, aluno.idaluno, aluno.nome, aluno.matricula, aluno.celular, aluno.ativo 
from acesso 
inner join aluno on acesso.aluno = aluno.idaluno
where acesso.academia={$id} and aluno.ativo='S' group by acesso.aluno order by hora desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('dias' => $row['dias'], 'status' => utf8_encode($row['status']),'horario' => substr($row['hora'],8,2).'/'.substr($row['hora'],5,2).'/'.substr($row['hora'],0,4).' - '.substr($row['hora'],11,5), 'matricula' => $row['matricula'], 'celular' => utf8_encode($row['celular']),  'nome' => utf8_encode($row['nome'])));
	}

	return $retorno;
}