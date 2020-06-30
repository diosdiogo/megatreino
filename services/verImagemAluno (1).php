<?php

include 'conecta.php';

$id = $_GET["id"];

$lista = getTotal_Agenda_mes($conexao, $id);
echo $lista['total'];

function getTotal_Agenda_mes($conexao, $id) {
	$retorno = array();

	$sql = "select count(*) as total from agenda where academia = $id and data_inicial >= '2020-04-01' and data_final <= '2020-04-30' order by hora_inicial;";

	$resultado = mysqli_query($conexao, $sql);

	return mysqli_fetch_assoc($resultado);
}
