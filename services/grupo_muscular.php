<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getGrupo($conexao, $id)) . ']}';
	echo $lista;
}

function getGrupo($conexao, $id) {
	$retorno = array();

	$sql = "select * from grupo_muscular where academia={$id} and deletado='N' order by descricao";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'descricao' => $row['descricao']));
	}

	return $retorno;
}