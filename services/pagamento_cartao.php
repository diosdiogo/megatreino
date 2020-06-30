
<?php

include 'conecta.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);

if (isset($_GET['renova'])) {
	//renova matricula recebimento cartão

	$renova = $_GET['renova'];
	$idaluno = $_GET["idaluno"];
	$nomealuno = $_GET["nomealuno"];
	$idacademia = $_GET["academia"];
	$idcolaborador = $_GET["idcolaborador"];
	$ocorrencia = $_GET["ocorrencia"];
	$documento = $_GET["documento"];
	$pago = 1;
	$dataI = $_GET["dataI"];
	$canc = $_GET["canc"];
	$tipodoc = tipodoc($conexao, $idacademia, $canc);
	$parc = '1/1';
	$bloq_banco = 0;
	$desc_documento = "PAGAR RECEBER";
	$idmodalidade = $_GET["idmodalidade"];
	$modnome = planoNome($conexao, $idacademia, $idmodalidade);
	$planonome = $_GET["planonome"];
	$valorRecebe = $_GET['valor'];
	//$valor = valorPlano($conexao, $idacademia, $idmodalidade, $planonome);
	$valor = str_replace('R', '', $valorRecebe);
	$valor = str_replace('$', '', $valor);
	$valor = str_replace(',', '', $valor);

	//print("Valor: " . $valor . "<br>");
	$banco = $_GET["banco"];

	if (isset($_GET["desconto"])) {

		if ($desconto = $_GET["desconto"] != 'undefined') {

			$desconto = number_format($_GET["desconto"], 2, '.', '');

			$historico = $modnome['nome'] . '-' . $planonome . ' Desconto: ' . number_format($desconto, 2, '.', '');

			if ($desconto == 0) {
				$historico = $modnome['nome'] . '-' . $planonome;
			}
		} else {
			$desconto = 0;

			$historico = $modnome['nome'] . '-' . $planonome;
		}
	}

	$vencimento = $_GET["vencimento"];
	$vezes = $_GET["vezes"];
	$cataotipo = $_GET["cataotipo"];

	$dados = vezesData($conexao, $idmodalidade, $planonome, $idacademia);
	$mes_dia = $dados['mes_dia'];
	$mes_diaQuant = $dados['quantidade'];

	$dataF = date('Y-m-d', strtotime($dataI));

	if ($mes_dia == "M") {
		$dataF = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' months', strtotime($dataI)));
	} else {
		$dataF = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' day', strtotime($dataI)));
	}

	if ($canc == 'CT') {

		$canc = 'CA';

	}

	$idcartao = taxaCartao($conexao, $cataotipo);
	//$valorParcelado = $valor['valor'] / $vezes;
	$valorParcelado = $valor / $vezes;
	$prazo = 30;
	$vlrec = $valorParcelado - (($valorParcelado * $idcartao["taxacred"]) / 100);

	for ($i = 1; $i <= $vezes; $i++) {

		$prazo = 30 * $i;
		/*$dataIM++;

			if ($dataIM > 12) {
				$dataIM = 01;
			}
		*/
		$dataI = date('Y-m-d', strtotime("+1 month", strtotime($dataI)));
		$parc = $i . '/' . $vezes;

		movimentoCartaoReceber($conexao, $cataotipo, $idacademia, $documento, $valorParcelado, $prazo, $idcartao["taxacred"], $dataI, $vlrec, $ocorrencia, $parc);

	}

/*
print("Renova: " . $renova . "<br>");
print("ID aluno: " . $idaluno . "<br>");
print("Nome Aluno: " . $nomealuno . "<br>");
print("ID Academia: " . $idacademia . "<br>");
print("ID Consulto: " . $idcolaborador . "<br>");
print("Ocorrencia: " . $ocorrencia . "<br>");
print("documento: " . $documento . "<br>");
print("Pago: " . $pago . "</br>");
print("Data Inicio: " . $dataI . "<br>");
print("Data Fim: " . $dataF . "<br>");
print("Tipo de Documento: " . $canc . "<br>");
print("Tipodoc: " . $tipodoc['idforma_pagamento'] . "<br>");
print("Parcelamento: " . $parc . "<br>");
print("Histórico: " . $historico . "<br>");
print("Descrição Documento: " . $desc_documento . "<br>");
print("ID Modalidade: " . $idmodalidade . "<br>");
print("Nome do Plano: " . $planonome . "<br>");
//print("Valor: " . $valor['valor'] . "<br>");
print("Valor: " . $valor . "<br>");
print("Banco: " . $banco . "<br>");
print("Desconto: " . $desconto . "<br>");
print("Vencimento: " . $vencimento . "<br>");
print("Parcelas: " . $vezes . "X<br>");
print("Cartão: " . $cataotipo . "<br>");
print("Mes Dia: " . $mes_dia . "<br>");
print("Mes dia Quantidade: " . $mes_diaQuant . "<br>");
print("Parcelamento: " . $parc . "<br>");

print("Cartão: " . $cataotipo . "<br>");

print("Histórico: " . $historico . "<br>");

print("Valor parcelas: " . $valorParcelado . "<br>");
print("Taxa cartao: " . $idcartao["taxacred"] . "<br>");
print("Valor recalculado: " . $vlrec . "<br>");
 */
	if (!isset($_GET['dividirPagamento'])) {

		alterarPagamento($conexao, $idaluno, $parc, $dataF, $tipodoc['idforma_pagamento'], $canc, $bloq_banco, $ocorrencia, $documento, $pago, $valor);

	}

	if (isset($_GET['dividirPagamento'])) {

		inserePagamento($conexao, $documento, $parc, $idaluno, $idcolaborador, $dataI, $dataF, $valor, $canc, $historico, $desconto, $idacademia, $ocorrencia);

	}
	alteraAluno($conexao, $idaluno, $cod_atualiza);

	movimentoCaixaAberto($conexao, $documento, $idacademia, $historico, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor, $banco, $idcolaborador, $ocorrencia);

	insereLog($conexao, $nomealuno, $idcolaborador, $idacademia);
	verifica($conexao, $idacademia, $idaluno, $cod_atualiza);

}

function alterarPagamento($conexao, $idaluno, $parc, $dataF, $tipodoc, $canc, $bloq_banco, $ocorrencia, $documento, $pago, $valor) {

	$query = "update pagar_receber set
parc='{$parc}',
pago={$pago},
canc='{$canc}',
tipodoc='{$tipodoc}',
bloq_banco={$bloq_banco},
val_pago={$valor},
pagamento=now()

where aluno_fornecedor={$idaluno} and documento='{$documento}' and ocorrencia={$ocorrencia} and id>0";

	//echo '<p>alterar pagamento: ' . $query . '</p>';

	$alterar = mysqli_query($conexao, $query);
	echo mysqli_affected_rows($conexao);

	//echo ("ID alterado = \n" . mysqli_affected_rows($conexao) . "<br>");
	//echo $query . "<br>";

	return $alterar;

}

function movimentoCaixaAberto($conexao, $documento, $idacademia, $historico, $tipodoc, $desc_documento, $nomealuno, $valor, $banco, $idcolaborador, $ocorrencia) {

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
{$valor},
'N',
'N',
{$ocorrencia},
'N'
)";

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 008 - Recebimento não gerado em movimentação de caixa - linhas alteradas 0');</script></br>";

	}

	//echo '<p>Movimento de Caixa: ' . $query . '</p>';
	//echo ("ID inserido = \n" . mysqli_insert_id($conexao) . '</p>');

	return $inserir;
}

function alteraAluno($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='Liberado', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";

	$alterar = mysqli_query($conexao, $query);
	mysqli_affected_rows($conexao);

	//echo "<br> alterar aluno" . $query . "<br>";
	//echo ("ID alterado = \n" . mysqli_affected_rows($conexao));

	return $alterar;
}

// cadastro cartão receber

function movimentoCartaoReceber($conexao, $cataotipo, $idacademia, $documento, $valorParcelado, $prazo, $taxa, $dataI, $vlrec, $ocorrencia, $parc) {

	$query = "insert into cartao_receber(emissao,cartao,academia,doc,valor,prazo,taxa,venc,vlrec,ocorrencia,parcela,deletado)

					values(now(),{$cataotipo},{$idacademia},{$documento},{$valorParcelado},{$prazo},{$taxa},'{$dataI}',{$vlrec},{$ocorrencia},'{$parc}','N')";

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 009 - Não gerado parcelas - linhas alteradas 0');</script></br>";

	}

	//echo '<p>Cartão: ' . $query . '</p>';

	//echo ("ID inserido = \n" . mysqli_insert_id($conexao) . '</p>');

	return $inserir;

}

function taxaCartao($conexao, $cataotipo) {

	$retorno = array();

	$query = "select * from cartao where id={$cataotipo}";

	$resultado = mysqli_query($conexao, $query);

	$retorno = mysqli_fetch_assoc($resultado);

	return $retorno;

}
function vezesData($conexao, $modid, $plano, $academia) {

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
function planoNome($conexao, $academia, $mod) {

	$dados = array();

	$sql = "select * from modalidade where academia={$academia} and id={$mod}";

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
function tipodoc($conexao, $academia, $canc) {

	$dados = array();

	$sql = "select * from forma_pagamento where (academia=1 or academia={$academia}) and sigla='{$canc}';";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}
function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Efetuado pagamento no cartão {$nomealuno}',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

function inserePagamento($conexao, $documento, $parc, $idaluno, $idcolaborador, $dataI, $dataF, $valor, $canc, $historico, $desconto, $idacademia, $ocorrencia) {

	$query = "insert into pagar_receber (documento,pagar_receber,parc,aluno_fornecedor,colaborador,emissao,vencimento,liberadoate,valor,canc,historico,
val_origem,desconto,pagamento,pago,academia,ocorrencia,val_pago,deletado)
values ($documento,'R','$parc',$idaluno,$idcolaborador,'$dataI','$dataF','$dataF',$valor,'$canc','$historico',$valor,$desconto,now(),1,$idacademia,$ocorrencia,$valor,'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 004 - Pagamento não gerado - linhas alteradas 0');</script></br>";

	}
	//echo ("ID inserido = \n" . $query);

	return $inserir;

}
