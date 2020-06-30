<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getAlunos($conexao, $id)) . ']}';
	echo $lista;
}

function getAlunos($conexao, $id) {
	$retorno = array();

	$sql = "SELECT al.idaluno,
al.matricula,
al.nome,
al.ativo,
al.academia,
al.aluno_colaborador,
al.professor,
al.celular,
al.status, 
tr.idtreinoPadraoAluno,
tr.quantidadeSemanas,
tr.dataInicio FROM aluno AS al  
inner JOIN treinoPadraoAluno AS tr on 
al.idaluno = tr.aluno and tr.treinoAtual = 'S'  WHERE al.ativo = 'S' AND al.academia = {$id} AND al.aluno_colaborador = 0 ORDER BY al.nome ASC";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'matricula' => $row['matricula'],
			'celular' => utf8_encode($row['celular']),
			'dataInicio' => $row['dataInicio'],
			'ativo' => utf8_encode($row['ativo']),
			'academia' => $row['academia'],
			'professor' => utf8_encode($row['professor']),
			'status' => utf8_encode($row['status']),
			'aluno_colaborador' => $row['aluno_colaborador'],
			'idtreinoPadraoAluno' => $row['idtreinoPadraoAluno'],
			'quantidadeSemanas'=> $row['quantidadeSemanas'],

		));
	}

	return $retorno;
}

/*
idaluno
matricula
nome
ativo
academia
aluno_colaborador
professor
celular
status
idtreinoPadraoAluno
quantidadeSemanas
dataInicio

*/