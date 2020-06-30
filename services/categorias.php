<?php 

include 'conecta.php';


$lista=json_encode(getCategorias($conexao));
echo $lista;



function getCategorias($conexao){
	$retorno = array();
	$sql="select * from categorias";
  	
	$resultado = mysqli_query($conexao,$sql);
	while($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('codigo' => $row['codigo'],'nome' => utf8_encode($row['nome']),'grupo' => utf8_encode($row['grupo'])));			
	}

	return $retorno;
}

