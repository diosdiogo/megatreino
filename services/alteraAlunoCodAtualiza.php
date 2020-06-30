<?php 

function alteraAlunoCodAtualiza($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";

	//echo "query uptade aluno: " . $query . "<br>";

	return mysqli_query($conexao, $query);
}
 ?>