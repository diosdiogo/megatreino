<?php 

include 'conecta.php';

$lista=json_encode(getDespesas($conexao));
echo $lista;

function getDespesas($conexao){
	$retorno = array();
	$sql="select * from despesas";
  	
	$resultado = mysqli_query($conexao,$sql);
	while($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('codigo' => $row['codigo'],
									'emissao' => $row['emissao'], 
									'descricao' => utf8_encode($row['descricao']),
									'valor' => $row['valor'],
									'vencimento' => $row['vencimento'], 
									'categoria' => utf8_encode($row['categoria']), 
									'status_cor' => $row['status_cor']));	
	}

	return $retorno;
}