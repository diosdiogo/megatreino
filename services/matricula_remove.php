
<?php

include 'conecta.php';
include 'verificaStatus.php';

$idRemove = $_GET["idRemove"];
$idacademia = $_GET["academia"];
$idaluno = $_GET["idaluno"];

//print("ID: " . $idRemove . "<br>");

deletaMatricula($conexao, $idRemove);
verifica($conexao, $idacademia, $idaluno);

function deletaMatricula($conexao, $idRemove) {

	$query = "delete from matricula where id = {$idRemove}";

	return mysqli_query($conexao, $query);
}
