<?php 
	
	
	$valor1=str_replace('R','',$_GET['valor1']);

	$valor1=str_replace('$','',$valor1);
	$valor1=str_replace(',','',$valor1);

	$plano=$_GET['plano'];
	$valor2 =$plano-$valor1;
	if ($valor2 < 0) {
		$valor2=0.00;
	}

	echo $valor2;

 ?>