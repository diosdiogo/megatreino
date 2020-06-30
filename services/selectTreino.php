<?php

include 'conecta.php';

$id = $_GET["id"];
$academia = $_GET['academia'];

$lista = '{"result":[' . json_encode(getSetarExercicio($conexao, $id, $academia)) . ']}';
echo $lista;

function getSetarExercicio($conexao, $id, $academia) {
	$retorno = array();

	$sql = "select * from exercicio where academia={$academia} and deletado='N' and idexercicio={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['idexercicio'], 'nome' => utf8_encode($row['nome']), 'grupoMuscular' => utf8_encode($row['grupoMuscular']), 'padrao' => $row['padrao'], 'ordem' => $row['ordem'], 'caminhoVideo' => utf8_encode($row['caminhoVideo'])));
	}

	return $retorno;
}