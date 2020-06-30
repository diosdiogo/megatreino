<?php
    include 'conecta.php';
    date_default_timezone_set('America/Bahia');
    $data = date('Y-m-d'); 
    $hora = date('H:i:s');
    $array = json_decode(file_get_contents("php://input"), true);

    $cadastarCP = $array['cadastarCP'];
    $valor = str_replace('R','',$cadastarCP['valor']);
    $valor =  str_replace('$','',$valor);
    $valor =  str_replace(',','',$valor);

    for($i=0; $i < $cadastarCP['parcelas']; $i++){
        
        $parcela = $i+1 . '/'. $cadastarCP['parcelas'];
        cadastrarContasPagar($conexao, $parcela, $cadastarCP['fornecedor'], $cadastarCP['colaborador'], $data, $hora, $cadastarCP['dataV'], $valor, $cadastarCP['historico'], $cadastarCP['academia'], $cadastarCP['descricao']);
    }

  

    function cadastrarContasPagar($conexao, $parc, $fornecedor, $colaborador, $dataEmissao, $hora, $vencimento, $valor, $historico, $academia, $descricao){
        ocorencia($conexao, $dataEmissao, $hora);
        $sql = "INSERT INTO pagar_receber(
            documento, 
            pagar_receber, 
            parc, 
            aluno_fornecedor, 
            colaborador, 
            emissao, vencimento, valor, canc, 
            historico, 
            obs, val_origem, pago, academia, 
            ocorrencia, 
            historico_model, deletado) 
            select (max(documento)+1), 
            'P', 
            '$parc', 
            $fornecedor, 
            $colaborador, 
            '$dataEmissao', '$vencimento', '$valor','N', 
            (SELECT descricao FROM historico where idhistorico = $historico), 
            '$descricao', '$valor',0, $academia, 
            (SELECT max(id) FROM ocorrencia),
             $historico, 'N' from pagar_receber;";

        $query = mysqli_query($conexao,$sql);

        echo $sql;
    }

    function  ocorencia($conexao, $dataEmissao, $hora){
        $sql = "INSERT INTO ocorrencia (datahora)value('$dataEmissao $hora'); ";
        
        $query = mysqli_query($conexao,$sql);

    }
?>