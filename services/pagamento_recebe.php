
<?php

include 'conecta.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);

$idPagamento = $_GET["idPagamento"];
$caixa = $_GET['caixa'];
$canc = $_GET['fPagamento'];
$idacademia = $_GET['idacademia'];
$valor = $_GET['valor'];
$vezes = $_GET['vezes'];
$cataotipo = $_GET["cataotipo"];
$dataIC = $_GET['dataI'];
$tipodoc = idFormaPagamento($conexao, $idacademia, $canc);

$parc = '1/1';
$bloq_banco = 0;
$pago = 1;

if ($canc == 'CT') {

	$canc = 'CA';

}
print("<br> ID: " . $dataIC . "<br>");
print("<br> ID: " . $idPagamento . "<br>");
print("Caixa: " . $caixa . "<br>");
print("Pagamento: " . $canc . "<br>");
print("Academia: " . $idacademia . "<br>");
print("ID Pagamento: " . $tipodoc['idforma_pagamento'] . "<br>");
print("Valor: " . $valor . "<br>");
print("Vezes: " . $vezes . "<br>");
print("Tipo cartão: " . $cataotipo . "<br>");

//movimento caixa

$idaluno = $_GET['idaluno'];
$nomealuno = $_GET['nomealuno'];
$idcolaborador = $_GET['idcolaborador'];
$historico = 1;
$desc_documento = "PAGAR RECEBER";

print("ID Aluno: " . $idaluno . "<br>");
print("Aluno nome: " . $nomealuno . "<br>");
print("ID idcolaborador: " . $idcolaborador . "<br>");

$pagamento = idpagemento($conexao, $idacademia, $idPagamento);

$documento = $pagamento['documento'];
$ocorrencia = $pagamento['ocorrencia'];

print("Documento: " . $documento) . "<br>";
print("ocorrencia: " . $ocorrencia) . "<br>";

alterarPagamento($conexao, $idPagamento, $parc, $tipodoc['idforma_pagamento'], $canc, $bloq_banco, $pago, $valor);
alteraAluno($conexao, $idaluno, $cod_atualiza);
insereLog($conexao, $nomealuno, $idcolaborador, $idacademia);

movimentoCaixaAberto($conexao, $pagamento['documento'], $idacademia, $historico, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor, $caixa, $idcolaborador, $pagamento['ocorrencia']);
verifica($conexao, $idacademia, $idaluno);

if ($canc == 'CA') {

	$idcartao = taxaCartao($conexao, $cataotipo);
	$valorParcelado = $valor / $vezes;
	$prazo = 30;
	$vlrec = $valorParcelado - (($valorParcelado * $idcartao["taxacred"]) / 100);
	print("Valor parcelas: " . $valorParcelado . "<br>");
	print("Taxa cartao: " . $idcartao["taxacred"] . "<br>");
	print("Valor recalculado: " . $vlrec . "<br>");

	//gravar banco cartões receber
	for ($i = 1; $i <= $vezes; $i++) {

		$prazo = 30 * $i;

		$dataIC = date('Y-m-d', strtotime("+1 month", strtotime($dataIC)));
		$parc = $i . '/' . $vezes;

		movimentoCartaoReceber($conexao, $cataotipo, $idacademia, $documento, $valorParcelado, $prazo, $idcartao["taxacred"], $dataIC, $vlrec, $ocorrencia, $parc);

	}

}

function alterarPagamento($conexao, $idPagamento, $parc, $tipodoc, $canc, $bloq_banco, $pago, $valor) {

	$query = "update pagar_receber set
						 parc='{$parc}',
						 pago={$pago},
						 canc='{$canc}',
						 tipodoc={$tipodoc},
						 bloq_banco={$bloq_banco},
						 val_pago={$valor},
						 pagamento=now()

						 where id={$idPagamento}";

	$alterar = mysqli_query($conexao, $query);

	echo ("ID inserido = \n" . mysqli_affected_rows($conexao)) . '<br>';
	echo $query . '<br>';

	return $alterar;

}

function movimentoCaixaAberto($conexao, $documento, $idacademia, $historico, $tipodoc, $desc_documento, $nomealuno, $valor, $caixa, $idcolaborador, $ocorrencia) {

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
{$historico},
{$tipodoc},
{$caixa},
{$idcolaborador},
{$idacademia},
now(),
'{$desc_documento}',
'{$nomealuno}',
{$valor},
'N',
'N',
{$ocorrencia},
'N'
)";

	$inserir = mysqli_query($conexao, $query);

	echo ("ID inserido = \n" . mysqli_insert_id($conexao) . '<br>');

	echo $inserir . '<br>';
	return $inserir;
}

function movimentoCartaoReceber($conexao, $cataotipo, $idacademia, $documento, $valorParcelado, $prazo, $taxa, $dataI, $vlrec, $ocorrencia, $parc) {

	$query = "insert into cartao_receber(emissao,cartao,academia,doc,valor,prazo,taxa,venc,vlrec,ocorrencia,parcela,deletado)

					values(now(),{$cataotipo},{$idacademia},{$documento},{$valorParcelado},{$prazo},{$taxa},'{$dataI}',{$vlrec},{$ocorrencia},'{$parc}','N')";

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		echo "<script type=\"text/javascript\">alert('ERRO COD 009 - Não gerado parcelas - linhas alteradas 0');</script></br>";

	}

	//echo '<p>Cartão: ' . $query . '</p>';

	echo ("ID inserido = \n" . mysqli_insert_id($conexao) . '</p>');

	return $inserir;

}
function alteraAluno($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='Liberado', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";

	return mysqli_query($conexao, $query);
}

function idFormaPagamento($conexao, $idacademia, $canc) {

	$dados = array();

	$query = "select idforma_pagamento from forma_pagamento where (academia=1 or academia={$idacademia}) and sigla='{$canc}'";

	$resultado = mysqli_query($conexao, $query);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;
}

function idpagemento($conexao, $idacademia, $idPagamento) {

	$dados = array();

	$query = "select * from academia.pagar_receber where academia={$idacademia} and id = {$idPagamento}";

	$resultado = mysqli_query($conexao, $query);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;
}

function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Recebida pagamento de {$nomealuno}',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

function taxaCartao($conexao, $cataotipo) {

	$retorno = array();

	$query = "select * from cartao where id={$cataotipo}";

	$resultado = mysqli_query($conexao, $query);

	$retorno = mysqli_fetch_assoc($resultado);

	return $retorno;

}