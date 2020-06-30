<?php 

include 'conecta.php';
include 'alteraAlunoCodAtualiza.php';
$cod_atualiza = rand(10000, 9999999);

	if (isset($_GET['copiarTreino'])) {
		$aluno=$_GET['aluno'];
		$academia=$_GET['academia'];
		$idtreinoCopia=$_GET['idtreinoCopia'];
		$alunoCopia=$_GET['alunoCopia'];

		echo "Aluno: ".$aluno."<br>";
		echo "Academia: ".$academia."<br>";
		echo "Treino Copia: ".$idtreinoCopia."<br>";
		echo "Aluno Cpia: ".$alunoCopia."<br>";


		removerTreinoPadrao($conexao,$aluno,$academia);
		$treinoCopia= treinoPadraoCopia($conexao,$alunoCopia,$idtreinoCopia);

		copiarTreinoPadraoForALuno($conexao,$treinoCopia['quantidadeSemanas'],$treinoCopia['quantidadeSessoes'],$treinoCopia['nomeTreino'],$treinoCopia['nivelTreino'],$treinoCopia['velocidade'],$treinoCopia['tipoTreino'],$aluno,$academia,$idtreinoCopia);

		
		alteraAlunoCodAtualiza($conexao, $aluno, $cod_atualiza);


	}

	function removerTreinoPadrao($conexao,$aluno,$academia){
		$sql = "update treinoPadraoAluno set treinoAtual='' where aluno=$aluno and academia=$academia and idtreinoPadraoAluno <> 0";
		$query= mysqli_query($conexao,$sql);
	}

	
	function treinoPadraoCopia($conexao,$alunoCopia,$idtreinoCopia){
		$treino=array();

		$sql ="select * from treinoPadraoAluno where aluno = $alunoCopia and idtreinoPadraoAluno=$idtreinoCopia";
		$query= mysqli_query($conexao,$sql);

		//echo "SQL: ". $sql;

		return mysqli_fetch_assoc($query);

	}

	function copiarTreinoPadraoForALuno($conexao,$quantidadeSemanas,$quantidadeSessoes,$nomeTreino,$nivelTreino,$velocidade,$tipoTreino,$aluno,$academia,$idtreinoCopia){

		$sql = "insert into treinoPadraoAluno 
(dataInicio,quantidadeSemanas,quantidadeSessoes,nomeTreino,nivelTreino,velocidade,tipoTreino,aluno,academia,treinoAtual)
value(now(),
$quantidadeSemanas,
$quantidadeSessoes,
'$nomeTreino',
'$nivelTreino',
'$velocidade',
'$tipoTreino',
$aluno,
$academia,
'S'
)";
	
		$query= mysqli_query($conexao,$sql);

		$insereDB = mysqli_insert_id($conexao);

		if ($insereDB <= 0) {
			echo "Erro<br>";
		}else{
			//echo $insereDB;
			treinoAlunoCopia($conexao,$idtreinoCopia,$insereDB,$aluno,$academia);
			
		}

		//echo $sql;
}

function treinoAlunoCopia($conexao,$idtreinoCopia,$treinoPadraoAluno,$aluno,$academia){
	$treinoAluno = array();
	$sql = "select * from treinoAluno where treinoPadraoAluno= $idtreinoCopia;";
	$query= mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($treinoAluno, array(
			'idtreinoAluno' => $row['idtreinoAluno'],
			'tipo' => $row['tipo'],
			'nome' => $row['nome'],
			'ordem' => $row['ordem'],
			'treinoPadraoAluno' => $row['treinoPadraoAluno'],
			
			//print_r("nome: ".$row['nome']."<br>")
			copiarTreinoForAluno($conexao,$row['tipo'],$row['nome'],$row['ordem'],$treinoPadraoAluno,$aluno,$academia,$row['idtreinoAluno'])
		));
	}

}

function copiarTreinoForAluno($conexao,$tipo,$nome,$ordem,$treinoPadraoAluno,$aluno,$academia,$treinoAlunoCopia){

	$sql="insert into treinoAluno(tipo,nome,ordem,treinoPadraoAluno,aluno,academia,concluido)
VALUE('$tipo','$nome',$ordem,$treinoPadraoAluno,$aluno,$academia,'N')";
	
	$query= mysqli_query($conexao,$sql);

	$insereDB = mysqli_insert_id($conexao);

	if ($insereDB <= 0) {
			echo "Erro";
	}else{
			//echo $insereDB;
		exercicioAlunoCopia($conexao,$treinoAlunoCopia,$insereDB,$aluno,$academia);
		
			
	}

}

function exercicioAlunoCopia($conexao,$treinoAlunoCopia,$treinoAluno,$aluno,$academia){
	$treino = array();
	$sql = "select * from exercicioTreinoAluno where  treinoAluno= $treinoAlunoCopia;";
	$query= mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($treino, array(
			'idexercicioTreinoAluno' => $row['idexercicioTreinoAluno'],
			'exercicio' => $row['exercicio'],
			'series' => $row['series'],
			'repeticoes' => $row['repeticoes'],
			'tipo_repeticoes' => $row['tipo_repeticoes'],
			'carga' => $row['carga'],
			'descanso' => $row['descanso'],
			'zona_alvo' => $row['zona_alvo'],
			'grupo'=>$row['grupo'],
			'ordem'=>$row['ordem'],
			
			//print_r("nome: ".$row['exercicio']."<br>")
		copiarexercicioForAluno($conexao,$treinoAluno,$aluno,$academia,$row['exercicio'],$row['series'],$row['repeticoes'],$row['tipo_repeticoes'],$row['carga'],$row['descanso'],$row['zona_alvo'],$row['grupo'],$row['ordem'],$row['idexercicioTreinoAluno'])
			
		));
	}

}

function copiarexercicioForAluno($conexao,$treinoAluno,$aluno,$academia,$exercicio,$series,$repeticoes,$tipo_repeticoes,$carga,$descanso,$zona_alvo,$grupo,$ordem,$idexercicioTreinoAluno){

	$sql="insert into exercicioTreinoAluno
(treinoAluno,aluno,academia,exercicio,series,repeticoes,tipo_repeticoes,carga,descanso,zona_alvo,concluido,grupo,ordem)value
($treinoAluno,$aluno,$academia,$exercicio,$series,'$repeticoes','$tipo_repeticoes',$carga,'$descanso','$zona_alvo','N','$grupo',$ordem)";
	
	$query= mysqli_query($conexao,$sql);

	$insereDB = mysqli_insert_id($conexao);
	//echo $sql."<br>";
	if ($insereDB <= 0) {
			echo "Erro";
	}else{
		/*
		echo "<pre>";
		echo $insereDB;
		echo "</pre>";
		*/

		serieItemAluno($conexao,$idexercicioTreinoAluno,$insereDB,$aluno,$academia)	;
	}

}

function serieItemAluno($conexao,$idexercicioTreinoAluno,$idExercicioNovo,$aluno,$academia){
	$serieAluno=array();

	$sql ="select * from serieItemAluno where exercicioTreinoAluno=$idexercicioTreinoAluno";

	$query= mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($serieAluno, array(
			'idserieItem'=>$row['idserieItem'],
			'exercicioTreinoAluno'=>$row['exercicioTreinoAluno'],
			'series'=>$row['series'],
			'carga'=>$row['carga'],
			'ordem'=>$row['ordem'],

			//print_r("nome: ".$row['idserieItem']."<br>")
			copiarSerieItemForAluno($conexao,$idExercicioNovo,$row['series'],$row['carga'],$row['ordem'],$aluno,$academia)
			
		));

	}
}

function copiarSerieItemForAluno($conexao,$idExercicioNovo,$series,$carga,$ordem,$aluno,$academia){
	$sql="insert into serieItemAluno 
(exercicioTreinoAluno,aluno,academia,series,carga,concluido,ordem)value
($idExercicioNovo,$aluno,$academia,$series,'$carga','N',$ordem)";
	$query= mysqli_query($conexao,$sql);

	$insereDB = mysqli_insert_id($conexao);

	if ($insereDB <= 0) {
			echo "Erro";
	}else{
		
		echo "<pre>";
		echo $insereDB;
		echo "</pre>";
		echo $sql;
		

	
	}
}



 ?>