<?php
include 'conecta.php';

//$idacademia = $_POST["idacademia"];
//s$banco = $_POST["banco"];

//$lista = '{"result":[' . json_encode(getDebito($conexao, $idacademia, $banco)) . ']}';
	//echo $lista;

function getDebito($conexao, $idacademia, $banco) {
	$retorno = array();

	$sql = "select sum(movimento_caixa_aberto.valor) as total from movimento_caixa_aberto inner join historico on movimento_caixa_aberto.historico = historico.idhistorico where movimento_caixa_aberto.academia={$idacademia} and dc='D' and banco={$banco}";

	$resultado = mysqli_query($conexao, $sql);
	$retorno = mysqli_fetch_assoc($resultado);
	/*while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('total' => $row['total']));
	}*/
	
	return $retorno;
}