<?php 

function listaAluno($conexao, $academia) {
	$alunos = array();
	#if $academia<>"0" {
	$resultado = mysqli_query($conexao, "SELECT * FROM academia.subconta_iugu where academia='{$academia}'");
	#} else {
	#$resultado = mysqli_query($conexao,'select * from exercicio');
	#}

	while ($aluno = mysqli_fetch_assoc($resultado)) {
		array_push($alunos, $aluno);
	}
	return $alunos;
}

 ?>