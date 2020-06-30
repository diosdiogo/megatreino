<?php
include 'conecta.php';

$cod_atualiza = rand(10000, 9999999);

if (isset($_GET["aluno"]) && isset($_GET["idacademia"])) {

	$idaluno = $_GET["aluno"];
	$idacademia = $_GET["idacademia"];

	verifica($conexao, $idacademia, $idaluno, $cod_atualiza);

}

function verifica($conexao, $idacademia, $idaluno, $cod_atualiza) {
	$pagamento = verificaPagamento($conexao, $idacademia, $idaluno);
	$matricula = verificaMatricula($conexao, $idacademia, $idaluno);
	$existeMatricula = verificaExisteMatricula($conexao, $idacademia, $idaluno);

	if ($existeMatricula == 0 or $pagamento != 0 or $matricula['data'] == null) {
		$status = "Bloqueado";
		alteraAlunoStatus($conexao, $status, $idaluno, $cod_atualiza);

	} else {
		$status = "Liberado";
		alteraAlunoStatus($conexao, $status, $idaluno, $cod_atualiza);
	}
}

function verificaExisteMatricula($conexao, $idacademia, $idaluno) {

	$sql = "select * from matricula where academia ={$idacademia} and aluno={$idaluno};";

	$inserir = mysqli_query($conexao, $sql);

	//echo ("Linhas retornadas = \n" . mysqli_num_rows($inserir) . "</br>");
	//echo $sql . "</br>";

	return mysqli_num_rows($inserir);
}

function verificaPagamento($conexao, $idacademia, $idaluno) {

	$sql = "select * from pagar_receber where academia={$idacademia} and aluno_fornecedor={$idaluno} and pago=0 and liberadoate < now();";

	$inserir = mysqli_query($conexao, $sql);

	//echo ("Linhas retornadas = \n" . mysqli_num_rows($inserir) . "</br>");
	//echo $sql . "</br>";

	return mysqli_num_rows($inserir);
}

function verificaMatricula($conexao, $idacademia, $idaluno) {

	$dados = array();

	$sql = "select max(data_fim) as data from matricula where academia= {$idacademia} and aluno={$idaluno} and data_fim > now();";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}

function alteraAlunoStatus($conexao, $status, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='{$status}', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";

	//echo "query uptade aluno: " . $query . "<br>";

	return mysqli_query($conexao, $query);
}
?>