<?php

include 'conecta.php';

if (isset($_GET['buscaAvaliacao'])) {
		$idavaliacao = $_GET['idavaliacao'];
		$lista = '{"result":[' . json_encode(buscaAvaliacao($conexao, $idavaliacao)) . ']}';
		echo $lista;
}

if (isset($_GET['buscaAvaliacaoSimplificada'])) {
		$idaluno = $_GET['idaluno'];
		$lista = '{"result":[' . json_encode(buscaAvaliacaoSimplificada($conexao, $idaluno)) . ']}';
		echo $lista;
}

function buscaAvaliacao($conexao, $idavaliacao) {
	$retorno = array();

	$sql = "select * from avaliacao_fisica AS avaliacao
				INNER JOIN diametro ON (avaliacao.diametro = diametro.iddiametro)
				INNER JOIN perimetro ON (avaliacao.perimetro = perimetro.idperimetro)
				INNER JOIN dobra_cutanea on (avaliacao.dobraCutanea = dobra_cutanea.iddobra_cutanea)
				INNER JOIN anamnese ON (avaliacao.anamnese = anamnese.idanamnese)
				LEFT OUTER JOIN teste_resistencia  ON (avaliacao.teste_resistencia = teste_resistencia.id)
				LEFT OUTER JOIN desvio_postural as desvio ON (avaliacao.desvio_postural = desvio.id)
				WHERE avaliacao.idavaliacao_fisica = {$idavaliacao}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idavaliacao_fisica' => $row['idavaliacao_fisica'],
			'aluno' => $row['aluno'],
			'dobraCutanea' => $row['dobraCutanea'],
			'diametro' => $row['diametro'],
			'perimetro' => $row['perimetro'],
			'resultado_gordura_ideal_minimo' => $row['resultado_gordura_ideal_minimo'],
			'r_gordura_ideal_max' => $row['resultado_gordura_ideal_maximo'],
			'resultado_gordura_ideal_maximo' => $row['resultado_gordura_atual'],
			'resultado_peso_gordo' => $row['resultado_peso_gordo'],
			'resultado_peso_magro' => $row['resultado_peso_magro'],
			'resultado_peso_ideal' => $row['resultado_peso_ideal'],
			'peso_magro_residual' => $row['peso_magro_residual'],
			'peso_magro_osseo' => $row['peso_magro_osseo'],
			'peso_magro_muscular' => $row['peso_magro_muscular'],
			'peso_magro_perc_muscular' => $row['peso_magro_perc_muscular'],
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
//			'idaluno' => $row['idaluno'],
//			'nome' => utf8_encode($row['nome']),
//			'genero' => utf8_encode($row['genero']),
//	Diametros
			'iddiametro' => $row['iddiametro'],
			'biestiloide' => $row['biestiloide'],
			'bimaleolar' => $row['bimaleolar'],
			'biepicondilo_umeral' => $row['biepicondilo_umeral'],
			'biepicondilo_femeral' => $row['biepicondilo_femeral'],
			'biacromial' => $row['biacromial'],
			'torax_antero_posterior' => $row['torax_antero_posterior'],
			'torax_transverso' => $row['torax_transverso'],
			'crista_iliaca' => $row['crista_iliaca'],
			'bitrocanterica' => $row['bitrocanterica'],
//	Perimetros
			'idperimetro' => $row['idperimetro'],
			'pescoco' => $row['pescoco'],
			'torax' => $row['torax'],
			'abdominal' => $row['abdominal'],
			'cintura' => $row['cintura'],
			'ombro' => $row['ombro'],
			'quadril' => $row['quadril'],
			'braco_direito_relaxado' => $row['braco_direito_relaxado'],
			'braco_esquerdo_relaxado' => $row['braco_esquerdo_relaxado'],
			'braco_direito_contraido' => $row['braco_direito_contraido'],
			'braco_esquerdo_contraido' => $row['braco_esquerdo_contraido'],
			'coxa_direita' => $row['coxa_direita'],
			'coxa_esquerda' => $row['coxa_esquerda'],
			'perna_direita' => $row['perna_direita'],
			'perna_esquerda' => $row['perna_esquerda'],
			'antebraco_direito' => $row['antebraco_direito'],
			'antebraco_esquerdo' => $row['antebraco_esquerdo'],
			'toraxInspirado' => $row['toraxInspirado'],
//	Dobra Cutanea
			'iddobra_cutanea' => $row['iddobra_cutanea'],
			'triceps_media' => $row['triceps_media'],
			'subescapular_media' => $row['subescapular_media'],
			'abdominal_media' => $row['abdominal_media'],
			'coxa_medial_media' => $row['coxa_medial_media'],
			'pantu_medial_media' => $row['pantu_medial_media'],
			'torax_media' => $row['torax_media'],
			'biceps_media' => $row['biceps_media'],
			'axiliar_media_media' => $row['axiliar_media_media'],
			'supra_iliaca_media' => $row['supra_iliaca_media'],
			'supra_espinal_media' => $row['supra_espinal_media'],
//Anamnese
			'idanamnese' => $row['idanamnese'],
			'meta' => utf8_encode($row['meta']),
			'praticaExercicios' => utf8_encode($row['praticaExercicios']),
			'praticaExercicios_quais' => utf8_encode($row['praticaExercicios_quais']),
			'praticaExercicios_frequencia' => $row['praticaExercicios_frequencia'],
			'habitoFumar' => utf8_encode($row['habitoFumar']),
			'habitoFumar_tempo' => $row['habitoFumar_tempo'],
			'habitoFumar_qtd_dia' => $row['habitoFumar_qtd_dia'],
			'restricoesAtividades' => utf8_encode($row['restricoesAtividades']),
			'utilizaMedicamento' => utf8_encode($row['utilizaMedicamento']),
			'senteDores' => utf8_encode($row['senteDores']),
			'sofreuAcidente' => utf8_encode($row['sofreuAcidente']),
			'estaemDieta' => utf8_encode($row['estaemDieta']),
			'possuiAlergia' => utf8_encode($row['possuiAlergia']),
//Observações			
			'observacoes' => utf8_encode($row['observacoes']),
//			'id' => $row['id'],
			'teste_abdominal' => $row['teste_abdominal'],
//
			'teste_flexao_braco' => $row['teste_flexao_braco'],
//
			'teste_sentar_alcancar' => $row['teste_sentar_alcancar'],
//	Desvio Postural (não será utilizado no momento)
//			'pescoco_dorsal' => $row['pescoco_dorsal'],
//			'pescoco_dorsal_obs' => $row['pescoco_dorsal_obs'],
//			'ombro_dorsal' => $row['ombro_dorsal'],
//			'ombro_dorsal_obs' => $row['ombro_dorsal_obs'],
//			'coluna_dorsal' => $row['coluna_dorsal'],
//			'coluna_dorsal_obs' => $row['coluna_dorsal_obs'],
//			'quadril_dorsal' => $row['quadril_dorsal'],
//			'quadril_dorsal_obs' => $row['quadril_dorsal_obs'],
//			'joelho_dorsal' => $row['joelho_dorsal'],
//			'joelho_dorsal_obs' => $row['joelho_dorsal_obs'],
//			'calcanhar_dorsal' => $row['calcanhar_dorsal'],
//			'calcanhar_dorsal_obs' => $row['calcanhar_dorsal_obs'],
//			'pe_dorsal' => $row['pe_dorsal'],
//			'pe_dorsal_obs' => $row['pe_dorsal_obs'],
//			'pescoco_lateral' => $row['pescoco_lateral'],
//			'pescoco_lateral_obs' => $row['pescoco_lateral_obs'],
//			'peitoral_lateral' => $row['peitoral_lateral'],
//			'peitoral_lateral_obs' => $row['peitoral_lateral_obs'],
//			'ombro_escapula_lateral' => $row['ombro_escapula_lateral'],
//			'ombro_escapula_lateral_obs' => $row['ombro_escapula_lateral_obs'],
//			'cifose_lateral' => $row['cifose_lateral'],
//			'cifose_lateral_obs' => $row['cifose_lateral_obs'],
//			'lordose_lateral' => $row['lordose_lateral'],
//			'lordose_lateral_obs' => $row['lordose_lateral_obs'],
//			'tronco_lateral' => $row['tronco_lateral'],
//			'tronco_lateral_obs' => $row['tronco_lateral_obs'],
//			'abdome_lateral' => $row['abdome_lateral'],
//			'abdome_lateral_obs' => $row['abdome_lateral_obs'],
//			'joelho_lateral' => $row['joelho_lateral'],
//			'joelho_lateral_obs' => $row['joelho_lateral_obs'],
//			'imagepath_1' => $row['imagepath_1'],
//			'imagepath_2' => $row['imagepath_2'],
//			'imagepath_3' => $row['imagepath_3'],
		));
	}

	return $retorno;
}


function buscaAvaliacaoSimplificada($conexao, $idaluno) {
	$retorno = array();

	$sql = "select * from avaliacao_fisica WHERE aluno = {$idaluno}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idavaliacao_fisica' => $row['idavaliacao_fisica'],
			'aluno' => $row['aluno'],
			'numero_avaliacao' => $row['numero_avaliacao'],
			'idade_atual' => $row['idade_atual'],
			'peso_atual' => $row['peso_atual'],
			'altura' => $row['altura'],
			'data' => $row['data'],
			'protocolo' => utf8_encode($row['protocolo']),

		));
	}

	return $retorno;
}

/*

 */
