<!DOCTYPE HTML>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <FORM NAME="lancamento" ACTION={$SCRIPT_NAME} METHOD="post">
            <input name=opcao           type=hidden value="">   
            <input name=submenu         type=hidden value={$subMenu}>
            <input name=letra           type=hidden value={$letra}>
            <input name=usuario         type=hidden value={$usuario}>
            <input name=pessoa          type=hidden value={$pessoa}>
            <input name=tipo            type=hidden value={$tipo_id}>
            <input name=situacao        type=hidden value={$situacao_id}>
            <input name=grupo           type=hidden value={$grupo_id}>
            <input name=conta           type=hidden value={$conta}>
            <input name=salario         type=hidden value={$salario}>

            <h1>
                Perfil Usu&aacute;rio
            </h1>
            <div class="mensagem">
                <label>{$mensagem}</label>

            </div>    
            <fieldset class="grupo">
                <div class="campo">
                    <label for="usuario">Matr&iacute;cula</label>
                    <input type="text" disabled id="usuario" name="usuario" style="width: 8em" value={$usuario}>
                </div>
                 <div class="campo">

                    <label for="nomeReduzido">Nome Reduzido</label>
                    <input type="text" id="nomeReduzido" name="nomeReduzido" style="width: 20em" value={$nomeReduzido}>
                </div>
                
            </fieldset>
            <fieldset class="grupo">
               <div class="campo">
                    <label for="nome">Login</label>
                    <input type="text" id="login" name="login" style="width: 10em" value={$login}>
                </div>
                <div class="campo">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" style="width: 15em" value={$senha}>
                </div>
                <div class="campo">
                    <label for="ConfimSenha">Confirme a Senha</label>
                    <input type="password" id="ConfimSenha" name="ConfimSenha" style="width: 15em" value={$ConfimSenha}>
                </div>
            </fieldset>
            <fieldset class="grupo">
                <div class="campo">
                    <label for="pessoa">Cod.</label>
                    <input type="text" disabled id="pessoa" name="pessoa" style="width: 5em" value={$pessoa}>
                </div>    
                <div class="campo">
                    <label for="nome">Usu&aacute;rio Cadastrado em Pessoa</label>
                    <input type="text" disabled id="nome" name="nome" disabled style="width: 32em" value={$pessoaNome}>
                </div>    
            </fieldset>
            <fieldset class="grupo">
                <div class="campo">
                    <label for="tipo">Tipo</label>
                    <SELECT name="tipo" style="width: 15em" disabled> 
                        {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                    </SELECT>
                </div>
                <div class="campo">
                    <label for="situacao">Situa&ccedil;&amacr;o</label>
                    <SELECT name="situacao" style="width: 15em" disabled> 
                        {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                    </SELECT>
                </div>
                <div class="campo">
                    <label for="grupo">Grupo</label>
                    <SELECT name="grupo" style="width: 15em" disabled> 
                        {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                    </SELECT>
                </div>
            </fieldset>
            <fieldset class="grupo">
                <div class="campo">
                    <label for="conta">Conta Banc&aacute;ria</label>
                    <input type="text" disabled id="conta" name="conta" style="width: 8em" value={$conta}>
                </div>
                <div class="campo">
                    <label for="nome">Custo Hora</label>
                    <input type="text" disabled id="salario" name="salario" style="width: 10em" value={$salario}>
                </div>
            </fieldset>
            <fieldset class="grupo">
                <div class="campo">
                    <button class="botao submit" type="button" name="submit"onClick="javascript:submitConfirmarPerfil('');">Confirmar</button>
                </div>    
            </fieldset>

         
        </form>
    </body>
</html>

