                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'error'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {else}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-warning" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}