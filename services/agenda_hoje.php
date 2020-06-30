<?php

include 'conecta.php';

$id = $_GET["id"];

$lista = '{"result":[' . json_encode(getAgenda_hoje($conexao, $id)) . ']}';
echo $lista;

function getAgenda_hoje($conexao, $id) {
	$retorno = array();

	$sql = "select * from agenda where academia = 2 and data_inicial = CURDATE() order by hora_inicial";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'titulo' => utf8_encode($row['titulo']),
			'data_inicial' => $row['data_inicial'],
			'data_final' => $row['data_final'],
			'hora_inicial' => $row['hora_inicial'],
			'academia' => $row['academia'],
			'dia_todo' => $row['dia_todo'],
			'id_professor' => $row['id_professor'],
			'id_colaborador' => $row['id_colaborador'],
		));
	}

	return $retorno;
}
