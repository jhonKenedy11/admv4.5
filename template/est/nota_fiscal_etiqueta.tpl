<!-- page content -->
<div class="x_panel" style="padding: 0; margin: 0">

      {assign var="qtde" value=1}
      {section name=i loop=$qtdeVol}
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0 !important">
                  <table style="width: 610px !important">
                        {section name=i loop=$pedido}
                              <tr>
                                    <th rowspan="3" class="borda-direita">
                                          <img src="images/logo_login_sf.png" aloign="right" width=100 height=45 border="0"></A>
                                    </th>
                                    <td colspan="3">
                                          <center><b>{$empresa[0].NOMEEMPRESA}</b></center>
                                    </td>
                              </tr>
                              <tr>
                                    <td colspan="3">{$empresa[0].TIPOEND} {$empresa[0].TITULOEND}
                                          {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO}
                                          {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF}
                                          {$empresa[0].CEP}</td>
                              </tr>
                              <tr>
                                    <td colspan="3"><b>FONE :</b> ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}
                                          <b>Email:</b> {$empresa[0].EMAIL}
                                    </td>
                              </tr>
                              <tr>
                                    <td colspan="4">
                                          <center><b>Destinatário</b></center>
                                    </td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Empresa:</th>
                                    <td colspan="3">{$cliente[0].NOME}</td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Endereco:</th>
                                    <td>{$cliente[0].ENDERECO}</td>
                                    <td class="borda-esquerda borda-direita">
                                          <center><b>N.º </center></b>
                                    </td>
                                    <td>{$cliente[0].NUMERO}</td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Bairro:</th>
                                    <td>{$cliente[0].BAIRRO}</td>
                                    <td class="borda-esquerda borda-direita">
                                          <center><b>CEP </center></b>
                                    </td>
                                    <td>{$cliente[0].CEP}</td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Telefone:</th>
                                    <td colspan="3">{$cliente[0].FONE}</td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Cidade:</th>
                                    <td>{$cliente[0].CIDADE}</td>
                                    <td class="borda-esquerda borda-direita">
                                          <center><b>Estado</center></b>
                                    </td>
                                    <td>{$cliente[0].UF}</td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Num NF:</th>
                                    <td>{$pedido[i].NUMERO}</td>
                                    <td class="borda-esquerda borda-direita">
                                          <center><b>Volume</center></b>
                                    </td>
                                    <td>{$qtde} DE {$qtdeVol[i]}</td>
                              </tr>
                              <tr>
                                    <th class="borda-direita">Transportadora:</th>
                                    <td colspan="3">
                                          {if is_array($transportadora) && isset($transportadora[0].NOME)}
                                                {$transportadora[0].NOME}
                                          {else}
                                                Sem Transportadora favor verificar.
                                          {/if}
                                    </td>
                              </tr>

                              <tr>
                                    <th class="borda-direita">Obs:</th>
                                    <td colspan="3">
                                          <textarea style="width: 610px !important" class="resizable_textarea form-control" id="obs"
                                                name="obs">{$pedido[i].OBS}</textarea>
                                    </td>
                              </tr>
                        {/section}
                  </table>
            </div>
      </div>
      {$qtde=$qtde+1}
{/section}

<!--div class="row no-print">            
            <div class="col-xs-6">
              <button id="printBtn" class="btn btn-default" onclick="window.print()"><i class="fa fa-print"></i> Imprimir</button>
            </div> 
            <div class="col-xs-6" style="font-size:10px;" align="right">
              {$dataImp}
            </div>

      </div-->

</div>




</div>


</div>
</div>

</div> <!-- fim x_panel -->
<style>
      #obs {
            height: auto;
            font-size: 11px;
      }

      tr {
            border-style: solid;
            border-width: 1.5px;
            border-top-color: black;
      }

      .borda-direita {
            border-right-style: solid;
            border-width: 1.5px;
            border-top-color: black;
      }

      .borda-esquerda {
            border-left-style: solid;
            border-width: 1.5px;
            border-top-color: black;

      }
</style>


<!-- /page content -->