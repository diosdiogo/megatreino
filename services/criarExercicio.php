<?php
include 'conecta.php';
include 'alteraAlunoCodAtualiza.php';

$cod_atualiza = rand(10000, 9999999);

if (isset($_GET['montarExerc'])) {
		
	$idAluno= $_GET['idaluno'];
	echo "Aluno: ".$idAluno."</br>";

	$academia= $_GET['academia'];
	echo "Academia: ".$academia."</br>";

	if (isset($_GET['Treino'])) {
		$treinoAluno=$_GET['Treino'];
		echo "Treino Aluno: ".$treinoAluno."</br>";
	}

	if (isset($_GET['exercicioTreino'])) {
		$idExercicio=$_GET['exercicioTreino'];
		echo "Exercício: ".$idExercicio."</br>";
	}

	if (isset($_GET['serieExercios'])) {
		$series=$_GET['serieExercios'];
		if ($series == 'undefined') {
				$series = 3;
			}	
	}else{$series=3;}

	if (isset($_GET['repeticaoExercicios'])) {
		$repeticoes=$_GET['repeticaoExercicios'];
		if ($repeticoes == 'undefined') {
			$repeticoes = 3;
		}
	}else{$repeticoes=0;}

	if (isset($_GET['tipoRepeticaoExercicios'])) {
		$tipo_repeticoes=$_GET['tipoRepeticaoExercicios'];
		if ($tipo_repeticoes == 'undefined') {
			$tipo_repeticoes = '';
		}
	}else{$tipo_repeticoes='';}

	if (isset($_GET['carga'])) {
		$carga=$_GET['carga'];
		if ($carga == 'undefined') {
			$carga = 0;
		}
	}else{$carga=0;}

	if (isset($_GET['descansoExercicios'])) {
		$descanso=$_GET['descansoExercicios'];
		if ($descanso == 'undefined') {
			$descanso= 0;
		}
	}else{$descanso=0;}

	if (isset($_GET['dicaExercicios'])) {
		$dica=$_GET['dicaExercicios'];
		if ($dica == 'undefined') {
			$dica='';
		}
	}else{$dica='';}

	if (isset($_GET['zona_alvo'])) {
		$zona_alvo=$_GET['zona_alvo'];
	}else{$zona_alvo='';}

	if (isset($_GET['concluido'])) {
		$concluido=$_GET['concluido'];
	}else{$concluido='N';}

	if (isset($_GET['tipoGrupoExercicios'])) {
		$grupo=$_GET['tipoGrupoExercicios'];
		if ($grupo == 'undefined') {
			$grupo ='';
		}
	}else{$grupo='';}
/*
	if (isset($_GET['ordem'])) {
		$ordem=$_GET['ordem'];
	}else{$ordem=1;}

*/

$ordem=1;

echo "Serie: ".$series."</br>";
echo "Repetiços: ".$repeticoes."</br>";
echo "Repetiços: ".$tipo_repeticoes."</br>";
echo "Carga: ".$carga."</br>";
echo "Descanso: ".$descanso."</br>";
echo "Dica: ".$dica."</br>";
echo "Zona Alvo: ".$zona_alvo."</br>";
echo "Grupo: ".$grupo."</br>";
echo "Ordem: ".$ordem."</br>";

	
	$ordemBD=ordem($conexao,$treinoAluno);
	while ($ordem <= $ordemBD['ordem']) {
		$ordem ++;
	}
	
	echo "Ordem: ".$ordem."</br>";
	criarExercicios($conexao,$treinoAluno,$idAluno,$idExercicio,$series,$repeticoes,$tipo_repeticoes,$carga,$descanso,$dica,$zona_alvo,$concluido,$grupo,$ordem,$academia);
}

if (isset($_GET['apagar'])) {
	
	$id=$_GET['idexercicioTreinoAluno'];
	apagarExerc($conexao,$id);
}

if (isset($_GET['posicao'])) {

	$posicao = $_GET['posicao'];
	$id=$_GET['idexercicioTreinoAluno'];
	$ordem=$_GET['ordem'];
	$treinoAluno=$_GET['treinoAlunoOrdem'];
	$pos = $ordem;
	//$down=$ordem;
	$idTreino=$_GET['idTreino'];

	$ordemMax = verificaPosicaoMax($conexao,$idTreino);
	$ordemMin = verificaPosicaoMin($conexao,$idTreino);
		echo 'Max'.$ordemMax['ordem'].'</br>';
		echo 'Min'.$ordemMin['ordem'].'</br>';

	if ($posicao == 'up') {

		if ($ordemMin['ordem'] != $ordem) {
			if ($ordem > 1) {
			$pos--;
			echo "Ordem Up: ".$pos."</br>";
			echo "Ordem: ".$ordem."</br>";
			echo "Ordem:  up </br>";
			ordemUpDown($conexao,$id, $pos);
			ordemAlterarPosicaoUpDown($conexao,$id, $ordem, $pos,$treinoAluno);
			}
			
		}
	}

	if ($posicao == 'down') {

		if ($ordemMax['ordem'] != $ordem) {

			if ($ordem < $ordemMax['ordem']) {
				$pos++;
				echo "Ordem Up: ".$pos."</br>";
				echo "Ordem: ".$ordem."</br>";
				echo "Ordem:  down </br>";
				ordemUpDown($conexao,$id, $pos);
				ordemAlterarPosicaoUpDown($conexao,$id, $ordem, $pos,$treinoAluno);
			}
			
		}
	}
		
	

}

alteraAlunoCodAtualiza($conexao, $idAluno, $cod_atualiza);

// função SQL
function criarExercicios($conexao,$treinoAluno,$idAluno,$idExercicio,$series,$repeticoes,$tipo_repeticoes,$carga,$descanso,$dica,$zona_alvo,$concluido,$grupo,$ordem,$academia) {

	$query = "insert into exercicioTreinoAluno (
treinoAluno,
aluno,
exercicio,
series,
repeticoes,
tipo_repeticoes,
carga,
descanso,
dica,
zona_alvo,
concluido,
grupo,
ordem,
academia)

values(
$treinoAluno,
$idAluno,
$idExercicio,
$series,
'$repeticoes',
'$tipo_repeticoes',
$carga,
$descanso,
'$dica',
'$zona_alvo',
'$concluido',
'$grupo',
$ordem,
$academia);";

	$inserir = mysqli_query($conexao, $query);

	
	//echo ("ID inserido = \n" . mysqli_insert_id($conexao));
	$idExercicioDaSerie =  mysqli_insert_id($conexao);
	echo ("ID inserido = " . $idExercicioDaSerie. "<br>");
	
	for ($i=1; $i <= $series; $i++){
		criarSerie($conexao,$idExercicioDaSerie,$idAluno,$academia,$i,$repeticoes);
	}
	echo $query;
	return $inserir;
}


function criarSerie($conexao,$idExercicioDaSerie,$idAluno,$academia,$i,$repeticoes) {

	$query = "insert into serieItemAluno(exercicioTreinoAluno,aluno,series,academia,concluido,ordem)
				value($idExercicioDaSerie,$idAluno,$repeticoes,$academia,'N',$i)";

	$inserir = mysqli_query($conexao, $query);

	
	echo ("ID inserido = \n" . mysqli_insert_id($conexao). "SQL ". $query."<br>");
	

	//echo $query;
	return $inserir;
}

function ordem($conexao,$id) {
	
	
	$resultado = mysqli_query($conexao, "SELECT max(ordem) as ordem FROM academia.exercicioTreinoAluno where treinoAluno=$id;");	

	 return mysqli_fetch_assoc($resultado);
}


function editararExercSerie($conexao,$id,$valor) {
	
	
	$query = "UPDATE exercicioTreinoAluno SET series = '{$valor}'
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function editararExercRepeticoes($conexao,$id,$valor) {
	
	
	$query = "UPDATE exercicioTreinoAluno SET repeticoes = '{$valor}'
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function editararExercCarga($conexao,$id,$valor) {
	
	$query = "UPDATE exercicioTreinoAluno SET carga = '{$valor}'
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function editararExercDescanso($conexao,$id,$valor) {
	
	$query = "UPDATE exercicioTreinoAluno SET descanso = {$valor}
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function editararExercTipo_repeticoes($conexao,$id,$valor) {
	
	$query = "UPDATE exercicioTreinoAluno SET tipo_repeticoes = '{$valor}'
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function editararExercGrupo($conexao,$id,$valor) {
	
	$query = "UPDATE exercicioTreinoAluno SET grupo = '{$valor}'
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function editararExercDica($conexao,$id,$valor) {
	
	$query = "UPDATE exercicioTreinoAluno SET dica = '{$valor}'
WHERE idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function apagarExerc($conexao,$id) {
	
	$query = "delete from exercicioTreinoAluno where idexercicioTreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function verificaPosicaoMax($conexao,$id) {
	$query="select max(ordem) as ordem FROM academia.exercicioTreinoAluno where treinoAluno={$id};";

$resultado = mysqli_query($conexao, $query);	
	
	//echo $query;
	 return mysqli_fetch_assoc($resultado);
}

function verificaPosicaoMin($conexao,$id) {

$resultado = mysqli_query($conexao, "select min(ordem) as ordem FROM academia.exercicioTreinoAluno where treinoAluno={$id};");	

	 return mysqli_fetch_assoc($resultado);
}

function ordemUpDown($conexao,$id, $ordem) {
	
	$query = "update exercicioTreinoAluno set ordem={$ordem} where idexercicioTreinoAluno={$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}

function ordemAlterarPosicaoUpDown($conexao,$id, $ordem, $up,$treinoAluno) {
	
	$query = "update exercicioTreinoAluno set ordem={$ordem} where ordem=$up and treinoaluno={$treinoAluno} and idexercicioTreinoAluno != {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao))."<br>sql: ". $query."<br>";

	 return $query;
}
