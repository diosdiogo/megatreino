<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getEquipe($conexao, $id)) . ']}';
	echo $lista;
}

function getEquipe($conexao, $id) {
	$retorno = array();

	$sql = "select * from academia.colaborador where academia={$id};";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idcolaborador' => $row['idcolaborador'],
			'nome' => utf8_encode($row['nome']),
			'email' => utf8_encode($row['email']),
			'perfil' => $row['perfil'],
			'academia' => $row['academia'],
			'senha' => utf8_encode($row['senha']),
			'nutricionista' => utf8_encode($row['nutricionista']),
			'ativo' => $row['ativo'],
			'aluno' => $row['aluno'],
			'cod_atualiza' => $row['cod_atualiza'],
			'codinterno' => utf8_encode($row['codinterno'])));
	}

	return $retorno;
}