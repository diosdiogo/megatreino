<?php
include 'conecta.php';

function treino($conexao, $id) {
	$treinos = array();

	$sql = "SELECT al.idaluno,
al.nome,
al.matricula,
al.genero,
al.ativo,
al.academia,
tr.idtreinoPadraoAluno,
tr.dataInicio,
tr.quantidadeSemanas,
tr.quantidadeSessoes,
tr.nomeTreino,
tr.nivelTreino,
tr.velocidade,
tr.tipoTreino,
tr.avaliacoes,
tr.treinoAtual,
col.nome as nomeProf
 FROM aluno AS al
LEFT JOIN
treinoPadraoAluno
AS tr on
al.idaluno = tr.aluno and tr.treinoAtual = 'S'
LEFT JOIN colaborador
AS col ON al.professor = col.idcolaborador where  al.idaluno =  {$id}";

	$resultado = mysqli_query($conexao, $sql);

	$treinos = mysqli_fetch_assoc($resultado);

	//echo $sql;
	return $treinos;
}

function treinoPadraoAluno($conexao, $id) {
	$treinos = array();

	$query = ("SELECT * FROM treinoAluno AS ta INNER JOIN
treinoPadraoAluno AS tpa  ON (ta.treinoPadraoAluno = tpa.idtreinoPadraoAluno) WHERE ta.treinoPadraoAluno = {$id} AND tpa.treinoAtual = 'S' ORDER BY ta.ordem;");

	$resultado = mysqli_query($conexao, $query);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($treinos, array(
			'idtreinoAluno' => $row['idtreinoAluno'],
			'tipo' => utf8_encode($row['tipo']),
			'nome' => $row['nome'],
			'ordem' => utf8_encode($row['ordem']),
			'treinoPadraoAluno' => $row['treinoPadraoAluno'],
			'concluido' => utf8_encode($row['concluido']),
			'dataInicio' => $row['dataInicio'],
			'quantidadeSemanas' => $row['quantidadeSemanas'],
			'quantidadeSessoes' => $row['quantidadeSessoes'],
			'nomeTreino' => utf8_encode($row['nomeTreino']),
			'nivelTreino' => utf8_encode($row['nivelTreino']),
			'velocidade' => utf8_encode($row['velocidade']),
			'tipoTreino' => utf8_encode($row['tipoTreino']),
			'avaliacoes' => utf8_encode($row['avaliacoes']),
			'treinoAtual' => utf8_encode($row['treinoAtual']),

		));
	}

	return $treinos;
}

function treinoPadraoAlunoQTS($conexao, $id) {

	$resultado = mysqli_query($conexao, "SELECT * FROM treinoAluno AS ta INNER JOIN
treinoPadraoAluno AS tpa  ON (ta.treinoPadraoAluno = tpa.idtreinoPadraoAluno) WHERE ta.treinoPadraoAluno = {$id} AND tpa.treinoAtual = 'S' ORDER BY ta.ordem;");

	return mysqli_num_rows($resultado);
}
function todosTreinosAluno($conexao, $id) {
	$treinos = array();

	$resultado = mysqli_query($conexao, "SELECT * FROM treinoPadraoAluno WHERE aluno = {$id} ORDER BY idtreinoPadraoAluno DESC, treinoAtual ASC;");

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($treinos, array(
			'idtreinoPadraoAluno' => $row['idtreinoPadraoAluno'],
			'dataInicio' => $row['dataInicio'],
			'quantidadeSemanas' => $row['quantidadeSemanas'],
			'quantidadeSessoes' => $row['quantidadeSessoes'],
			'nomeTreino' => utf8_encode($row['nomeTreino']),
			'nivelTreino' => utf8_encode($row['nivelTreino']),
			'velocidade' => utf8_encode($row['velocidade']),
			'tipoTreino' => utf8_encode($row['tipoTreino']),
			'aluno' => utf8_encode($row['aluno']),
			'treinoAtual' => utf8_encode($row['treinoAtual']),

		));
	}

	return $treinos;
}

function utimoTrino($conexao) {
	$treinos = array();

	$resultado = mysqli_query($conexao, "SELECT max(idtreinoPadraoAluno)+1 as ultimoTreino FROM treinopadraoaluno;");

	$treinos = mysqli_fetch_assoc($resultado);

	return $treinos;
}

function exercicioAluno($conexao, $id) {
	$treinos = array();

	$query = ("SELECT exTr.idexercicioTreinoAluno, exTr.treinoAluno, exTr.exercicio,  exTr.series, exTr.repeticoes, exTr.carga,  exTr.descanso,  exTr.dica,exTr.grupo, exer.nome,exer.caminhoVideo, exer.academia FROM exercicioTreinoAluno AS exTr INNER JOIN exercicio AS exer on exTr.exercicio = exer.idexercicio  WHERE exTr.treinoAluno = {$id} GROUP BY exTr.idexercicioTreinoAluno ORDER BY exTr.ordem ASC;");

	$resultado = mysqli_query($conexao, $query);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($treinos, array(
			'idexercicioTreinoAluno' => $row['idexercicioTreinoAluno'],
			'treinoAluno' => $row['treinoAluno'],
			'series' => $row['series'],
			'repeticoes' => $row['repeticoes'],
			'carga' => $row['carga'],
			'descanso' => $row['descanso'],
			'dica' => utf8_encode($row['dica']),
			'grupo' => $row['grupo'],
			'nome' => utf8_encode($row['nome']),
			'caminhoVideo' => utf8_encode($row['caminhoVideo']),
			'academia' => $row['academia'],

		));
	}

	return $treinos;
}

function tipo_repeticoes($conexao) {
	$treinos = array();

	$resultado = mysqli_query($conexao, "select * from tipo_repeticoes;");

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($treinos, array(
			'id' => $row['id'],
			'nome' => $row['nome'],
		));
	}

	return $treinos;
}
