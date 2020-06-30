<?php
include 'conecta.php';

$idaluno = $_GET['idaluno'];
$academia = $_GET['academia'];
$treinoPadraoAtual = $_GET['treinoPadraoAtual'];
$idtreinoPadrao = $_GET['idtreinoPadrao'];
$prof = $_GET['prof'];

$nomeTreino = $_GET['nomeTreino'];
$quantidadeSemanas = $_GET['quantidadeSemanas'];
$quantidadeSessoes = $_GET['quantidadeSessoes'];
$velocidadeMovimento = $_GET['velocidadeMovimento'];
$avaliacao = $_GET['avaliacao'];
$tipoTreino = $_GET['tipoTreino'];
$nivel = $_GET['nivel'];
$dataI = $_GET['dataI'];

$treino = treino($conexao, $idaluno, $treinoPadraoAtual);

echo 'ID Aluno: ' . $idaluno . '<br>';
echo 'Treino Padrão Atual: ' . $treinoPadraoAtual . '<br>';
echo 'Treino Padrão: ' . $idtreinoPadrao . '<br>';
echo 'Professor: ' . $prof . '<br>';
echo 'Nome Treino: ' . $nomeTreino . '<br>';
echo 'Quantidade de Semana: ' . $quantidadeSemanas . '<br>';
echo 'Quantidade de sessões: ' . $quantidadeSessoes . '<br>';
echo 'Velocidade Movimento: ' . $velocidadeMovimento . '<br>';
echo 'Tipo Treino: ' . $tipoTreino . '<br>';
echo 'Descrição: ' . $avaliacao . '<br>';
echo 'Nivel: ' . $nivel . '<br>';
echo 'Data Inicial: ' . $dataI . '<br>';
echo "Treino Atual:  " . $treino['treinoAtual'] . "<br>";

if ($treinoPadraoAtual == 0) {
	//criarTreinoAtual($conexao, $dataI, $quantidadeSemanas, $quantidadeSessoes, $nomeTreino, $nivel, $velocidadeMovimento, $tipoTreino, $avaliacao, $idaluno, $academia);
}
if ($treino['treinoAtual'] == 'S') {
	//removerTreinoAtual($conexao, $treinoPadraoAtual);
	//criarTreinoAtual($conexao, $dataI, $quantidadeSemanas, $quantidadeSessoes, $nomeTreino, $nivel, $velocidadeMovimento, $tipoTreino, $avaliacao, $idaluno, $academia);
}
 treinoPadrao_Para_TreinoAluno($conexao, $idtreinoPadrao);
//mudarProfessor($conexao, $idaluno, $prof);

function treino($conexao, $idaluno, $idTreino) {
	$treinos = array();

	$resultado = mysqli_query($conexao, "select * from treinoPadraoAluno where aluno={$idaluno} and idtreinoPadraoAluno={$idTreino};");

	$treinos = mysqli_fetch_assoc($resultado);

	return $treinos;
}

function criarTreinoAtual($conexao, $dataI, $qtsemanas, $qtsessoes, $nomeTreino, $nivel, $velocidade, $tipoTreino, $AvR, $idaluno, $academia) {

	$query = "insert into treinoPadraoAluno
(dataInicio,
quantidadeSemanas,
quantidadeSessoes,
nomeTreino,
nivelTreino,
velocidade,
tipoTreino,
avaliacoes,
aluno,
academia,
treinoAtual)

value(
'{$dataI}',
{$qtsemanas},
{$qtsessoes},
'{$nomeTreino}',
'{$nivel}',
'{$velocidade}',
'{$tipoTreino}',
'{$AvR}',
{$idaluno},
{$academia},
'S');";

	$inserir = mysqli_query($conexao, $query);

	echo ("ID inserido = \n" . mysqli_insert_id($conexao));

	return $inserir;
}
function removerTreinoAtual($conexao, $idTreino) {

	$query = "update treinoPadraoAluno set treinoAtual= '' where idtreinoPadraoAluno={$idTreino};";

	//echo '<br>' . $query . '<br>';

	$alterar = mysqli_query($conexao, $query);

	echo ("ID alterado = \n" . mysqli_affected_rows($conexao)) . "\n";

	return $alterar;

}

function treinoPadrao_Para_TreinoAluno($conexao, $idTreino) {
	$categoria = array();

	$sql = "select * from treino where treinoPadrao=$idTreino";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'id'= $row[''];
			''= $row[''];
			''= $row[''];
			''= $row[''];
			''= $row[''];
		))};

	return mysqli_fetch_assoc($resultado);
}

function mudarProfessor($conexao, $idaluno, $prof) {
	$sql = "update aluno set professor=$prof where idaluno=$idaluno";

	$query = mysqli_query($conexao, $sql);

}

?>