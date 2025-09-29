<!-- page content -->
    <script type="text/javascript" src="{$pathJs}/cat/s_os_imagem.js"> </script>
    

        <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} enctype="multipart/form-data">
        <input name=mod                   type=hidden value="est">   
        <input name=form                  type=hidden value="inventario">   
        <input name=id                    type=hidden value={$id}>   
        <input name=idOs                  type=hidden value={$idOs}> 
        <input name=idImg                 type=hidden value={$idImg}>
        <input name=destaque              type=hidden value=''>
        <input name=letra                 type=hidden value={$letra}>
        <input name=submenu               type=hidden value={$subMenu}>
        <input name=tituloImg             type=hidden value={$titulo}>
        <input name=opcao                 type=hidden value={$opcao}>
        <input name=table                 type=hidden value={$table}>
        <input name=table_id              type=hidden value={$table_id}>
        <input name=path                  type=hidden value={$path}>
        
        
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="page-title">
                <div class="title_left">
                  <h3>&nbsp;&nbsp;&nbsp;&nbsp;Imagens - Ordem de Serviço - {$idOs}</h3>
                </div>
            </div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                      <strong>
                        {if $mensagem neq ''}
                          <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                        {/if}
                      </strong>
                    </h2>

                    <div class="clearfix"></div>
                    
                  </div>
                  
                  <div class="x_content">
                      <div class="form-group">
                         <div class="col-md-10 col-sm-6 col-xs-12">
                              <div style="position:relative; margin-bottom: 10px;">
                                  <a class='btn btn-dark' href='javascript:;'>
                                      <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span><span> Inserir Imagem</span> 
                                      <input class="input-group" type="file" id="user_image" name="user_image" accept="image/*">
                                  </a>   
                                  <span id="upload-file-info"></span>
                              </div>
                          </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <button type="button" class="btn btn-success" aria-hidden="true" onClick="javascript:submitSalvarImagem('{$idOs}');">
                                    <span class="glyphicon glyphicon-floppy-save glyphicon-align-center" aria-hidden="true"></span><span> Salvar Imagem</span>
                            </button>
                        </div>
                            
                      </div>
                    <div class="clearfix"></div>
                      
                    <div class="container">

                      <div class="x_panel">
                        <div class="row">
                         {section name=i loop=$lanc}
                            
                            
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                    <a href="#" class ="img-rounded">
                                    <img src="{$pathCliente}{$lanc[i].PATH}" class="img-rounded" style="width:180px; height:130px;" width="auto" height="auto" />
                                    <p class="page-header">
                                    </a>
                                    <button type="button" class="btn-xs btn-danger" onClick="javascript:submitExcluirImagem('{$lanc[i].ID}','{$lanc[i].TABLE}', '{$lanc[i].PATH}');">
                                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span> Apagar</span>
                                    </button>
                                    </p>
                                    
                            </div>  
                              
                            {assign var="total" value=($total+1)} 
                         {/section}

                         <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                           <div class="modal-dialog" data-dismiss="modal">
                             <div class="modal-content"  >              
                               <div class="modal-body">
                                 <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                 <img src="" class="imagepreview" style="width: 100%;" >
                               </div> 
                  
                                               
                                               
                             </div>
                           </div>
                         </div>
                        
                        </div>	
                      </div>	

                    </div>

                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>

    {include file="template/database.inc"}  
    
    <!-- /Datatables -->

    <style>
      #user_image{
        position:absolute;
        z-index:2;
        top:0;
        left:0;
        filter: alpha(opacity=0);
        -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
        opacity:0;
        background-color:transparent;
        color:transparent;
      }
      .sair{
        width: 25px;
        height: 25px;
        align-items: center;
      }

    </style>
    <script>
      var arqName = document.querySelector("#upload-file-info");
      var input = document.querySelector("#user_image");
      var imgWidth = document.querySelector("#imgLarg");
      var imgHeight = document.querySelector("#imgAlt");
      input.addEventListener("change", function(){
        arqName.innerText = "Nenhuma imagem selecionada.";
        if(input.files.length > 0){
          arqName.innerText = input.files[0].name;          
        }
      })
    </script>
    <script>
     $(function() {
            $('.img-rounded').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');   
            });     
    });
   </script>