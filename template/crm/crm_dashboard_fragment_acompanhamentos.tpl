<input name=idCliente type=hidden value={$idCliente}>
<input name=nomeCliente type=hidden value={$nomeCliente}>

{section name=i loop=$resultAcomp}
<input name=tempClienteOtimizaIcone type=hidden value={$tempClienteOtimizaIcone}>
<li class="media event table-striped">
  <a class="pull-left border-aero profile_thumb">
    <i class="fa fa-user aero"></i>
  </a>
  <div class="media-body">
    <div class="clearfix" style="margin-bottom:4px;">
      <div class="pull-right" style="white-space: nowrap;">
          {if $resultAcomp[i].STATUS eq '1'}
            <span class="status-pill" style="background-color: #5c7b4c;">ABERTO</span>
          {elseif $resultAcomp[i].STATUS eq '2'}
            <span class="status-pill" style="background-color: #c66d08;">ANDAMENTO</span>
          {elseif $resultAcomp[i].STATUS eq '3'}
            <span class="status-pill" style="background-color: #3662ab;">CONCLUÍDO</span>
          {else}
            <span class="status-pill" style="background-color: #451455;">SEM STATUS</span>
          {/if}
        <button type="button" class="btn btn-info btn-xs" style="margin-left:8px; vertical-align: middle;" title="E-mail"
          onclick="javascript:crmDashAbrirEmailAcomp({$resultAcomp[i].ID}); return false;" aria-label="E-mail do acompanhamento">
          <i class="fa fa-envelope" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-primary btn-xs" style="margin-left:4px; vertical-align: middle;"
          onclick="javascript:editarAcompanhamento({$resultAcomp[i].ID}); return false;" title="Editar" aria-label="Editar">
          <span class="glyphicon glyphicon-pencil" aria-hidden="true"
            data-toggle="tooltip" data-placement="left"></span>
        </button>
      </div>
      <p class="text-left" style="color: #156857; margin:0;">
        <b> {$resultAcomp[i].NOMEREDUZIDO} </b>
      </p>
    </div>
    <p><strong>{$resultAcomp[i].RESULTADO} </strong> </p>
    {if $resultAcomp[i].VENDEDOR_NOME neq ''}
      <p><small><b>Vendedor:</b> {$resultAcomp[i].VENDEDOR_NOME}</small></p>
    {/if}
    <p>{$resultAcomp[i].DATA|date_format:"%e %b, %Y - %H:%M:%S"} - Ligar:
      {$resultAcomp[i].LIGARDIA|date_format:"%e %b, %Y - %H:%M:%S"}
    </p>
  </div>
</li>
{/section}
