<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getColaborador($conexao, $id)) . ']}';
	echo $lista;
}

function getColaborador($conexao, $id) {
	$retorno = array();

	$sql = "select *,substring_index(substring_index(nome,' ',1),' ',-1) as nomeColabolador from colaborador where academia={$id};";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idcolaborador' => $row['idcolaborador'],
			'nome' => utf8_encode($row['nome']),
			'nomeColabolador' => utf8_encode($row['nomeColabolador']),
			'email' => utf8_encode($row['email']),
			'academia' => $row['academia'],
			'senha' => utf8_encode($row['senha']),
			'nutricionista' => $row['nutricionista'],
			'ativo' => $row['ativo'],
			'aluno' => $row['aluno'],
			'cod_atualiza' => $row['cod_atualiza'],
			'codinterno' => $row['codinterno'],

		));
	}

	return $retorno;
}
/*
idcolaborador int(11) AI PK
nome varchar(60)
email varchar(60)
perfil int(11)
academia int(11)
senha varchar(25)
nutricionista varchar(1)
ativo tinyint(1)
aluno int(11)
cod_atualiza bigint(20)
codinterno varchar
 */