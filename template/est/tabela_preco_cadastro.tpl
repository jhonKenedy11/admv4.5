<script type="text/javascript" src="{$pathJs}/est/s_tabela_preco.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<div class="right_col" role="main">      
  <div class="">
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
        <input name=mod                 type=hidden value="est">   
        <input name=form                type=hidden value="tabela_preco">   
        <input name=submenu             type=hidden value={$subMenu}>
        <input name=letra               type=hidden value={$letra}>
        <input name=id                  type=hidden value={$id}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                  {if $subMenu eq "cadastrar"}
                      Tabela de Preço - Cadastro 
                  {else}
                      Tabela de Preço - Altera&ccedil;&atilde;o 
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
                </ul>
                <div class="clearfix"></div>                
            </div>
            <div class="x_content">                        
              <br/>

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="nome">Nome <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input class="form-control" type="text" maxlength="60" required id="nome" tittle="Preencha o nome da tabela." 
                              name="nome" placeholder="Digite o nome da tabela." value={$nome}>
                  </div>
              </div> 

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="centroCusto">Centro de Custo<span class="required">*</span>
                  </label>           
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select name="centroCusto" class="form-control">
                          {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                      </select>
                  </div>
              </div> 

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="validade">Valido<span class="required">*</span>
                  </label>           
                  <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control has-feedback-left" type="text" id="validade" 
                          name="validade" data-inputmask="'mask': '99/99/9999'" 
                          placeholder="Válido até ." value={$validade}>
                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                      
                  </div>
              </div> 

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="precoBase">Preço Base<span class="required">*</span>
                  </label>           
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control" name="precoBase">
                        {html_options values=$precoBase_ids selected=$precoBase_id output=$precoBase_names}
                      </select>
                  </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3" for="pessoa">Pessoa</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="hidden" id="pessoa" name="pessoa" value={$pessoa|default:""}>
                      <div class="input-group">
                          <input type="text" class="form-control" readonly id="nomePessoa" name="nomePessoa" placeholder="Pessoa" value={$nomePessoa|default:""}>
                          <span class="input-group-btn">
                              <button type="button" class="btn btn-primary" style="height:34px;"
                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                  <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                              </button>
                          </span>
                      </div>
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


              <div class="ln_solid"></div>                    
            </div>
          </div>        
        </div>          
    </form>
  </div>
</div>

{include file="template/form.inc"}  

<script>
$(function() {
    var dataValidadeValue = $('#validade').val();
    var daterangepickerOptions = {
        singleDatePicker: true,
        calender_style: "picker_1",
        parentEl: 'body',
        opens: 'center', // left | right | center
        drops: 'up',     // força abrir acima do input
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            cancelLabel: 'Limpar'
        }
    };

    if (dataValidadeValue && dataValidadeValue.trim() !== '') {
        daterangepickerOptions.startDate = dataValidadeValue;
        daterangepickerOptions.autoUpdateInput = true;
    }

    $('#validade').daterangepicker(daterangepickerOptions);

    $('#validade').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('#validade').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
{literal}
<script>
    $(document).ready(function() {
        $("input.dinheiro").maskMoney({decimal: ",", thousands: ".", allowNegative: true, precision:
{/literal}{$casasDecimais|default:2}{literal}
        });
    });
</script>
{/literal}
                    