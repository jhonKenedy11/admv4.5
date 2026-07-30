<style type="text/css">
  .form-control,
  .x_panel {
    border-radius: 5px;
  }

  .radio-group {
    display: flex;
    gap: 15px;
  }

  .radio-group label {
    display: flex;
    align-items: center;
    white-space: nowrap;
    font-weight: normal !important;
  }

  .x_title h2 {
    color: #38478b;
  }

  .x_title h2 i {
    color: #283468;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2d69;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #95a9fa;
    position: relative;
  }

  .section-title:before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 5%;
    height: 2px;
    background: #1f2d69;
  }

  .section-title i {
    color: #2d3e8a;
    margin-right: 8px;
  }

  .param-box {
    background: linear-gradient(to bottom, #f8f7ff 0%, #ffffff 100%);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
  }

  .param-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background: white;
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid #e9e4ff;
    transition: all 0.3s;
  }

  .param-item:last-child {
    margin-bottom: 0;
  }

  .param-item:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
  }

  .param-item .param-label {
    margin: 0;
    font-weight: 500;
    color: #4b5563;
    flex: 1;
  }

  .param-item .param-label i {
    color: #1f2d69;
    margin-right: 8px;
  }

  .param-item .radio-group {
    display: flex;
    gap: 20px;
  }

  .tributos-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  .tributos-box label {
    color: #4b5563;
    font-weight: 500;
  }

  .tributo-item {
    background: linear-gradient(to bottom, rgb(243, 241, 241) 0%, rgb(216, 216, 216) 100%);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  .tributo-item:last-child {
    margin-bottom: 0;
  }

  .tributo-item label i {
    color: #1f2d69;
  }

  .input-with-icon {
    position: relative;
  }

  .input-icon-right {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: #1f2d69;
  }

  .form-control.has-icon-right {
    padding-right: 35px;
  }

  .form-control:focus,
  .form-control:focus-within {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .info-card {
    background: rgb(255, 248, 248);
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #e9e4ff;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  select.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  select.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  input.form-control,
  textarea.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  input.form-control:focus,
  textarea.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .x_panel {
    border-top: 4px solid #1f2d69;
    box-shadow: 0 4px 20px rgba(0, 35, 192, 0.1);
  }

  .control-label {
    color: #414852;
    font-weight: 400;
    font-size: 14px;
  }

  .swal-modal {
    width: 550px !important;
  }

  .param-note {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  table {
    border-spacing: 0;
    border-collapse: none !important;
  }

  .table-bordered>thead>tr>th {
    border-radius: 7px !important;
    padding: 5px !important;
  }

  .x_panel,
  [name=datatable-buttons_length],
  [type=search] {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="{$mod}">
    <input name=form type=hidden value="{$form}">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>


    <div class="">
      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>CAT - Par&acirc;metro - Consulta
                {include file="../bib/msg.tpl"}
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro();">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
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
            <div class="x_content">
              <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-6">
                  <input type="text" class="form-control" name="filtro_busca" id="filtro_busca"
                    placeholder="Filtrar por mensagem, centro de custo ou ID..." value="{$filtro_busca|escape:'html'}">
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-primary" onclick="submitConsulta();">
                    <span class="glyphicon glyphicon-search"></span> Filtrar
                  </button>
                  <button type="button" class="btn btn-default" onclick="submitLimparFiltro();">
                    <span class="glyphicon glyphicon-remove"></span> Limpar
                  </button>
                </div>
              </div>
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>ID</th>
                    <th>Msg Atendimento</th>
                    <th>Msg Or&ccedil;amento</th>
                    <th>Centro de Custo</th>
                    <th class=" no-link last" style="width: 40px;">Manuten&ccedil;&atilde;o</th>
                  </tr>
                </thead>

                <tbody>

                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr class="even pointer">
                      <td> {$lanc[i].ID} </td>
                      <td> {$lanc[i].MSGATENDIMENTO} </td>
                      <td> {$lanc[i].MSGORCAMENTO} </td>
                      <td> {$lanc[i].CENTROCUSTO_DESC|default:$lanc[i].CENTROCUSTO} </td>
                      <td class=" last">
                        <button type="button" class="btn btn-primary btn-xs"
                          onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil"
                            aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs"
                          onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash"
                            aria-hidden="true"></span></button>
                      </td>
                    </tr>
                  {/section}

                </tbody>

              </table>

            </div> <!-- div class="x_content" = inicio tabela -->
          </div> <!-- div class="x_panel" = painel principal-->
        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
      </div> <!-- div class="row "-->
    </div> <!-- class='' = controla menu user -->

  </form>


  {include file="template/database.inc"}
  <script type="text/javascript" src="{$pathJs}/cat/s_parametro.js"></script>

{if $swalText}
<script>
$(function () {
  Swal.fire({
    icon: '{$swalIcon}',
    title: '{$swalTitle|escape:'javascript'}',
    text: '{$swalText|escape:'javascript'}',
    width: 510,
    {if $swalAutoClose}
    timer: 3000,
    showConfirmButton: false
    {else}
    confirmButtonText: 'OK'
    {/if}
  });
});
</script>
{/if}

<!-- /Datatables -->