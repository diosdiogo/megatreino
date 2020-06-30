<?php

include 'conecta.php';

$idacademia = 12;
$id = 263;

$lista = json_encode(getModalidade($conexao, $idacademia, $id));
echo $lista;
$dados;

function getModalidade($conexao, $idacademia, $id) {
	$retorno = array();

	$sql = "select * from modalidade where academia={$idacademia} and id = {$id};";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			$row['nome'],

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