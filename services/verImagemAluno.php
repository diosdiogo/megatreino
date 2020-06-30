<?php

include 'conecta.php';

$id = $_GET["id"];
$academia = $_GET['academia'];

$lista = '{"result":[' . json_encode(getTotal_Agenda_mes($conexao, $id, $academia)) . ']}';
echo $lista;

function getTotal_Agenda_mes($conexao, $id, $academia) {

	$retorno = array();

	$sql = "SELECT * FROM academia.aluno where academia=$academia and idaluno=$id;";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'genero' => utf8_encode($row['genero']),
			'matricula' => $row['matricula'],
			'email' => utf8_encode($row['email']),
			'celular' => utf8_encode($row['celular']),
			'data_nascimento' => $row['data_nascimento'],
			'ativo' => utf8_encode($row['ativo']),
			'academia' => $row['academia'],
			'professor' => utf8_encode($row['professor']),
			'senhaapp' => utf8_encode($row['senha']),
			'pacote' => utf8_encode($row['pacote']),
			'cod_atualiza' => $row['cod_atualiza'],
			'ncatraca' => $row['ncatraca'],
			'caminhoimagem' => utf8_encode($row['imagem']),
			'rg' => utf8_encode($row['rg']),
			'cpf' => utf8_encode($row['cpf']),
			'endereco' => utf8_encode($row['endereco']),
			'bairro' => utf8_encode($row['bairro']),
			'numero' => $row['numero'],
			'cep' => utf8_encode($row['cep']),
			'cidade' => utf8_encode($row['cidade']),
			'profissao' => utf8_encode($row['profissao']),
			'uf' => utf8_encode($row['uf']),
			'estado_civil' => utf8_encode($row['estado_civil']),
			'nomepai' => utf8_encode($row['nomepai']),
			'nomemae' => utf8_encode($row['nomemae']),
			'observacao' => utf8_encode($row['observacao']),
			'aluno_principal' => $row['aluno_principal'],
			'status' => utf8_encode($row['status']),
			'aluno_colaborador' => $row['aluno_colaborador'],
			'consultor' => $row['consultor'],

		));
	}

	return $retorno;
}
