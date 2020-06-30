
<?php 

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');
include '../conecta.php';

$alunoIugu=$_GET['alunoIugu'];
//echo "ID Aluno: ".$alunoIugu.'<br>';

$tokenAcademia = $_GET['tokenAcademia'];
//echo 'Token Academia: '. $tokenAcademia.'<br>';

$clienteIugu = $_GET['clienteIugu'];
//echo 'Cliente Iugu: '. $clienteIugu.'<br>';

$config['apiToken'] = ''.$tokenAcademia.'';

$headers = array(
  'Content-Type: application/json',
  'Authorization: Basic '.base64_encode($config['apiToken'].':')
);
/*
$data = array(

  
  'description'=>''.$nomeAcademia.'',
  'token'=>''.$token.'',
  'set_as_default'=> 'true'
 
  );
*/
$url = 'https://api.iugu.com/v1/customers/'.$clienteIugu.'';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
$response = curl_exec($curl);
$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if (!empty(curl_error($curl)))
{
  echo curl_error($curl);

}
else
{
  $array_response = json_decode($response, true);
  /*
  echo $response_code;
  echo '<pre>';
  print_r($array_response['id']);
  echo '</pre>';
  */
  if ($array_response['id'] == $clienteIugu) {
    echo 1;
  }else{
    echo 2;
    removerIdIugu($conexao, $alunoIugu);
  }

}
 //echo '<br> teste: '.$array_response;

 

curl_close($curl);


function removerIdIugu($conexao, $alunoIugu) {

  $query = "update aluno set idiugu= null where idaluno={$alunoIugu};";

  $alterar = mysqli_query($conexao, $query);
  //echo "<br>".$query;
  return $alterar;

}

 ?>