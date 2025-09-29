<script type="text/javascript" src="{$pathJs}/est/s_baixa_estoque_new.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Movimentação de Estoque Saida</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar()">
                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span> Voltar</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar();">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span>
                            </button>
                        </li>
                        
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                         <ul class="dropdown-menu" role="menu">
                              <li><button type="button" class="btn btn-warning btn-xs" onClick="javascript:limpaDadosForm();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span> Limpar Dados Formulário</span>
                                    </button>
                              </li>
                         </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="baixa_estoque_new">   
                            <input name=id            type=hidden value="">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=pessoa        type=hidden value={$pessoa}>
                            <input name=fornecedor    type=hidden value={$fornecedor}>
                            <input name=codProduto    type=hidden value={$codProduto}>
                            <input name=unidade       type=hidden value={$unidade}>
                            <input name=descProduto   type=hidden value={$descProduto}>
                            <input name=valorVenda    type=hidden value={$valorVenda}> 
                            <input name=uniFracionada type=hidden value="{$uniFracionada}">
                            <input name=pesq          type=hidden value={$pesq}>
                            <input name=genero        type=hidden value={$genero}>
                            
                            <input name=mostraRelMatConsumoConta        type=hidden value={$mostraRelMatConsumoConta}> 

                        <div class="form-group line-formated">
                            <div class="form-group col-md-6 col-sm-6 col-xs-6 line-formated">
                                <label class="">Conta</label>
                                <div class="input-group line-formated">
                                    <input type="text" class="form-control" readonly id="nome" name="nome" placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                        </div>
                        <div class="form-group line-formated">
                            <div class="col-lg-6 col-sm-10 col-xs-10 text-left line-formated">
                                <label>Produto</label>
                                <div class="input-group line-formated">
                                    <input READONLY      
                                    class="form-control" placeholder="Produto" id="pesProduto" 
                                    name="pesProduto" value="{$pesProduto}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&from=baixa_estoque', 'produto');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span> 
                                </div>
                            </div>

                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>Qtde Atual no estoque</label>
                                <input readonly class="form-control" id="quantAtual" name="quantAtual" placeholder="Quantidade Atual."  value={$quantAtual} >
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-3">
                                <label>Quantidade a movimentar</label>
                                <input class="form-control money" id="qtdeEntrada" name="qtdeEntrada" placeholder="Quantidade" value={$qtdeEntrada} >
                            </div>

                        </div>
                        <div class="form-group line-formated">
                            <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                <label for="genero" >G&ecirc;nero</label>
                                <div class="input-group line-formated">
                                    <input readonly type="text" class="form-control" id="descgenero" name="descgenero" placeholder="Genero" required="required"
                                           value="{$descGenero}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div> 
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label for="desc">Observações</label>
                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="3" >{$obs}</textarea>
                            </div>  
                        </div>                

                        
                        
                        
                    </form>
                  </div>
                    
                </div> <!-- x_panel -->

                {if $mostraRelMatConsumoConta eq true}
                    <div role="main">
                        <div class="">
                                <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                                    <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
                                </div>   
                                <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                                    <h2>
                                        <strong>MATERIAL DE CONSUMO CONTA</strong><br>
                                            Periodo - {$periodoIni} | {$periodoFim}
                                    </h2>
                                </div>              
                        </div>  
                        <!-- page content -->
                        <div role="main"> <!-- just this -->
                                <div class="row small">
                                    <div class="col-xs-12 table">
                                            <table class="table table-striped" >
                                                <h4>CONTA : {$cliente}</h4>
                                                <thead>
                                                    <!--th id="cliente" colspan="10">CONTA: {$cliente}</th-->
                                                    <tr>
                                                        <th>        </th>
                                                        <th>TIPO    </th>
                                                        <th>DOC.    </th>
                                                        <th>EMISSAO </th>
                                                        <th>COD.    </th>
                                                        <th>PRODUTO </th>
                                                        <th>UNIDADE </th>
                                                        <th>QTDE    </th>
                                                        <th>TOTAL   </th>
                                                        <th>        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>           
                                                    
                                                        {assign var="quantSaida" value=0}
                                                        {assign var="totalSaida" value=0}                                                 
                                                        {section name=i loop=$pedido}
                                                            {if $pedido[i].TIPO eq 'SAIDA' }
                                                                    {$quantSaida = $quantSaida+$pedido[i].QTDE}
                                                                    {$totalSaida = $totalSaida+$pedido[i].TOTAL}

                                                                    <tr>
                                                                        <td> </td>
                                                                        <td> {$pedido[i].TIPO} </td>
                                                                        <td> {$pedido[i].ID} </td>
                                                                        <td> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].CODIGO} </td>
                                                                        <td> {$pedido[i].DESCRICAO} </td>
                                                                        <td> {$pedido[i].UNIDADE} </td>
                                                                        <td> {$pedido[i].QTDE|number_format:0:",":"."} </td>
                                                                        <td> {$pedido[i].TOTAL|number_format:2:",":"."} </td>
                                                                        <td> </td>
                                                                    </tr >
                                                            
                                                            {/if}                                                                  
                                                            
                                                        {/section} 
                                                        </tr>
                                                        <tr><b>
                                                            <td></td>
                                                            <td><h4>TOTAL</h4></td>
                                                            <td><h5>QUANTIDADE</h5></td>
                                                            <td><h5>VALOR</h5></td>
                                                        </b></tr>
                                                    
                                                        <tr>
                                                            <td></td>
                                                            <td><h5>Saídas</h5></td>
                                                            <td><h5> {$quantSaida|number_format:2:",":"."}</h5></td>
                                                            <td><h5>R$ {$totalSaida|number_format:2:",":"."}</h5></td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                {/if}
                          
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->



        
        
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <!-- select2 -->
    <script>
       
        $("#centroCustoOrigem.js-example-basic-single").select2({
            placeholder: "Selecione o Centro de Custo",
            allowClear: true
        });

        $("#centroCustoDestino.js-example-basic-single").select2({
            placeholder: "Selecione o Centro de Custo",
            allowClear: true
        });
        
    </script>

    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>

     $(document).ready(function(){
        $(".money").maskMoney({
         decimal: ",",
         thousands: ".",
         allowNegative: true
        });
    });
    </script>
    <style>
        .line-formated{
            margin-bottom: 1px;
        }
    </style>

   