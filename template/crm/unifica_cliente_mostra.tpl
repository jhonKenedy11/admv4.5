<script type="text/javascript" src="{$pathJs}/crm/s_unifica_cliente.js"> </script>

        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Unificação De Cliente</h2>
                    {include file="../bib/msg.tpl"}                    
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar();">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span>
                            </button>
                        </li>
                        {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> *}
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="crm">   
                            <input name=form          type=hidden value="unifica_cliente">   
                            <input name=id            type=hidden value="">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=codCliente    type=hidden value={$codCliente}>
                            <input name=unidade       type=hidden value={$unidade}>
                            <input name=descCliente   type=hidden value={$descCliente}>
                            <input name=valorVenda    type=hidden value={$valorVenda}> 
                            <input name=uniFracionada type=hidden value="{$uniFracionada}">

                        
                        <div class="form-group col-lg-19 col-sm-10 col-xs-10">
                            <label style="font-size: 16px; margin-left: 10px;">Cliente a Permanecer no Cadastro</label>
                            </br>
                            </br>
                            <div class=" text-left">
                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                    <label>C&oacute;d. Cliente</label>
                                    <input READONLY class="form-control" id="codPermanecer" name="codPermanecer" placeholder="Código do cliente."  value={$codPermanecer} >
                                </div>
                                <label>Nome</label>
                                <div class="input-group">
                                    <input READONLY      
                                    class="form-control" placeholder="Cliente" id="descPermanecer" 
                                    name="descPermanecer" value="{$pesCliente}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarUnificaCliente&from=uni_cliente_permanece');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span> 
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-10 col-sm-10 col-xs-10">
                            <label style="font-size: 16px; margin-left: 10px;">Cliente a Retirar do Cadastro</label>
                            </br>
                            </br>
                            <div class="text-left">
                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                    <label>C&oacute;d. Cliente</label>
                                    <input READONLY class="form-control" id="codRetirar" name="codRetirar" placeholder="Código do cliente."  value={$codRetirar} >
                                </div>
                                <label>Nome</label>
                                <div class="input-group">
                                    <input READONLY      
                                    class="form-control" placeholder="Cliente" id="descRetirar" 
                                    name="descRetirar" value="{$pesCliente}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarUnificaCliente&from=uni_cliente_retira');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span> 
                                </div>
                            </div>
                        
                        </div>
                    </form>

                </div> <!-- x_panel -->
                          
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->



        
        
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/form.inc"}  

    
   