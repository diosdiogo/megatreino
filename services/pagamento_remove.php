
<?php

include 'conecta.php';
include 'verificaStatus.php';

$idPagamento = $_GET["idPagamento"];
$idacademia = $_GET["academia"];
$idaluno = $_GET["idaluno"];

//print("ID: " . $idPagamento . "<br>");

deletaMatricula($conexao, $idPagamento);
verifica($conexao, $idacademia, $idaluno);

function deletaMatricula($conexao, $idPagamento) {

	$query = "delete from pagar_receber where id = {$idPagamento}";

	return mysqli_query($conexao, $query);
}
