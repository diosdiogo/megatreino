<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$ativo = $_GET["ativo"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getAlunos($conexao, $id, $ativo)) . ']}';
	echo $lista;
}

function getAlunos($conexao, $id, $ativo) {
	$retorno = array();

	$sql = "select * from aluno where academia={$id} and deletado = 'N' and ativo = '{$ativo}' order by nome";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idaluno' => $row['idaluno'],
			'nome' => ($row['nome'],
			'genero' => $row['genero'],
			'matricula' => $row['matricula'],
			'email' => $row['email'],
			'celular' => $row['celular'],
			'data_nascimento' => $row['data_nascimento'],
			'ativo' => $row['ativo'],
			'academia' => $row['academia'],
			'professor' => $row['professor'],
			'senhaapp' => $row['senha'],
			'pacote' => $row['pacote'],
			'cod_atualiza' => $row['cod_atualiza'],
			'ncatraca' => $row['ncatraca'],
			'imagem' => $row['imagem'],
			'rg' => $row['rg'],
			'cpf' => $row['cpf'],
			'endereco' => $row['endereco'],
			'bairro' => $row['bairro'],
			'cep' => $row['cep'],
			'cidade' => $row['cidade'],
			'profissao' => $row['profissao'],
			'uf' => $row['uf'],
			'estado_civil' => $row['estado_civil'],
			'nomepai' => $row['nomepai'],
			'nomemae' => $row['nomemae'],
			'observacao' => $row['observacao'],
			'aluno_principal' => $row['aluno_principal'],
			'status' => $row['status'],
			'aluno_colaborador' => $row['aluno_colaborador'],

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
