
<?php

include 'conecta.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);

if (isset($_GET["idmatricula"])) {

	$idmatricula = $_GET["idmatricula"];
	//print("ID: " . $idmatricula . "<br>");
}

if (isset($_GET["idaluno"])) {
	$idaluno = $_GET["idaluno"];
	//print("Aluno ID: " . $idaluno . "<br>");
}

if (isset($_GET["academia"])) {
	$idacademia = $_GET["academia"];
	//print("Academia: " . $idacademia . "<br>");
}

if (isset($_GET["modalidade"])) {
	$modalidade = $_GET["modalidade"];
	//print("ID modalidade: " . $modalidade . "<br>");

	$modnome = modalidade($conexao, $idacademia, $modalidade);
	//print("Modalidade nome : " . $modnome['nome'] . "<br>");
}

if (isset($_GET["plano_pagamento"])) {
	$plano = $_GET["plano_pagamento"];
	//print("ID Plano: " . $plano . "<br>");
}
if (isset($_GET["pagor"])) {
	$pago = $_GET["pagor"];
}

$pagar = 'R';

if (isset($_GET["dataI"])) {
	$dataInicio = $_GET["dataI"];
	//print("Data Inicio: " . $dataInicio . "<br>");

}

if (isset($_GET["vencimento"])) {
	$vencimento = $_GET["vencimento"];
	//print("Vencimento: " . $vencimento . "<br>");

}

if (isset($_GET["ativor"])) {
	$ativo = $_GET["ativor"];
}

if (isset($_GET["idcolaborador"])) {
	$idcolaborador = $_GET["idcolaborador"];
	//print("Colaborador ID: " . $idcolaborador . "<br>");
}

if (isset($_GET["ocorrencia"])) {
	$ocorrencia = $_GET["ocorrencia"];
	//print("Ocorrencia: " . $ocorrencia . "<br>");
}

if (isset($_GET["documento"])) {
	$documento = $_GET["documento"];
	//print("Documento: " . $documento . "<br>");
}

if (isset($_GET["desconto"])) {
	$descontoRecebe = $_GET["desconto"];
	$desconto = str_replace('R', '', $descontoRecebe);
	$desconto = str_replace('$', '', $desconto);
	$desconto = str_replace(',', '.', $desconto);
	//print("Desconto: " . $desconto . "<br>");

	if ($desconto == 0) {
		$historico = $modnome['nome'] . " - " . $plano;
	}

	if ($desconto == 'undefined') {
		$desconto = 0;
		$historico = $modnome['nome'] . " - " . $plano;
	}

	else{
		$historico = $modnome['nome'] . " - " . $plano . " - Desconto: R$" . $desconto;
	}

	
}else{$desconto=0;}



if (isset($_GET["plano_pagamento"]) && isset($_GET["modalidade"])) {

	$dados = renovadata($conexao, $modalidade, $plano, $idacademia);
	$dataFim = $dataInicio;

	if ($dados['mes_dia'] == "M") {

		$dataFim = date('Y-m-d', strtotime('+' . $dados['quantidade'] . 'months', strtotime($dataFim)));

	} else {
		$dataFim = date('Y-m-d', strtotime('+' . $dados['quantidade'] . 'days', strtotime($dataFim)));

	}

	//echo '<br>Mes Dia: ' . $dados['mes_dia'] . '<br>';
	//echo '<br>Quantidade: ' . $dados['quantidade'] . '<br>';
	//print("Data Fim: " . $dataFim . "<br>");

	$valor1 = (float) ($dados['valor'] - $desconto);

} else {
	$dados = 0;

}
$nomealuno = $_GET["nomealuno"];
$pagar = 'R';
$pago = 0;

//$dataI = date('Y-m-d', strtotime(substr($dataInicio, 4, 11)));
//$dataF = date('Y-m-d', strtotime(substr($dataFim, 4, 11)));
//
/*
$valor1 = substr($valor, 2);
//$valor1 = $valor;

if ($idmatricula == null) {
$idmatricula = 0;
}*/

alterarMatricula($conexao, $modalidade, $plano, $valor1, $vencimento, $desconto, $dataInicio, $dataFim, $idcolaborador, $ocorrencia, $idmatricula,$modnome['nome']);

inserePagamento($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataFim, $valor1, $desconto, $historico);

insereLog($conexao, $nomealuno, $idcolaborador, $idacademia);
alteraAluno($conexao, $idaluno, $cod_atualiza);
verifica($conexao, $idacademia, $idaluno, $cod_atualiza);

function alterarMatricula($conexao, $modalidade, $plano, $valor1, $vencimento, $desconto, $dataInicio, $dataFim, $idcolaborador, $ocorrencia, $idmatricula,$modnome) {

	$query = "update matricula set
modalidade={$modalidade},
plano_pagamento='{$plano}',
valor={$valor1},
vencimento={$vencimento},
desconto='{$desconto}',
data_inicio='{$dataInicio}',
data_fim='{$dataFim}',
colaborador={$idcolaborador},
ocorrencia='{$ocorrencia}' where id={$idmatricula}";

	$altera = mysqli_query($conexao, $query);

	//echo 'Alterar banco Matricula= ' . $query . '</br>';

	//echo ("</br>ID inserido = \n" . mysqli_affected_rows($conexao) . '</br>');
	if (mysqli_affected_rows($conexao) <= 0) {
		
		$array = array('id'=>mysqli_affected_rows($conexao),
						);
		$matricula = '{"result":[' . json_encode($array) . ']}';

		echo $matricula;
		
	} else{

		$array = array('id'=> $idmatricula,
					'modalidade' => $modalidade,
					'modnome' => utf8_encode($modnome),
					'plano'=> utf8_encode($plano),
					'vencimentoMatricula' => $vencimento, 
					'dataInicio' => $dataInicio= date('d-m-Y', strtotime($dataInicio)), 
					'dataFim' => $dataFim= date('d-m-Y', strtotime($dataFim)), 
					'valor' => $valor1, 
					'desconto' => $desconto,
					'total' => number_format (($valor1),2,".","."),

						);


	$matricula = '{"result":[' . json_encode($array) . ']}';

	echo $matricula;

		
		
	}
	//echo $query;
	return $altera;
}

function inserePagamento($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataFim, $valor1, $desconto, $historico) {

	$query = "insert into pagar_receber (
pagar_receber,
pago,
documento,
aluno_fornecedor,
colaborador,
ocorrencia,
academia,
emissao,
vencimento,
liberadoate,
valor,
canc,
desconto,
tipodoc,
historico,
val_origem,
deletado)

values (
'{$pagar}',
{$pago},
{$documento},
{$idaluno},
{$idcolaborador},
{$ocorrencia},
{$idacademia},
now(),
'{$dataFim}',
'{$dataFim}',
{$valor1},
'AB',
{$desconto},
0,
'{$historico}',
{$valor1},
'N')";

	$inserir = mysqli_query($conexao, $query);

	//echo 'pagamento pagar receber= ' . $query . '</br>';

	//echo ("</br>ID inserido pagamento = \n" . mysqli_insert_id($conexao) . '</br>');

	return $inserir;

}

function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Renovado matricula de {$nomealuno}',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

/*

id int(11) AI PK
aluno int(11)
modalidade int(11)
plano_pagamento varchar(45)
valor varchar(45)
data_inicio date
vencimento int(11)
desconto decimal(10,2)
ativo tinyint(1)
academia int(11)
data_fim date
colaborador int(11)
ocorrencia bigint(20)

 */

function renovadata($conexao, $modid, $plano, $academia) {

	$dados = array();

	$sql = "select modalidade_plano.id, modalidade_plano.academia, modalidade_plano.modalidade,
modalidade_plano.valor, modalidade_plano.plano, plano.descricao, plano.mes_dia,
plano.quantidade from modalidade_plano inner join plano
on (modalidade_plano.plano=plano.id) where (modalidade_plano.academia=1 or
modalidade_plano.academia={$academia}) and
modalidade_plano.modalidade={$modid} and plano.descricao='{$plano}'
and modalidade_plano.deletado='N'";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}
function modalidade($conexao, $academia, $modid) {

	$dados = array();
	$sql = "select * from modalidade where academia={$academia} and id ={$modid}";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}
function alteraAluno($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='Liberado', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";
	//echo "query uptade aluno: " . $query . "<br>";
	return mysqli_query($conexao, $query);
}
