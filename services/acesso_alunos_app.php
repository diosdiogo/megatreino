<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getAlunos_APP($conexao, $id)) . ']}';
	echo $lista;
}

function getAlunos_APP($conexao, $id) {
	$retorno = array();

	$sql = "select aluno_conectado_app.id, aluno_conectado_app.aluno, aluno_conectado_app.academia, aluno.idaluno, aluno.nome, aluno.matricula, aluno.celular from aluno_conectado_app inner join aluno on (aluno_conectado_app.aluno = aluno.idaluno)  where aluno_conectado_app.academia={$id} and quantidade>=1";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'aluno' => $row['aluno'], 'academia' => $row['academia'], 'idaluno' => $row['idaluno'],  'nome' => utf8_encode($row['nome']), 'matricula' => utf8_encode($row['matricula']),'celular' => utf8_encode($row['celular']) ));
	}

	return $retorno;
}