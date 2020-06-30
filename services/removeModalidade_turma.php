<?php 

include 'conecta.php';

$id = $_GET["id"]; 
$state = $_GET["state"]; 

if ($state=="C"){
	$sql = "update modalidade_turma set modalidade_turma.deletado='S' where modalidade_turma.id={$id}";
	mysqli_query($conexao,$sql);

  //$lista='{"result":['.json_encode(getCategorias($conexao,$id)).']}';
  //echo $lista;
}


