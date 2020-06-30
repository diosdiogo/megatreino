<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getProduto($conexao, $id)) . ']}';
	echo $lista;
}

function getProduto($conexao, $id) {
	$retorno = array();

	$sql = "select * from produto where academia={$id} and ativo='S' order by nome;";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'nome' => utf8_encode($row['nome']),
			'valor' => $row['valor'],
			'custo' => $row['custo'],
			'quantidade' => $row['quantidade'],
			'ativo' => utf8_encode($row['ativo']),
			'codBarras' => $row['codBarras'],
			'fornecedor' => $row['fornecedor'],
			'grupo' => $row['grupo'],
			'academia' => $row['academia'],
			'cod_interno' => $row['cod_interno'],
			'imagem' => utf8_encode($row['imagem']),

		));
	}

	return $retorno;
}

/*
idaluno int(11) AI PK
nome varchar(70)
genero varchar(10)
matricula bigint(20)
email varchar(60)
celular varchar(18)
data_nascimento date
ativo varchar(1)
academia int(11)
professor int(11)
senha varchar(45)
pacote varchar(60)
cod_atualiza bigint(20)
ncatraca int(11)
imagem varchar(255)
rg varchar(25)
cpf varchar(25)
endereco varchar(255)
bairro varchar(80)
cep varchar(10)
cidade varchar(80)
profissao varchar(60)
uf varchar(60)
estado_civil varchar(20)
nomepai varchar(80)
nomemae varchar(80)
observacao longtext
aluno_principal int(11)
status varchar(45)
aluno_colaborador tinyint(1)
 */
