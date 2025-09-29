        <!-- page content -->
    <script type="text/javascript" src="{$pathJs}/est/s_inventario_imagem.js"> </script>

        <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} enctype="multipart/form-data">
        <input name=mod                   type=hidden value="est">   
        <input name=form                  type=hidden value="inventario">   
        <input name=id                    type=hidden value={$id}>   
        <input name=idInventarioProduto   type=hidden value={$idInventarioProduto}>  
        <input name=idimg                 type=hidden value=''>
        <input name=destaque              type=hidden value=''>
        <input name=letra                 type=hidden value={$letra}>
        <input name=submenu               type=hidden value={$subMenu}>
        <input name=tituloImg             type=hidden value={$titulo}>
        
        
        <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Inventario </h3>
              </div>
            </div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Imagens - {$titulo}
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarImagem('{$id}');">
                                <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>

                    <div class="clearfix"></div>
                    
                  </div>
                  
                  <div class="x_content">
                      <div class="form-group">
                         <div class="col-md-10 col-sm-6 col-xs-12">
                              <div style="position:relative;">
                                  <a {$btnDisabled} class='btn btn-primary ' href='javascript:;'>
                                      Inserir Imagem 
                                      
                                      <input class="input-group" type="file" id="user_image" name="user_image" accept="image/*" 
                                            >
                                  </a>   
                                  <span  id="upload-file-info"></span>
                              </div>
                          </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <button type="button" class="btn btn-dark" {$btnDisabled}  onClick="javascript:submitSalvarImagem({$id});">
                                    <span class="glyphicon glyphicon-save" aria-hidden="true"></span><span> Salvar Imagem</span>
                            </button>
                        </div>
                            
                      </div>
                    <div class="clearfix"></div>
                      
                    <div class="container">

                      <div class="x_panel">
                        <div class="row">
                         {section name=i loop=$lanc}
                            
                            
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                    <p class="page-header"><?php echo $userName."&nbsp;/&nbsp;".$userProfession; ?></p>
                                    <img src="images/doc/inv/{$lanc[i].ID_DOC}/{$lanc[i].ID}.jpg" class="img-rounded" style="max-width:150px; max-height:150px;" width="auto" height="auto" />
                                    <p class="page-header">
                                    
                                    <button type="button" {$btnDisabled} {if $lanc[i].DESTAQUE eq 'N'} class="btn-xs btn-warning" {else} class="btn-xs btn-success" {/if} onClick="javascript:submitDestaqueImagem({$lanc[i].ID}, '{$lanc[i].DESTAQUE}');">
                                            <span class="glyphicon glyphicon-flag" aria-hidden="true"></span><span> Destaque</span>
                                    </button> 
                                    <button type="button" {$btnDisabled} class="btn-xs btn-danger"  onClick="javascript:submitExcluirImagem({$lanc[i].ID_DOC}, {$lanc[i].ID});">
                                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span> Apagar</span>
                                    </button>
                                    </p>
                            </div>  
                              
                            {assign var="total" value=($total+1)} 
                         {/section} 
                        
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
