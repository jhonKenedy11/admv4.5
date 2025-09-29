<script type="text/javascript" src="{$pathJs}/est/s_tabela_preco_item.js"> </script>
<div class="right_col" role="main">      
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <h3>Tabela Preço</h3>
      </div>
    </div>
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
        <input name=mod                 type=hidden value="crm">   
        <input name=form                type=hidden value="banco">   
        <input name=submenu             type=hidden value={$subMenu}>
        <input name=letra               type=hidden value={$letra}>
        <input name=id                  type=hidden value={$id}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                  {if $subMenu eq "cadastrar"}
                      Cadastro 
                  {else}
                      Altera&ccedil;&atilde;o 
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
                        <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar();">
                        <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Cancelar</span></button>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
                </ul>
                <div class="clearfix"></div>                
            </div>
            <div class="x_content">                        
              <br/>
              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="nome">Código <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input class="form-control" type="text" maxlength="60" required id="codigo" tittle="Preencha o código." 
                              name="codigo" placeholder="Digite o código." value={$codigo}>
                  </div>
              </div> 

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="nome">Grupo <span class="required">*</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <SELECT class="form-control" name="grupo" > 
                        {html_options values=$grupo_ids selected=$grupo output=$grupo_names}
                    </SELECT>
                  </div>
              </div> 

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="precobase">Preço Base<span class="required">*</span>
                  </label>           
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input class="form-control dinheiro" type="text" id="precobase" name="precobase" 
                                    placeholder="% para o calculo do preço venda." value={$precobase}>                        
                  </div>
              </div>


              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="margem">% Calculo<span class="required">*</span>
                  </label>           
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input class="form-control dinheiro" type="text" id="margem" name="margem" 
                                    placeholder="% para o calculo do preço venda." value={$margem}>                        
                  </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="precofinal">Preço<span class="required">*</span>
                  </label>           
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input class="form-control dinheiro" type="text" id="precofinal" name="precofinal" 
                                    placeholder="% para o calculo do preço venda." value={$precofinal}>                        
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

<script>
$('#validade').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_1",
    locale: {
      format: 'DD/MM/YYYY',
        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    }
    
});
</script>
                    