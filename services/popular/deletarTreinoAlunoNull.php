<?php 
include '../../conecta.php';

$treinoAluno = treinoAluno($conexao);
$i=0;
foreach ($treinoAluno as $treinoAluno) {
	$treinoPadraoAluno =  treinoPadraoAluno($conexao,$treinoAluno['treinoPadraoAluno']);

	echo "Treino Padrão: ".$treinoPadraoAluno['idtreinoPadraoAluno'];
	
if($treinoPadraoAluno['idtreinoPadraoAluno'] == NULL){
			echo "<pre><font color='#FF9484FF' style='background-color: #FF0004FF'>";
			echo "Sem treino Padrão ID treino aluno ".$treinoAluno['treinoPadraoAluno'];
			echo "</font></pre>";
		}
else {

			echo "<pre><font color='#0DF173FF' style='background-color: #3BA300FF'>";
			echo "Com treino Padrão ID treino aluno ".$treinoPadraoAluno['idtreinoPadraoAluno'];
			echo "</pre>";

			//alteraTreinoAluno($conexao,$row['idtreinoPadraoAluno']);
		}



	 $i++;
}

echo "<pre>";
echo $i;
echo "</pre>";

function treinoAluno($conexao){

	$array = array();

	$sql = "select * from treinoAluno where academia is null order by treinoPadraoAluno;";

	$query = mysqli_query($conexao,$sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($array, array('treinoPadraoAluno' => $row['treinoPadraoAluno'],
								'aluno' => $row['aluno'],
								'academia'=>$row['academia']));
		
		
	}
	return $array;
}

function treinoPadraoAluno($conexao,$id){

	$array = array();

	$sql = "select * from treinoPadraoAluno where idtreinoPadraoAluno =$id";

	$query = mysqli_query($conexao,$sql);

	//while ($row = mysqli_fetch_assoc($query)) ;
		

		$linha = mysqli_num_rows($query);
		/*
		if($linha == 0){
			echo "<pre><font color='#FF9484FF' style='background-color: #FF0004FF'>";
			echo "Sem treino Padrão ID treino aluno ".$id;
			echo "</font></pre>";
		}
		else if ($linha != 0) {

			echo "<pre>";
			echo "Com treino Padrão ID treino aluno ".$row['idtreinoPadraoAluno'];
			echo "</pre>";

			//alteraTreinoAluno($conexao,$row['idtreinoPadraoAluno']);
		}*/

		echo $sql;
		

	
	return $array;
}


function alteraTreinoAluno($conexao,$id){
	$sql = "update treinoAluno set aluno = (select aluno from treinoPadraoAluno where idtreinoPadraoAluno = $id), 
academia=(select academia from treinoPadraoAluno where idtreinoPadraoAluno = $id)
where treinoPadraoAluno=$id and aluno is null and idtreinoAluno <>0;";

	$altera= mysqli_query($conexao,$sql);
	echo ("<pre><font color='green'>Linha alterada = " . mysqli_affected_rows($conexao))." sql: ". $sql."</font></pre>";
}

?>