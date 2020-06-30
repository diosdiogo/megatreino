<?PHP
    include 'conecta.php';

    
    setlocale(LC_ALL, 'pt_BR.utf-8');
    date_default_timezone_set('America/Bahia');

    $id = $_GET["id"];

    if(isset($_GET['state'])){
        $state = $_GET["state"];

        if ($state == "C") {
            $lista = '{"result":[' . json_encode(getFornecedor($conexao, $id)) . ']}';
            echo $lista;
        }

    }
    if(isset($_GET['remover'])){
        $idfornecedor = $_GET['idfornecedor'];
        removerFornecedor($conexao, $id, $idfornecedor);
    }

    function getFornecedor($conexao, $id){
        
        $retorno = array();

        $sql="SELECT * FROM academia.fornecedor where academia = $id;";
        
        $query = mysqli_query($conexao,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($retorno, array(
                'id' =>$row['id'],
                'razao_social' =>utf8_encode($row['razao_social']),
                'nome_fantasia' =>utf8_encode($row['nome_fantasia']),
                'cpfCnpj' =>utf8_encode($row['cpfCnpj']),
                'endereco' =>utf8_encode($row['endereco']),
                'numero' =>utf8_encode($row['numero']),
                'complemento' =>utf8_encode($row['complemento']),
                'bairro' =>utf8_encode($row['bairro']),
                'cep' =>utf8_encode($row['cep']),
                'cidade' =>utf8_encode($row['cidade']),
                'estado' =>utf8_encode($row['estado']),
                'email' =>utf8_encode($row['email']),
                'telefone' =>utf8_encode($row['telefone']),
                'celular' =>utf8_encode($row['celular']),
                'contato' =>utf8_encode($row['contato']),
                'foneContato' =>utf8_encode($row['foneContato']),
                'academia' =>utf8_encode($row['academia']),
                'codinterno' =>$row['codinterno'],

            ));
        }

        return $retorno;
    }
    
    function removerFornecedor($conexao, $id, $idfornecedor){
        $sql = "DELETE FROM fornecedor WHERE (id = '$idfornecedor');";
        
        $query = mysqli_query($conexao, $sql);


    }
?>