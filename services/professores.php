<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getProfessores($conexao, $id)) . ']}';
	echo $lista;
}

function getProfessores($conexao, $id) {
	$retorno = array();

	$sql = "select colaborador.idcolaborador, colaborador.nome, colaborador.perfil, perfil.professor  from colaborador inner join perfil on( colaborador.perfil = perfil.idperfil) where colaborador.academia = {$id} and perfil.professor = 'S' and colaborador.ativo = 1 order by colaborador.nome;";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idcolaborador' => $row['idcolaborador'],
			'nomecolaborador' => utf8_encode($row['nome']),
			'perfil' => $row['perfil'],
			'professor' => $row['professor'],

		));
	}

	return $retorno;
}
