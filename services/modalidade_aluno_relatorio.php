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

	$sql = "select aluno.idaluno,
aluno.matricula,
aluno.nome,
date_format( from_days(to_days(now()) - to_days(aluno.data_nascimento)),'%Y')+0 as idade,
aluno.data_nascimento,
aluno.genero,
aluno.celular,
matricula.aluno,
matricula.modalidade,
matricula.plano_pagamento,
modalidade.id,
modalidade.nome as nomemod
from aluno inner join matricula inner join modalidade on
(aluno.idaluno = matricula.aluno and matricula.modalidade = modalidade.id)
where aluno.academia={$id} and aluno.data_nascimento is not null order by nome;
";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'idaluno' => $row['idaluno'],
			'matricula' => $row['matricula'],
			'nome' => $row['nome'],
			'idade' => $row['idade'],
			'data_nascimento' => $row['data_nascimento'],
			'genero' => $row['genero'],
			'celular' => $row['celular'],
			'aluno' => $row['aluno'],
			'modalidade' => $row['modalidade'],
			'planopag' => $row['plano_pagamento'],
			'id' => $row['id'],
			'nomemod' => $row['nomemod']));
	}

	return $retorno;
}

/*

 */