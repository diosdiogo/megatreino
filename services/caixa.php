<?php

include 'conecta.php';


//ini_set("memory_limit","256M");

	$lista = '{"result":[' . json_encode(getMovimento_caixa_fechado($conexao)) . ']}';
	echo $lista;


function getMovimento_caixa_fechado($conexao) {
	
	$retorno = array();

	$sql = "select * from movimento_caixa_fechado where academia=2 and numfechamento=1";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push ($retorno, array( 'id' => $row['id'], 'documento' => $row['documento'],'desc_documento' => utf8_encode($row['desc_documento']),'emissao' => $row['emissao'],'historico' => $row['historico'],'forma_pagamento' => $row['forma_pagamento'],'nome' => utf8_encode($row['nome']),'observacao' => utf8_encode($row['observacao']),'valor' => $row['valor'],'banco' => $row['banco'],'cancelado' => utf8_encode($row['cancelado']),'manual' => utf8_encode($row['manual']),'colaborador' => $row['colaborador'],'numfechamento' => $row['numfechamento'],'academia' => $row['academia'],'fechamento' => $row['fechamento'],'ocorrencia' => $row['ocorrencia'],'deletado' => utf8_encode($row['deletado'])));
	}

	return $retorno;
}
