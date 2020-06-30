<?php
include 'conecta.php';

if (isset($_GET['plano']) && isset($_GET['modalidade']) && isset($_GET['academia'])) {

	$plano = $_GET['plano'];
	$modalidade = $_GET['modalidade'];
	$idacademia = $_GET['academia'];
	if ($desconto = $_GET['desconto'] == 'undefined') {
		$desconto = 0;
	} else {
		$desconto = $_GET['desconto'];
	}
	$dataP = date('d-m-Y', strtotime($_GET["data"]));
	$vezes = $_GET["vezes"];
	$vencimento = $_GET["datavenc"];

	//$dados = modalidadePlano($conexao, $modalidade, $plano, $idacademia);
	$valor=$_GET['valor'];

	$dados = str_replace('R','',$valor);
	$dados = str_replace('$','',$dados);
	$dados = str_replace(',','',$dados);
	
	$dia = $vencimento;
	$mes = substr($dataP, 2);

	$novaData = date('Y-m-d', strtotime($vencimento . $mes));

	//$parcelaValor = $dados['valor'] / $vezes;
	$parcelaValor = $dados / $vezes;

	$parcelas = '{"result":[' . json_encode(parcelas($dados, $vezes, $parcelaValor, $novaData)) . ']}';

	echo $parcelas;

}
function modalidadePlano($conexao, $modid, $plano, $academia) {

	$dados = array();

	$sql = "select modalidade_plano.id, modalidade_plano.academia, modalidade_plano.modalidade,
modalidade_plano.valor, modalidade_plano.plano, plano.descricao, plano.mes_dia,
plano.quantidade from modalidade_plano inner join plano
on (modalidade_plano.plano=plano.id) where (modalidade_plano.academia=1 or
modalidade_plano.academia={$academia}) and
modalidade_plano.modalidade={$modid} and plano.descricao='{$plano}'
and modalidade_plano.deletado='N'";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}
function parcelas($dados, $vezes, $parcelaValor, $novaData) {
	$dados = array();

	for ($i = 1; $i <= $vezes; $i++) {
		array_push($dados, array(
			'id'=>$i-1,
			'vezes' => $row = $i . '/' . $vezes,
			'vencimento' => $row = date('Y-m-d', strtotime("+" . $i . " month", strtotime($novaData))),
			'parcela' => $row = number_format((float) $parcelaValor, 2, ".", ","),

		));

	}

	return $dados;
}
