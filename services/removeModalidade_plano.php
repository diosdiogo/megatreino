<?php 

include 'conecta.php';

$id = $_GET["id"]; 
$state = $_GET["state"]; 

if ($state=="C"){
	$sql = "update modalidade_plano set modalidade_plano.deletado='S' where modalidade_plano.id={$id}";
	mysqli_query($conexao,$sql);

  //$lista='{"result":['.json_encode(getCategorias($conexao,$id)).']}';
  //echo $lista;
}


