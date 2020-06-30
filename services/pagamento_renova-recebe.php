
<?php

include 'conecta.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);

$idaluno = $_GET["idaluno"];
$idacademia = $_GET["academia"];
$pago = 1;
$dataI = date('Y-m-d', strtotime($_GET["dataI"]));

$canc = $_GET["canc"];
$tipodoc = tipodoc($conexao, $idacademia, $canc);
$parc = '1/1';
$bloq_banco = 0;
$idcolaborador = $_GET["idcolaborador"];
$ocorrencia = $_GET["ocorrencia"];

$documento = $_GET["documento"];

$desc_documento = "PAGAR RECEBER";
$nomealuno = $_GET["nomealuno"];
$banco = $_GET["banco"];
$idmodalidade = $_GET["idmodalidade"];
$modnome = modNome($conexao, $idacademia, $idmodalidade);
$planonome = $_GET["planonome"];

if ($desconto = $_GET["desconto"] != 'undefined') {
	if ($desconto == 0) {
		$historico = $modnome['nome'] . '-' . $planonome;
	} else {
		$historico = $modnome['nome'] . '-' . $planonome;
		$desconto = number_format($_GET["desconto"], 2, '.', '');

		$historico = $modnome['nome'] . '-' . $planonome . ' Desconto: ' . number_format($desconto, 2, '.', '');
	}

} else {
	$desconto = 0;
	$historico = $modnome['nome'] . '-' . $planonome;
}

$vencimento = $_GET["vencimento"];

$dados = renovadata($conexao, $idmodalidade, $planonome, $idacademia);

$mes_dia = $dados['mes_dia'];
$mes_diaQuant = $dados['quantidade'];

$valor = valorPlano($conexao, $idacademia, $idmodalidade, $planonome);

//$valor1 = $valor['valor'] - $desconto;
$valorRecebe = $_GET['valor'];

$valor1 = str_replace('R', '', $valorRecebe);

$valor1 = str_replace('$', '', $valor1);
$valor1 = str_replace(',', '', $valor1);

//print("Valor pago: " . $valor1 . "<br>");

$dataF = $dataI;

if ($mes_dia == "M") {
	$dataF = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' months', strtotime($dataI)));
} else {
	$dataF = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' day', strtotime($dataI)));
}

if ($canc == 'CT') {

	$canc = 'CA';

}
//print("Data Fim: " . $dataF . "<br>");

//print("Data inicio: " . $dataIC . "<br>");
//print("Data fim: " . $dataFC . "<br>");
/*
print("ID aluno: " . $idaluno . "<br>");
print("ID Academia: " . $idacademia . "<br>");
print("ID Modalidade: " . $idmodalidade . "<br>");
print("Pago: " . $pago . "</br>");
print("Data Inicio: " . $dataI . "<br>");
print("Data Fim: " . $dataF . "<br>");
print("Tipodoc: " . $tipodoc['idforma_pagamento'] . "<br>");
print("Tipo de Documento: " . $canc . "<br>");
print("Parcelamento: " . $parc . "<br>");
//print("Valor pago: " . $valor1 . "<br>");
print("Valor plano: " . $valor['valor'] . "<br>");
print("Valor Real: " .  $valor1 . "<br>");

print("Vencimento: " . $vencimento . "<br>");
print("Desconto: " . $desconto . "<br>");
//print("Ativo: " . $ativo . "<br>");
print("ID Consulto: " . $idcolaborador . "<br>");
print("Ocorrencia: " . $ocorrencia . "<br>");
print("documento: " . $documento . "<br>");
print("Histórico: " . $historico . "<br>");
print("Descrição Documento: " . $desc_documento . "<br>");
print("Nome Aluno: " . $nomealuno . "<br>");
print("Banco: " . $banco . "<br>");
print("Nome do Plano: " . $planonome . "<br>");

print("Mes Dia: " . $mes_dia . "<br>");
print("Mes dia Quantidade: " . $mes_diaQuant . "<br>");
print("cod_atualiza: " . $cod_atualiza . "<br>");

print("Histórico: " . $historico . "<br>");
 */

if (!isset($_GET['dividirPagamentoCH'])) {
	alterarPagamento($conexao, $idaluno, $parc, $documento, $tipodoc['idforma_pagamento'], $canc, $bloq_banco, $ocorrencia, $pago, $valor1, $dataF);
} else {
	inserePagamento($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataF, $valor1, $desconto, $historico, $parc, $canc);
}

alteraAluno($conexao, $idaluno, $cod_atualiza);

movimentoCaixaAberto($conexao, $documento, $idacademia, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor1, $banco, $idcolaborador, $ocorrencia);

insereLog($conexao, $nomealuno, $idcolaborador, $idacademia);
verifica($conexao, $idacademia, $idaluno, $cod_atualiza);

function alterarPagamento($conexao, $idaluno, $parc, $documento, $tipodoc, $canc, $bloq_banco, $ocorrencia, $pago, $valor1, $dataF) {

	$query = "update pagar_receber set
						 parc='{$parc}',
						 valor={$valor1},
						 pago={$pago},
						 canc='{$canc}',
						 tipodoc={$tipodoc},
						 bloq_banco={$bloq_banco},
						 val_pago={$valor1},
						 pagamento=now(),
						 liberadoate='{$dataF}'

						 where aluno_fornecedor={$idaluno} and documento={$documento} and ocorrencia={$ocorrencia} and id>0";

	//echo '<br>' . $query . '<br>';

	$alterar = mysqli_query($conexao, $query);

	if (mysqli_affected_rows($conexao) == 0) {
		$retorno = array(
			'rest' => '0',
			'func'=> 'alterarPagamento',
			'pedido' => 'renovacao',
			'pag_div'=>'N'
		);
/*
		$url = 'https://www.megatreino.com.br/AcademiaNova/log/erro.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $retorno);

		$result = curl_exec($ch);
		echo $result;
		curl_close($ch);
		*/
	} else {

		$retorno = array(
			'rest' => '1',
			'func'=> 'alterarPagamento',
			'pedido' => 'renovacao',
			'pag_div'=>'N'
		);
	}

	echo '{"result":[' . json_encode($retorno). ']}';

	//	echo '<br> alterar pagamento' . $query . '<br>';

	//echo ("<br>ID alterado = \n" . mysqli_affected_rows($conexao));

	return $alterar;
}

function inserePagamento($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataF, $valor1, $desconto, $historico, $parc, $canc) {

	$query = "insert into pagar_receber (
pagar_receber,
pago,
parc,
canc,
bloq,
documento,
aluno_fornecedor,
colaborador,
ocorrencia,
academia,
emissao,
pagamento,
vencimento,
liberadoate,
valor,
desconto,
tipodoc,
historico,
val_pago,
deletado)

values (
'R',
1,
'{$parc}',
'{$canc}',
'{$parc}',
{$documento},
{$idaluno},
{$idcolaborador},
{$ocorrencia},
{$idacademia},
now(),
now(),
'{$dataF}',
'{$dataF}',
{$valor1},
{$desconto},
0,
upper('{$historico}'),
{$valor1},
'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	echo ("ID inserido = \n" . mysqli_insert_id($conexao)) . '</br>';
	echo $query . '<br>';

	return $inserir;

}

function movimentoCaixaAberto($conexao, $documento, $idacademia, $tipodoc, $desc_documento, $nomealuno, $valor1, $banco, $idcolaborador, $ocorrencia) {

	$query = "insert into movimento_caixa_aberto(documento,
	historico,
	forma_pagamento,
	banco,
	colaborador,
	academia,
	emissao,
	desc_documento,
	nome,
	valor,
	cancelado,
	manual,
	ocorrencia,
	deletado
	)

	values({$documento},
	1,
	{$tipodoc},
	{$banco},
	{$idcolaborador},
	{$idacademia},
	now(),
	'{$desc_documento}',
	'{$nomealuno}',
	{$valor1},
	'N',
	'N',
	{$ocorrencia},
	'N'
	)";

	//echo "query movimentoCaixaAberto: " . $query . "<br>";
	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 008 - Recebimento não gerado em movimentação de caixa - linhas alteradas 0');</script></br>";

	}

	//echo '<br>' . $query . '<br>';

	//echo ("ID inserido = \n" . mysqli_insert_id($conexao));

	return $inserir;

}

function alteraAluno($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='Liberado', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";
	//echo "query uptade aluno: " . $query . "<br>";

	$alterar = mysqli_query($conexao, $query);
	mysqli_affected_rows($conexao);

	//echo "<br>Alterar aluno" . $alterar . "<br>";
	//echo ("ID alterado = \n" . mysqli_affected_rows($conexao));

	return $alterar;
}

function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Recebida Mensalidade de {$nomealuno}',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

function tipodoc($conexao, $academia, $canc) {

	$dados = array();

	$sql = "select * from forma_pagamento where (academia=1 or academia={$academia}) and sigla='{$canc}';";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}

function valorPlano($conexao, $academia, $mod, $plano) {

	$dados = array();

	$sql = "select modalidade_plano.id, modalidade_plano.academia, modalidade_plano.modalidade,
modalidade_plano.valor, modalidade_plano.plano, plano.descricao, plano.mes_dia,
plano.quantidade from modalidade_plano inner join plano on (modalidade_plano.plano=plano.id)
where modalidade_plano.academia={$academia} and modalidade={$mod} and descricao='{$plano}' and modalidade_plano.deletado='N';";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}

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

function modNome($conexao, $academia, $mod) {

	$dados = array();

	$sql = "select * from modalidade where academia={$academia} and id={$mod}";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}
/*

/*
id int(11) AI PK
documento int(11)
pagar_receber varchar(1)
parc varchar(5)
aluno_fornecedor int(11)
colaborador int(11)
emissao date
vencimento date
liberadoate date
valor decimal(10,2)
canc varchar(80)
tipodoc int(11)
bloq varchar(20)
historico varchar(80)
obs longtext
sel varchar(1)
bloq_banco int(11)
val_origem decimal(10,2)
val_pago decimal(10,2)
desconto decimal(10,2)
pagamento date
pago tinyint(1)
academia int(11)
ocorrencia bigint(20)
historico_model int(11)
venda tinyint(1)
idassinatura_iugu varchar(255)
deletado char(1)
 */
/*
Table: movimento_caixa_aberto
Columns:
id int(11) AI PK
documento int(11)
emissao datetime
historico int(11)
forma_pagamento int(11)
desc_documento varchar(70)
nome varchar(70)
observacao longtext
valor decimal(10,2)
banco int(11)
cancelado varchar(1)
manual varchar(1)
colaborador int(11)
academia int(11)
ocorrencia bigint(20)
deletado char(1)
 */