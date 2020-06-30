<?php 
include '../../conecta.php';

$academia=$_GET['academia'];

aluno($conexao,$academia);

function aluno($conexao,$academia){

	$array = array();

	$sql = "select * from aluno where academia=$academia;";

	$query = mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($array, array('idaluno' => $row['idaluno'],
								'academia' => $row['academia']));
		//echo $row['academia'];
		alteraTreinoPadrao($conexao,$row['idaluno'],$row['academia']);
	}
	return $array;
}

function alteraTreinoPadrao($conexao,$aluno,$academia){
	$sql = "update treinoPadraoAluno set academia = $academia where aluno=$aluno and academia is null;";

	$altera= mysqli_query($conexao,$sql);
	echo ("<br>Linha alterada = " . mysqli_affected_rows($conexao))."<br>sql: ". $sql."<br>";
}

 ?>