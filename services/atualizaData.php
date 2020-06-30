<?php

include 'conecta.php';

if (isset($_GET["plano"])) {
	$plano = $_GET["plano"];
}

if (isset($_GET["modid"])) {
	$modid = $_GET["modid"];
}

if (isset($_GET["academia"])) {
	$academia = $_GET["academia"];
}
if (isset($_GET["dataInicio"])) {
	$atualizaData = $_GET["dataInicio"];
}

if (isset($_GET["plano"]) && isset($_GET["modid"])) {

	$dados = renovadata($conexao, $modid, $plano, $academia);
	//echo $dados['mes_dia'];

} else {
	$dados = 0;

}

/*echo $plano . '</br>';
echo $quantidade . '</br>';
echo $modid . '</br>';
echo $academia . '</br>';

echo $dados['quantidade'] . '</br>';
echo $dados['mes_dia'] . '</br>';*/

if ($dados['mes_dia'] == "M") {

	$atualizaData = date('d/m/Y', strtotime('+' . $dados['quantidade'] . 'months', strtotime($atualizaData)));

} else {
	$atualizaData = date('d/m/Y', strtotime('+' . $dados['quantidade'] . 'day', strtotime($atualizaData)));

}

//$return = "<input type='text' id='dataFim' class='form-control' value='" . $atualizaData . "' ng-model='dataF'  disabled='disabled'>";

$return = '{"result":[' . json_encode($atualizaData) . ']}';

echo $return;

function renovadata($conexao, $modid, $plano, $academia) {

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

?>