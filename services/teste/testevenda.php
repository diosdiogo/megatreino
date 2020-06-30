<?php

$array = array(
	"id" => "2110010761",
	"vendaNome" => "Venda de Produtos / ServiÃ§os",
	"dataInicio" => "18-03-2020",
	"dataFim" => "18-03-2020",
	"valor" => 500.49999999999994,
	"desconto" => "0.00",
	"total" => "500.50",

);

$matricula = '{"result":[' . json_encode($array) . ']}';

echo $matricula;

?>