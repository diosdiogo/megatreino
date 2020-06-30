
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

	$sql = "select * from modalidade where academia={$id} and deletado = 'N' order by nome";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'nome' => utf8_encode($row['nome']),
			'academia' => $row['academia'],
			'valor1' => $row['valor1'],
			'valor2' => $row['valor2'],
			'valor3' => $row['valor3'],
			'valor4' => $row['valor4'],
			'valor6' => $row['valor6'],
			'valor12' => $row['valor12'],
			'ativo' => $row['ativo'],
			'codinterno' => $row['codinterno'],
		));
	}

	return $retorno;
}

/*
id int(11) AI PK
nome varchar(80)
valor1 decimal(10,2)
valor2 decimal(10,2)
valor3 decimal(10,2)
valor4 decimal(10,2)
valor6 decimal(10,2)
valor12 decimal(10,2)
academia int(11)
ativo tinyint(1)
codinterno varchar(20)
cod_atualiza bigint(20)

 */
