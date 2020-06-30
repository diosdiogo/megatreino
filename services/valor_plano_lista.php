<?php

include 'conecta.php';

$idplano = $_GET["idplano"];
$plano = $_GET["plano"];

$lista = '{"result":[' . json_encode(getVavorPlano($conexao, $idplano, $plano)) . ']}';
echo $lista;

function getVavorPlano($conexao, $idplano, $plano) {
	$retorno = array();

	$sql = "select valor from academia.modalidade_plano where modalidade = {$idplano} and plano={$plano} and deletado = 'N'";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(

			'valor' => $row['valor'],
		));
	}

	return $retorno;
}

/*

id int(11) AI PK
academia int(11)
modalidade int(11)
valor decimal(10,2)
deletado char(1)
plano int(11)
 */