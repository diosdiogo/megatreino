<?php
include 'conecta.php';




$lista = '{"result":[' . json_encode(getDocumento($conexao)) . ']}';
	echo $lista;

function getDocumento($conexao) {
	$retorno = array();

	$sql = "select max(documento) + 1 as documento from movimento_caixa_aberto";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push ($retorno, array('documento' => $row['documento']));
	}

	return $retorno;
}
	

