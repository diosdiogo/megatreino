<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];


if ($state == "C") {
	$lista = '{"result":[' . json_encode(getVendas($conexao, $id)) . ']}';
	echo $lista;
}

function getVendas($conexao, $id) {
	$retorno = array();

	$sql = "select venda_item.id, venda_item.produto, venda_item.venda, venda_item.quantidade, venda_item.emissao, venda_item.academia, produto.nome as descricao, venda.total, venda.aluno, aluno.nome, aluno.matricula from venda_item inner join produto on (venda_item.produto = produto.id) inner join venda on (venda_item.venda = venda.id) inner join aluno on (venda.aluno = aluno.idaluno) where venda_item.academia={$id} ";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'produto' => $row['produto'], 'venda' => $row['venda'], 'quantidade' => $row['quantidade'], 'emissao' => $row['emissao'], 'academia' => $row['academia'], 'descricao' =>utf8_encode($row['descricao']), 'total' => $row['total'], 'aluno' => $row['aluno'], 'nome' => utf8_encode($row['nome']), 'matricula' => $row['matricula']));
	}

	return $retorno;
}