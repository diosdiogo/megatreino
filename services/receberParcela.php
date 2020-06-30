<?php 
include 'conecta.php';
include 'pagamento_cartao.php';
date_default_timezone_set('UTC');
$cod_atualiza = rand(10000, 9999999);

	
	if (isset($_GET['retorno'])==1) {
	
	$id=$_GET['id'];
	$historico=$_GET['historico'];
	$vencimento=$_GET['vencimento'];
	$valor=$_GET['valor'];
	$descricao=$_GET['descricao'];
	$dataInicio= date("Y-m-d");

	$array = array(
		"id"=> $id,
		"historico"=> $historico,
		"vencimentoMatricula"=> $vencimento,
		"dataInicio"=>$dataInicio,
		"dataFim"=> $vencimento,
		"valor"=> $valor,
		"desconto"=> "0",
		"total"=> $valor,
		);

	$pagamento = '{"result":[' . json_encode($array) . ']}';

	echo $pagamento;
	}

	if (isset($_GET['pagamentoUnico'])) {

		$parc = '1/1';
		$pago = 1;
		$bloq_banco = 0;
		$idacademia=$_GET['idacademia'];
		//echo 'ID Academia: '.$idacademia.'<br>';
		$idaluno=$_GET['idaluno'];
		$id=$_GET['id'];
		//echo 'ID: '.$id.'<br>';
		$valor=$_GET['valor'];
		//echo 'Valor: '.$valor.'<br>';
		$canc=$_GET['canc'];
		//echo 'Canc: '.$canc.'<br>';
		$tipodoc = tipodoc($conexao, $idacademia, $canc);
		//echo 'Tipo Doc id: '.$tipodoc['idforma_pagamento'].'<br>';
		$pagar_receber=pagar_receber($conexao, $id);
		$desc_documento = "PAGAR RECEBER";
		$nomealuno = $_GET["nomealuno"];
		$idcolaborador = $_GET["idcolaborador"];
		$banco = $_GET["banco"];
		
		if ($canc=='DN' or $canc=='CH'){
			alterarPagamentoReceber($conexao, $id, $parc, $tipodoc['idforma_pagamento'], $canc, $bloq_banco, $pago, $valor);
			//movimentoCaixaAberto($conexao, $pagar_receber['documento'], $idacademia, $pagar_receber['historico'], $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor, $banco, $idcolaborador, $pagar_receber['ocorrencia']);

		}
		if ($canc == 'CT') {

			if ($canc == 'CT') {

				$canc = 'CA';

			}

			
			$vencimento = $_GET["vencimento"];
			$dataI=$vencimento;
			//echo 'Vencimento: '.$vencimento.'<br>';
			$vezes = $_GET["vezes"];
			//echo 'Vezes: '.$vezes.'<br>';
			$cataotipo = $_GET["cataotipo"];
			//echo 'Cartão tipo: '.$cataotipo.'<br>';

			
			$modnome=$pagar_receber['historico'];
			//echo 'Historico: '.$modnome.'<br>';
			
			$idcartao = taxaCartao($conexao, $cataotipo);
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

				movimentoCartaoReceber($conexao, $cataotipo, $idacademia, $pagar_receber['documento'], $valorParcelado, $prazo, $idcartao["taxacred"], $dataI, $vlrec, $pagar_receber['ocorrencia'], $parc);

			}

			alterarPagamentoReceber($conexao, $id, $parc, $tipodoc['idforma_pagamento'], $canc, $bloq_banco, $pago, $valor);
			
		}
		movimentoCaixaAberto($conexao, $pagar_receber['documento'], $idacademia, $pagar_receber['historico'], $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor, $banco, $idcolaborador, $pagar_receber['ocorrencia']);
		alteraAluno($conexao, $idaluno, $cod_atualiza);
		verifica($conexao, $idacademia, $idaluno,$cod_atualiza );
	}

	if (isset($_GET['dividirPagamento'])) {

		$parc = '1/1';
		$pago = 1;
		$bloq_banco = 0;
		$idacademia=$_GET['idacademia'];
		//echo 'ID Academia: '.$idacademia.'<br>';
		$idaluno=$_GET['idaluno'];
		$id=$_GET['id'];
		//echo 'ID: '.$id.'<br>';
		$valor=$_GET['valor'];
		//echo 'Valor: '.$valor.'<br>';
		$canc=$_GET['canc'];
		//echo 'Canc: '.$canc.'<br>';
		$tipodoc = tipodoc($conexao, $idacademia, $canc);
		//echo 'Tipo Doc id: '.$tipodoc['idforma_pagamento'].'<br>';
		$pagar_receber=pagar_receber($conexao, $id);
		$desc_documento = "PAGAR RECEBER";
		$nomealuno = $_GET["nomealuno"];
		$idcolaborador = $_GET["idcolaborador"];
		$banco = $_GET["banco"];


		if (isset($_GET['PrimeiroPg'])) {
			alterarPagamentoReceber($conexao, $id, $parc, $tipodoc['idforma_pagamento'], $canc, $bloq_banco, $pago, $valor);
		}else{

			if ($canc == 'CH') {
				inserePagamento($conexao,$pagar_receber['documento'],$parc,$idaluno,$idcolaborador,$pagar_receber['emissao'],$pagar_receber['vencimento'],$valor,$canc,$pagar_receber['historico'],0,$idacademia,$pagar_receber['ocorrencia']);
			}

			if ($canc == 'CT'){
				
				if ($canc == 'CT') {

				$canc = 'CA';

				}
				$vencimento = $_GET["vencimento"];
				$dataI=$vencimento;
				//echo 'Vencimento: '.$vencimento.'<br>';
				$vezes = $_GET["vezes"];
				//echo 'Vezes: '.$vezes.'<br>';
				$cataotipo = $_GET["cataotipo"];
				$banco = $_GET["banco"];
				$idcartao = taxaCartao($conexao, $cataotipo);
				$valorParcelado = $valor / $vezes;
				$prazo = 30;
				$vlrec = $valorParcelado - (($valorParcelado * $idcartao["taxacred"]) / 100);

				for ($i = 1; $i <= $vezes; $i++) {

					$prazo = 30 * $i;
					
					$dataI = date('Y-m-d', strtotime("+1 month", strtotime($dataI)));
					$parc = $i . '/' . $vezes;

					movimentoCartaoReceber($conexao, $cataotipo, $idacademia, $pagar_receber['documento'], $valorParcelado, $prazo, $idcartao["taxacred"], $dataI, $vlrec, $pagar_receber['ocorrencia'], $parc);

				}
			}
			
		}

		movimentoCaixaAberto($conexao, $pagar_receber['documento'], $idacademia, $pagar_receber['historico'], $tipodoc['idforma_pagamento'], $desc_documento, $nomealuno, $valor, $banco, $idcolaborador, $pagar_receber['ocorrencia']);
		alteraAluno($conexao, $idaluno, $cod_atualiza);
		verifica($conexao, $idacademia, $idaluno,$cod_atualiza );
		
		
	}


function alterarPagamentoReceber($conexao, $id, $parc, $tipodoc, $canc, $bloq_banco, $pago, $valor) {

	$query = "update pagar_receber set
						 parc='{$parc}',
						 valor={$valor},
						 pago={$pago},
						 canc='{$canc}',
						 tipodoc={$tipodoc},
						 bloq_banco={$bloq_banco},
						 val_pago={$valor},
						 pagamento=now()

						 where id={$id}";

	//echo '<br>' . $query . '<br>';

	$alterar = mysqli_query($conexao, $query);

	if (mysqli_affected_rows($conexao) == 0) {
		echo 0;
	}else{
		echo 1;
	}

	//echo '<br> alterar pagamento' . $query . '<br>';

	//echo ("<br>ID alterado = \n" . mysqli_affected_rows($conexao));

	return $alterar;
}


function pagar_receber($conexao, $id) {

	$dados = array();

	$sql = "SELECT * FROM academia.pagar_receber where id={$id}";

	$resultado = mysqli_query($conexao, $sql);

	$dados = mysqli_fetch_assoc($resultado);

	return $dados;

}



 ?>