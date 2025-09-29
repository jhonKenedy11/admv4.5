
     <fieldset class="small">
        <h1>
		Gerar Previs&atilde;o Or&ccedil;amentaria
        </h1>
	<button class="but but-error but-shadow but-rc" type="submit" name="submit"onClick="javascript:submitGeraOrcamento('');">Confirmar</button>
        <span class="ou">ou</span>
        <button class="" type="submit" name="submit" onClick="javascript:submitVoltar('');">Descartar</button>
    </fieldset>  
    <div class="mensagem">
        <label>{$mensagem}</label>
    </div>
<FORM class="small" NAME="lancamento" ACTION={$SCRIPT_NAME} METHOD="post">
  <input name=opcao         type=hidden value="">   
  <input name=submenu       type=hidden value={$subMenu}>
  <input name=letra         type=hidden value={$letra}>
  <input name=mesTrabalho   type=hidden value="">
  <input name=anoTrabalho   type=hidden value="">

    <fieldset>
        <div class="campo">
            <label for="filial">Filial</label>
            <SELECT name="filial" style="width: 15em"> 
    		{html_options values=$filial_ids output=$filial_names selected=$filial_id}
            </SELECT>
        </div>  
    </fieldset>  
  
    <fieldset>
        <div class="campo">
            <label for="mesBase">M&ecirc;s</label>
            <SELECT name="mesBase" style="width: 15em"> 
    		{html_options values=$mesBase_ids output=$mesBase_names selected=$mesBase_id}
            </SELECT>
        </div>  
    </fieldset>    
    
    <fieldset>
        <div class="campo">
            <label for="anoBase"Ano</label>
            <input type="text" id="anoBase" name="anoBase" style="width: 5em" value={$anoBase}>
        </div>  
    </fieldset> 

    <fieldset>
        <div class="campo">
            <label for="media">M&eacute;dia de Meses</label>
            <SELECT name="media" style="width: 15em"> 
        	{html_options values=$media_ids selected=$media_id output=$media_names}
            </SELECT>
        </div>  
    </fieldset>  

</form>
