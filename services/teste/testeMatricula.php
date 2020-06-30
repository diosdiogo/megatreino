<?php 

$array = array(
"id"=> "601678",
"modalidade"=> "1",
"modnome"=> "MUSCULACAO",
"plano"=> "ANUAL",
"vencimentoMatricula"=> "02",
"dataInicio"=> "03-03-2020",
"dataFim"=> "03-03-2021",
"valor"=> "680.00",
"desconto"=> "0",
"total"=> "680.00",
);

$matricula = '{"result":[' . json_encode($array) . ']}';

	echo $matricula;

 ?>