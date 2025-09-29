    <script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
    <script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Pessoas (Clientes/Fornecedores/Usu&aacute;rios)</h3>
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
                                    <div class="container">
                                        <div class="alert alert-success fade in">{$mensagem}</div>
                                    </div>    
                                {else}
                                    <div class="container">
                                        <div class="alert alert-danger fade in"> {$mensagem}</div>
                                    </div>    
                                {/if}
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning" id="btnSubmit"  onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="javascript:submitVoltar('');">Perfil</a>
                              </li>
                              <li><a href="javascript:submitVoltar('lista');">Lista</a>
                              </li>
                            </ul>
                            
                        </li>
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
                        <input name=mod           type=hidden value="crm">   
                        <input name=form          type=hidden value="contas">   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=pesCnpjCpf    type=hidden value="">
                        <input name=pesCidade     type=hidden value="">
                        <input name=pesObs        type=hidden value="">
                        <input name=idEstado      type=hidden value="">
                        <input name=idVendedor    type=hidden value="">
                        <input name=idAtividade   type=hidden value="">
                        <input name=idClasse      type=hidden value="">
                        <input name=idPessoa      type=hidden value="">
                        <input name=pessoa        type=hidden value="">
                        <input name=checkPedido   type=hidden value="">

                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label>Pessoa</label>
                        <input class="form-control" id="pesNome" autofocus name="pesNome" placeholder="Digite o nome do Pessoa."  value={$pesNome} >
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <ul class="pagination pagination-split">
                            {assign var=arr value='A'|range:'Z'}
                            {foreach from=$arr item=item}
                                <li><a href="javascript:submitLetra('{$item}')">{$item}</a></li>
                            {/foreach}                          
                        </ul>
                    </div>

                    </form>

                </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->

                            

        <!-- panel tabela dados -->  
        {section name=i loop=$lanc}

          <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
            <div class="well profile_view">
              <div class="col-sm-12">
                <div class="left col-xs-7">
                <h4 class="brief"><i>{$lanc[i].NOMEREDUZIDO}</i></h4>
                  <h2><small>{$lanc[i].NOME}</h2>
                  <ul class="list-unstyled">
                    <li><i class="fa fa-building"></i> Endere&ccedil;o: {$lanc[i].ENDERECO}, {$lanc[i].NUMERO}</li>
                    <li><i class="fa fa-home"></i> Cidade: {$lanc[i].CIDADE}</li>
                    <li><i class="fa fa-phone"></i> Phone #: {$lanc[i].FONE} / {$lanc[i].CELULAR}</li>
                    <li><i class="fa fa-at"></i> Email: {$lanc[i].EMAIL}</li>
                    <li><i class="fa fa-info-circle"></i> Sobre: {$lanc[i].OBS}</li>
                  </ul></small>
                </div>
                <div class="right col-xs-5 text-center">
                  <p class="ratings"><small>
                    <a>Pedidos</a>
                    <button id="btnPed" type="button" class="btn btn-dark btn-xs" onclick="javascript:submitLetraPed('{$lanc[i].NOME}');">
                            <i class="fa fa-folder-open"></i>
                        </button>
                    <br>
                    {section name=v loop=$lanc[i].VENDAS max=5}
                        <small>
                        <a href={ADMhttpCliente}/index.php?mod=ped&form=pedido_venda&id={$lanc[i].VENDAS[v].ID}><span class="fa fa-star">
                          {$lanc[i].VENDAS[v].ID|number_format:0:"":"."} 
                          {$lanc[i].VENDAS[v].EMISSAO|date_format:"%d/%m/%Y"} 
                          {$lanc[i].VENDAS[v].TOTAL|number_format:2:",":"."}</span></a><br>
                          </small>
                    {/section}     
                    </small>                     
                  </p>
                </div>
                <!--div class="col-xs-5 text-left">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-calendar"></i> Ultimo: {$lanc[i].DATA|date_format:"%d/%m/%Y %H:%M"}</li>
                    <li><i class="fa fa-calendar"></i> Proximo: {$lanc[i].LIGARDIA|date_format:"%d/%m/%Y %H:%M"}</li>
                    <li><i class="fa fa-history"></i> Hist&oacute;rico: {$lanc[i].RESULTADO}</li>
                  </ul></small>
                </div-->
              </div>
              <div class="col-xs-12 text-center">
                <div class="col-xs-12 col-sm-6 emphasis">
                  <button type="button" class="btn btn-primary btn-xs btnEdit" onclick="javascript:submitAlterar('{$lanc[i].CLIENTE}');">
                    <i class="fa fa-user"> </i> Edit Profile
                  </button>
                </div>
                <div class="col-xs-12 col-sm-6 emphasis">
                  <!--button type="button" class="btn btn-success btn-xs" onclick="javascript:submitAcompanhamento('{$lanc[i].CLIENTE}');"> 
                      <i class="fa fa-user">
                    </i> <i class="fa fa-comments-o"></i> </button-->
                </div>
              </div>
            </div>
          </div>

            {assign var="total" value=$total+1}
        {/section} 

          </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
<style>
.swal-title{
  font-size: 24px !important;
}
.swal-modal{
  width: 600px !important;
}
.form-control{
  border-radius: 5px;
}
.profile_details{
  width: 445px;
  padding: 9px;
}
.well.profile_view .right{
  margin-top: -6px;
  padding: 21px;
}
.btnEdit{
  margin-left: -93px;
}
</style>