<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getCategorias($conexao, $id)) . ']}';
	echo $lista;
}

function getCategorias($conexao, $id) {
	$retorno = array();

	$sql = "select * from exercicio where academia={$id} and deletado='N' order by nome";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['idexercicio'], 'nome' => utf8_encode($row['nome']), 'grupoMuscular' => utf8_encode($row['grupoMuscular']), 'video' => utf8_encode($row['video']), 'padrao' => $row['padrao'], 'ordem' => $row['ordem'], 'caminhoVideo' => utf8_encode($row['caminhoVideo'])));
	}

	return $retorno;
}