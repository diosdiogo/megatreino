<?php
include 'conecta.php';

$setarLetra = array('A','B','C','D','E','F','G');
$setarSemana = array('Segunda','Terça','Quarta','Quinta','Sexta','Sabado','Domingo');
$setarLivre = "Treino Livre - ";

if (isset($_GET['criarTreino'])) {
	

	if (isset($_GET['idaluno'])) {
		$idaluno=$_GET['idaluno'];
	}
	if (isset($_GET['idTreino'])) {
	$idTreino=$_GET['idTreino'];
	}

	if (isset($_GET['academia'])) {
	$academia=$_GET['academia'];
	}

	//$ultimoTreino=$_GET['ultimoTreino'];



	if (isset($_GET['dataI'])) {
		$dataI=$_GET['dataI'];
	}
	if (isset($_GET['nomeTreino'])) {
		$nomeTreino=$_GET['nomeTreino'];
	}


	if (isset($_GET['prof'])) {
		$prof=$_GET['prof'];
	}
	if(isset($_GET['qtsemanas'])){
		$qtsemanas=$_GET['qtsemanas'];
	}
	if (isset($_GET['qtsessoes'])) {
		$qtsessoes=$_GET['qtsessoes'];
	}
	if (isset($_GET['nivel'])) {
		$nivel=$_GET['nivel'];
	}
	if (isset($_GET['velocidade'])) {
		$velocidade=$_GET['velocidade'];
	}
	if (isset($_GET['tipoTreino'])) {
		$tipoTreino=$_GET['tipoTreino'];
	}

	//$tipoTreinoAluno = $_GET['tipoTreinoAluno'];
	if (isset($_GET['ordem'])) {
		$ordem = $_GET['ordem'];
	}


	// pegar o tipo do treio
	if (isset($_GET['letra'])) {
	 	$letra = $_GET['letra'];
	 	if ($letra == "false") {
	 		$letra = null;
	 	}
	 	else {

	 		$tipo = "Letra";
	 	}
	 	
	 	print "Letra: ".$letra ."<br>";
	 } 

	if (isset($_GET['semana'])) {
	  	$semana = $_GET['semana'];
	  	if ($semana == "false") {
	 		$semana = null;

	 	}
	 	else {

	 		$tipo = "Semana";
	 	}

	 	print "Semana: ".$semana ."<br>";
	  } 
	 
	if (isset($_GET['dia'])) {
	   	$dia = $_GET['dia'];
	   if ($dia == "false") {
	 		$dia = null;
	 	}
	 	else {

	 		$tipo = "Dia";
	 	}

	 	print "dia: ".$dia ."<br>";
	   } 
	 
	if (isset($_GET['livre'])) {
	   	$livre = $_GET['livre'];
	    if ($livre == "false") {
	 		$livre = null;
	 	}
	 	else {

	 		$tipo = "Livre";
	 	}

	 	print "livre: ".$livre ."<br>";
	   }

	if (isset($_GET['qtsTreino'])) {
	  $qtsTreino = $_GET['qtsTreino'];
	  

	 	print "Quantidade de treino: ".$qtsTreino ."<br>";
	}
	if (isset($_GET['AvR'])) {
		$AvR=$_GET['AvR'];
		if ($AvR == "undefined") {
			$AvR="";
		}
	}

	// verifica o treino setado
	$tipoTreinoAluno = $tipo;
	//$nome= $letra.$semana.$dia.$livre;



	print "Id Aluno: ".$idaluno ."<br>";
	print "Id treino: ".$idTreino ."<br>";

	print "Data: ".$dataI ."<br>";
	print "Nome Treino: ". $nomeTreino."<br>";
	print "Professor: ".$prof ."<br>";
	print "Quantidade de Semandas: ". $qtsemanas."<br>";
	print "Quantidade de Sessões: ". $qtsessoes."<br>";
	print "Nivel: ".$nivel ."<br>";
	print "Velocidade: ".$velocidade ."<br>";
	print "Tipo Treino: ".$tipoTreino ."<br>";
	print "Avaliação ou Restrição: ".$AvR ."<br>";
	print "Tipo treino aluno: ".$tipoTreinoAluno ."<br>";
	print "Tipo:  ".$tipo."<br>";
	print "Ordem:  ".$ordem ."<br>";


	$treino = treino($conexao, $idaluno,$idTreino);
	 echo "Treino Atual:  ".$treino['treinoAtual']. "<br>";

	 $verificaSeTemTreino = verificaSeTemTreino($conexao,$idaluno);

	 echo "Tem Treino ".$verificaSeTemTreino;

	 if ($verificaSeTemTreino == 0) {
	 	criarTreinoAtual($conexao,$dataI,$qtsemanas,$qtsessoes,$nomeTreino,$nivel,$velocidade,$tipoTreino,$AvR,$idaluno,$academia);
	 }

	if ($treino['treinoAtual'] == 'S') {
		removerTreinoAtual($conexao, $idTreino);
		criarTreinoAtual($conexao,$dataI,$qtsemanas,$qtsessoes,$nomeTreino,$nivel,$velocidade,$tipoTreino,$AvR,$idaluno,$academia);
	}
	mudarProfessor($conexao,$idaluno,$prof);

	$idtreinoPadraoAluno=ultimo_idtreinoPadraoAluno($conexao,$idaluno);

	print "Ultimo id treino :  ".$idtreinoPadraoAluno['idtreinoPadraoAluno'] ."<br>";


	for($i=0; $i<$qtsTreino; $i++){
		if ($letra == "true") {
			$nome = $setarLetra[$i];
		}
		else if ($semana == "true") {
			$nome = $setarSemana[$i];
		}
		else if ($livre == "true") {
			$nome = $setarLivre . ($i+1);
		}
		else if ($dia == "true") {
			$nome = date('d/m/Y');
		}
		
		//print $nome .'<br>';
		treinoaluno($conexao,$tipoTreinoAluno,$nome,($i+1),$idtreinoPadraoAluno['idtreinoPadraoAluno'],$idaluno,$academia);
	}
}

if (isset($_GET['novoTreino'])) {
	$idTreino=$_GET['idTreino'];
	//echo "Id Treino: ".$idTreino."<br>";

	$idaluno=$_GET['idaluno'];
	//echo "Aluno; ". $idaluno."<br>";
	$academia=$_GET['academia'];
	//echo "academia; ". $academia."<br>";

	

	$ordemDB=verificaUltimoTreino($conexao,$idTreino);
	//echo "Oerdem ".$ordemDB['ordem']."<br>";
	$tipo =verificaUltimoTreino($conexao,$idTreino);
	//echo "Tipo ".$tipo['tipo']."<br>";

	$pTreino = $ordemDB['ordem'];


		if ($tipo['tipo'] == "Letra") {
			$nome = $setarLetra[$pTreino];
		}
		else if ($tipo['tipo'] == "Semana") {
			$nome = $setarSemana[$i];
		}
		else if ($tipo['tipo'] == "Livre") {
			$nome = $setarLivre . ($i+1);
		}
		else if ($tipo['tipo'] == "Dia") {
			$nome = date('d/m/Y');
		
		}
		//echo "Nome do Treino: ".$nome."<br>";


	treinoaluno($conexao,$tipo['tipo'],$nome,($ordemDB['ordem']+1),$idTreino,$idaluno,$academia);

	

}

if (isset($_GET['editarTreino'])) {
	$idTreino=$_GET['idTreino'];
	$nomeTreino=$_GET['nomeTreino'];

	editarTreino($conexao,$idTreino,$nomeTreino);
}


function treino($conexao,$idaluno,$idTreino) {
	$treinos = array();
	
	$resultado = mysqli_query($conexao, "select * from treinoPadraoAluno where aluno={$idaluno} and idtreinoPadraoAluno={$idTreino};");
		
	$treinos=mysqli_fetch_assoc($resultado);
	
	
	return $treinos;
}

function removerTreinoAtual($conexao, $idTreino) {


	$query = "update treinoPadraoAluno set treinoAtual= '' where idtreinoPadraoAluno={$idTreino};";

	//echo '<br>' . $query . '<br>';

	$alterar = mysqli_query($conexao, $query);

	echo ("ID alterado = \n" . mysqli_affected_rows($conexao))."\n";

	return $alterar;

}

function ultimo_idtreinoPadraoAluno($conexao,$idAluno) {
		
	$sql = "select max(idtreinoPadraoAluno) as idtreinoPadraoAluno FROM treinoPadraoAluno where aluno=$idAluno;";
	$resultado = mysqli_query($conexao,$sql);	
	
	return mysqli_fetch_assoc($resultado);
}

function verificaSeTemTreino($conexao,$idAluno) {
		
	$resultado = mysqli_query($conexao, "select * from treinoPadraoAluno where aluno = {$idAluno}");	

	return mysqli_num_rows($resultado);
}


function criarTreinoAtual($conexao,$dataI,$qtsemanas,$qtsessoes,$nomeTreino,$nivel,$velocidade,$tipoTreino,$AvR,$idaluno,$academia) {

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

function treinoaluno($conexao,$tipoTreinoAluno,$tipo,$ordem,$idTreino,$idaluno,$academia) {

	$query = "insert into treinoAluno (
	tipo,
	nome,
	ordem,
	treinoPadraoAluno,
	aluno,
	academia,
	concluido) 

	value(
	'{$tipoTreinoAluno}',
	'{$tipo}',
	{$ordem},
	$idTreino,
	$idaluno,
	$academia,
	'N');";

	$inserir = mysqli_query($conexao, $query);

	
	$insereDB = mysqli_insert_id($conexao);


	
	if ($insereDB <= 0) {
		$result = array('id'=>$insereDB,
						'ordem'=> 0);
		$lista = '{"result":[' . json_encode($result). ']}';
		echo $lista;
	}else{
		$result = array('id'=>$insereDB,
						'ordem'=> $ordem);
		$lista = '{"result":[' . json_encode($result).']}';
		echo $lista;
	}

	return $inserir;
}

function editarTreino($conexao,$idTreino,$nomeTreino) {


	$query = "update treinoAluno set nome= '$nomeTreino' where idtreinoAluno={$idTreino};";

	//echo '<br>' . $query . '<br>';

	$alterar = mysqli_query($conexao, $query);

	echo ("ID alterado = \n" . mysqli_affected_rows($conexao))."\n";
	echo $query;
	return $alterar;

}

function verificaUltimoTreino($conexao,$idTreino){

	$sql = "select max(ordem) as ordem, tipo from treinoAluno where treinopadraoaluno={$idTreino};";
	$resultado = mysqli_query($conexao,$sql);	
	
	return mysqli_fetch_assoc($resultado);
}

function mudarProfessor($conexao,$idaluno,$prof){
	$sql="update aluno set professor=$prof where idaluno=$idaluno";

	$query=mysqli_query($conexao,$sql);

}