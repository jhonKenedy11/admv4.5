<style>
.form-control,
.x_panel {
    border-radius: 5px;
}

.title-cadastro {
    margin-top: 11px;
    margin-left: -13px;
    width: 400px !important;
}
</style>

<script type="text/javascript" src="{$pathJs}/crm/s_cliente_endereco.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod                 type=hidden value="crm">
      <input name=form                type=hidden value="cliente_endereco">
      <input name=submenu             type=hidden value={$subMenu}>
      <input name=opcao               type=hidden value={$opcao}>
      <input name=letra               type=hidden value={$letra}>
      <input name=id_cliente          type=hidden value={$id_cliente}>
      <input name=id_endereco         type=hidden value={$id_endereco}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
            
                <div class="">
                    <div class="col-md-2">
                      <h3 class="title-cadastro_">Endereço entrega -</h3>
                    </div>
                    <div class="col-md-10 title-cadastro">
                      <h2><i>Cadastro</i></h2>
                    </div>
                </div>

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar();">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" id="btnReturn" class="btn btn-danger" onClick="javascript:submitVoltar();">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span> Cancelar</span></button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />

              <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <span class="fa fa-asterisk" aria-hidden="true"></span>
                  <label for="address_titulo_endereco" class="col-form-label">Titulo endereco</label>
                  <input class="form-control" maxlength="15" type="text" id="address_titulo_endereco-address"
                    name="address_titulo_endereco" placeholder="titulo do endereco" value={$address_titulo_endereco}>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <span class="fa fa-comments" aria-hidden="true"></span>
                  <label for="address_descricao" class="col-form-label">Descricao</label>
                  <input class="form-control" maxlength="35" type="text" id="address_descricao" name="address_descricao"
                    placeholder="descricao do endereço" value={$address_descricao}>
                </div>

              </div>

              <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <span class="fa fa-globe" aria-hidden="true"></span>
                  <label for="address_ddd" class="col-form-label">DDD</label>
                  <input class="form-control" maxlength="4" type="text" id="address_ddd" name="address_ddd"
                    placeholder="ddd" value={$address_ddd}>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <span class="fa fa-phone-square" aria-hidden="true"></span>
                  <label for="address_fone" class="col-form-label">Celular</label>
                  <input class="form-control" maxlength="10" type="text" id="address_fone" name="address_fone"
                    data-inputmask="'mask' : '99999-9999', 'keepStatic': 'true'" placeholder="Celular"
                    value={$address_fone}>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <span class="fa fa-phone-square" aria-hidden="true"></span>
                  <label for="address_fone_contato" class="col-form-label">Fone Contato</label>
                  <input class="form-control" maxlength="15" type="text" id="address_fone_contato"
                    name="address_fone_contato" data-inputmask="'mask' : '9999-9999', 'keepStatic': 'true'"
                    placeholder="Fone contato" value={$address_fone_contato}>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <span class="fa fa-home" aria-hidden="true"></span>
                  <label for="address_cep" class="col-form-label">Cep</label>
                  <input class="form-control" required="required" maxlength="11" type="text"
                    data-inputmask="'mask' : '99999-999'" id="address_cep" name="address_cep" placeholder="cep"
                    onblur="pesquisarEnderecoECarregarFormulario(this.value);" value={$address_cep}>
                </div>

              </div>
              <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <span class="fa fa-map-marker" aria-hidden="true"></span>
                  <label for="address_endereco" class="col-form-label">Endereço</label>
                  <input class="form-control" maxlength="40" type="text" id="address_endereco" name="address_endereco"
                    placeholder="Endereço" value={$address_endereco}>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <label for="numero-address" class="col-form-label">Numero</label>
                  <input class="form-control" maxlength="7" type="text" id="address_numero" name="address_numero"
                    value={$address_numero}>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <span class="fa fa-plus-square" aria-hidden="true"></span>
                  <label for="address_complemento" class="col-form-label">Complemento</label>
                  <input class="form-control" maxlength="15" type="text" id="address_complemento"
                    name="address_complemento" placeholder="Complemento" value={$address_complemento}>
                </div>

              </div>

              <div class="row">

                <div class="col-md-4 col-sm-4 col-xs-4">
                  <span class="fa fa-home" aria-hidden="true"></span>
                  <label for="address_bairro" class="col-form-label">Bairro</label>
                  <input class="form-control" maxlength="20" type="text" id="address_bairro" name="address_bairro"
                    placeholder="Bairro" value={$address_bairro}>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                  <span class="fa fa-home" aria-hidden="true"></span>
                  <label for="address_cidade" class="col-form-label">Cidade</label>
                  <input class="form-control" maxlength="40" type="text" id="address_cidade" name="address_cidade"
                    placeholder="Cidade" value={$address_cidade}>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                  <span class="fa fa-home" aria-hidden="true"></span>
                  <label for="address_estado" class="col-form-label">Estado</label>
                  <SELECT class="form-control" name="address_estado" id="address_estado">
                    {html_options values=$address_estado_ids output=$address_estado_names selected=$address_estado_id}
                  </SELECT>
                </div>
              </div>

              <div class="ln_solid"></div>

            </div>
          </div>
        </div>
      </div>
    </form>

  </div>

{include file="template/form.inc"}