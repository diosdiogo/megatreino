<?php

include 'conecta.php';

function buscaCaixa($conexao, $id) {
	$dados = array();
	$resultado = mysqli_query($conexao, "select * from movimento_caixa_fechado where id={$id}");
	$dados = mysqli_fetch_assoc($resultado);
	return $dados;
}