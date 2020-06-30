
<?php 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
include '../conecta.php';
include '../../aluno_banco.php';

$contaMestre = '297fc619d4ad21f4b39f2dee2ff98c1d';
echo "Conta Mestre".$contaMestre. "<br>";

$idaluno=$_GET['idaluno'];
echo $idaluno."<br>";
$tokenAcademia=$_GET['tokenAcademia'];

$nome=$_GET['name'];
$email=$_GET['email'];

if (isset($_GET['telefone'])) {
	
	$telefone=$_GET['telefone'];
}else{
	$telefone="";
}


if (isset($_GET['cpf'])) {
	$cpf=limpaCPF_CNPJ($_GET['cpf']);
	echo $cpf."<br>";
	
}else{
	$cpf="";
}


if (isset($_GET['cep'])) {
	$cep=$_GET['cep'];
}else{
	$cep="";
}


if (isset($_GET['numero'])) {
	$numero=$_GET['numero'];
}else{
	$numero="";
}


if (isset($_GET['endereco'])) {
	$endereco=$_GET['endereco'];
}else{
	$endereco="";
}


if (isset($_GET['cidade'])) {
	$cidade=$_GET['cidade'];

}else{
	$cidade="";
}

if (isset($_GET['uf'])) {
	$uf=$_GET['uf'];
}else{
	$uf="";
}


if (isset($_GET['bairro'])) {
	$bairro=$_GET['bairro'];
}else{
	$bairro="";
}


$subContaNome=$_GET['subContaNome'];
$subContaId=$_GET['subContaId'];

$config['apiToken'] = ''.$contaMestre.''; //token da subconta

$headers = array(
  'Content-Type: application/json',
  'Authorization: Basic '.base64_encode($config['apiToken'].':')
);

$data = array(

  'name'=> ''.$nome.'',
  'email'=>''.$email.'',
  //'phone'=>''.$telefone.'',
  //'phone_prefix'=>'',
  'cpf_cnpj'=>$cpf,
  'zip_code'=>''.$cep.'',
  'number'=>''.$numero.'',
  'street'=>''.$endereco.'',
  'city'=>''.$cidade.'',
  'state'=>''.$uf.'',
  'district'=>''.$bairro.'',
  'custom_variables' =>[[
  	'name' =>''.$subContaNome.'',
  	'value' => ''.$subContaId.''
  ]]
  	

  );

$url = 'https://api.iugu.com/v1/customers';

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
  echo ('id: '.$array_response['id']);

}
alteraAlunoIugu($conexao, $array_response['id'], $idaluno);
curl_close($curl);

function limpaCPF_CNPJ($valor){
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 return $valor;
}

 ?>