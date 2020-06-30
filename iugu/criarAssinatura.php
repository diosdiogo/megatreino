<?php


$tokenAcademia = $_GET['tokenAcademia'];
//echo 'Token Academia: '. $tokenAcademia.'<br>';

$idIugu=$_GET['idIugu'];
//echo 'ID iugu cliente: '.$idIugu.'<br>';

$nomeAcademia =$_GET['nomeAcademia'];
//echo 'Nome Academia: '. $nomeAcademia.'<br>';
 
$modalidade = $_GET['modnome'];
//echo 'Modalidade: '. $modalidade.'<br>';

$data = date('d-m-Y', strtotime($_GET['dataInicio']));
//echo 'Data Inicio: '. $data.'<br>';

$valor = str_replace('.', '',substr($_GET['valor'], 2));
//echo 'Valor: '. $valor.'<br>';

$subContaNome=$_GET['subContaNome'];
$subContaId=$_GET['subContaId'];
//echo 'ID Subconta: '. $subContaId.'<br>';

$config['apiToken'] = ''.$tokenAcademia.''; //token da subconta

$headers = array(
  'Content-Type: application/json',
  'Authorization: Basic '.base64_encode($config['apiToken'].':')
);

$data = array(
  'plan_identifier'=>'plano_mensal',
  'customer_id'=>''.$idIugu.'',
  'expires_at'=>''.$data.'',
  'only_on_charge_success'=>'true',
  'subitems'=>[[
    'description'=>''.$modalidade.'',
    'price_cents'=>''.$valor.'',
    'quantity'=>'1',
    'recurrent'=>'true'
  ]],
  'custom_variables'=>[[
    'name'=>''.$subContaNome.'',
    'value'=>''.$subContaId.''
  ]]
  );

$url = 'https://api.iugu.com/v1/subscriptions';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
$response = curl_exec($curl);
$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if (!empty(curl_error($curl)))
{
  //echo curl_error($curl);
  echo 0;
}
else
{
  $array_response = json_decode($response, true);
  //echo $response_code;
  //echo '<pre>';
  //print_r($array_response);
  //echo '</pre>';
  //echo "--------------------------------";

  //echo '<pre>'.$array_response['id'].'</pre>';
  //echo '<pre> 1'.$array_response['recent_invoices'][0]['status'].'</pre>';
  //echo ('Erro: '.$array_response['errors']);
 
 if ($response_code == 200) {
  
   
    if ($array_response['LR'] == 57) {
      //echo $array_response['LR'];

      $retorno = array('errors' => $array_response['errors'],
                      'LR'=>$array_response['LR'] );
      echo json_encode($retorno);
    }
    else if ($array_response['LR'] == 51) {
      //echo $array_response['LR'];
      $retorno = array('errors' => $array_response['errors'],
                      'LR'=>$array_response['LR'] );
      echo json_encode($retorno);
    }
    else if ($array_response['LR'] == 15) {
      //echo $array_response['LR'];
       $retorno = array('errors' => $array_response['errors'],
                      'LR'=>$array_response['LR'] );
        echo json_encode($retorno);

    }

    else if($array_response['recent_invoices'][0]['status']=='paid'){
    $retorno = array('status' => $array_response['recent_invoices'][0]['status'],
                      'id'=>$array_response['id'] );

    echo json_encode($retorno);
    //echo $array_response['id'];
    //echo '<pre>';
    //print_r($array_response);
    //echo '</pre>';
    }
    else{
      echo 0;
    }
  }else{
    echo 0;
  }
  
}

curl_close($curl);

?>
