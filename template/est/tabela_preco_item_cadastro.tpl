<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_tabela_preco_item.js"> </script>
<div class="right_col" role="main">      
  <div class="">
    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
        <input name="mod"                 type="hidden" value="est">   
        <input name="form"                type="hidden" value="tabela_preco_item">   
        <input name="submenu"             type="hidden" value="{$subMenu}">
        <input name="letra"               type="hidden" value="{$letra}">
        <input name="id"                  type="hidden" value="{$id}">
        <input name="id_tabela_preco"     type="hidden" value="{$id_tabela_preco}">

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                  {if $subMenu eq "cadastrar"}
                      Tabela Preço - Item - Cadastro 
                  {else}
                      Tabela Preço - Item - Altera&ccedil;&atilde;o 
                  {/if} 
                  {if $mensagem neq ''}
                      {if $tipoMsg eq 'sucesso'}
                          <div class="row">
                              <div class="col-lg-12 text-left">
                                  <div>
                                      <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                  </div>
                              </div>
                          </div>
                      {elseif $tipoMsg eq 'alerta'}
                          <div class="row">
                              <div class="col-lg-12 text-left">
                                  <div>
                                      <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                  </div>
                              </div>
                          </div>       
                      {/if}

                  {/if}
              </h2>    

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar();">
                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('{$id_tabela_preco}');">
                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                </li>
                </ul>
                <div class="clearfix"></div>                
            </div>
            <div class="x_content">                        
              <br/>
              <div class="row">
               <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="codigo_produto">Código Produto </label>
                  <input class="form-control" type="text" maxlength="60" id="codigo_produto" title="Código produto"
                         name="codigo_produto" placeholder="Código produto" value="{$codigo_produto|escape}">
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="codigo_fabricante">Código Fabricante </label>
                  <input class="form-control" type="text"  id="codigo_fabricante" title="Código fabricante"
                         name="codigo_fabricante" placeholder="Código fabricante" value="{$codigo_fabricante|escape}">
                </div>
               

              <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <label for="descricao">Descrição do Produto</label>
                  <input class="form-control" type="text" id="descricao" name="descricao" placeholder="Descrição do produto" value="{$descricao|escape}">
                </div>
               
              </div>
                 <div class="col-md-4 col-sm-12 col-xs-12">  
                  <label for="grupo">Grupo  </label>
                  <select class="form-control" name="grupo" id="grupo">
                    {html_options values=$grupo_ids selected=$grupo output=$grupo_names}
                  </select>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <label for="marca">Marca</label>
                  <select class="form-control" name="marca" id="marca">
                    {html_options values=$marca_ids selected=$marca output=$marca_names}
                  </select>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <label for="id_tabela_preco_select">Tabela Preço</label>
                  <select class="form-control" id="id_tabela_preco_select" name="id_tabela_preco">
                    {html_options values=$tabela_ids selected=$tabela output=$tabela_names}
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="precobase">Preço Base  </label>
                  <input class="form-control money" type="text" id="precobase" name="precobase"
                         placeholder="% para o calculo do preço venda." value="{$precobase|escape}">
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="margem">% Margem  </label>
                  <input class="form-control money" type="text" id="margem" name="margem"
                         placeholder="% para o calculo do preço venda." value="{$margem|escape}">
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="precofinal">Preço  </label>
                  <input class="form-control money" type="text" id="precofinal" name="precofinal"
                         placeholder="% para o calculo do preço venda." value="{$precofinal|escape}">
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="precobase_anterior">Preço Base Anterior</label>
                  <input class="form-control money" type="text" id="precobase_anterior" name="precobase_anterior" readonly
                         placeholder="Preço base anterior" value="{$precobase_anterior|escape}">
                </div>
              </div>


              <div class="ln_solid"></div>                    
            </div>
          </div>        
        </div>          
    </form>
  </div>
</div>

{include file="template/form.inc"}  
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowNegative: false,
        });

        $(".money").blur(function() {
            var value = $(this).val();
            if (value === "") {
                $(this).val("0,00");
            }
        });
        
        // Inicializa tooltips do Bootstrap
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            container: 'body'
        });
    });
</script>