
<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getModalidade($conexao, $id)) . ']}';
	echo $lista;
}

function getModalidade($conexao, $id ) {
	$retorno = array();

	$sql = "select aluno.matricula, aluno.nome, TIMESTAMPDIFF(YEAR, aluno.data_nascimento, CURDATE()) as idade, aluno.genero, aluno.celular, matricula.plano_pagamento 
from aluno inner join matricula on matricula.aluno = aluno.idaluno where aluno.academia = {$id} and aluno.ativo = 'S' and aluno.aluno_colaborador = 0";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'matricula' => $row['matricula'],
			'nome' => utf8_encode($row['nome']),
			'idade' => $row['idade'],
			'genero' => $row['genero'],
			'celular' => $row['celular'],
			'plano_pagamento' => utf8_encode($row['plano_pagamento'])

		));
	}

	return $retorno;
}
