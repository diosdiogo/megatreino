
<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getTurmas($conexao, $id)) . ']}';
	echo $lista;
}

function getTurmas($conexao, $id) {
	$retorno = array();

	$sql = "select * from turma where academia={$id} and deletado = 'N' order by nome";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'academia' => $row['academia'],
			'nome' => utf8_encode($row['nome']),
			'qtd_alunos' => $row['qtd_alunos'],
			'hora_inicio' => $row['hora_inicio'],
			'hora_fim' => $row['hora_fim'],
			'livre' => $row['livre'],
			'segunda' => $row['segunda'],
			'terca' => $row['terca'],
			'quarta' => $row['quarta'],
			'quinta' => $row['quinta'],
			'sexta' => $row['sexta'],
			'sabado' => $row['sabado'],
			'domingo' => $row['domingo'],
			'ativo' => $row['ativo'],

		));
	}

	return $retorno;
}

/*
id int(11) AI PK
academia int(11)
nome varchar(70)
qtd_alunos int(11)
hora_inicio time
hora_fim time
livre tinyint(4)
segunda tinyint(4)
terca tinyint(4)
quarta tinyint(4)
quinta tinyint(4)
sexta tinyint(4)
sabado tinyint(4)
domingo tinyint(4)
ativo tinyint(4)
 */
