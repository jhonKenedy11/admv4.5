<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_unidade.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<div class="right_col" role="main">
    <form class="full" name="lancamento" method="POST" class="form-horizontal form-label-left" novalidate action={$SCRIPT_NAME}>
        <input name=mod type=hidden value="est">
        <input name=form type=hidden value="unidade">
        <input name=id type=hidden value="">
        <input name=letra type=hidden value={$letra}>
        <input name=submenu type=hidden value={$subMenu}>

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Unidade - Consulta
                                <strong>
                                    {if $mensagem neq '' && $tipoMsg neq ''}
                                        <div class="alert alert-{if $tipoMsg eq 'Sucesso'}success{elseif $tipoMsg eq 'Alerta'}warning{elseif $tipoMsg eq 'Erro'}danger{/if}" role="alert">{$tipoMsg}!&nbsp;{$mensagem}</div>
                                    {/if}
                                </strong>
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro();">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>Sigla</th>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Ativo</th>
                                        <th class="no-link last" style="width: 120px;">Manuten&ccedil;&atilde;o</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$lanc}
                                        <tr class="even pointer">
                                            <td>{$lanc[i].UNIDADE}</td>
                                            <td>{$lanc[i].DESCRICAO}</td>
                                            <td>{if $lanc[i].ATIVO eq 'S'}Sim{else}Não{/if}</td>
                                            <td class="last">
                                                <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].UNIDADE}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].UNIDADE}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                            </td>
                                        </tr>
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {include file="template/database.inc"}
</div>
