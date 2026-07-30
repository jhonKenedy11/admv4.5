<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_tabela_preco_item.js"> </script>

<style>
/* estilos para validação (hover aplicado) */
.validated {
    background-color: #c8e6c9;
    color: #000;
}
.validated:hover {
    background-color: #a6d5a0;
}
/* células compactas */
.preview-td {
    padding: 2px 6px;
    line-height: 14px;
    font-size: 12px;
}
/* padroniza botões e inputs dentro das células de preview */
.preview-td .form-control.input-sm,
.preview-td select.form-control.input-sm {
    height: 30px;
    padding: 4px 6px;
    box-sizing: border-box;
    font-size: 12px;
    line-height: 1.2;
}
.preview-td .input-group-btn .btn {
    height: 30px;
    padding: 4px 8px;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>

        <!-- page content -->
        <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="est">   
        <input name=form          type=hidden value="tabela_preco_item">
        <input name=submenu       type=hidden value="confirmar_import">
        <input name=id            type=hidden value="{$id}"> 
        <input name=id_tabela_preco type=hidden value="{$id_tabela_preco}">
        <input type="hidden" name="codProduto" id="codProduto" value="">
        <input type="hidden" name="descProduto" id="descProduto" value="">
        <input type="hidden" name="uniProduto" id="uniProduto" value="">

        <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2> Pré-visualização da Importação
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-info" role="alert">{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar();">
                                    <span class="glyphicon glyphicon-backward" aria-hidden="true">Voltar</span></button></li>
                        <li><button type="button" id="btnConfirmarImport" class="btn btn-success" onclick="submitConfirmarImport({$id_tabela_preco});">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Confirmar Importação
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr><th>#</th>
                            <th>Código</th>
                            <th>Código Fabricante</th>
                            <th>Descrição</th>
                            <th>Grupo</th> 
                            <th>Marca</th>
                            <th>Preço Base</th>
                            <th>Margem</th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach $preview as $i => $row}
                            <tr class="{if $row.codigo neq ''}validated{/if}">
                                <td class="preview-td">{$i+1}</td>
                                
                                <td class="preview-td">
                                    <div class="input-group">
                                        <input type="text" name="codigo_override[{$i}]" value="{$row.codigo}" class="form-control input-sm" placeholder="{if !$row.exists}Preencher para incluir novo{else}Pode alterar o código{/if}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" onclick="abrirPesquisaProdutoPopup({$i})">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-success" style="margin-left:4px" title="Cadastrar produto" onclick="abrirCadastrarProdutoPopup({$i});">
                                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="preview-td td-codfab">
                                    {$row.codigo_fabricante}
                                    <input type="hidden" name="codigo_fabricante_override[{$i}]" value="{$row.codigo_fabricante}">
                                </td>
                                <td class="preview-td td-descricao">
                                    {$row.descricao}
                                    <input type="hidden" name="descricao_override[{$i}]" value="{$row.descricao}">
                                </td>
                                <td class="preview-td td-grupo">
                                    <select name="grupo_override[{$i}]" class="form-control input-sm">
                                        <option value="">Selecione Grupo</option>
                                        {foreach from=$grupo_names key=gk item=gn}
                                            <option value="{$grupo_ids[$gk]}" 
                                            {if $grupo_ids[$gk] == $row.grupo}selected{/if}>{$gn}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td class="preview-td td-marca">
                                    <select name="marca_override[{$i}]" class="form-control input-sm">
                                        <option value="">Selecione Marca</option>
                                        {foreach from=$marca_names key=mk item=mn}
                                            <option value="{$marca_ids[$mk]}" {if $marca_ids[$mk] == $row.marca}selected{/if}>{$mn}</option>
                                        {/foreach}
                                    </select>
                                </td>                                
                                <td class="preview-td td-precobase">
                                    R$ {$row.precobase}
                                    <input type="hidden" name="precobase_override[{$i}]" value="{$row.precobase}">
                                </td>
                                <td class="preview-td td-margem">
                                    {$row.margem}%
                                    <input type="hidden" name="margem_override[{$i}]" value="{$row.margem}">
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>            
    </form>

    {include file="template/database.inc"}  
    
    <!-- /Datatables -->