<?php
include 'conecta.php';
//include 'alteraAlunoCodAtualiza.php';
//include 'editarExercicio.php';

$cod_atualiza = rand(10000, 9999999);

if (isset($_GET['idaluno'])) {
	$idaluno = $_GET['idaluno'];
	if (isset($_GET['exerciciosTreino'])) {
		$lista = '{"result":[' . json_encode(todosTreinosAluno($conexao, $idaluno)) . ']}';
		echo $lista;
	}

}

if (isset($_GET['treinoPadrao'])) {
	$id = $_GET['idtreinoPadrao'];
	$lista = '{"result":[' . json_encode(treinoPadrao($conexao, $id)) . ']}';
	echo $lista;
}

if (isset($_GET['treinoPadraoAluno'])) {
	$id = $_GET['idtreinoPadrao'];
	$lista = '{"result":[' . json_encode(treinoPadraoAluno($conexao, $id)) . ']}';
	echo $lista;
}

if (isset($_GET['exercicioDoAluno'])) {
	$id = $_GET['treinoAlunoEx'];
	$lista = '{"result":[' . json_encode(exercicioDoAluno($conexao, $id)) . ']}';
	echo $lista;
}

//alteraAlunoCodAtualiza($conexao, $idaluno, $cod_atualiza);

function exercicioDoAluno($conexao, $id) {
	$categoria = array();

	$sql = "SELECT tr.idtreino, tr.tipo,tr.nome, tr.ordem as ordemTreino, tr.treinoPadrao,
 exTr.idexercicioTreino, exTr.treino, exTr.exercicio, exTr.series, exTr.repeticoes, exTr.tipo_repeticoes, exTr.carga,
 exTr.descanso, exTr.dica,
 ex.idexercicio, ex.nome as nomeEx, ex.grupoMuscular, ex.academia, ex.caminhoVideo
 from treino as tr inner join exerciciotreino as exTr
 on(tr.idtreino = exTr.treino) inner JOIN exercicio as ex
 on(exTr.exercicio = ex.idexercicio) where tr.idtreino = $id order by exTr.ordem";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idexercicioTreinoAluno' => $row['idexercicioTreino'],
			'treinoAluno' => $row['treino'],
			'exercicio' => $row['exercicio'],
			'series' => $row['series'],
			'repeticoes' => $row['repeticoes'],
			'tipo_repeticoes' => utf8_encode($row['tipo_repeticoes']),
			'carga' => $row['carga'],
			'descanso' => $row['descanso'],
			'dica' => utf8_encode($row['dica']),
			'idexercicio' => $row['idexercicio'],
			'nomeEX' => utf8_encode($row['nomeEx']),
			'grupoMuscular' => utf8_encode($row['grupoMuscular']),
			'academia' => $row['academia'],
			'caminhoVideo' => utf8_encode($row['caminhoVideo']),
			'idtreinoAluno' => $row['idtreino'],
			'treinoPadraoAluno' => $row['treinoPadrao'],

		));
	}

	//echo ("query = " . $sql . "<br>");

	return $categoria;
}

function removerTreino($conexao, $id) {

	$query = "delete from treinoAluno where idtreinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao)) . "<br>sql: " . $query . "<br>";

	return $query;
}

function apagarTodosTreinoAluno($conexao, $id) {

	$query = "delete from treinoAluno where treinoPadraoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao)) . "<br>sql: " . $query . "<br>";

	return $query;
}

function exercicioDoAlunoEdicao($conexao, $id) {
	$categoria = array();

	$resultado = mysqli_query($conexao, " select tr.idtreinoAluno, tr.tipo,tr.nome, tr.ordem as ordemTreino,tr.treinoPadraoAluno,tr.aluno,tr.academia,tr.concluido,
 exTr.idexercicioTreinoAluno,exTr.treinoAluno, exTr.exercicio,exTr.series, exTr.repeticoes, exTr.tipo_repeticoes, exTr.carga,
 exTr.descanso, exTr.dica, exTr.grupo, exTr.ordem as ordemExerc,
 ex.idexercicio, ex.nome as nomeEx,ex.grupoMuscular, ex.academia, ex.caminhoVideo
 from treinoAluno as tr left join exercicioTreinoAluno as exTr
 on(tr.idtreinoAluno = exTr.treinoAluno) left JOIN exercicio as ex
 on(exTr.exercicio = ex.idexercicio) where tr.treinoPadraoAluno = {$id} order by exTr.ordem;");

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($categoria, array(
			'idexercicioTreinoAluno' => $row['idexercicioTreinoAluno'],
			'treinoAluno' => $row['treinoAluno'],
			'exercicio' => $row['exercicio'],
			'series' => $row['series'],
			'repeticoes' => $row['repeticoes'],
			'tipo_repeticoes' => utf8_encode($row['tipo_repeticoes']),
			'carga' => $row['carga'],
			'descanso' => $row['descanso'],
			'dica' => utf8_encode($row['dica']),
			'grupo' => utf8_encode($row['grupo']),
			'ordemExerc' => $row['ordemExerc'],
			'idexercicio' => $row['idexercicio'],
			'nomeEX' => utf8_encode($row['nomeEx']),
			'grupoMuscular' => utf8_encode($row['grupoMuscular']),
			'academia' => $row['academia'],
			'caminhoVideo' => utf8_encode($row['caminhoVideo']),
			'idtreinoAluno' => $row['idtreinoAluno'],
			'nome' => utf8_encode($row['nome']),
			'tipo' => utf8_encode($row['tipo']),
			'ordemtreino' => $row['ordemTreino'],
			'treinoPadraoAluno' => $row['treinoPadraoAluno'],

		));
	}
	//echo ("<br>ID inserido = " .$categoria."<br>");
	return $categoria;
}

//novo 15/04/2020

function treinoPadrao($conexao, $id) {
	$treino = array();
	$sql = "select * from treinoPadrao where idtreinoPadrao=$id";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($treino, array(
			'idtreinoPadrao' => $row['idtreinoPadrao'],
			'nome' => utf8_encode($row['nome']),
			'quantidadeSemanas' => $row['quantidadeSemanas'],
			'quantidadeSessoes' => $row['quantidadeSessoes'],
			'velocidadeMovimento' => utf8_encode($row['velocidadeMovimento']),
			'descricao' => $row['descricao'],
			'categoria' => utf8_encode($row['categoria']),
			'dataInicio' => $row['dataInicio'],
			'tipoTreino' => $row['tipoTreino'],
			'academia' => $row['academia'],
		));
	}
	return $treino;
}

function treinoPadraoAluno($conexao, $id) {
	$treino = array();
	$sql = "select * from treino where treinoPadrao = $id";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($treino, array(
			'idtreinoAluno' => $row['idtreino'],
			'tipo' => utf8_encode($row['tipo']),
			'nome' => utf8_encode($row['nome']),
			'ordem' => $row['ordem'],
			'treinoPadraoAluno' => $row['treinoPadrao'],

		));
	}
	return $treino;
}

//---------//
function idtreinoPadraoAluno($conexao, $id) {
	//$categoria = array();
	$sql = "select idtreinoPadraoAluno from treinoPadraoAluno where idtreinoPadraoAluno = $id";
	$resultado = mysqli_query($conexao, $sql);

	$row = mysqli_fetch_assoc($resultado);
	//echo $sql;

	return $row;
}
function apagarExercTreino($conexao, $id) {

	$query = "delete from exercicioTreinoAluno where treinoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao)) . "<br>sql: " . $query . "<br>";

	return $query;
}

function apagarTreinoPadrao($conexao, $id) {

	$query = "delete from treinoPadraoAluno where idtreinoPadraoAluno = {$id};";
	$inserir = mysqli_query($conexao, $query);

	echo ("<br>Linha alterada = <br>" . mysqli_affected_rows($conexao)) . "<br>sql: " . $query . "<br>";

	return $query;
}