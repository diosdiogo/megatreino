<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];
$app = $_GET["app"];

if ($state == "C") {
	if ($app=="T") {
		$lista = '{"result":['.json_encode(getAlunos_APP($conexao, $id)).']}';
		echo $lista;
	}
	else if ($app=="S") {
		$lista = '{"result":['.json_encode(getAlunos_APP_S($conexao, $id)).']}';
		echo $lista;
	}
	else if ($app=="C") {
		$lista = '{"result":['.json_encode(getAlunos_APP_C($conexao, $id)).']}';
		echo $lista;
	}
			
}	

function getAlunos_APP($conexao, $id) {
	$retorno = array();

	$sql = "select aluno.idaluno, 
					aluno.matricula,
				   aluno.nome, 
				   aluno.celular, 
				   aluno.genero, 
				   aluno_conectado_app.aluno as alunoapp, 
				   aluno_conectado_app.data_atualizacao as ultimo_acesso 
			  from aluno left join aluno_conectado_app on (aluno.idaluno = aluno_conectado_app.aluno) 
			 where aluno.academia = {$id} and aluno.ativo = 'S'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idaluno' => $row['idaluno'],
								   'matricula'=>$row['matricula'],
								   'nome' => utf8_encode($row['nome']),
								   'celular' => ($row['celular']),
								   'genero' => utf8_encode($row['genero']),
								   'alunoapp' => $row['alunoapp'],
								   'ultimo_acesso' => $row['ultimo_acesso'],
								));
	}
	return $retorno;
}

function getAlunos_APP_C($conexao, $id) {
	$retorno = array();

	$sql = "select aluno.idaluno, 
				   aluno.matricula, 
				   aluno.nome, 
				   aluno.celular, 
				   aluno.genero, 
				   aluno_conectado_app.aluno as alunoapp, 
				   aluno_conectado_app.data_atualizacao as ultimo_acesso 
		      from aluno inner join aluno_conectado_app 
		        on (aluno.idaluno = aluno_conectado_app.aluno) 
		      where aluno.academia = {$id} 
		        and aluno.ativo = 'S'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idaluno' => $row['idaluno'],
								   'matricula'=>$row['matricula'],
								   'nome' => utf8_encode($row['nome']),
								   'celular' => ($row['celular']),
								   'genero' => utf8_encode($row['genero']),
								   'alunoapp' => $row['alunoapp'],
								   'ultimo_acesso' => $row['ultimo_acesso'],
								));
	}
	return $retorno;
}
function getAlunos_APP_S($conexao, $id) {
	$retorno = array();

	$sql = "select aluno.idaluno, 
				   aluno.matricula, 
				   aluno.nome, 
				   aluno.celular, 
				   aluno.genero, 
				   aluno_conectado_app.aluno as alunoapp, 
				   aluno_conectado_app.data_atualizacao as ultimo_acesso 
			  from aluno left join aluno_conectado_app 
			    on (aluno.idaluno = aluno_conectado_app.aluno) 
			 where aluno.academia = {$id} 
			   and aluno.ativo = 'S' 
			   and aluno is null";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idaluno' => $row['idaluno'],
								   'matricula'=>$row['matricula'],
								   'nome' => utf8_encode($row['nome']),
								   'celular' => ($row['celular']),
								   'genero' => utf8_encode($row['genero']),
								   'alunoapp' => $row['alunoapp'],
								   'ultimo_acesso' => $row['ultimo_acesso'],
								));
	}

	return $retorno;
}