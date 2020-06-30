<?php


$data = $_GET["data"];
$dias = $_GET["dias"];


$lista = '{"result":[[' . json_encode(calculaData($data,$dias)) . ']]}';
echo $lista;

function calculaData($data,$dias){
	$retorno = array();
	$nova_data = date('Y-m-d', strtotime('+'.$dias.' days', strtotime($data)));
	return $nova_data;
}
