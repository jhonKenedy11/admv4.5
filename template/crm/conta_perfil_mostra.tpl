<style>
  .form-control {
    border-radius: 5px;
  }

  .btnPedidos {
    margin-left: -27px;
  }

  .btnAComp {
    margin-left: -12px;
  }

  .btnAHist {
    margin-left: 42px;
  }

  .btnEditar {
    margin-left: -5px;
  }

  .tabelaHistorico {
    background: rgba(103, 32, 48, 0.94) !important;
    border-radius: 5px !important;
  }

  .tabelaSize {
    width: 60% !important;
  }

  table {
    border-collapse: inherit !important;
    border-radius: 5px;
  }

  .table-bordered {
    border-radius: 10px !important;
  }

  .infoCard {
    height: 31px !important;
  }

  .profile_details {
    padding: 4px !important;
  }

  .btnRelatorios {
    width: 100% !important;
  }

  .dropMenuRel {
    right: -94% !important;
    border-radius: 5px;
    background-color: rgba(76, 75, 75, 0.882);
  }
</style>
<script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-xs-12" style="padding: 0 !important;">
      <!-- panel principal  -->
      <div class="x_panel" style="border-radius: 8px !important;">
        <div class="x_title">
          <h2>Pessoas (Clientes/Fornecedores/Usu&aacute;rios) - Consulta
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
            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
              </button>
            </li>
            <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('');">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
              </button>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                  class="fa fa-wrench"></i></a>
              <ul class="dropdown-menu dropMenuRel" role="menu">
                <li>
                  <button type="button" class="btn btn-primary btn-xs btnRelatorios"
                    onClick="javascript:submitVoltar('');"><span>Perfil</span></button>
                  <!--<a href="javascript:submitVoltar('');">Perfil</a>-->
                </li>

                <li>
                  <button type="button" class="btn btn-primary btn-xs btnRelatorios"
                    onClick="javascript:submitVoltar('lista');"><span>Lista</span></button>
                  <!--<a href="javascript:submitVoltar('lista');">Lista</a>-->
                </li>
              </ul>

            </li>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div> <!-- div class="x_title" -->

        <div class="x_content">

          <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="{$mod}">
            <input name=form type=hidden value="{$form}">
            <input name=id type=hidden value="">
            <input name=opcao type=hidden value={$opcao}>
            <input name=letra type=hidden value={$letra}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=pesCnpjCpf type=hidden value="">
            <input name=pesCidade type=hidden value="">
            <input name=idEstado type=hidden value="">
            <input name=idVendedor type=hidden value="">
            <input name=idAtividade type=hidden value="">
            <input name=idClasse type=hidden value="">
            <input name=idPessoa type=hidden value="">
            <input name=pessoa type=hidden value="">
            <input name=checkPedido type=hidden value="">
            <input name=data_previous type=hidden value={$data_previous}>

            <div class="form-group col-md-12 col-sm-12 col-xs-12">
              <label>Pessoa</label>
              <input class="form-control" id="pesNome" name="pesNome" placeholder="Digite o nome do Pessoa."
                value={$pesNome}>
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

        </div> <!-- div class="x_content" = inicio tabela -->

      </div> <!-- div class="x_panel" -->
    </div> <!-- div class="x_panel" = painel principal-->



    <!-- panel tabela dados -->
    {section name=i loop=$lanc}
      {if $smarty.section.i.index % 3 == 0}
        <div class="row">
        {/if}

        <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
          <div class="well profile_view">
            <div class="col-sm-12">
              <div class="col-sm-12">
                <h4 class="brief"><i>{$lanc[i].NOME_REDUZIDO}</i></h4>
                <h2>{$lanc[i].NOME}</h2>
                <small>
                  <ul class="list-unstyled">
                    <li class="infoCard"><i class="fa fa-building"></i> Endereço: {$lanc[i].ENDERECO}, {$lanc[i].NUMERO}
                    </li>
                    <li><i class="fa fa-home"></i> Cidade: {$lanc[i].CIDADE}</li>
                    <li><i class="fa fa-phone"></i> Telefone: {$lanc[i].FONE} / {$lanc[i].CELULAR}</li>
                    <li><i class="fa fa-at"></i> Email: {$lanc[i].EMAIL}</li>
                  </ul>
                </small>
              </div>
            </div>

            <div class="countainer row-xs-12 row-sm-12 row-md-12">
              <div class="col-md-3 col-xs-3 col-sm-3 btnEditar">
                <button type="button" class="btn btn-success btn-xs" title="Editar Perfil"
                  onclick="javascript:submitAlterar('{$lanc[i].CLIENTE}');">
                  <i class="fa fa-user"></i> Editar
                </button>
              </div>
              <div class="col-md-3 col-xs-3 col-sm-3 btnPedidos">
                <button type="button" class="btn btn-primary btn-xs" title="Pedidos"
                  onclick="javascript:buscaPedCliente('{$lanc[i].CLIENTE}');">
                  <i class="fa fa-folder-open"></i> Pedidos
                </button>
              </div>
              <div class="col-md-3 col-xs-3 col-sm-3 btnAComp">
                <button type="button" class="btn btn-warning btn-xs" title="Acompanhamentos"
                  onclick="javascript:abrir('index.php?mod=crm&form=contas_acompanhamento&opcao=imprimir&submenu=cadastrar&idPedido={$lanc[i].ID}&pessoa={$lanc[i].CLIENTE}&pessoaNome={$lanc[i].NOMEREDUZIDO}');">
                  <i class="fa fa-comments-o"></i> Acompanhamento
                </button>
              </div>
              <div class="col-md-3 col-xs-3 col-sm-3 btnAHist">
                <button type="button" class="btn btn-danger btn-xs" title="Histórico"
                  onclick="javascript:buscaHistorico('{$lanc[i].CLIENTE}');">
                  <i class="fa fa-bars"></i> Histórico
                </button>
              </div>
            </div>
          </div>
        </div>

        {if $smarty.section.i.index % 3 == 2 || $smarty.section.i.last}
        </div>
      {/if}

      {assign var="total" value=$total+1}
    {/section}

  </div> <!-- div class="row "-->
</div> <!-- div class="right_col" -->

<div id="modalPedidosCliente" class="modal fade" style="background-color: transparent;" role="dialog">
  <div class="modal-dialog" style="background-color: transparent;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <center><b>Pedidos</b></center>
        </h4>
      </div>

      <table id="datatable" class="table table-bordered jambo_table">
        <thead>
          <tr class="">
            <th style="border-radius:5px !important">
              <center>Pedido</center>
            </th>
            <th style="border-radius:5px !important">
              <center>Emiss&atilde;o</center>
            </th>
            <th style="border-radius:5px !important">
              <center>Valor</center>
            </th>
            <th style="border-radius:5px !important">
              <center>Centro Custo</center>
            </th>
          </tr>
        </thead>
        <tbody id="dadosPedidos">

        </tbody>
      </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<div id="modalHistoricoCliente" class="modal fade" style="background-color: transparent;" role="dialog">
  <div class="modal-dialog tabelaSize" style="background-color: transparent;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <center><b>Histórico de Contato</b></center>
        </h4>
      </div>

      <table id="datatable" class="table table-bordered jambo_table">
        <thead class="tabelaHistorico">
          <tr class="">
            <th style="width:138px; border-radius:5px !important">
              <center>Data<center>
            </th>
            <th style="border-radius:5px !important">Descrição</th>
          </tr>
        </thead>
        <tbody id="dadosHistorico">

        </tbody>
      </table>
      <div class="modal-footer">
        <button type="button" infoCard infoCard infoCard infoCard class="btn btn-default"
          data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

{include file="template/database.inc"}

<!-- /Datatables -->