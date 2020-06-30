<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$mod = $_GET["mod"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getModalidadeTurma($conexao, $id, $mod)) . ']}';
	echo $lista;
}

function getModalidadeTurma($conexao, $id, $mod) {
	$retorno = array();

	$sql = "select modalidade_turma.id, modalidade_turma.academia, modalidade_turma.modalidade, modalidade_turma.turma, turma.nome, turma.qtd_alunos, turma.livre from modalidade_turma inner join turma on (modalidade_turma.turma=turma.id) where modalidade_turma.academia={$id} and modalidade_turma.modalidade={$mod} and modalidade_turma.deletado='N' order by turma.nome";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'id' => $row['id'],
			'academia' => $row['academia'],
			'modalidade' => $row['modalidade'],
			'turma' => $row['turma'],
			'nome' => $row['nome'],
			'qtd_alunos' => $row['qtd_alunos'],
			'livre' => $row['livre'],

		));
	}

	return $retorno;
}