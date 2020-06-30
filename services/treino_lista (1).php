<?php
include 'conecta.php';
//include 'alteraAlunoCodAtualiza.php';
//include 'editarExercicio.php';


$cod_atualiza = rand(10000, 9999999);

if (isset($_GET['idaluno'])) {	
	$idaluno = $_GET['idaluno'];
	if (isset($_GET['exerciciosTreino'])) {
		$lista = '{"result":[' . json_encode(todosTreinosAluno($conexao, $idaluno)) . ']}';
		echo $lista;
	}
	
}

if (isset($_GET['treinoPadrao'])) {
	$idaluno=$_GET['aluno'];
	$lista = '{"result":[' . json_encode(treinoPadraoAluno($conexao, $idaluno)) . ']}';
		echo $lista;
}

if (isset($_GET['categoria'])) {
		$idacademia = $_GET['idacademia'];
		$lista = '{"result":[' . json_encode(categoria($conexao, $idacademia)) . ']}';
		echo $lista;
}
if (isset($_GET['treinoNovo'])) {
		$treino = $_GET['idTreinoNovo'];
		$idaluno = $_GET['idaluno'];
		$lista = '{"result":[' . json_encode(treinoNovo($conexao, $treino)) . ']}';
		echo $lista;
}

 if (isset($_GET['ultimoTreino'])) {
 	$idaluno = $_GET['idaluno'];
 	$lista = ultimo_idtreinoPadraoAluno($conexao,$idaluno);
 	echo $lista['idtreinoPadraoAluno'];
 }

 if (isset($_GET['removerTreino'])) {
 	include 'editarExercicio.php';
 	$idtreinoAluno=$_GET['idtreinoAluno'];

 	$treino = exercicioDoAluno($conexao, $idtreinoAluno);

 	foreach ($treino as $treino) {

 		apagarSerie($conexao,$treino['idexercicioTreinoAluno']);
 		apagarExerc($conexao,$treino['idexercicioTreinoAluno']);
 	}
 	
 	removerTreino($conexao,$idtreinoAluno);
 	
 }

 if (isset($_GET['treinoAluno'])) {
		$id = $_GET['id'];
		$idaluno=$_GET['aluno'];
		$lista = '{"result":[' . json_encode(treinoAluno($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['exercicios'])) {
		$id = $_GET['id'];
		$lista = '{"result":[' . json_encode(todosExercicios($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['exercicioDoAluno'])) {
		$id = $_GET['treinoAlunoEx'];
		$lista = '{"result":[' . json_encode(exercicioDoAluno($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['exercicioDoAlunoEdicao'])) {
		$id = $_GET['treinoAlunoEx'];
		$lista = '{"result":[' . json_encode(exercicioDoAlunoEdicao($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['apagarTreino'])) {
	include 'editarExercicio.php';
	$idAluno=$_GET['idaluno'];
	$idTreinoPadrao=$_GET['idTreino'];
	$treinoPadrao = idtreinoPadraoAluno($conexao, $idTreinoPadrao);
	//print_r($treinoPadrao);
	$treinoAluno =  treinoAluno($conexao, $treinoPadrao['idtreinoPadraoAluno']);
	
	foreach ($treinoAluno as $treinoAluno) {
		$exercicioTreinoAluno = exercicioDoAluno($conexao, $treinoAluno['idtreinoAluno']);
		
		foreach ($exercicioTreinoAluno as $exercicioTreinoAluno) {
				apagarSerie($conexao,$exercicioTreinoAluno['idexercicioTreinoAluno']);
			}
		apagarExercTreino($conexao,$treinoAluno['idtreinoAluno']);
			

	}	
		apagarTodosTreinoAluno($conexao,$idTreinoPadrao);
		apagarTreinoPadrao($conexao,$idTreinoPadrao);
	
}

	//alteraAlunoCodAtualiza($conexao, $idaluno, $cod_atualiza);

function todosTreinosAluno($conexao, $id) {
	$treinos = array();
	
	$resultado = mysqli_query($conexao, "SELECT * FROM treinoPadraoAluno WHERE aluno = {$id} ORDER BY idtreinoPadraoAluno DESC, treinoAtual ASC;");
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($treinos, array(
			'idtreinoPadraoAluno' => $row['idtreinoPadraoAluno'],
			'dataInicio' => $row['dataInicio'],
			'quantidadeSemanas' => $row['quantidadeSemanas'],
			'dias' => $dias=$row['quantidadeSemanas']*7,
			'dataFim' => $dataI=date('Y-m-d',strtotime('+ '.$dias.' days',strtotime($row['dataInicio']))),
			'quantidadeSessoes' => $row['quantidadeSessoes'],
			'nomeTreino' => utf8_encode($row['nomeTreino']),
			'nivelTreino' => utf8_encode($row['nivelTreino']),
			'velocidade' => utf8_encode($row['velocidade']),
			'tipoTreino' => utf8_encode($row['tipoTreino']),
			'aluno' => utf8_encode($row['aluno']),
			'treinoAtual' => utf8_encode($row['treinoAtual']),
			//'dataFim'=> date('d-m-Y', strtotime('+ 1 days', strtotime($row['dataInicio']))),

		));
	}		
	

	return $treinos;
}

function categoria($conexao, $id) {
	$categoria = array();
	
	$resultado = mysqli_query($conexao, "SELECT * FROM categoria WHERE academia = {$id} ORDER BY nome;");
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idcategoria' => $row['idcategoria'],
			'nome' => utf8_encode($row['nome']),
			'academia' => $row['academia'],
			

		));
	}		
	
	echo ("<br>ID inserido = " .$categoria."<br>");
	return $categoria;
}



function treinoNovo($conexao, $id) {

	$categoria = array();
	
	$resultado = mysqli_query($conexao, "SELECT * FROM treinoAluno where treinoPadraoAluno={$id};");
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idtreinoAluno' => $row['idtreinoAluno'],
			'tipo' => utf8_encode($row['tipo']),
			'nome' => utf8_encode($row['nome']),
			'ordem' => $row['ordem'],
			'treinoPadraoAluno' => $row['treinoPadraoAluno'],
			'concluido' => utf8_encode($row['concluido']),
			

		));
	}		
	
	return $categoria;
}

function ultimo_idtreinoPadraoAluno($conexao,$idaluno) {
	
	
	$resultado = mysqli_query($conexao, "select max(idtreinoPadraoAluno) as idtreinoPadraoAluno FROM academia.treinoPadraoAluno where aluno = {$idaluno} and treinoAtual='S';");	

	 return mysqli_fetch_assoc($resultado);
}

function treinoAluno($conexao, $id){
	$categoria = array();
	
	$sql="SELECT * FROM treinoAluno where treinoPadraoAluno={$id} order by ordem";
	$resultado = mysqli_query($conexao,$sql);
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idtreinoAluno' => $row['idtreinoAluno'],
			'nome' => utf8_encode($row['nome']),
			'ordem'=>$row['ordem'],
		));
	}		
	//echo $sql;
	return $categoria;
}

function todosExercicios($conexao, $id){
	$categoria = array();
	
	$resultado = mysqli_query($conexao, "SELECT idExercicio,nome,grupoMuscular,ativo,academia,caminhoVideo,ordem,padrao FROM exercicio WHERE academia = {$id}  ORDER BY nome;");
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idExercicio' => $row['idExercicio'],
			'nome' => utf8_encode($row['nome']),
			'grupoMuscular' => $row['grupoMuscular'],
			'ativo' => ['ativo'],
			'academia' => $row['academia'],
			'caminhoVideo' => utf8_encode($row['caminhoVideo']),
			'ordem' => ['ordem'],
			'padrao' => utf8_encode($row['padrao']),


		));
	}		
	
	return $categoria;
}

function exercicioDoAluno($conexao, $id){
	$categoria = array();
	
	$sql = "SELECT exTr.idexercicioTreinoAluno, 
exTr.treinoAluno, 
exTr.exercicio, 
exTr.series, 
exTr.repeticoes, 
exTr.tipo_repeticoes, 
exTr.carga, 
exTr.descanso, 
exTr.dica, 
exTr.grupo,
exTr.ordem as ordemExerc, 
ex.idexercicio, 
ex.nome as nomeEx, 
ex.grupoMuscular, 
ex.academia, 
ex.caminhoVideo, 
tr.idtreinoAluno,
tr.treinoPadraoAluno FROM exercicioTreinoAluno exTr  INNER JOIN exercicio ex  on 
(exTr.exercicio = ex.idexercicio)  INNER JOIN treinoAluno tr  on (exTr.treinoAluno = tr.idtreinoAluno)  WHERE exTr.treinoAluno = {$id} order by ordemExerc;";

	$resultado = mysqli_query($conexao, $sql);
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idexercicioTreinoAluno' => $row['idexercicioTreinoAluno'],
			'treinoAluno' => $row['treinoAluno'],
			'exercicio' => $row['exercicio'],
			'series' => $row['series'],
			'repeticoes' => $row['repeticoes'],
			'tipo_repeticoes' => utf8_encode($row['tipo_repeticoes']),
			'carga' => $row['carga'],
			'descanso' => $row['descanso'],
			'dica'=>utf8_encode($row['dica']),
			'grupo'=> utf8_encode($row['grupo']),
			'ordemExerc'=>$row['ordemExerc'],
			'idexercicio'=>$row['idexercicio'],
			'nomeEX'=>utf8_encode($row['nomeEx']),
			'grupoMuscular'=>utf8_encode($row['grupoMuscular']),
			'academia'=>$row['academia'],
			'caminhoVideo'=>utf8_encode($row['caminhoVideo']),
			'idtreinoAluno'=>$row['idtreinoAluno'],
			'treinoPadraoAluno' =>$row['treinoPadraoAluno'],


		));
	}		

	//echo ("query = " .$sql."<br>");

	
	return $categoria;
}

function removerTreino($conexao,$id) {
	
	$query = "delete from treinoAluno where idtreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function apagarTodosTreinoAluno($conexao,$id) {
	
	$query = "delete from treinoAluno where treinoPadraoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}


function exercicioDoAlunoEdicao($conexao, $id){
	$categoria = array();
	
	$resultado = mysqli_query($conexao, " select tr.idtreinoAluno, tr.tipo,tr.nome, tr.ordem as ordemTreino,tr.treinoPadraoAluno,tr.aluno,tr.academia,tr.concluido,
 exTr.idexercicioTreinoAluno,exTr.treinoAluno, exTr.exercicio,exTr.series, exTr.repeticoes, exTr.tipo_repeticoes, exTr.carga,
 exTr.descanso, exTr.dica, exTr.grupo, exTr.ordem as ordemExerc,
 ex.idexercicio, ex.nome as nomeEx,ex.grupoMuscular, ex.academia, ex.caminhoVideo
 from treinoAluno as tr left join exercicioTreinoAluno as exTr 
 on(tr.idtreinoAluno = exTr.treinoAluno) left JOIN exercicio as ex
 on(exTr.exercicio = ex.idexercicio) where tr.treinoPadraoAluno = {$id} order by exTr.ordem;");
	
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idexercicioTreinoAluno' => $row['idexercicioTreinoAluno'],
			'treinoAluno' => $row['treinoAluno'],
			'exercicio' => $row['exercicio'],
			'series' => $row['series'],
			'repeticoes' => $row['repeticoes'],
			'tipo_repeticoes' => utf8_encode($row['tipo_repeticoes']),
			'carga' => $row['carga'],
			'descanso' => $row['descanso'],
			'dica'=>utf8_encode($row['dica']),
			'grupo'=> utf8_encode($row['grupo']),
			'ordemExerc'=>$row['ordemExerc'],
			'idexercicio'=>$row['idexercicio'],
			'nomeEX'=>utf8_encode($row['nomeEx']),
			'grupoMuscular'=>utf8_encode($row['grupoMuscular']),
			'academia'=>$row['academia'],
			'caminhoVideo'=>utf8_encode($row['caminhoVideo']),
			'idtreinoAluno'=>$row['idtreinoAluno'],
			'nome'=>utf8_encode($row['nome']), 
			'tipo'=>utf8_encode($row['tipo']),
			'ordemtreino' => $row['ordemTreino'],
			'treinoPadraoAluno' =>$row['treinoPadraoAluno'],

		));
	}		
	//echo ("<br>ID inserido = " .$categoria."<br>");
	return $categoria;
}

function treinoPadraoAluno($conexao, $id){
	$treino = array();
	$sql = "select * from treinoPadraoAluno where aluno=$id and treinoAtual='S'";

	$query = mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($treino, array(
			'idtreinoPadraoAluno' => $row['idtreinoPadraoAluno'],
			'quantidadeSemanas' => $row['quantidadeSemanas'],
			'quantidadeSessoes' => $row['quantidadeSessoes'],
			'nomeTreino' => $row['nomeTreino'],
			'nivelTreino' => $row['nivelTreino'],
			'velocidade' => $row['velocidade'],
			'tipoTreino' => $row['tipoTreino'],
			'avaliacoes' => $row['avaliacoes'],
			'aluno'=>$row['aluno'],
			'academia'=> $row['academia'],
			'treinoAtual'=>$row['treinoAtual'],
			'dataInicio'=>$row['dataInicio'],
		));
	}
return $treino;
}
function idtreinoPadraoAluno($conexao, $id){
	//$categoria = array();
	$sql ="select idtreinoPadraoAluno from treinoPadraoAluno where idtreinoPadraoAluno = $id";
	$resultado = mysqli_query($conexao, $sql);
	
	$row = mysqli_fetch_assoc($resultado);
	 //echo $sql;		
	
	return $row ;
}

function apagarExercTreino($conexao,$id) {
	
	$query = "delete from exercicioTreinoAluno where treinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function apagarTreinoPadrao($conexao,$id) {
	
	$query = "delete from treinoPadraoAluno where idtreinoPadraoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}