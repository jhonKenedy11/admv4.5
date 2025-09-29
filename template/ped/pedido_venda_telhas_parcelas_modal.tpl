<div class="modal fade" id="modalParcelasLanc" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Parcelas Lançamento </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <!--button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitAgruparPedidos();">Confirmar</button-->
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">              
               <div class="col-md-12 col-sm-12 col-xs-12">
                    <table id="datatable-buttons-parcelas" class="table table-bordered jambo_table">
                        <thead>
                            <tr style="background: gray; color: white;">
                                <th>Parcela</th>
                                <th>Data Vencimento</th>
                                <th>Valor</th>
                                <th>Tipo Documento</th>
                                <th>Conta Recebimento</th>
                                <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$parcelas}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td> {$parcelas[i].PARCELA} </td>
                                    <td> 
                                        {$parcelas[i].VENCIMENTO|date_format:"%d/%m/%Y"} 
                                    </td>
                                    <td> 
                                        {$parcelas[i].VALOR|number_format:2:",":"."} 
                                    </td>
                                    <td> 
                                       {$parcelas[i].TIPODOCTO}
                                    </td>
                                    <td> 
                                        {$parcelas[i].CONTA}
                                    </td>
                                    <td> 
                                       {$parcelas[i].SITPGTO}
                                    </td>
                                   
                                    <td> 
                                       {$parcelas[i].OBS}
                                    </td>
                                </tr>
                            <p>
                        {/section} 
                        </tbody>
                    </table>
                </div>       
          </div>
        </div>  
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div> 
