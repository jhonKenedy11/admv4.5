{if $resultClientes|@count neq 0}
  {section name=i loop=$resultClientes}
    <li class="media event crm-dash-cliente-item" id="{$resultClientes[i].CLIENTE}">
      <a class="pull-left profile_thumb">
        <i class="fa fa-user"></i>
      </a>
      <button type="button" id="iconesManutencaoCotacao" class="btn btn-primary btn-xs pull-right"
        onclick="javascript:abrirNewTab('index.php?mod=ped&form=pedido_ps&submenu=abrirDashboardCrm&dashboard_origem=dashboard_crm&param={$resultClientes[i].CLIENTE}');">
        <i class="fa fa-shopping-basket" style="margin-left: -4px !important;" aria-hidden="true"></i>
      </button>
      <button type="button" id="iconesManutencao" class="btn btn-primary btn-xs pull-right"
        onclick="javascript:abrirNewTab('index.php?mod=crm&form=contas&submenu=alterar&dashboard_origem=dashboard_crm&param={$resultClientes[i].CLIENTE}');">
        <span class="glyphicon glyphicon-pencil" style="margin-left: -4px;" aria-hidden="true"
          data-toggle="tooltip" title="Editar"></span>
      </button>
      <div class="media-body">
        <a class="title"
          href="javascript:buscaAcompanhamentos({$resultClientes[i].CLIENTE})">
          {$resultClientes[i].NOME} </a>
        <p><small>{$resultClientes[i].CIDADE} - {$resultClientes[i].UF} </small></p>
        <p> ({$resultClientes[i].FONEAREA}) {$resultClientes[i].FONE} |
      {if $resultClientes[i].EMAIL eq ''}
            SEM E-MAIL
      {else} {$resultClientes[i].EMAIL}
      {/if}</p>
      </div>
    </li>
  {/section}
{else}
  <div class="clienteNaoLocalizado"><center>
    <span class="" aria-hidden="true" data-toggle="tooltip" title="Clientes não localizados">
      <i style="font-size: 40px;" class="fa fa-user-times" aria-hidden="true"></i>
    </span>
    <h3> Não foi localizado nenhum cliente </h3>
  </center></div>
{/if}
