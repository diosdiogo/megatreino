
<?php

include 'conecta.php';

$cod_atualiza = rand(10000, 9999999);

//if (isset($_GET['renova'])) {
//Renovar
	$renova = $_GET['renova'];
	$idaluno = $_GET["idaluno"];
	$idacademia = $_GET["academia"];
	$pago = 1;
	$idcolaborador = $_GET["idcolaborador"];
	$ocorrencia = $_GET["ocorrencia"];
	$documento = $_GET["documento"];
	$dataI = $_GET["dataI"];
	$canc = 'PZ';
	$tipodoc = tipodoc($conexao, $idacademia, $canc);
	$parc = '1/1';
	$bloq_banco = 0;
	$banco = $_GET["banco"];
	$idmodalidade = $_GET["idmodalidade"];
	$modnome = planoNome($conexao, $idacademia, $idmodalidade);
	$planonome = utf8_decode($_GET["planonome"]);
	//$valor = valorPlano($conexao, $idacademia, $idmodalidade, $planonome);
	$valorRecebe=$_GET['valor'];

	$valor = str_replace('R','',$valorRecebe);
	$valor = str_replace('$','',$valor);
	$valor = str_replace(',','',$valor);
	//print("Valor plano: " . $valor . "<br>");

	$desconto = $_GET["desconto"];
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

	if ($canc == 'PZ') {

		$canc = 'BL';

	}

	$vencimento = $_GET["datavenc"];
	$mes = substr($dataI, 0, 8);
	$vezes = $_GET["vezes"];

	$dataF = $_GET['dataF'];
	//$dataF = date('Y-m-d', strtotime($mes . $vencimento));

	//$parcelaValor = number_format($valor['valor'] / $vezes, 2, '.', '');

	$parcelaValor = number_format($valor / $vezes, 2, '.', '');
/*
	print("Renova: " . $renova . "<br>");
	print("Data inicio: " . $dataI . "<br>");
	print("Ano Mes: " . $mes . "<br>");
	print("Data Fim: " . $dataF . "<br>");
	print("ID alno: " . $idaluno . "<br>");
	print("ID Academia: " . $idacademia . "<br>");
	print("Ocorrencia: " . $ocorrencia . "<br>");
	print("Documento: " . $documento . "<br>");
	print("Documento: " . $documento . "<br>");
	print("Tipo de Pagamento: " . $canc . "<br>");
	print("ID de Pagamento: " . $tipodoc['idforma_pagamento'] . "<br>");
	print("ID do banco: " . $banco . "<br>");
	print("ID de modalidade: " . $idmodalidade . "<br>");
	print("Modalidade nome: " . $modnome['nome'] . "<br>");
	print("Plano nome: " . $planonome . "<br>");
	print("Valor plano: " . $valor . "<br>");
	print("Desconto: " . $desconto . "<br>");
	print("histórico: " . $historico . "<br>");
	print("Dia de Vencimento: " . $vencimento . "<br>");
	print("Vezes: " . $vezes . "<br>");
	print("Parcelas2: " . $parcelaValor . "<br>");
*/
	$array['parcelas'] =  json_decode(file_get_contents("php://input"), true);


	foreach ($array['parcelas']['parcelas'] as $parcela) {
		
		$i= $parcela['id']+1;

		//echo 'Vezes: '.$i.'<br>';
		//echo 'Vezes: '. $parcela['vezes'].'<br>';
		//echo "Vencimentos: " . $parcela['vencimento'].'<br>';
		$parcelaValorP = ($parcela['parcela']);
		//echo "Parcelas: " . $parcelaValorP.'<br>';
		if ($i == 1) {

			if (!isset($_GET['dividirPagamento'])) {
				alterarPagamento($conexao, $idaluno, $documento, $parcela['vezes'], $parcela['vencimento'], $tipodoc, $canc, $ocorrencia, $parcelaValor, $i);
			}
			else{
				inserePagamento($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $parcela['vencimento'], $parcelaValor, $desconto, $historico, $parcela['vezes'], $canc, $i);
			}
			
		}
		else {

			inserePagamento($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $parcela['vencimento'], $parcelaValor, $desconto, $historico, $parcela['vezes'], $canc, $i);
		}

		alteraAluno($conexao, $idaluno, $cod_atualiza);
		
	}


function alterarPagamento($conexao, $idaluno, $documento, $parc, $dataF, $tipodoc, $canc, $ocorrencia, $valorParcelado, $i) {

	$query = "update pagar_receber set parc='{$parc}', bloq='{$parc}', canc='{$canc}', vencimento='{$dataF}', valor={$valorParcelado}, val_origem=0, bloq_banco={$i}
where aluno_fornecedor={$idaluno} and documento={$documento} and ocorrencia={$ocorrencia} and id>0";

	//echo '<p>alterar pagamento: ' . $query . '</p>';

	$alterar = mysqli_query($conexao, $query);

	mysqli_affected_rows($conexao);

	echo ("ID alterado = \n" . mysqli_affected_rows($conexao)) . '</p>';
	echo $query . '<br>';

	return $alterar;

}

function inserePagamento($conexao, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataF, $valorParcelado, $desconto, $historico, $parc, $canc, $i) {

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
liberadoate,
valor,
desconto,
tipodoc,
historico,
val_origem,
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
'{$dataF}',
{$valorParcelado},
{$desconto},
0,
upper('{$historico}'),
0,
'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	echo ("ID inserido = \n" . mysqli_insert_id($conexao)) . '</br>';
	echo $query . '<br>';

	return $inserir;

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

function planoNome($conexao, $academia, $mod) {

	$dados = array();

	$sql = "select * from modalidade where academia={$academia} and id={$mod}";

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

function alteraAluno($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='Liberado', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";

	$alterar = mysqli_query($conexao, $query);
	mysqli_affected_rows($conexao);

	//echo "<br> alterar aluno" . $query . "<br>";
	echo ("ID alterado = \n" . mysqli_affected_rows($conexao));

	return $alterar;
}