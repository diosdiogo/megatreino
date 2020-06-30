<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getModalidadeTurma($conexao, $id)) . ']}';
	echo $lista;
}

function getModalidadeTurma($conexao, $id) {

	$retorno = array();

	$sql = "select 	distinct aluno.matricula,
					aluno.nome,
					aluno.genero,
					aluno.celular,
					TIMESTAMPDIFF(YEAR, aluno.data_nascimento, CURDATE()) as idade, 
					matricula.plano_pagamento, 
					modalidade.id as idmodal, 
					modalidade.nome as nomemodal
	from aluno inner join matricula on aluno.idaluno = matricula.aluno, 
		 modalidade 
	where aluno.academia = {$id} 
		  and matricula.modalidade = modalidade.id
          and aluno.ativo = 'S' 
		  and matricula.academia = {$id}
    	  and modalidade.academia = {$id}
     	  and modalidade.deletado = 'N'
	order by aluno.nome";
	
	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'matricula' => $row['matricula'],
			'nome' => utf8_encode($row['nome']),
			'idade' => $row['idade'],
			'genero' => $row['genero'],
			'plano_pagamento' => utf8_encode($row['plano_pagamento']),
			'celular' => $row['celular'],
			'idmodal' => $row['idmodal'],
			'nomemodal' => utf8_encode($row['nomemodal']),
		));
	}
	
	return $retorno;
}