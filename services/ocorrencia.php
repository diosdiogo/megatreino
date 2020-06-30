<?php

function ultimaOcorrencia($conexao) {

	$dados = array();

	$resultado = mysqli_query($conexao, "select max(id) +1 as id from ocorrencia");

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;
}

function ultimaDocumento($conexao) {

	$dados = array();

	$resultado = mysqli_query($conexao, "select max(id) +1 as id from pagar_receber");

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;
}
