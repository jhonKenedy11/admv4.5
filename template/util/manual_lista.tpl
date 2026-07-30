<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }

  .manual-tree .panel-heading {
    cursor: pointer;
    padding: 0;
    border-radius: 4px;
  }

  .manual-tree .panel-heading a.manual-mod-toggle {
    display: block;
    padding: 12px 15px;
    color: #73879c;
    font-weight: 600;
    text-decoration: none;
    outline: none;
  }

  .manual-tree .panel-heading a.manual-mod-toggle:hover,
  .manual-tree .panel-heading a.manual-mod-toggle:focus {
    color: #26a69a;
    background: #f9f9f9;
  }

  .manual-tree .manual-mod-chevron {
    transition: transform 0.2s ease;
    margin-right: 8px;
    color: #999;
  }

  .manual-tree .panel-heading a.manual-mod-toggle:not(.collapsed) .manual-mod-chevron {
    transform: rotate(90deg);
    color: #26a69a;
  }

  .manual-tree .manual-mod-badge {
    margin-left: 8px;
    vertical-align: middle;
  }

  .manual-tree-leaf {
    margin: 0;
    padding-left: 8px;
    border-left: 2px solid #e7e7e7;
    margin-left: 12px;
  }

  .manual-tree-leaf li {
    padding: 10px 12px 10px 8px;
    margin-bottom: 8px;
    background: #fafafa;
    border-radius: 4px;
    border: 1px solid #eee;
  }

  .manual-tree-leaf li:last-child {
    margin-bottom: 0;
  }

  .manual-leaf-title {
    font-weight: 600;
    color: #334;
    margin-bottom: 4px;
  }

  .manual-leaf-title .fa {
    margin-right: 8px;
    color: #c0392b;
  }

  .manual-desc {
    color: #73879c;
    font-size: 13px;
    margin-bottom: 8px;
    padding-left: 22px;
  }

  .manual-leaf-actions .btn {
    margin-right: 6px;
  }

  .manual-tree-empty-hint {
    color: #999;
    font-size: 13px;
    padding: 8px 12px;
  }

  /* Evita encavalamento com o h2 do x_title (layout em float no tema) */
  .manual-hint-bar {
    margin: 0 0 20px 0;
    padding: 12px 14px;
    background: #f7f7f7;
    border-left: 3px solid #26a69a;
    border-radius: 4px;
    color: #73879c;
    font-size: 13px;
    line-height: 1.55;
    clear: both;
  }

  .manual-hint-bar .glyphicon {
    margin-right: 8px;
    color: #26a69a;
    vertical-align: text-bottom;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<div class="right_col" role="main">
  <div class="">
    <div class="row">

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{$tituloPagina}</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div class="manual-hint-bar">
              <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
              Clique no nome do <strong>módulo</strong> para expandir ou recolher os manuais disponíveis.
            </div>

            {if $erroManifest neq '' && $erroManifest !== null}
              <div class="alert alert-warning" role="alert">
                {$erroManifest}
              </div>
            {/if}

            {if $modulos|@count eq 0 && ($erroManifest eq '' || $erroManifest === null)}
              <p class="text-muted">Nenhum manual disponível no momento.</p>
            {/if}

            <div class="panel-group manual-tree" id="manualTreeRoot" role="tablist" aria-multiselectable="true">

              {foreach from=$modulos item=mod name=m}
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="heading_mod_{$smarty.foreach.m.iteration}">
                    <h4 class="panel-title">
                      <a class="manual-mod-toggle {if !$smarty.foreach.m.first}collapsed{/if}" role="button"
                        data-toggle="collapse"
                        href="#collapse_mod_{$smarty.foreach.m.iteration}"
                        aria-expanded="{if $smarty.foreach.m.first}true{else}false{/if}"
                        aria-controls="collapse_mod_{$smarty.foreach.m.iteration}">
                        <span class="glyphicon glyphicon-chevron-right manual-mod-chevron" aria-hidden="true"></span>
                        <span class="glyphicon glyphicon-folder-close" style="margin-right:6px;color:#999;"
                          aria-hidden="true"></span>
                        {$mod.titulo|escape}
                        <span class="badge manual-mod-badge">{$mod.manuais|@count}</span>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse_mod_{$smarty.foreach.m.iteration}"
                    class="panel-collapse collapse {if $smarty.foreach.m.first}in{/if}" role="tabpanel"
                    aria-labelledby="heading_mod_{$smarty.foreach.m.iteration}">
                    <div class="panel-body" style="padding-top: 10px;">

                      {if $mod.manuais|@count eq 0}
                        <p class="manual-tree-empty-hint">Nenhum manual neste módulo.</p>
                      {else}
                        <ul class="list-unstyled manual-tree-leaf">
                          {foreach from=$mod.manuais item=man}
                            <li>
                              <div class="manual-leaf-title">
                                <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                {$man.titulo|escape}
                              </div>
                              {if isset($man.descricao) && $man.descricao neq ''}
                                <div class="manual-desc">{$man.descricao|escape}</div>
                              {/if}
                              <div class="manual-leaf-actions">
                                <a href="{$man.urlPdf|escape}" class="btn btn-primary btn-xs" target="_blank"
                                  rel="noopener">Abrir PDF</a>
                                <a href="{$man.urlPdf|escape}" class="btn btn-default btn-xs"
                                  download>Baixar</a>
                              </div>
                            </li>
                          {/foreach}
                        </ul>
                      {/if}

                    </div>
                  </div>
                </div>
              {/foreach}

            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

  {include file="template/database.inc"}

</div>
