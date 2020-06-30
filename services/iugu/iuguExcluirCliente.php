
<?php 

header('Access-Control-Allow-Origin: *');


$apiToken=$_POST['apiToken'];
echo "API token: ". $apiToken."<br>";

$idClienteIugu=$_POST['idClienteIugu'];
echo "ID: ".$idClienteIugu."<br>";


$config['apiToken'] = ''.$apiToken.''; //token da subconta

$headers = array(
  'Content-Type: application/json',
  'Authorization: Basic '.base64_encode($config['apiToken'].':')
);

$data = array(

  'id'=> ''.$idClienteIugu  .'',
  
  );

$url = 'http://api.iugu.com/v1/customers/id';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
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
 

curl_close($curl);


 ?>