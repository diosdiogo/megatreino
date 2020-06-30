<?php

include 'conecta.php';

$idaluno = $_GET["idaluno"];

	$lista = '{"result":[' . json_encode(getTreinoAlunos($conexao, $idaluno)) . ']}';
	echo $lista;


function getTreinoAlunos($conexao, $idaluno) {
	$today = date("Y-m-d"); 
	$retorno = array();

	$sql = "select * from aluno AS al LEFT JOIN treinoPadraoAluno AS tr on al.idaluno = tr.aluno and tr.treinoAtual = 'S' 
LEFT JOIN colaborador AS col ON al.professor = col.idcolaborador where  al.idaluno ={$idaluno}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'idaluno' => $row['idaluno'],
			'nome' => utf8_encode($row['nome']),
			'genero' => utf8_encode($row['genero']),
			'matricula' => $row['matricula'],
			'email' => utf8_encode($row['email']),
			'celular' => utf8_encode($row['celular']),
			'data_nascimento' => $row['data_nascimento'],
			'ativo' => utf8_encode($row['ativo']),
			'academia' => $row['academia'],
			'professor' => utf8_encode($row['professor']),
			'senhaapp' => utf8_encode($row['senha']),
			'pacote' => utf8_encode($row['pacote']),
			'cod_atualiza' => $row['cod_atualiza'],
			'ncatraca' => $row['ncatraca'],
			'imagem' => utf8_encode($row['imagem']),
			'rg' => utf8_encode($row['rg']),
			'cpf' => utf8_encode($row['cpf']),
			'endereco' => utf8_encode($row['endereco']),
			'bairro' => utf8_encode($row['bairro']),
			'numero' => $row['numero'],
			'cep' => utf8_encode($row['cep']),
			'cidade' => utf8_encode($row['cidade']),
			'profissao' => utf8_encode($row['profissao']),
			'uf' => utf8_encode($row['uf']),
			'estado_civil' => utf8_encode($row['estado_civil']),
			'nomepai' => utf8_encode($row['nomepai']),
			'nomemae' => utf8_encode($row['nomemae']),
			'observacao' => utf8_encode($row['observacao']),
			'aluno_principal' => $row['aluno_principal'],
			'status' => utf8_encode($row['status']),
			'aluno_colaborador' => $row['aluno_colaborador'],
			'consultor' => $row['consultor'],
			'deletado' => $row['deletado'],
			'idtreinoPadraoAluno' => $row['idtreinoPadraoAluno'],
			'dataInicio'=>$row['dataInicio'],
			'quantidadeSemanas'=> $row['quantidadeSemanas'],
			
			'quantidadeSessoes'=> $row['quantidadeSessoes'],
			'nomeTreino'=>$row['nomeTreino'],
			'nivelTreino'=>$row['nivelTreino'],
			'velocidade'=>$row['velocidade'],
			'tipoTreino'=>$row['tipoTreino'],
			'avaliacoes'=>$row['avaliacoes'],
			'aluno'=>$row['aluno'],
			'treinoAtual'=>$row['treinoAtual'],
			'idcolaborador'=>$row['idcolaborador'],
			'nomeColaborados'=>$row['nome'],
			'emailColaborador'=>$row['email'],
			'perfil'=> $row['perfil']));
	}
//echo $sql;
	return $retorno;
}

function calculaData(){
	$retorno = array();
	$nova_data = date('Y-m-d', strtotime('+'.$dias.' days', strtotime($data)));
	return $nova_data;
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
