<?php

include 'conecta.php';



	function getIDaluno($conexao) {
	$retorno = array();

	$sql = "select idaluno from aluno order by idaluno desc limit 1";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idaluno' => $row['idaluno']));
	}

	return $retorno;
}