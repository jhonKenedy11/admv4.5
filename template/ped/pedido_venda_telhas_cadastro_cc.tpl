<div class="modal fade" id="modalCC" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <label for="grupo">Sequencia de pesquisa</label>
                        <SELECT class="form-control" name="pesq_cc" > 
                            {html_options values=$pesq_cc_ids selected=$pesq_cc_id output=$pesq_cc_names}
                        </SELECT>                                            
                    </div> 
                    
                    <div class="col-md-8 col-sm-12 col-xs-12" align="right">
                    <button type="button" class="btn btn-warning" onClick="javascript:submitLetraModalCC();">
                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitModalCC({$lancCCModal});">Confirmar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" onClick="javascript:limpaModalCC()">Fechar</button>
                    
                    </div>
                </div>

                <!-- msg -->
                <div id="content_msg">
                {if $msg_cc_modal neq ''}
                    <div  class="row">
                        <div class="col-lg-12 text-left">
                            <div>
                                <div class="alert alert-danger small" role="alert">Aviso ! Produto(s) não encontrado(s)! <br>{$msg_cc_modal} </div>
                            </div>
                        </div>
                    </div> 
                {/if}
                </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>Produtos para pesquisa</label>
                    <div class="panel panel-default small">                                               
                        <textarea class="resizable_textarea form-control" id="desc_cc" name="desc_cc" rows="4" >{$desc_cc}</textarea>
                    </div>
                </div>    
            </div>
            <div class="x_panel">   
            <table id="datatable" class="table table-bordered jambo_table">
                <thead>
                    <tr style="background: #2A3F54; color: white;">
                        
                        <th>C&oacute;digo</th>
                        <th>Descri&ccedil;&atilde;o</th>
                        <th>Grupo</th>
                        <th>Unidade</th>
                        <th>Venda</th>
                        <th>Qtde</th>
                    </tr>
                </thead>
                
                <tbody>

                    {section name=i loop=$lancCCModal}
                        {assign var="total" value=$total+1}
                        
                        <tr>
                            <td> {$lancCCModal[i].CODIGO} </td>
                            <td> {$lancCCModal[i].DESCRICAO} </td>
                            <td> {$lancCCModal[i].GRUPO} </td>
                            <td> {$lancCCModal[i].UNIDADE} </td>
                            <td> {$lancCCModal[i].VENDA} </td>
                            
                            <td class="price-value"> <input class="form-control input-sm" 
                                title="Digite a qtde para este item." 
                                name="input_quant_{$lancCCModal[i].CODIGO}" 
                                value="{$lancCCModal[i].QUANT}" > 
                            </td >
                            
                        </tr>                        
                    {/section} 
                </tbody>                
            </table>
            </div> <!-- FIM X-PANEL -->            

            
        </div>
    </div> 
</div>                            
                            
                            
                                    
                                        
                                         

                                        
                                    
                                    
                                    
                                    
                                    


                    

                  