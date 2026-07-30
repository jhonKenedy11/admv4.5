{* Seção E-mail (accordion collapse2) — incluída em crm_contas_acompanhamento_cadastro.tpl *}
                                    <a class="panel-heading collapsed" role="tab" id="heading2" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapse2" aria-expanded="false"
                                        aria-controls="collapse2">
                                        <h4 class="panel-title"><i class="fa fa-chevron-down"></i>&nbsp; E-mail</h4>
                                    </a>

                                    <div id="collapse2" class="panel-collapse collapse{if $dashboard_origem eq 'dashboard_crm'} in{/if}" role="tabpanel" aria-labelledby="heading2">
                                        <div class="panel-body">
                                            <div class="x_panel">
                                                <div id="form_email">

                                                    <div class="form-group col-md-9" id="cabecalho">
                                                        <!-- ID EMAIL SE EXISTIR -->
                                                        <input name=email_id type=hidden value={$email_id}>
                                                        <!-- FIM EMAIL SE EXISTIR -->
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Remetente</span>
                                                            <input type="text" class="form-control" readonly id="email_remetente" placeholder="remetente" value="{$email_remetente}">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Destinatário(s)</span>
                                                            <input type="text" class="form-control" readonly id="email_destinatario" placeholder="incluir remetente pela lista de contatos" value="{$email_destinatario}">
                                                        </div>

                                                        <div class="input-group">
                                                            <span class="input-group-addon">Assunto</span>
                                                            <input type="text" class="form-control" id="email_assunto" placeholder="digite o assunto do e-mail" value="{$email_assunto}">
                                                        </div>

                                                        <div class="input-group">
                                                            <span class="input-group-addon">Anexo</span>
                                                            <input type="text" class="form-control" readonly id="email_anexo" placeholder="" value="{$email_anexo}">
                                                        </div>

                                                        <div class="clearfix">
                                                    
                                                            <div id="alerts"></div>

                                                            <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                                                                {* <div class="btn-group">
                                                                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                                                                    <ul class="dropdown-menu">
                                                                    </ul>
                                                                </div> *}
                            
                                                                <div class="btn-group">
                                                                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a data-edit="fontSize 5">
                                                                                <p style="font-size:17px">Huge</p>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-edit="fontSize 3">
                                                                                <p style="font-size:14px">Normal</p>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-edit="fontSize 1">
                                                                                <p style="font-size:11px">Small</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                            
                                                                <div class="btn-group">
                                                                    <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                                                                    <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                                                                    <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                                                                    <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                                                                </div>
                            
                                                                <div class="btn-group">
                                                                    <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                                                                    <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                                                                    <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                                                                    <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                                                                </div>
                            
                                                                <div class="btn-group">
                                                                    <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                                                                    <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                                                                    <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                                                                    <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                                                                </div>

                                                                <div class="btn-group">
                                                                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                                                                    <div class="dropdown-menu input-append">
                                                                        <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                                                                        <button class="btn" type="button">Add</button>
                                                                    </div>
                                                                    <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                                                                </div>

                                                                <div class="btn-group">
                                                                    <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                                                                    <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                                                                </div>
                            
                                                                <div class="btn-group">
                                                                    <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                                                                    <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                                                                </div>
                                                            </div>


                                                            <div id="editor-one" class="editor-wrapper" value={$editorOne}></div>
                            
                                                            <textarea name="descr" id="descr" style="display:none;"></textarea>
                                                        
                                                            <br />
                                                            
                                                            <div class="ln_solid"></div>
                                                            
                                                            <button class="btn btn-info pull-right" onclick="sendEmail(event)">Enviar</button>
                                                            <button class="btn btn-success pull-right" onclick="savedEmail(event)">Salvar</button>
                                                            
                                                        </div>
                                                    </div>
                                                    

                                                    <div class="col-md-3">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Anexos</th>
                                                                </tr>
                                                            </thead>
                    
                                                            {* <tbody class="bodyContatos" id="bodyContatos">
                                                                {section name=h loop=$contatos_cliente}
                                                                    <tr class="trContatos small">
                                                                        <th scope="row">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" value="" id="checkbox1">
                                                                            </div>
                                                                        </th>
                                                                        <td>{$contatos_cliente[h].NOME_CONTATO}</td>
                                                                        <td>{$contatos_cliente[h].TELEFONE}</td>
                                                                        <td>{$contatos_cliente[h].EMAIL}</td>
                                                                    </tr>
                                                                {/section}
                                                            </tbody> *}

                                                            <tbody class="bodyAnexos" id="bodyAnexos">
                                                            
                                                                <tr class="">
                                                                    <th scope="row">
                                                                        <input class="form-check-input anexoEmail" type="checkbox" value="/file/anexo_email/tractor_venda_mais.pdf" id="venda_mais.pdf"  
                                                                        {if $anexo1 eq 'true'} checked {/if} onclick="updateInputAnexo()">
                                                                    </th>
                                                                    <td>venda_mais.pdf</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div> <!-- <div class="col-md-3" id="body"> -->

                                                    <!------------------------------------------------------------------------------------------------------------------------->

                                                    <div class="col-md-3">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Template</th>
                                                                </tr>
                                                            </thead>
                    
                                                            {* <tbody class="bodyContatos" id="bodyContatos">
                                                                {section name=h loop=$contatos_cliente}
                                                                    <tr class="trContatos small">
                                                                        <th scope="row">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" value="" id="checkbox1">
                                                                            </div>
                                                                        </th>
                                                                        <td>{$contatos_cliente[h].NOME_CONTATO}</td>
                                                                        <td>{$contatos_cliente[h].TELEFONE}</td>
                                                                        <td>{$contatos_cliente[h].EMAIL}</td>
                                                                    </tr>
                                                                {/section}
                                                            </tbody> *}

                                                            <tbody class="bodyContatos" id="bodyContatos">
                                                {if $templates_email|@count gt 0}
                                                                <tr>
                                                                    <th scope="row">
                                                                        <input class="form-check-input templateEmail" type="radio" name="crm_template_email"
                                                                            id="crm_template_none" value="" checked="checked" onclick="verificaCheckTemplate()">
                                                                    </th>
                                                                    <td><em>Nenhum</em></td>
                                                                </tr>
                                                  {section name=t loop=$templates_email}
                                                                <tr class="crm-template-row" data-template-id="{$templates_email[t].ID|escape:'html'}" data-template-descricao="{$templates_email[t].DESCRICAO|escape:'html'}">
                                                                    <th scope="row">
                                                                        <input class="form-check-input templateEmail" type="radio" name="crm_template_email"
                                                                            id="tmpl_{$smarty.section.t.index}"
                                                                            value="{$templates_email[t].ID|escape:'html'}"
                                                                            onclick="verificaCheckTemplate()">
                                                                    </th>
                                                                    <td>{$templates_email[t].DESCRICAO|escape:'html'}</td>
                                                                </tr>
                                                  {/section}
                                                {else}
                                                                <tr>
                                                                    <td colspan="2"><small>Nenhum template cadastrado.</small></td>
                                                                </tr>
                                                {/if}
                                                        </tbody>
                                                        </table>
                                            {* Metadado por ID (não entra no submit do form principal) *}
                                            <div class="crm-templates-hidden" style="display:none" aria-hidden="true">
                                                {section name=ht loop=$templates_email}
                                                <input type="hidden" class="crmTmplById" data-template-id="{$templates_email[ht].ID|escape:'html'}" data-template-descricao="{$templates_email[ht].DESCRICAO|escape:'html'}" value="" />
                                                {/section}
                                            </div>
                                                    </div> <!-- <div class="col-md-3" id="body"> -->




                                                </div> <!-- <div id="form_email"> -->
                                            </div> <!-- x_panel-->

                                        </div> <!-- FIM panel-body -->

                                    </div> <!-- FIM collpase2 -->
