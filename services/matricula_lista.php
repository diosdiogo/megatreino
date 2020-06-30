
<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$idaluno = $_GET["idaluno"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getMatricula($conexao, $id, $idaluno)) . ']}';
	echo $lista;
}

function getMatricula($conexao, $id, $idaluno) {
	$retorno = array();

	$sql = "select matricula.id, matricula.aluno, matricula.modalidade, modalidade.nome,matricula.plano_pagamento,matricula.desconto,
matricula.valor,matricula.data_inicio,matricula.data_fim,matricula.vencimento,matricula.ativo,matricula.academia,matricula.colaborador,matricula.status
from matricula inner join modalidade on (matricula.modalidade=modalidade.id)
where matricula.academia={$id} and matricula.aluno={$idaluno} order by nome";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'aluno' => $row['aluno'],
			'modalidade' => $row['modalidade'],
			'nome' => utf8_encode(strtoupper($row['nome'])),
			'plano_pag' => utf8_encode(strtoupper($row['plano_pagamento'])),
			'desconto' => $row['desconto'],
			'valor' => $row['valor'],
			'data_inicio' => $row['data_inicio'],
			'vencimento' => $row['vencimento'],
			'ativo' => $row['ativo'],
			'academia' => $row['academia'],
			'data_fim' => $row['data_fim'],
			'colaborador' => $row['colaborador'],
			'status' =>  utf8_encode($row['status']),

		));
	}

	return $retorno;
}

/*
id int(11) AI PK
aluno int(11)
modalidade int(11)
plano_pagamento varchar(45)
valor varchar(45)
data_inicio date
vencimento int(11)
desconto decimal(10,2)
ativo tinyint(1)
academia int(11)
data_fim date
colaborador int(11)
ocorrencia bigint(20)

 */
