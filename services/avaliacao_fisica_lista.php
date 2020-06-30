<?php

include 'conecta.php';

$state = $_GET["state"];
$idaluno = $_GET["idaluno"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getAlunos($conexao, $idaluno)) . ']}';
	echo $lista;
}

function getAlunos($conexao, $idaluno) {
	$retorno = array();

	$sql = "select * from avaliacao_fisica  AS avaliacao
INNER JOIN aluno ON (avaliacao.aluno = aluno.idAluno)
INNER JOIN diametro ON (avaliacao.diametro = diametro.iddiametro)
INNER JOIN perimetro ON (avaliacao.perimetro = perimetro.idperimetro)
INNER JOIN dobra_cutanea on (avaliacao.dobraCutanea = dobra_cutanea.iddobra_cutanea)
INNER JOIN anamnese ON (avaliacao.anamnese = anamnese.idanamnese)
LEFT OUTER JOIN teste_resistencia  ON (avaliacao.teste_resistencia = teste_resistencia.id)
LEFT OUTER JOIN desvio_postural as desvio ON (avaliacao.desvio_postural = desvio.id)
WHERE avaliacao.aluno = $idaluno order by numero_avaliacao desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idavaliacao_fisica' => $row['idavaliacao_fisica'],
			'aluno' => $row['aluno'],
			'dobraCutanea' => $row['dobraCutanea'],
			'diametro' => $row['diametro'],
			'perimetro' => $row['perimetro'],
			'r_gordura_ideal_min' => $row['resultado_gordura_ideal_minimo'],
			'r_gordura_ideal_max' => $row['resultado_gordura_ideal_maximo'],
			'r_gordura_ideal_atual' => $row['resultado_gordura_atual'],
			'r_peso_gordo' => $row['resultado_peso_gordo'],
			'r_peso_magro' => $row['resultado_peso_magro'],
			'r_peso_ideal' => $row['resultado_peso_ideal'],
			'p_magro_residual' => $row['peso_magro_residual'],
			'p_magro_osseo' => $row['peso_magro_osseo'],
			'p_magro_muscular' => $row['peso_magro_muscular'],
			'p_magro_perc_muscular' => $row['peso_magro_perc_muscular'],
			'numero_avaliacao' => $row['numero_avaliacao'],
			'idade_atual' => $row['idade_atual'],
			'peso_atual' => $row['peso_atual'],
			'altura' => $row['altura'],
			'imc' => $row['imc'],
			'data' => $row['data'],
			'resultado_imc' => utf8_encode($row['resultado_imc']),
			'protocolo' => utf8_encode($row['protocolo']),
			'observacao' => utf8_encode($row['observacao']),
			'anamnese' => $row['anamnese'],
			'teste_resistencia' => $row['teste_resistencia'],
			'desvio_postural' => $row['desvio_postural'],
			'resultadoPesoGorduraIdeal' => $row['resultadoPesoGorduraIdeal'],
			'resultadoPesoGorduraAtual' => $row['resultadoPesoGorduraAtual'],
			'valorCinturaQuadril' => $row['valorCinturaQuadril'],
			'resultadoValorCinturaQuadril' => $row['resultadoValorCinturaQuadril'],
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'genero' => utf8_encode($row['genero']),

		));
	}

	return $retorno;
}

/*

 */
