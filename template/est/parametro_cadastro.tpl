<script type="text/javascript" src="{$pathJs}/est/s_parametro.js"> </script>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="{$bootstrap}/css/switchery/switchery.min.css" />
    <body class="nav-md">
        <FORM class="full" NAME="upload"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="{$mod}">   
            <input name=form          type=hidden value="{$form}">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}>  

            <div class="row">
                <div class="col-lg-8 text-left">
                    <div>
                        <h1>
                            Par&acirc;metro de Estoque
                        </h1>
                    </div>
                </div>
            </div>
            {if $mensagem neq ''}
                {if $tipoMsg eq 'sucesso'}
                    <div class="row">
                        <div class="col-lg-12 text-left">
                            <div>
                                <div class="alert alert-success" role="alert"><strong>Sucesso!</strong>&nbsp;{$mensagem}</div>
                            </div>
                        </div>
                    </div>
                {elseif $tipoMsg eq 'alerta'}
                    <div class="row">
                        <div class="col-lg-12 text-left">
                            <div>
                                <div class="alert alert-danger" role="alert"><strong>Aviso!</strong>&nbsp;{$mensagem}</div>
                            </div>
                        </div>
                    </div>       
                {/if}

            {/if}

            <div class="row">
                <div class="col-lg-6 text-left">
                    <label>CFOP:</label>
                    <div class="panel panel-default">
                        <input type="text" class="form-control" name="file" id="file" value={$cfop}>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6 text-left">
                    <div>
                        <button type="button" class="btn btn-primary" onClick="javascript:submitVisualizar();"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Visualizar </button>
                        <button type="button" class="btn btn-success" onClick="javascript:submitCadastrar();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Cadastrar</button>
                    </div>
                </div>

            </div>







        </form>
    </body>        

</html>






<meta charset="UTF-8">
<fieldset class="full">
    <h1>
        Parametros do Estoque
    </h1>
    <button class="but but-error but-shadow but-rc" type="button" name="submit"onClick="javascript:submitConfirmar('');">Salvar</button>
    <div class="mensagem">
        <label>{$mensagem}</label>
    </div>
</fieldset>  

<FORM class="full" NAME="ordemservico" ACTION={$SCRIPT_NAME} METHOD="post">
    <input name=mod           type=hidden value="{$mod}">   
    <input name=form          type=hidden value="{$form}">   
    <input name=opcao         type=hidden value="">   
    <input name=submenu       type=hidden value={$subMenu}>
    <input name=letra         type=hidden value={$letra}>
    <input name=id            type=hidden value={$id}>  

    <fieldset>
        <div class="campo" style="width: 50em">
            <label class="negrito"> Identifica&ccedil;&atilde;o da Nota fiscal</label>
        </div>    
    </fieldset>  

    <fieldset class="grupo">
        <div class="campo">
            <label for="cfop" style="width: 25em">CFOP</label>
            <input type="text" id="cfop" name="cfop" style="width: 5em" value={$cfop}>
        </div>
        <div class="campo">
            <label for="natOperacao" style="width: 25em">Natureza Opera&ccedil;&atilde;o</label>
            <input type="text" id="natOperacao" name="natOperacao" style="width: 5em" value={$natOperacao}>
        </div>
        <div class="campo">
            <label for="condPgto" style="width: 25em">Condição Pagamento</label>
            <select id="condPgto" name="condPgto" style="width: 20em">
                {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
            </select>
        </div>
        <div class="campo">
            <label for="genero" style="width: 25em">G&ecirc;nero</label>
            <select id="genero" name="genero" style="width: 20em">
                {html_options values=$genero_ids selected=$genero_id output=$genero_names}
            </select>
        </div>
        <div class="campo">
            <label for="conta" style="width: 25em">Conta</label>
            <select id="conta" name="conta" style="width: 20em">
                {html_options values=$conta_ids selected=$conta_id output=$conta_names}
            </select>
        </div>
        <div class="campo">
            <label for="serie" style="width: 25em">Serie</label>
            <input type="text" id="serie" name="serie" style="width: 5em" value={$serie}>
        </div>
    </fieldset>


</form>