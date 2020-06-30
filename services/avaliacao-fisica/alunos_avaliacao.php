<?php

include 'conecta.php';


if (isset($_GET['dadosAluno'])) {
		$id = $_GET['idaluno'];
		$lista = '{"result":[' . json_encode(dadosAluno($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['dadosAlunoSimplificado'])) {
		$id = $_GET['idacademia'];
		$lista = '{"result":[' . json_encode(dadosAlunoSimplificado($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['AlunoCliente'])) {
		$id = $_GET['idacademia'];
		$lista = '{"result":[' . json_encode(AlunoCliente($conexao, $id)) . ']}';
		echo $lista;
}

if (isset($_GET['dadosAlunoAcademia'])) {
		$id = $_GET['idacademia'];
		$lista = '{"result":[' . json_encode(dadosAluno($conexao, $id)) . ']}';
		echo $lista;
}

function dadosAluno($conexao, $id) {
	$retorno = array();

	$sql = "select * from aluno where idaluno={$id};";

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
			'pacote' => utf8_encode($row['pacote']),
			'cod_atualiza' => $row['cod_atualiza'],
			'imagem' => utf8_encode($row['imagem']),
			'rg' => utf8_encode($row['rg']),
			'cpf' => utf8_encode($row['cpf']),
			'endereco' => utf8_encode($row['endereco']),
			'bairro' => utf8_encode($row['bairro']),
			'numero' => $row['numero'],
			'cep' => utf8_encode($row['cep']),
			'cidade' => utf8_encode($row['cidade']),
			'profissao' => utf8_encode($row['profissao']),
			'uf' => utf8_encode($row['uf']),
			'observacao' => utf8_encode($row['observacao']),
			'aluno_principal' => $row['aluno_principal'],
			'status' => utf8_encode($row['status']),

		));
	}

	return $retorno;
}

function dadosAlunoSimplificado($conexao, $id) {
	$retorno = array();

	$sql = "select idaluno, nome, genero, data_nascimento, matricula, email, celular from aluno where academia={$id} order by nome;";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'genero' => utf8_encode($row['genero']),
			'matricula' => $row['matricula'],
			'data_nascimento' => calcularIdade($row['data_nascimento']),
			'email' => utf8_encode($row['email']),
			'celular' => utf8_encode($row['celular']),			
		));
	}

	return $retorno;
}

function AlunoCliente($conexao, $id) {
	$retorno = array();

	$sql = "select nome from aluno where academia={$id} order by nome;";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'nome' => utf8_encode($row['nome']),
		));
	}

	return $retorno;
}

function calcularIdade($data) {
	//Data atual
	$dia = date('d');
	$mes = date('m');
	$ano = date('Y');

	$nascimento = explode('-', $data);
	$dianasc = ($nascimento[2]);
	$mesnasc = ($nascimento[1]);
	$anonasc = ($nascimento[0]);

	//Calculando sua idade
	$idade = $ano - $anonasc; // simples, ano- nascimento!
	if ($mes < $mesnasc) // se o mes é menor, só subtrair da idade
	{
		$idade--;
		return $idade;
	} elseif ($mes == $mesnasc && $dia <= $dianasc) // se esta no mes do aniversario mas não passou ou chegou a data, subtrai da idade
	{
		$idade--;
		return $idade;
	} else // ja fez aniversario no ano, tudo certo!
	{
		return $idade;
	}
}



function dadosAlunoAcademia($conexao, $id) {
	$retorno = array();

	$sql = "select * from aluno where academia={$id} order by nome;";

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
			'pacote' => utf8_encode($row['pacote']),
			'cod_atualiza' => $row['cod_atualiza'],
			'imagem' => utf8_encode($row['imagem']),
			'rg' => utf8_encode($row['rg']),
			'cpf' => utf8_encode($row['cpf']),
			'endereco' => utf8_encode($row['endereco']),
			'bairro' => utf8_encode($row['bairro']),
			'numero' => $row['numero'],
			'cep' => utf8_encode($row['cep']),
			'cidade' => utf8_encode($row['cidade']),
			'profissao' => utf8_encode($row['profissao']),
			'uf' => utf8_encode($row['uf']),
			'observacao' => utf8_encode($row['observacao']),
			'aluno_principal' => $row['aluno_principal'],
			'status' => utf8_encode($row['status']),

		));
	}

	return $retorno;
}