<style>
.form-control:focus-within {
  border: solid 2px;
  border-color: rgb(68, 147, 250) !important;
}
#id {
  height: 28px;
  width: 38px;
  text-align: center;
}
.x_panel {
  border-radius: 5px;
}
.form-control {
  border-radius: 5px;
}
input[type="number"]::-webkit-outer-spin-button, 
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
#divRadios{
  margin-top: 20px;
}
.swal-modal{
  width: 550px !important;
}
.radiosUni{
  position: static;
  padding-top: 10px;
}

[name=fluxoPedidoB], 
[name=lancPedBaixadoB], 
[name=aprovacaoB], 
[name=encomendaB]{
  position: static;
}
</style>

<script type="text/javascript" src="{$pathJs}/ped/s_parametro.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="ped">
      <input name=form type=hidden value="parametro">
      <input name=submenu type=hidden value={$subMenu}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>FAT Par&acirc;metro -
                {if $subMenu eq "cadastrar"}
                  Cadastro
                {else}
                  Altera&ccedil;&atilde;o
                {/if}
              </h2>

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('parametro');">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('parametro');">
                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />
              <div class="row row-md-12 row-sm-12 row-xs-12">

                  <div class="col-md-3 col-sm-6 col-xs-6">
                      <label class="control-label" for="filial">Filial</label>
                      <select name="filial" class="form-control input-sm">
                          {html_options values=$filial_ids selected=$filial output=$filial_names}
                      </select>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-6">
                      <label class="control-label" for="grupoServico">Grupo Servi&ccedil;o</label>
                      <input class="form-control input-sm" type="text" maxlength="15" name="grupoServico" id="grupoServico" value="{$grupoServico}">
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <label class="control-label" for="descontoMaximo">Desconto M&aacute;ximo</label>
                      <input class="form-control input-sm money" type="text" maxlength="13" name="descontoMaximo" id="descontoMaximo" value="{$descontoMaximo}">
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <label class="control-label" for="valorPedMinimo">Valor Pedido M&iacute;nimo</label>
                      <input class="form-control input-sm money" type="text" maxlength="13" name="valorPedMinimo" id="valorPedMinimo" value="{$valorPedMinimo}">
                  </div>


              </div>

              <div class="row row-md-12 row-sm-12 row-xs-12">

                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <label class="control-label">Situa&ccedil;&atilde;o Aberto</label>
                      <div>
                          <select name="sitAberto" class="form-control input-sm">
                              {html_options values=$pedido_ids selected=$sitAberto output=$pedido_names}
                          </select>
                      </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <label class="control-label">Situa&ccedil;&atilde;o Baixado</label>
                      <div>
                          <select name="sitBaixado" class="form-control input-sm">
                              {html_options values=$pedido_ids selected=$sitBaixado output=$pedido_names}
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-3 col-sm-6 col-xs-6 text-left line-formated">
                      <label class="control-label">Situa&ccedil;&atilde;o Emitir NFe</label>
                      <div>
                          <select name="sitEmitirNf" class="form-control input-sm">
                              {html_options values=$pedido_ids selected=$sitEmitirNf output=$pedido_names}
                          </select>
                      </div>
                  </div>

                  <div class="col-md-3 col-sm-2 col-xs-12">
                      <label class="control-label" for="tipoDesconto">Tipo Desconto</label>
                      <input class="form-control input-sm" type="text" maxlength="1" name="tipoDesconto" id="tipoDesconto" value="{$tipoDesconto}" placeholder="T ou L">
                  </div>

              </div>
              <center>
              <div class="row row-md-12 row-sm-12 row-xs-12" id="divRadios">
                  <div class="col-md-3 col-sm-6 col-xs-12" name="lancPedBaixadoB">
                      <label class="control-label" for="lancPedBaixado">Lan&ccedil;amento Pedido Baixado</label>
                      <div class="radiosUni">
                          {html_radios class="control-label flat" name="lancPedBaixado" values=$boolean_ids output=$boolean_names selected=$lancPedBaixado separator="&emsp;"}
                      </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12" name="fluxoPedidoB">
                      <label class="control-label" for="fluxoPedido">Fluxo Pedido</label>
                      <div class="radiosUni">
                          {html_radios class="flat" name="fluxoPedido" values=$boolean_ids output=$boolean_names selected=$fluxoPedido separator="&emsp;"}
                      </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12" name="aprovacaoB">
                      <label class="control-label">Aprova&ccedil;&atilde;o</label>
                      <div class="radiosUni">
                          {html_radios class="flat" name="aprovacao" values=$boolean_ids output=$boolean_names selected=$aprovacao separator="&emsp;"}
                      </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12" name="encomendaB">
                      <label class="control-label">Encomenda</label>
                      <div class="radiosUni">
                          {html_radios class="flat" name="encomenda" values=$boolean_ids output=$boolean_names selected=$encomenda separator="&emsp;"}
                      </div>
                  </div>

              </div>
              </center>

              <!-- INPUTS TEMPORARIAMENTE DESATIVADOS PARA EDICAO DE HTML POSTERIORMENTE
              <div class="row-md-12 row-sm-12 row-xs-12">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="apresentacao">Apresentacao</label>
                  <textarea class="form-control" id="apresentacao" name="apresentacao" rows="3">{$apresentacao}</textarea>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="objetivo">Objetivo</label>
                  <textarea class="form-control" id="objetivo" name="objetivo" rows="3">{$objetivo}</textarea>
                </div>

              </div>

              <div class="row-md-12 row-sm-12 row-xs-12">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="garantia">Garantia</label>
                  <textarea class="form-control" id="garantia" name="garantia" rows="3">{$garantia}</textarea>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="impostos">Impostos</label>
                  <textarea class="form-control" id="impostos" name="impostos" rows="3">{$impostos}</textarea>
                </div>

              </div>

              <div class="row-md-12 row-sm-12 row-xs-12">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="prazoentrega">Prazo de Entrega</label>
                  <textarea class="form-control" id="prazoentrega" name="prazoentrega" rows="3">{$prazoentrega}</textarea>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="validade">validade</label>
                  <textarea class="form-control" id="validade" name="validade" rows="3">{$validade}</textarea>
                </div>

              </div>

              <div class="row-md-12 row-sm-12 row-xs-12">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="aceite">Aceite</label>
                  <textarea class="form-control" id="aceite" name="aceite" rows="3">{$aceite}</textarea>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="obs">Observação</label>
                  <textarea class="form-control" id="obs" name="obs" rows="3">{$obs}</textarea>
                </div>

              </div> -->

            </div>
          </div>
        </div>
      </div>
    </form>

  </div>

{include file="template/form.inc"}
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
<script>
$(document).ready(function(){
  $(".money").maskMoney({            
   decimal: ",",
   thousands: ".",
   allowNegative: true,
   allowZero: true
  });        
});
</script>