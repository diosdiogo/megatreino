<?php 
include 'conecta.php';

if (isset($_GET['idIugu'])) {
 	$idaluno=$_GET['alunoIugu'];
 	$academia=$_GET['academia'];
 	$lista = idIugu($conexao,$idaluno,$academia);
 	echo $lista['idiugu'];
 }



function idIugu($conexao,$idaluno,$academia) {
	
	$query = "select idiugu from aluno where academia = {$academia} and idaluno={$idaluno}";
	$resultado = mysqli_query($conexao, $query);

		//echo "<br> SQL: ".$query;

	 return mysqli_fetch_assoc($resultado);
} ?>