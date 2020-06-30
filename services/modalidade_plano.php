<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getModalidadePlano($conexao, $id)) . ']}';
	echo $lista;
}

function getModalidadePlano($conexao, $id) {
	$retorno = array();
/*
$sql = "select plano.id, plano.academia, plano.descricao,plano.deletado, modalidade_plano.modalidade, modalidade_plano.valor from plano inner join modalidade_plano on( plano.id = modalidade_plano.id) where plano.academia = {$id} and modalidade_plano.modalidade = {$mod} and plano.deletado = 'N'";
 */
	$sql = "select modalidade_plano.id, modalidade_plano.academia, modalidade_plano.modalidade, modalidade_plano.valor, modalidade_plano.plano, plano.descricao, plano.mes_dia, plano.quantidade from modalidade_plano inner join plano on (modalidade_plano.plano=plano.id) where modalidade_plano.academia={$id} and modalidade_plano.deletado='N' order by plano.descricao";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		/*
			array_push($retorno, array(
				'id' => $row['id'],
				'academia' => $row['academia'],
				'descricao' => utf8_encode($row['descricao']),
				'modalidade' => $row['modalidade'],
				'valor' => $row['valor'],
		*/

		array_push($retorno, array(
			'id' => $row['id'],
			'academia' => $row['academia'],
			'modalidade' => $row['modalidade'],
			'valor' => $row['valor'],
			'plano' => $row['plano'],
			'descricao' => utf8_encode(strtoupper($row['descricao'])),
			'mes_dia' => utf8_encode($row['mes_dia']),
			'quantidade' => utf8_encode($row['quantidade']),

		));
	}

	return $retorno;
}

/*
id int(11) AI PK
academia int(11)
modalidade int(11)
valor decimal(10,2)
deletado char(1)
plano int(11)

 */