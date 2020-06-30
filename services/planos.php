
<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getPlanos($conexao, $id)) . ']}';
	echo $lista;
}

function getPlanos($conexao, $id) {

	$retorno = array();

	$sql = "select * from plano where academia=1 or academia={$id} and deletado = 'N'";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'academia' => $row['academia'],
			'descricao' => utf8_encode($row['descricao']),
			'mes_dia' => $row['mes_dia'],
			'quantidade' => $row['quantidade']));
	}

	return $retorno;
}

/*
id int(11) AI PK
academia int(11)
descricao varchar(20)
mes_dia char(1)
quantidade int(11)

 */
