<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$idaluno = $_GET["idaluno"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getModalidadeTurma($conexao, $id, $idaluno)) . ']}';
	echo $lista;
}

function getModalidadeTurma($conexao, $id, $idaluno) {
	$retorno = array();

	$sql = "select turma.id,
					turma.academia,
					turma.nome,
					turma.qtd_alunos,
					turma.hora_inicio,
					turma.hora_fim,
					turma.segunda,
					turma.terca,
					turma.quarta,
					turma.quinta,
					turma.sexta,
					turma.sabado,
					turma.domingo,
					modalidade_turma.id as turmaid,
					modalidade_turma.turma,
					modalidade_turma.modalidade,
					aluno_turma.id as idmodturma,
					aluno_turma.modalidade_turma as modturma,
					aluno_turma.aluno,
					aluno_turma.matricula
					from academia.turma inner join academia.modalidade_turma inner join aluno_turma on
					(turma.id = modalidade_turma.turma and modalidade_turma.id = aluno_turma.modalidade_turma)
					where turma.academia={$id} and aluno_turma.aluno = {$idaluno}";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'id' => $row['id'],
			'nome' => utf8_encode($row['nome']),
			'qtd_alunos' => $row['qtd_alunos'],
			'hora_inicio' => $row['hora_inicio'],
			'hora_fim' => $row['hora_fim'],
			'segunda' => $row['segunda'],
			'terca' => $row['terca'],
			'quarta' => $row['quarta'],
			'quinta' => $row['quinta'],
			'sexta' => $row['sexta'],
			'sabado' => $row['sabado'],
			'domingo' => $row['domingo'],
			'turmaid' => $row['turmaid'],
			'turma' => $row['turma'],
			'modalidade' => $row['modalidade'],
			'idmodturma' => $row['idmodturma'],
			'modturma' => $row['modturma'],
			'aluno' => $row['aluno'],
			'matricula' => $row['matricula'],

		));
	}

	return $retorno;
}

/*

utf8_encode(
 */