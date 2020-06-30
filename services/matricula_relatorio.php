<?php

include 'conecta.php';
setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

if ($state == "C") {

	/*if ($dataI == 0 && $dataF == 0) {

	$lista = '{"result":[' . json_encode(getMatricula($conexao, $id)) . ']}';
	echo $lista;
	
	} else {*/


	$lista = '{"result":[' . json_encode(getMatriculaData($conexao, $id, $dataI, $dataF)) . ']}';
	echo $lista;
	
	

} 
	
/*function getMatricula($conexao, $id) {
	$retorno = array();

	$sql = "select matricula.id, matricula.aluno, matricula.modalidade,matricula.plano_pagamento,matricula.valor,matricula.data_inicio, matricula.vencimento, matricula.desconto, matricula.ativo, matricula.academia, matricula.data_fim, matricula.colaborador, matricula.ocorrencia, matricula.idassinatura_iugu, aluno.idaluno,aluno.nome, aluno.genero, aluno.matricula, aluno.email, aluno.celular, TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) as idade, aluno.ativo, aluno.professor, aluno.senha, aluno.pacote, aluno.cod_atualiza, aluno.ncatraca, aluno.imagem, aluno.rg, aluno.cpf, aluno.endereco, aluno.numero, aluno.bairro, aluno.cep, aluno.cidade, aluno.profissao, aluno.uf, aluno.estado_civil, aluno.nomepai, aluno.nomemae, aluno.observacao, aluno.aluno_principal, aluno.status, aluno.aluno_colaborador, aluno.bolsista, aluno.idiugu, aluno.deletado, aluno.consultor from matricula inner join aluno on (matricula.aluno  = aluno.idaluno) where matricula.academia={$id} and data_inicio >= cast('{$dataI}'  as date) and data_inicio <= cast('{$dataF}' as date) order by date(data_inicio) desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'aluno' => utf8_encode($row['aluno']), 
			'modalidade' => $row['modalidade'], 
			'plano_pagamento' => utf8_encode($row['plano_pagamento']), 
			'valor' => $row['valor'], 
			'data_inicio' => $row['data_inicio'], 
			'vencimento' => $row['vencimento'], 
			'desconto' => $row['desconto'], 
			'ativo' => $row['ativo'], 
			'academia' => $row['academia'], 
			'data_fim' => $row['data_fim'], 
			'colaborador' => $row['colaborador'], 
			'ocorrencia' => $row['ocorrencia'], 
			'idassinatura_iugu' => $row['idassinatura_iugu'], 
			'status' => $row['status'],
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'genero' => utf8_encode($row['genero']),
			'matricula' => $row['matricula'],
			'email' => utf8_encode($row['email']),
			'celular' => utf8_encode($row['celular']),
			'idade' => $row['idade'],
			'ativo' => utf8_encode($row['ativo']),
			'academia' => $row['academia'],
			'professor' => $row['professor'],
			'senhaapp' => utf8_encode($row['senha']),
			'pacote' => utf8_encode($row['pacote']),
			'cod_atualiza' => $row['cod_atualiza'],
			'ncatraca' => $row['ncatraca'],
			'imagem' => utf8_encode($row['imagem']),
			'rg' => utf8_encode($row['rg']),
			'cpf' => utf8_encode($row['cpf']),
			'endereco' => utf8_encode($row['endereco']),
			'bairro' => utf8_encode($row['bairro']),
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
			'bolsista' => $row['bolsista'],
			'idiugu' => $row['idiugu'],
			'deletado' => utf8_encode($row['deletado']),
			'colsultor' => $row['consultor']));
	}

	return $retorno;
}*/

function getMatriculaData($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select matricula.id, 
				   matricula.aluno, 
				   matricula.modalidade,
				   matricula.plano_pagamento,
				   matricula.valor,
				   matricula.data_inicio, 
				   matricula.vencimento, 
				   matricula.desconto, 
				   matricula.ativo, 
				   matricula.academia, 
				   matricula.data_fim, 
				   matricula.colaborador, 
				   matricula.ocorrencia, 
				   matricula.idassinatura_iugu, 
				   aluno.idaluno,aluno.nome, 
				   aluno.genero, 
				   aluno.matricula, 
				   aluno.email, 
				   aluno.celular, 
				   TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) as idade, 
				   aluno.ativo, 
				   aluno.professor, 
				   aluno.senha, 
				   aluno.pacote, 
				   aluno.cod_atualiza, 
				   aluno.ncatraca, 
				   aluno.imagem, 
				   aluno.rg, 
				   aluno.cpf, 
				   aluno.endereco, 
				   aluno.numero, 
				   aluno.bairro, 
				   aluno.cep, 
				   aluno.cidade, 
				   aluno.profissao, 
				   aluno.uf, 
				   aluno.estado_civil, 
				   aluno.nomepai, 
				   aluno.nomemae, 
				   aluno.observacao, 
				   aluno.aluno_principal, 
				   aluno.status, 
				   aluno.aluno_colaborador, 
				   aluno.bolsista, 
				   aluno.idiugu, 
				   aluno.deletado, 
				   aluno.consultor 
			from matricula inner join aluno on (matricula.aluno = aluno.idaluno) 
			where matricula.academia={$id} 
			and data_inicio >= cast('{$dataI}' as date) 
			and data_inicio <= cast('{$dataF}' as date) 
			order by date(data_inicio) desc";

	//echo $sql;

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'aluno' => utf8_encode($row['aluno']), 
			'modalidade' => $row['modalidade'], 
			'plano_pagamento' => utf8_encode($row['plano_pagamento']), 
			'valor' => $row['valor'], 
			'data_inicio' => $row['data_inicio'], 
			'vencimento' => $row['vencimento'], 
			'desconto' => $row['desconto'], 
			'ativo' => $row['ativo'], 
			'academia' => $row['academia'], 
			'data_fim' => $row['data_fim'], 
			'colaborador' => $row['colaborador'], 
			'ocorrencia' => $row['ocorrencia'], 
			'idassinatura_iugu' => $row['idassinatura_iugu'], 
			'status' => $row['status'],
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'genero' => utf8_encode($row['genero']),
			'matricula' => $row['matricula'],
			'email' => utf8_encode($row['email']),
			'celular' => utf8_encode($row['celular']),
			'idade' => $row['idade'],
			'ativo' => utf8_encode($row['ativo']),
			'academia' => $row['academia'],
			'professor' => $row['professor'],
			'senhaapp' => utf8_encode($row['senha']),
			'pacote' => utf8_encode($row['pacote']),
			'cod_atualiza' => $row['cod_atualiza'],
			'ncatraca' => $row['ncatraca'],
			'imagem' => utf8_encode($row['imagem']),
			'rg' => utf8_encode($row['rg']),
			'cpf' => utf8_encode($row['cpf']),
			'endereco' => utf8_encode($row['endereco']),
			'bairro' => utf8_encode($row['bairro']),
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
			'bolsista' => $row['bolsista'],
			'idiugu' => $row['idiugu'],
			'deletado' => utf8_encode($row['deletado']),
			'colsultor' => $row['consultor']));
	}

	return $retorno;
}