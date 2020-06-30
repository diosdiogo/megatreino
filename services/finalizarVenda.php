<?php
include 'conecta.php';
include 'ocorrencia.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);

$ocorrencia = ultimaOcorrencia($conexao);
//echo $ocorrencia['id'] . '<br>';
$documento = ultimaDocumento($conexao);
//echo $documento['id'] . '<br>';
$idaluno = $_GET['idaluno'];
//echo 'Aluno: ' . $idaluno . '<br>';
$academia = $_GET['academia'];
//echo 'Academia: ' . $academia . '<br>';

$idcolaborador = $_GET['idcolaborador'];
//echo 'idcolaborador: ' . $idcolaborador . '<br>';

$data = date('Y-m-d');
//echo 'Data' . $data . '<br>';

$historico = 'Venda de Produtos / Serviços';
$parc = '1/1';
$canc = null;
$desc_documento = "PAGAR RECEBER";
$array = json_decode(file_get_contents("php://input"), true);

//print_r($array);

if (isset($_GET['finalizarVenda'])) {

	$totalItens = $array['vendas'][0]['totalItens'];
	//echo '<br>Total de itens: ' . $totalItens . '<br>';

	$totalValor = $array['vendas'][0]['totalValor'];
	//echo 'Total de valor: ' . $totalValor . '<br>';

	$desconto = $array['vendas'][0]['desconto'];
	//echo 'Desconto: ' . $desconto . '<br>';

	$total = $totalValor - $desconto;
	//echo 'Total: ' . $total . '<br>';

	inserePagamento($conexao, $documento['id'], $idaluno, $idcolaborador, $ocorrencia['id'], $academia, $total, $desconto, $historico, $data);
}

if (isset($_GET['receber'])) {

	$idpagamento = $_GET['idpagamento'];
	$dataI = date('Y-m-d', strtotime($_GET['dataI']));
	$nomealuno = $_GET['nomeAluno'];
	//echo $dataI . '<br>';
	$canc = $_GET['canc'];
	//echo $canc . '<br>';
	$tipodoc = tipodoc($conexao, $academia, $canc);

	if ($canc == 'CT') {
		$canc = 'CA';
	}

	$canc2 = $_GET['canc2'];
	$tipodoc2 = tipodoc($conexao, $academia, $canc2);

	if ($canc2 == 'CT') {
		$canc2 = 'CA';
	}

	echo 'Tipo de Pg: ' . $canc2 . '<br>';

	$banco = $_GET['banco'];
	//echo $banco . '<br>';

	$vendaNome = $_GET['vendaNome'];
	//echo $vendaNome . '<br>';

	$descontoRecebe = $_GET['desconto'];
	$desconto = str_replace('R', '', $descontoRecebe);
	$desconto = str_replace('$', '', $desconto);
	$desconto = str_replace(',', '', $desconto);

	//echo $desconto . '<br>';

	$valor1Recebe = $_GET['valor1'];

	$valor1 = str_replace('R', '', $valor1Recebe);
	$valor1 = str_replace('$', '', $valor1);
	$valor1 = str_replace(',', '', $valor1);

	echo 'Valor: ' . $valor1 . '<br>';

	if (isset($_GET['valor2'])) {
		$valor2Recebe = $_GET['valor2'];
	} else {
		$valor2Recebe = 0;
	}
	$valor2 = str_replace('R', '', $valor2Recebe);
	$valor2 = str_replace('$', '', $valor2);
	$valor2 = str_replace(',', '', $valor2);
	echo 'Valor: ' . $valor2 . '<br>';
	if (isset($_GET['pagamentoUnico'])) {

		if ($canc != 'PZ') {
			$pagamento = receberVenda_p_u($conexao, $idpagamento, $parc, $canc, $tipodoc['idforma_pagamento'], $valor1, $dataI);

			insereLog($conexao, $nomealuno, $idcolaborador, $academia, 'Compra de produto modo venda rápida -');
		}

		if ($canc == 'CA') {

			$vezes = $_GET["vezes"];
			//echo 'Vezes ' . $vezes . '<br>';
			$cartaotipo = $_GET["cataotipo"];
			//echo 'Cartão ' . $cartaotipo . '<br>';
			$idcartao = taxaCartao($conexao, $cartaotipo);
			//echo $idcartao["taxacred"] . '<br>';

			$valorParcelado = $valor1 / $vezes;
			//echo 'Parcelas' . $valorParcelado . '<br>';
			$prazo = 30;
			$vlrec = $valorParcelado - (($valorParcelado * $idcartao["taxacred"]) / 100);
			//echo 'vlrec ' . $vlrec . '<br>';

			for ($i = 1; $i <= $vezes; $i++) {

				$prazo = 30 * $i;

				$dataI = date('Y-m-d', strtotime("+1 month", strtotime($dataI)));
				//echo 'Parcelas para: ' . $dataI . '<br>';

				$parc = $i . '/' . $vezes;
				//echo 'Parcelas: ' . $parc . '<br>';

				movimentoCartaoReceber($conexao, $cartaotipo, $academia, $documento['id'], $valorParcelado, $prazo, $idcartao["taxacred"], $dataI, $vlrec, $ocorrencia['id'], $parc);

			}

			insereLog($conexao, $nomealuno, $idcolaborador, $idacademia, 'Compra de produto modo venda rápida - CARTÃO ');

			//verifica($conexao, $idacademia, $idaluno, $cod_atualiza);

		}

		if ($canc == 'PZ') {

			$canc = 'BL';

			$array['parcelas'] = json_decode(file_get_contents("php://input"), true);

			$vezes = $_GET["vezes"];
			$parcelaValor = number_format($valor1 / $vezes, 2, '.', '');
			//echo 'parcelas: ' . $parcelaValor . '<br>';

			foreach ($array['parcelas']['parcelas'] as $parcela) {

				$i = $parcela['id'] + 1;
				//echo "parcela id: " . $i . '<br>';

				if ($i == 1) {
					alterarPagamento($conexao, $idpagamento, $idaluno, $documento['id'], $parcela['vezes'], $parcela['vencimento'], $tipodoc['idforma_pagamento'], $canc, $ocorrencia['id'], $parcelaValor, $i);
				} else {

					inserePagamentoPZ($conexao, $documento['id'], $idaluno, $idcolaborador, $ocorrencia['id'], $academia, $parcela['vencimento'], $parcelaValor, $desconto, $historico, $parcela['vezes'], $canc, $i);
				}

			}
			insereLog($conexao, $nomealuno, $idcolaborador, $idacademia, 'Compra de produto modo venda rápida - BOLETO ');
		}

		movimentoCaixaAberto($conexao, $documento['id'], $academia, $historico, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor1, $banco, $idcolaborador, $ocorrencia['id']);
		//$pagamento = 1;
	}

	if (isset($_GET['dividirPagamento'])) {

		if ($canc != 'PZ') {
			$pagamento = receberVenda_p_u_dividir($conexao, $idpagamento, $parc, $canc, $tipodoc['idforma_pagamento'], $valor1, $dataI, $documento['id'], $ocorrencia['id']);
		}

		if ($canc2 != 'PZ') {
			inserirPagamentoDividir($conexao, $documento['id'], $parc, $idaluno, $idcolaborador, $valor2, $canc2, $tipodoc2['idforma_pagamento'], $historico, $academia, $ocorrencia['id']);

			movimentoCaixaAberto($conexao, $documento['id'], $academia, $historico, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor1 + $valor2, $banco, $idcolaborador, $ocorrencia['id']);
		}

		if ($canc2 == 'CA') {

			$vezes = $_GET["vezes"];
			//echo 'Vezes ' . $vezes . '<br>';
			$cartaotipo = $_GET["cataotipo"];
			//echo 'Cartão ' . $cartaotipo . '<br>';
			$idcartao = taxaCartao($conexao, $cartaotipo);
			//echo $idcartao["taxacred"] . '<br>';

			$valorParcelado = $valor1 / $vezes;
			//echo 'Parcelas' . $valorParcelado . '<br>';
			$prazo = 30;
			$vlrec = $valorParcelado - (($valorParcelado * $idcartao["taxacred"]) / 100);
			//echo 'vlrec ' . $vlrec . '<br>';

			for ($i = 1; $i <= $vezes; $i++) {

				$prazo = 30 * $i;

				$dataI = date('Y-m-d', strtotime("+1 month", strtotime($dataI)));
				//echo 'Parcelas para: ' . $dataI . '<br>';

				$parc = $i . '/' . $vezes;
				//echo 'Parcelas: ' . $parc . '<br>';

				movimentoCartaoReceber($conexao, $cartaotipo, $academia, $documento['id'], $valorParcelado, $prazo, $idcartao["taxacred"], $dataI, $vlrec, $ocorrencia['id'], $parc);

			}

			movimentoCaixaAberto($conexao, $documento['id'], $academia, $historico, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor1 + $valor2, $banco, $idcolaborador, $ocorrencia['id']);
		}

		if ($canc2 == 'PZ') {

			$canc2 = 'BL';

			$array['parcelas'] = json_decode(file_get_contents("php://input"), true);

			$vezes = $_GET["vezes"];
			$parcelaValor = number_format($valor2 / $vezes, 2, '.', '');
			//echo 'parcelas: ' . $parcelaValor . '<br>';

			foreach ($array['parcelas']['parcelas'] as $parcela) {
				$i = $parcela['id'] + 1;

				inserePagamentoPZ($conexao, $documento['id'], $idaluno, $idcolaborador, $ocorrencia['id'], $academia, $parcela['vencimento'], $parcelaValor, $desconto, $historico, $parcela['vezes'], $canc2, $i);
			}

		}
		movimentoCaixaAberto($conexao, $documento['id'], $academia, $historico, $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor1, $banco, $idcolaborador, $ocorrencia['id']);

	}

	//echo $pagamento;

	$idvenda = venda($conexao, $idaluno, $valor1, $desconto, $academia, $idcolaborador, $ocorrencia['id']);
	echo 'ID venda: ' . $idvenda . '<br>';
	$i = 1;
	foreach ($array['vendasItens'] as $venda) {

		if ($venda['removido'] == 'N') {

			if ($venda['tipoVenda'] == 'P') {
				venda_item($conexao, $venda['id'], $idvenda, $venda['valor'], $i, $venda['qts'], $venda['total'], 0, 0, $academia);
				venda_item_quantidade($conexao, $venda['id'], $venda['qts']);
			}

			if ($venda['tipoVenda'] == 'S') {
				venda_item($conexao, 0, $idvenda, $venda['valor'], $i, $venda['qts'], $venda['total'], 1, $venda['id'], $academia);
			}

			$i++;

		}

	}

	//print_r($array);
}

function inserePagamento($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $academia, $total, $desconto, $historico, $data) {

	$query = "insert into pagar_receber (documento,pagar_receber,aluno_fornecedor,colaborador,emissao,vencimento,valor,
	tipodoc,historico,val_origem,desconto,pago,academia,ocorrencia,venda,deletado)

values ($documento,'R',$idaluno,$idcolaborador,now(),now(),$total,0,upper('$historico'),$total,$desconto,0,$academia,$ocorrencia,1,'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script>alert('ERRO COD 001 - matricula não foi cadastrada - linhas alteradas 0');</script></br>";
		$array = array('id' => mysqli_insert_id($conexao),
		);
		$venda = '{"result":[' . json_encode($array) . ']}';

		echo $venda;

	} else {

		//echo ("ID inserido = \n" . mysqli_insert_id($conexao));
		$array = array('id' => mysqli_insert_id($conexao),
			'vendaNome' => utf8_encode($historico),
			'dataInicio' => $data = date('d-m-Y', strtotime($data)),
			'dataFim' => $data = date('d-m-Y', strtotime($data)),
			'valor' => $total,
			'desconto' => $desconto,
			'total' => number_format(($total), 2, ".", "."),

		);

		$venda = '{"result":[' . json_encode($array) . ']}';

		echo $venda;
		//echo $query;
	}

	return $inserir;

}

function receberVenda_p_u($conexao, $idpagamento, $parc, $canc, $tipodoc, $valor1, $dataI) {

	$sql = "update pagar_receber set parc='1/1', canc='$canc', tipodoc=$tipodoc,val_pago=$valor1,pagamento='$dataI', pago=1, historico_model=11 where id=$idpagamento";

	$query = mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
		echo 1;
	} else {
		echo 0;
	}
	//echo $sql;
}

function receberVenda_p_u_dividir($conexao, $idpagamento, $parc, $canc, $tipodoc, $valor1, $dataI, $documento, $ocorrencia) {

	$sql = "update pagar_receber set documento=$documento, ocorrencia=$ocorrencia, parc='1/1', canc='$canc', tipodoc=$tipodoc,val_pago=$valor1,pagamento='$dataI', pago=1, historico_model=11 where id=$idpagamento";

	$query = mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
		echo 2;
	} else {
		echo 0;
	}
	//echo $sql;
}

function inserirPagamentoDividir($conexao, $documento, $parc, $idaluno, $colaborador, $valor2, $canc2, $tipodoc, $historico, $academia, $ocorrencia) {
	$sql = "insert into pagar_receber(documento,pagar_receber,parc,aluno_fornecedor,colaborador,emissao,vencimento,valor,canc,tipodoc,historico,
val_origem,val_pago,desconto,pagamento,pago,academia,ocorrencia,historico_model,venda,deletado)
value($documento,'R','$parc',$idaluno,$colaborador,now(),now(),$valor2,'$canc2',$tipodoc,upper('$historico'),$valor2,$valor2,0.00,now(),1,$academia,$ocorrencia,11,1,'N')";
	$query = mysqli_query($conexao, $sql);

	echo $sql;
}

function tipodoc($conexao, $academia, $canc) {

	$dados = array();

	$sql = "select * from forma_pagamento where (academia=1 or academia={$academia}) and sigla='{$canc}';";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}

function taxaCartao($conexao, $cataotipo) {

	$retorno = array();

	$query = "select * from cartao where id={$cataotipo}";

	$resultado = mysqli_query($conexao, $query);

	$retorno = mysqli_fetch_assoc($resultado);

	return $retorno;

}

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

function alterarPagamento($conexao, $idpagamento, $idaluno, $documento, $parc, $dataF, $tipodoc, $canc, $ocorrencia, $valorParcelado, $i) {

	$query = "update pagar_receber set parc='{$parc}', bloq='{$parc}', canc='{$canc}', vencimento='{$dataF}', valor={$valorParcelado}, val_origem=0, bloq_banco={$i}, documento={$documento}
where aluno_fornecedor={$idaluno} and id={$idpagamento}";

	//echo '<p>alterar pagamento: ' . $query . '</p>';

	$alterar = mysqli_query($conexao, $query);

	//mysqli_affected_rows($conexao);

	//echo ("ID alterado = \n" . mysqli_affected_rows($conexao)) . '</p>';
	//echo $query . '<br>';

	return $alterar;

}

function inserePagamentoPZ($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataF, $valorParcelado, $desconto, $historico, $parc, $canc, $i) {

	$query = "insert into pagar_receber (
pagar_receber,
pago,
parc,
canc,
bloq,
bloq_banco,
documento,
aluno_fornecedor,
colaborador,
ocorrencia,
academia,
emissao,
vencimento,
valor,
desconto,
tipodoc,
historico,
val_origem,
venda,
deletado)

values (
'R',
0,
'{$parc}',
'{$canc}',
'{$parc}',
'{$i}',
{$documento},
{$idaluno},
{$idcolaborador},
{$ocorrencia},
{$idacademia},
now(),
'{$dataF}',
{$valorParcelado},
{$desconto},
0,
upper('{$historico}'),
0,
1,
'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	///echo ("ID inserido = \n" . mysqli_insert_id($conexao)) . '</br>';
	//echo $query . '<br>';

	return $inserir;

}

function venda($conexao, $idaluno, $valor, $desconto, $idacademia, $colaborador, $ocorrencia) {
	$sql = "insert into venda(aluno,data,valor,desconto,total,cancelado,academia,colaborador,ocorrencia,deletado)
	value($idaluno,now(),($valor+$desconto),$desconto,$valor,0,$idacademia,$colaborador,$ocorrencia,'N');";
	$inserir = mysqli_query($conexao, $sql);
	mysqli_insert_id($conexao);

	return mysqli_insert_id($conexao);
}

function venda_item($conexao, $produto, $venda, $valor, $item, $quantidade, $total, $e_servico, $servico, $academia) {
	$sql = "insert into venda_item (produto,venda,emissao,valor,custo,item,quantidade,desconto,total,e_servico,servico,academia)value($produto,$venda,now(),$valor,0.00,$item,$quantidade,0.00,$total,$e_servico,$servico,$academia);";
	$inserir = mysqli_query($conexao, $sql);
	return $inserir;
}

function venda_item_quantidade($conexao, $id, $qts) {
	$sql = "update produto set quantidade = (SELECT quantidade where id=$id)-$qts where id = $id;";
	$inserir = mysqli_query($conexao, $sql);
	return $inserir;
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
11,
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
function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia, $tipoLog) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'$tipoLog {$nomealuno}',{$idcolaborador},{$idacademia})";

	echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

?>