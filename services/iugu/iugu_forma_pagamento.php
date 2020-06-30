
<?php 

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');



$tokenAcademia = $_GET['tokenAcademia'];
echo 'Token Academia: '. $tokenAcademia.'<br>';

$token=$_GET['token'];
echo 'Token Cartão: '. $token.'<br>';

$idIugu=$_GET['idIugu'];
echo 'ID iugu cliente: '.$idIugu.'<br>';

$nomeAcademia =$_GET['nomeAcademia'];
echo 'Nome Academia: '. $nomeAcademia.'<br>';
 
$modalidade = $_GET['modnome'];
echo 'Modalidade: '. $modalidade.'<br>';

$data = date('d-m-Y', strtotime($_GET['dataInicio']));
echo 'Data Inicio: '. $data.'<br>';

$valor = substr($_GET['valor'], 2);
echo 'Valor: '. $valor.'<br>';

$subContaNome=$_GET['subContaNome'];
$subContaId=$_GET['subContaId'];


$config['apiToken'] = ''.$tokenAcademia.'';

$headers = array(
  'Content-Type: application/json',
  'Authorization: Basic '.base64_encode($config['apiToken'].':')
);

$data = array(

  
  'description'=>''.$nomeAcademia.'',
  'token'=>''.$token.'',
  'set_as_default'=> 'true'
 
  );

$url = 'https://api.iugu.com/v1/customers/'.$idIugu.'/payment_methods';

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
  echo curl_error($curl);

}
else
{
  $array_response = json_decode($response, true);
  echo $response_code;
  echo '<pre>';
  print_r($array_response);
  echo '</pre>';

}
 //echo '<br> teste: '.$array_response;

 

curl_close($curl);



 ?>