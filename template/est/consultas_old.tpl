<style>
        
.dropdown-btn {
    padding: 6px 8px 6px 16px;
    text-decoration: none;
    font-size: 14px;
    color: #23395d;
    display: block;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    outline: none;
}
.dropdown-container {
    padding-left: 25px;
}
.form-control, .x_panel, .select2-selection--multiple{
    border-radius: 5px !important;
}
.btnRelatorios{
  width: 100% !important;
}
.dropdown-container {
    padding: 2px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_consultas.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="row">

              <!-- panel principal  -->  
              <div class="col-md-10 col-xs-12" style="padding: 0;">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consultas
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                         <ul class="dropdown-menu" role="menu">
                              <li>
                              
                                  <button id="limpaDados" type="button" class="btn btn-warning btn-xs btnRelatorios" 
                                          onClick="javascript:limpaDadosForm();"><span> Limpar campos</span></button>    
                              </li>
                         </ul>
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="consultas">   
                            <input name=id            type=hidden value="">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=dataIni       type=hidden value={$dataIni}> 
                            <input name=dataFim       type=hidden value={$dataFim}> 
                            <input name=pessoa        type=hidden value={$pessoa}>
                            <input name=fornecedor    type=hidden value={$fornecedor}>
                            <input name=codProduto    type=hidden value={$codProduto}>
                            <input name=unidade       type=hidden value={$unidade}>
                            <input name=grupoSelected type=hidden value={$grupoSelected}>
                            <input name=tipoLSelected type=hidden value={$tipoLSelected}>
                            <input name=sitLSelected  type=hidden value={$sitLSelected}>
                            <input name=localizacaoSelected  type=hidden value={$localizacaoSelected}>


                        <div class="form-group">
                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label>Data Referencia</label>
                                <SELECT class="form-control" name="dataReferencia"> 
                                    {html_options values=$dataRef_ids output=$dataRef_names selected=$dataReferencia_id}
                                </SELECT>
                            </div>
                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label class="">Per&iacute;odo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Curva ABC</label>
                                <SELECT class="form-control" id="tipoCurva" name="tipoCurva"> 
                                    {html_options values=$tipoCurva_ids output=$tipoCurva_names selected=$tipoCurva_id}
                                </SELECT>
                            </div>
                        </div>
                        <!--
                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>C&oacute;d. Fabricante</label>
                            <input class="form-control" id="codFabricante" name="codFabricante" placeholder="Código do Fabricante."  value={$codFabricante} >
                        </div>
                        -->
                        <div class="form-group">

                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Tipo Grupo</label>
                                <SELECT class="js-example-basic-single form-control"  id="tipoGrupo" name="tipoGrupo"> 
                                    {html_options values=$tipoGrupo_ids output=$tipoGrupo_names selected=$tipoGrupo_id}
                                </SELECT>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Grupo</label>
                                <SELECT class="select2_multiple form-control" multiple="multiple" id="grupo" name="grupo"> 
                                    <div id="grupoDiv">
                                    {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                    </div>
                                </SELECT>
                            </div>
                            <!--div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label for="descProduto">Produto</label>
                                <select class="js-example-basic-single form-control" name="produto" id="produto">
                                        {html_options values=$produto_ids selected=$id_produto output=$produto_names}
                                </select>
                            </div-->
                            
                        </div>
                        <div class="form-group">
                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label for="descProduto">Produto</label>
                                <div class="input-group">
                                    <input class="form-control"  readonly type="text" id="descProduto" name="descProduto" value="{$descProduto}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&origem=pedido');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>                                
                                </div>
                            </div>

                            {*<div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label>Localização</label>
                                <input  class="form-control" type="text" id="localizacao" name="localizacao" placeholder="Digite a localização."   value={$localizacao}>
                            </div> *}

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Localização</label>
                                <SELECT class="select2_multiple form-control" multiple="multiple" id="localizacao" name="localizacao"> 
                                    <div id="grupoDiv">
                                    {html_options values=$localizacao_ids output=$localizacao_names selected=$localizacao_id}
                                    </div>
                                </SELECT>
                            </div>


                        </div>
                        
                        <div class="form-group">
                             <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label class="">Conta</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly id="nome" name="nome" placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Centro de Custo</label>
                                <select class="js-example-basic-single form-control" name="ccusto" id="ccusto"> 
                                    {html_options values=$ccusto_ids output=$ccusto_names selected=$ccusto_id}
                                </SELECT>
                            </div>
                        </div>
                        <div class="form-group">
                            
                        </div>
                        <span class="section"></span>
                        <div class="form-group">
                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label>Tipo NF</label>
                                <select class="select2_multiple form-control" multiple="multiple" id="tipolanc" name="tipolanc">
                                    {html_options values=$tipoLanc_ids selected=$tipoLanc_id output=$tipoLanc_names}
                                </select>
                            </div>
                            
                            <div class="form-group col-md-5 col-sm-12 col-xs-12">
                                <label>Situa&ccedil;&atilde;o</label>
                                <select class="select2_multiple form-control" multiple="multiple" id="sitlanc" name="sitlanc">
                                    {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                </select>
                            </div>

                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label>Numero NF</label>
                                <input class="form-control" id="numNf" name="numNf" placeholder="Numero NF"  value={$numNf} >
                            </div>
                        </div>
                        <div class="form-group">
                           
                        </div>
                        <!--
                        <div class="form-group col-md-8 col-sm-12 col-xs-12">
                            <label>Descri&ccedil;&atilde;o</label>
                            <input class="form-control" id="produtoNome" name="produtoNome" autofocus placeholder="Digite a descrição."  value="{$produtoNome}" >
                        </div>
                        -->
                        
                    </form>
                  </div>

                </div> <!-- x_panel -->
            </div> <!-- div class="tamanho --> 
            <div class="col-md-2 col-sm-6 col-xs-12" style="padding: 1px;">
                <div class="x_panel" style="padding: 1px;">
                    <div class="menu_section">
                        <button class="dropdown-btn" ><center><i class="fa fa-file"></i> Relatórios</center> </button>
                        <div class="dropdown-container">
                            
                            <button type="button" title="Relatório de Compras" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioCompras();">
                                <span>Compras</span>
                            </button><br>
                            <button type="button" title="Relatório de Compras por estoque minimo" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioComprasEstoqueMin();">
                                <span>Compras E. Minimo</span>
                            </button><br>
                            <button type="button" title="Relatório Sugestões de Compras" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioSugestoesCompras();">
                                <span>Sugestões Compras</span>
                            </button><br>
                            <button type="button" title="Tabela de Precos por ordem Alphabetica" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:tabelaPrecos();">
                                <span>Tabela Preços</span>
                            </button><br>
                            <button type="button" title="Tabela de precos por Grupos" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:tabelaPrecosGrupo();">
                                <span>Tabela Preços por Grupo</span>
                            </button><br>
                            <button type="button" title="Relatório de Estoque Geral" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioEstoqueGeral();">
                                <span>Estoque Geral</span>
                            </button><br>
                            <button type="button" title="Relatório de Movimento de Estoque" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioMovimentoEstoque();">
                                <span>Mov. de Estoque</span>
                            </button><br>
                            <button type="button" title="Relatório de Movimento de Estoque por Cliente" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioMovimentoEstoqueCliente();">
                                <span>Mov. Estoque 
                                     Conta</span>
                            </button><br>
                            <button type="button" title="Relatório de Movimento de Estoque Localizacao" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioMovimentoEstoqueLocalizacao();">
                                <span>Mov. de Estoque Localizacao</span>
                            </button><br>

                            <button type="button" title="Relatório de Movimento de Estoque Localizacao" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioLocalizacaoGeral();">
                                <span>Estoque Localizacao</span>
                            </button><br>

                            <button type="button" title="Relatório de Material Consumo Conta" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioMaterialConsumoConta();">
                                <span>Material Consumo Conta</span>
                            </button><br>

                            <button type="button" title="Relatório Kardex Analitico" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioKardex();">
                                <span>Kardex Analítico</span>
                            </button><br>
                            <button type="button" title="Relatório Kardex Sintetico" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioKardexSintetico();">
                                <span>Kardex Sintético</span>
                            </button><br>

                            <button type="button" title="Relatório Curva ABC" class="btn btn-dark btn-xs btnRelatorios" 
                                    onClick="javascript:relatorioCurvaABC();">
                                <span>Curva ABC</span>
                            </button><br>
                        </div>
                        
                    </div>   
                     
                </div> <!-- FIM x_panel -->
            </div>
        </div>  <!-- div row = painel principal-->

      </div> <!-- div class="x_panel"-->
    </div> <!-- div class="x_panel" = tabela principal-->
  </div> <!-- div  "-->
</div> <!-- div role=main-->



    {include file="template/database.inc"}  
    <!-- /Datatables -->
    <!-- select 2 bootstrap -->
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

    <!-- select2 -->
    <script>
        $("#tipoGrupo.js-example-basic-single").select2({
            placeholder: "Selecione o Tipo Grupo",
            allowClear: true
        });
        $("#ccusto.js-example-basic-single").select2({
            placeholder: "Selecione o Centro de Custo",
            allowClear: true
        });
        $("#grupo.select2_multiple").select2({
          placeholder: "Selecione o Grupo"
        });
        $("#tipolanc.select2_multiple").select2({
          placeholder: "Escolha o Tipo NF"
        });
        $("#sitlanc.select2_multiple").select2({
          placeholder: "Escolha a Situação"
        });
        $("#localizacao.select2_multiple").select2({
          placeholder: "Selecione a localização",
          {* maximumSelectionLength: 30, *}
          language: "pt-BR",
          allowClear: true
        });
    </script>

    <script>

     
      $('#tipoGrupo').on('change', function(){
          javascript:submitBuscaGrupo();
      });
    </script>

    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
    

    <!-- daterangepicker -->
    
    <script type="text/javascript">
        $('input[name="dataConsulta"]').daterangepicker(
        {
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 1
            }

        }, 
        //funcao para recuperar o valor digirado        
        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');            
        });
    </script>    
    <!-- /daterangepicker -->
    <!-- side menu relatorios -->
    <script>
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                }else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>

    <!-- -->

    <script>
        $('#limpaDados').click(function () {
            $('#grupo').val('').trigger("change");
            $('#tipolanc').val('').trigger("change");
            $('#sitlanc').val('').trigger("change");
        });
    </script>
