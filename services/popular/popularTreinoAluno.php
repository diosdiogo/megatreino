<?php 
include '../../conecta.php';

//$academia=$_GET['academia'];

treino($conexao);

function treino($conexao){

	$array = array();

	$sql = "select * from treinoPadraoAluno";

	$query = mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($array, array('idtreinoPadraoAluno' => $row['idtreinoPadraoAluno'],
								'aluno' => $row['aluno'],
								'academia'=>$row['academia']));
		//echo $row['academia'];
		alteraTreinoAluno($conexao,$row['aluno'],$row['academia'],$row['idtreinoPadraoAluno']);
	}
	return $array;
}

function alteraTreinoAluno($conexao,$aluno,$academia,$idtreinoPadraoAluno){
	//$sql = "update treinoAluno set aluno = $aluno, academia=$academia where treinoPadraoAluno=$idtreinoPadraoAluno and aluno is null and idtreinoAluno <>0;";

	$sql = "update treinoAluno set aluno = $aluno, academia=$academia where treinoPadraoAluno=$idtreinoPadraoAluno and idtreinoAluno <>0";


	$altera= mysqli_query($conexao,$sql);
	echo ("<br>Linha alterada = " . mysqli_affected_rows($conexao))."<br>sql: ". $sql."<br>";
}

 ?>