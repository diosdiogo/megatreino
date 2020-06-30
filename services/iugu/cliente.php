<?php 




$headers = array(
  'Content-Type: application/json',
  'Authorization: Basic '.base64_encode($config['apiToken'].':')
);

$url = 'https://api.iugu.com/v1/customers';

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
 //echo $response_code;
 /*
for($i=0; $i <= $array_response['totalItems']; $i++){
	echo '<pre>';
  		echo $array_response['items'][$i]['name'];
  	echo '</pre>';
 }
 */
}

//echo "O total: ".$array_response['totalItems'];

curl_close($curl);

 ?>