<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getColaborador($conexao, $id)) . ']}';
	echo $lista;
}

function getColaborador($conexao, $id) {
	$retorno = array();

	$sql = "select * from colaborador where academia={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idcolaborador' => $row['idcolaborador'],
			'nome' => utf8_encode($row['nome']),
			'email' => $row['email'],
			'perfil' => $row['perfil'],
			'academia' => $row['academia'],
			'senha' => $row['senha'],
			'nutricionista' => $row['nutricionista'],
			'ativo' => $row['ativo'],
			'aluno' => $row['aluno'],
			'cod_atualiza' => $row['cod_atualiza'],
			'codinterno' => $row['codinterno']
			));
	}

	return $retorno;
}