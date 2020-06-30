<?php
include 'var-inicio.php';
include 'conecta.php';
include 'busca-dados-login.php';
include 'cabecalho.php';

$idacademia = $dados_usuario['academia'];

?>


<script>
  angular.module("AppMegaTreino",["ngMessages","angularUtils.directives.dirPagination"]);
  angular.module("AppMegaTreino").controller("AppMegaTreinoCtrl",function ($scope, $http) {
    // body...
    $scope.currentPage = 1;
    $scope.pageSize = 20;
    $scope.dados = [];
    $scope.total = [];
    $scope.totalRegistro = [];
    $scope.professores = [];
    $scope.modalidades = [];
    $scope.turma = [];
    $scope.formaPagamento = [];
    $scope.dataI = dataHoje();
    $scope.dataF = dataHoje();

    $scope.dataI2;

    $scope.company="Grupo Brasil Móbile";
    //$scope.urlBase="http://localhost/novo%20mega%20treino/services/";
    //$scope.urlBase2="http://localhost/novo%20mega%20treino/services/";
    //$scope.urlBase3="http://localhost/novo%20mega%20treino/services/";
    $scope.urlBase="https://www.megatreino.com.br/AcademiaNova/services/";
    $scope.urlBase2="https://www.megatreino.com.br/AcademiaNova/services/";
    $scope.urlBase3="https://www.megatreino.com.br/AcademiaNova/services/";








    var state='C';

    $scope.hoje=new Date();

    $scope.ordenar = function(keyname){
      $scope.sortKey = keyname;
      $scope.reverse = !$scope.reverse;
    };

    var carregaDados = function function_name(dataI,dataF){
      $http({
        method: 'GET',
        url: $scope.urlBase+'pagarReceber_relatorio.php?id='+<?=$idacademia?>+'&state='+state+'&dataI='+$scope.dataI+'&dataF='+$scope.dataF
      }).then(function onSuccess(response){
        $scope.dados=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }

    carregaDados();




    var carregaTotal = function function_name(dataI,dataF){
      $http({
        method: 'GET',
        url: $scope.urlBase2+'pagarReceber_relatorio_total.php?id='+<?=$idacademia?>+'&state='+state+'&dataI='+$scope.dataI+'&dataF='+$scope.dataF
      }).then(function onSuccess(response){
        $scope.total=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }

    carregaTotal();

    var carregaTotalRegistro = function function_name(dataI,dataF){
      $http({
        method: 'GET',
        url: $scope.urlBase3+'pagarReceber_relatorio_total_registro.php?id='+<?=$idacademia?>+'&state='+state+'&dataI='+$scope.dataI+'&dataF='+$scope.dataF
      }).then(function onSuccess(response){
        $scope.totalRegistro=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }

    carregaTotalRegistro();


    $scope.busca = function(dataI,dataF){

      $http({
        method: 'GET',
        url: $scope.urlBase+'pagarReceber_relatorio.php?id='+<?=$idacademia?>+'&state='+state+'&dataI='+$scope.dataI+'&dataF='+$scope.dataF
      }).then(function onSuccess(response){

      }).catch(function onError(response){

      });

      $http({
        method: 'GET',
        url: $scope.urlBase2+'pagarReceber_relatorio_total.php?id='+<?=$idacademia?>+'&state='+state+'&dataI='+$scope.dataI+'&dataF='+$scope.dataF
      }).then(function onSuccess(response){

      }).catch(function onError(response){

      });

      $http({
        method: 'GET',
        url: $scope.urlBase2+'pagarReceber_relatorio_total_registro.php?id='+<?=$idacademia?>+'&state='+state+'&dataI='+$scope.dataI+'&dataF='+$scope.dataF
      }).then(function onSuccess(response){

      }).catch(function onError(response){

      });

      carregaDados();
      carregaTotal();
      carregaTotalRegistro();

    };





    var carregaProfessores = function function_name(){
      $http({
        method: 'GET',
        url: $scope.urlBase+'professores.php?id='+<?=$idacademia?>+'&state='+state
      }).then(function onSuccess(response){
        $scope.professores=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }

    carregaProfessores();

        var carregaDadosModalidade = function function_name(){
      $http({
        method: 'GET',
        url: $scope.urlBase+'modalidade.php?id='+<?=$idacademia?>+'&state='+state
      }).then(function onSuccess(response){
        $scope.modalidades=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }

    carregaDadosModalidade();

     var carregaDadosTurma = function function_name(){
      $http({
        method: 'GET',
        url: $scope.urlBase+'turmas.php?id='+<?=$idacademia?>+'&state='+state
      }).then(function onSuccess(response){
        $scope.turma=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }
carregaDadosTurma();

var carregaFormaDePagamento = function function_name(){
      $http({
        method: 'GET',
        url: $scope.urlBase+'forma_de_pagamento_lista.php?id='+<?=$idacademia?>+'&state='+state
      }).then(function onSuccess(response){
        $scope.formaPagamento=response.data.result[0];
      }).catch(function onError(response){
        $scope.resultado=response.data.result[0];
      });
    }
carregaFormaDePagamento();



    function dataHoje() {
     var data = new Date();
     var dia = data.getDate();
     var mes = data.getMonth() + 1;
     if (mes < 10) {
        mes = "0" + mes;
    }
    var ano = data.getFullYear();

    var result = ano+"-"+mes+"-"+dia;
    return result;
}

$scope.acertacampos1 = function(){

  var dataI1 = new Date($scope.dataI);
  dataI1.setDate(dataI1.getDate());

  var diaI = dataI1.getDate();
  var mesI = dataI1.getMonth()+1;
  var anoI = dataI1.getFullYear();


  if (diaI<=9){
        diaI='0'+diaI;
      }

      if (mesI<=9){
        mesI='0'+mesI;
      }


  $scope.dataI= [anoI, mesI, diaI].join('-');



}

$scope.acertacampos2 = function(){

  var dataF1 = new Date($scope.dataF);
  dataF1.setDate(dataF1.getDate());

  var diaF = dataF1.getDate();
  var mesF = dataF1.getMonth()+1;
  var anoF = dataF1.getFullYear();


  if (diaF<=9){
        diaF='0'+diaF;
      }

      if (mesF<=9){
        mesF='0'+mesF;
      }


  $scope.dataF= [anoF, mesF, diaF].join('-');



}



});



</script>


 <script type="text/javascript">
      jQuery(window).load(function($){
        Relogio();
      });







    </script>



<style type="text/css">

  .relatorio{display: none;}
  .pesquisa{margin-right: -20px;}
  .lg{width: 100%;}
  select{margin-left:3px;}

  @media print {

    .relatorio{display: block;}
    .no-print{display: none;}


    /*td{font-size: 12px !important; height: 4px !important; padding: 0 !important;}

     tr:nth-child(even) td:nth-child(odd){background-color:#BEBDBD !important; }*/


}


</style>
<div>
  <div class="content-wrapper">
  <!-- Cabeçalho da Página -->
    <section class="content-header">
      <h1>
        Mensalidades recebidas
        <small>Listagem</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Início</a></li>
        <li class="active">Mensalidades recebidas</li>
      </ol>
    </section>
    <!-- Conteúdo Principal -->
    <section class="content"  ng-controller="AppMegaTreinoCtrl">
      <div class="row">
        <div class="col-md-12">
          <!-- Cabeçalho do Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <ul class="nav navbar-nav lg">
              <div class="row">
                <li>
                  <?php
if (substr($me_grupo_muscular, 1, 1) == 'S') {?>



                    <li>



                      <div class="col-md-3 pesquisa" >

                        <label for="" class="no-print">Por professor</label>

                        <select name="professor" class="form-control no-print" ng-model="filtrarProf">
                           <option value="">Todos professores</option>
                            <option ng-repeat="option in professores" value="{{option.idcolaborador}}">{{option.nomecolaborador}}</option>
                        </select>

                      </div>

                   </li>


                   <li>

                      <div class="col-md-3 pesquisa">

                        <label for="" class="no-print">Por modalidades</label>

                        <select name="modalidade" class="form-control no-print" ng-model="filtrarmod">
                          <option value="">Todas modalidades</option>
                          <option ng-repeat="option in modalidades" value="{{option.id}}">{{option.nome}}</option>
                        </select>

                      </div>

                    </li>

                    <li>

                    <div class="col-md-3 pesquisa">

                      <label for="" class="no-print">Por turmas</label>

                      <select name="turma" class="form-control no-print" ng-model="filtrarturma">
                          <option value="">Todas as turmas</option>
                          <option ng-repeat="option in turma" value="{{option.id}}">{{option.nome}}</option>
                      </select>

                    </div>


                   </li>

                   <li>

                    <div class="col-md-3 pesquisa">

                      <label for="" class="no-print">Por pagamento </label>

                      <select name="pagamento" class="form-control no-print" ng-model="filtrarpagamento">
                          <option value="">Todas os pagamento</option>
                          <option ng-repeat="option in formaPagamento" value="{{option.id}}">{{option.descricao}}</option>
                      </select>

                    </div>




                   </li>





                    <li>

                    <div class="col-md-3 pesquisa">

                      <label for="" class="no-print">De:</label>

                      <input type="date" class="form-control no-print" ng-model="dataI" value="<?php echo date('Y-m-d'); ?>" ng-change="acertacampos1()">

                      </div>

                      <div class="col-md-3 pesquisa">

                      <label for="" class="no-print">Até:</label>
                      <input type="date" class="form-control no-print" ng-model="dataF"value="<?php echo date('Y-m-d'); ?>" ng-change="acertacampos2()">

                    </div>

                  </li>

                  <li>

                  <div class="col-md-3">

                    <label for=""></label>

                      <button class="btn btn-default btn-block no-print" ng-value="Print this page" ng-click="busca(dataI,dataF)"><i class="fa fa-print"></i> Buscar</button>


                    </div>

                  </li>




                   <li>

                    <div class="col-md-3">

                      <label for=""></label>

                      <button class="btn btn-default btn-block no-print" ng-value="Print this page" onClick="window.print()"><i class="fa fa-print"></i> Imprimir</button>

                    </div>

                   </li>




                  <?php
}
?>




                </li>

            </div>

              </ul>
              <?php
if (substr($me_grupo_muscular, 4, 1) == 'S') {?>




              <?php
}?>
            </div>
            <!--<form role="form"> -->
              <div class="box-body">

                <?php
//alert("Exercício Removido com Sucesso !!!");
if ($removido == "t") {
	?>
                  <?php
$removido = "f";
}
?>

<!--cabeçalho-->
             <div class="row relatorio">




              <div class="col " style="float: left; width: 500px;">

                    <img src="imagens/logo_mega_mini.png" alt="" class="rounded float-left" style="float: left; padding: 15px 15px 15px 15px;">
                    <p><span><?=utf8_encode($dados_academia['nome'])?></span>
                    </p>


                     <h4> Relatório de mensalidades recebidas</h4>
              </div>

               <div class="col pull-right"  style="margin-right: 15px;">

                    <span id="data" style="font-size: 16px;"></span>

                    <span id="hora" style="font-size: 16px;"></span>



                     <script>
                          function Relogio(){
                            var momentoAtual = new Date();

                            var vhora = momentoAtual.getHours();
                            var vminuto = momentoAtual.getMinutes();
                            var vsegundo = momentoAtual.getSeconds();

                            var vdia = momentoAtual.getDate();
                            var vmes = momentoAtual.getMonth() + 1;
                            var vano = momentoAtual.getFullYear();

                            if (vdia < 10){ vdia = "0" + vdia;}
                            if (vmes < 10){ vmes = "0" + vmes;}
                            if (vhora < 10){ vhora = "0" + vhora;}
                            if (vminuto < 10){ vminuto = "0" + vminuto;}
                            if (vsegundo < 10){ vsegundo = "0" + vsegundo;}

                            dataFormat = " " + vdia + "/" + vmes + "/" + vano + " ";
                            horaFormat = " " + vhora + ":" + vminuto + ":" + vsegundo  + " ";

                            document.getElementById("data").innerHTML = dataFormat;
                            document.getElementById("hora").innerHTML = horaFormat;

                            setTimeout("Relogio()",1000);
                          }






                      </script>



              </div>

              <table class="table table-bordered table-striped">

                  <thead>
                    <th ng-click="ordenar('descricao')" style="width: 50px;">Matricula</th>
                    <th ng-click="ordenar('descricao')">Aluno</th>
                    <th ng-click="ordenar('descricao')">Histórico</th>
                    <th ng-click="ordenar('descricao')">Pagamento</th>
                    <th ng-click="ordenar('descricao')">Valor</th>

                  </thead>
                  <tbody>



                    <tr ng-repeat="linha in dados|filter:{professor:filtrarProf}|filter:{modalidade:filtrarmod}|orderBy:sortKey:reverse">


                       <td>{{linha.matricula}}</td>

                      <td>{{linha.nome}}</td>

                      <td>{{linha.historico}}</td>

                      <td>{{linha.pagamento|date:'dd/MM/yyyy'}}</td>

                      <td>{{linha.valpago|currency:'R$'}}</td>


                    </tr>
                  </tbody>
                </table>


                <div class="row">
                  <div class="col-md-6">
                    <span ng-repeat="linha in totalRegistro">Total de registro: {{linha.rg}}</span>
                  </div>

                  <div class="col-md-6">
                    <span ng-repeat="linha in total">Valor total: {{linha.pg | currency:'R$'}}</span>
                  </div>

                </div>

    </div>
<!--Fim cabeçalho-->


                <!-- <h4>Página Atual: {{ currentPage }}</h4> -->
                <table class="table table-bordered table-striped no-print">
                <!-- <table id="example4" class="table table-bordered table-hover"> -->
                  <thead>
                    <th ng-click="ordenar('descricao')" style="width: 50px;">Matricula</th>
                    <th ng-click="ordenar('descricao')">Aluno</th>
                    <th ng-click="ordenar('descricao')">Histórico</th>
                    <th ng-click="ordenar('descricao')">Pagamento</th>
                    <th ng-click="ordenar('descricao')">Valor</th>

                  </thead>
                  <tbody>

                    <!--filter:{pagamento:(dataI|date:'yyyy-MM-dd')} -->

                    <tr dir-paginate="linha in dados|filter:{professor:filtrarProf}|filter:{modalidade:filtrarmod}|orderBy:sortKey:reverse| itemsPerPage: pageSize" current-page="currentPage">


                       <td>{{linha.matricula}}</td>

                      <td>{{linha.nome}}</td>

                      <td>{{linha.historico}}</td>

                      <td>{{linha.pagamento|date:'dd/MM/yyyy'}}</td>

                      <td>{{linha.valpago|currency:'R$'}}</td>


                    </tr>
                  </tbody>
                </table>

                <dir-pagination-controls max-size="7" boundary-links="true" class="no-print"></dir-pagination-controls>



              </div>
              <!-- Rodapé do Box -->

              <div class="box-footer">

                  <!-- <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)"></dir-pagination-controls> -->
                  <!-- <dir-pagination-controls max-size="5" boundary-links="true"></dir-pagination-controls>
                  <button type="submit" class="btn btn-primary" style="margin:10px;"><i class="fa fa-save"></i> Salvar</button>
                  <button type="reset" class="btn btn-cancel" style="margin:10px;"><i class="fa fa-close"></i> Cancelar</button>  -->
              </div>

            <!--</form> -->
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php
include 'rodape_form.php';
?>
</div>





<?php
include 'rodape.php';
?>
