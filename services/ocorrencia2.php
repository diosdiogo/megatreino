<?php
include 'conecta.php';




$lista = '{"result":[' . json_encode(getDocumento($conexao)) . ']}';
	echo $lista;

function getDocumento($conexao) {
	$retorno = array();

	$sql = "select max(ocorrencia) + 1 as ocorrencia from movimento_caixa_aberto";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push ($retorno, array('ocorrencia' => $row['ocorrencia']));
	}

	return $retorno;
}