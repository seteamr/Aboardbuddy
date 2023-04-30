<div class="header">
<div class="logo">
    <?php  if($this->session->userdata('userid') == ''){ ?>
    <a href="<?php echo base_url(); ?>login"  ><img src="<?php echo base_url();?>images/sindhu_logo.png" alt="" /> </a>    
     <?php }else {?>
         <a href="<?php echo base_url(); ?>dashboard" ><img src="<?php echo base_url();?>images/sindhu_logo.png" alt="" /> </a> 
         
        <?php }?>
    

</div>
    <?php  if($this->session->userdata('userid') != ''){ ?>
    <a class=" setting-button-set" href="<?php echo base_url(); ?>registration/settings" >Settings</a>
    <?php }?>

<div class="clear"></div>
</div>
<?php 
    $this->load->model('common_model');
    $total_regi_no=$this->common_model->getTotalprofile_registrarion_accept(); 
    //print_r($total_regi_no); 
    ?>
  <ul class="top-menu clearfix">
            <li><a href="<?php echo base_url(); ?>dashboard" >Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>registration" >Add Profile</a></li>
        <li><a href="<?php echo base_url(); ?>profile" >Search Profile</a></li>
        <li><a href="<?php echo base_url(); ?>profile/disable_profile" >Disable Profile</a></li>
        <li><a href="<?php echo base_url(); ?>registration/exportUser" >Export All Profiles</a></li>
        <li><a href="<?php echo base_url(); ?>profile/candidate" >Print Profiles</a></li>
        <li><a href="<?php echo base_url(); ?>profile/boy_profile" >View Profiles</a></li>
        <li><a href="<?php echo base_url(); ?>profile/accept_registration" >Accept Registration<?php if(count($total_regi_no)>0){ ?><span><?php echo $total_regi_no; ?></span> <?php }?></a></li>
        <li><a href="<?php echo base_url(); ?>login/logout" >Logout</a></li>
</ul> 

<h3>Images</h3>
<?php if($profile_status == 1){ ?>
<a href="<?php echo base_url(); ?>profile/disable_profile" class="back-button">Back</a>
<?php }else if($current_profile->profile== "boy_profile") { ?>
<a href="<?php echo base_url(); ?>profile/boy_profile" class="back-button">Back</a>
<?php }else if($current_profile->profile== "girl_profile") { ?>
<a href="<?php echo base_url(); ?>profile/girl_profile" class="back-button">Back</a>
<?php }else{ ?>
<a href="<?php echo base_url(); ?>profile/searchresult" class="back-button">Back</a>
<?php } ?>

                <div id="content-admin">
                

                        <div id="upload-img">


                            <form id="fileupload" action="<?php echo base_url(); ?>upload/upload_img/<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                <div class="row fileupload-buttonbar">
                                    <div class="span7">
                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                        <span class="btn btn-success fileinput-button">
                                            <span ><i class="icon-plus icon-white" ></i> Add files...</span>
                                            <input type="file" name="userfile" multiple>
                                        </span>
                                       <!-- <button type="submit" class="btn btn-primary start">
                                            <i class="icon-upload icon-white"></i> Start upload
                                        </button>
                                        <button type="reset" class="btn btn-warning cancel">
                                            <i class="icon-ban-circle icon-white"></i> Cancel upload
                                        </button>
                                        <button type="button" class="btn btn-danger delete">
                                            <i class="icon-trash icon-white"></i> Delete
                                        </button>
                                        <input type="checkbox" class="toggle">-->
                                    </div>
                                    
                                    <?php /*?><div class="span5">
                                        <!-- The global progress bar -->
                                        <div class="progress progress-success progress-striped active fade">
                                            <div class="bar" style="width:0%;"></div>
                                        </div>
                                    </div><?php */?>
                                </div>
                                <!-- The loading indicator is shown during image processing -->
                                <div class="fileupload-loading"></div> 
                                <br>
                                
                                
                                
                                <!-- The table listing the files available for upload/download -->
                                
                                <table class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
                                 </form>
                            
               
<div style="position:absolute; left:500px; top: 300px;">
    <p style="padding:18px 17px 0 0; float: left; margin: 0px;"> <b>Candidate Name :</b>  <?php echo $candidate_name; ?> </p>
    <p style="padding:18px 0px 0 0; float: left; margin: 0px;"> <b>Registration No. :</b> <?php echo $registration_no; ?> </p>
    
</div>             
    <!-- get all user photos code start here  -->                               
   <?php  if(count($photos)>0){?>
    <table class="table table-striped">
        <tbody  data-target="#modal-gallery" data-toggle="modal-gallery" >
            <?php for($i=0;$i<count($photos);$i++){?>

                <tr class="template-download fade in" style="height: 61px;">
                    <td class="preview">
                        <a target="_blank" rel="gallery" title="<?php echo $photos[$i]->file_name; ?>" href="<?php echo base_url().'upload_profile/users/'.$photos[$i]->file_name; ?>">
                        <img width="100px" src="<?php echo base_url().'upload_profile/users/'.$photos[$i]->file_name; ?>">
                        </a> <br>
                        <a rel="gallery" title="<?php echo $photos[$i]->file_name; ?>" href="<?php echo base_url().'upload_profile/users/'.$photos[$i]->file_name; ?>"><?php echo $photos[$i]->file_name; ?></a>
                    </td>
                <!--    <td class="name">
                      <a rel="gallery" title="<?php echo $photos[$i]->file_name; ?>" href="<?php echo base_url().'upload_profile/users/'.$photos[$i]->file_name; ?>"><?php echo $photos[$i]->file_name; ?></a>  
                    </td>  -->
                    <td class="size">
                        <?php $file_size = number_format($photos[$i]->file_size / 1024, 2) . ' KB'; echo $file_size ; ?>
                    </td>
                    <td class="delete">
                       <button class="btn btn-danger" onClick="deleterecord(this.id)" title="Delete" id="<?php echo $photos[$i]->id;?>">
                        <i class="icon-trash icon-white"></i>
                        Delete
                        </button> 
                      
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <?php } ?>
    <script>
            function deleterecord(id)
            {    
                var x = confirm("Are you sure you want to delete Image?");                
                    if(x)
                    {                                     
                        jQuery.ajax({
                                    type    : "POST",
                                    url     : "<?php echo base_url(); ?>" + "upload/deleteimg",
                                    data    : {img_id: id},
                                    success : function(res){
                                                    if (res =='1')
                                                    {
                                                            document.location.href = "<?php echo base_url(); ?>"+"upload/index/" +"<?php echo $id; ?>";
                                                    }                                                                           
                                            }
                                    });
                    }
                    else
                    {
                        return false;
                    }

            }
            
    </script>


<!-- get all user photos code end here  -->  
                        </div>
    <script>
            function delrecord(id)
            {    
                var x = confirm("Are you sure you want to delete Image?");                
                    if(x)
                    {  
                       jQuery.ajax({
                                    type    : "POST",
                                    url     : "<?php echo base_url(); ?>" + "upload/deleteimg",
                                    data    : {img_id: id},
                                    success : function(res){
                                                    if (res =='1')
                                                    {
                                                            document.location.href = "<?php echo base_url(); ?>"+"upload/index/" +"<?php echo $id; ?>";
                                                    }                                                                           
                                            }
                                    });
                    }
                    else
                    {
                      location.href = "<?php echo base_url(); ?>"+"upload/index/" +"<?php echo $id; ?>";
                       
                    }

            }
            
    </script>



                        <!-- modal-gallery is the modal dialog used for the image gallery -->
                        <div id="modal-gallery" class="modal modal-gallery hide">
                            <div class="modal-header">
                                <a class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"></h3>
                            </div>
                            <div class="modal-body"><div class="modal-image"></div></div>
                            <div class="modal-footer">
                                <a class="btn btn-primary modal-next">Next <i class="icon-arrow-right icon-white"></i></a>
                                <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> Previous</a>
                                <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> Slideshow</a>
                                <a class="btn modal-download" target="_blank"><i class="icon-download"></i> Download</a>
                            </div>
                        </div>  

                        <!-- The template to display files available for upload -->
                        <script id="template-upload" type="text/x-tmpl">                            
                            {% for (var i=0, files=o.files, l=files.length, file=files[0]; i< l; file=files[++i]) { %}
                               
                            <tr class="template-upload fade">
                                <td class="preview"><span class="fade"></span></td>
                                <td class="name">{%=file.name%}</td>
                                <td class="size">{%=o.formatFileSize(file.size)%}</td>
                                {% if (file.error) { %}
                                <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
                                {% } else if (o.files.valid && !i) { %}
                                <td>
                                    <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
                                </td>
                                <td class="start">{% if (!o.options.autoUpload) { %}
                                    <button class="btn btn-primary">
                                        <i class="icon-upload icon-white"></i> {%=locale.fileupload.start%}
                                    </button>
                                    {% } %}</td>
                                {% } else { %}
                                <td colspan="2"></td>
                                {% } %}
                                <td class="cancel">{% if (!i) { %}
                                    <button class="btn btn-warning">
                                        <i class="icon-ban-circle icon-white"></i> {%=locale.fileupload.cancel%}
                                    </button>
                                    {% } %}</td>
                            </tr>
                            {% } %}
                       
                            </script>

                            <div id="download-files">
                            <!-- The template to display files available for download -->
                          
                           <script id="template-download" type="text/x-tmpl">
                       
                                {% for (var i=0, files=o.files, l=files.length, file=files[0]; i< l; file=files[++i]) { %}
                                <tr class="template-download fade">
                                    {% if (file.error) { %}
                                    <td> </td>
                                    <td class="name">{%=file.name%}</td>
                                    <td class="size">{%=o.formatFileSize(file.size)%}</td>
                                    <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
                                    {% } else { %}
                                    <td class="preview">{% if (file.thumbnail_url) { %}
                                        <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img width="100px" src="{%=file.thumbnail_url%}"></a>
                                <br><a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
                                        {% } %}</td>
                                    
                                    <td class="size">{%=o.formatFileSize(file.size)%}</td>
                                    {% } %}
                                    <td class="delete">
                                        <button class="btn btn-danger hide_demo" onClick="delrecord(this.id)"  id="{%=file.id%}">
                                            <i class="icon-trash icon-white"></i> {%=locale.fileupload.destroy%}
                                        </button>
                                        
                                    </td>
                                </tr>
                                {% } %}

                                
                                </script>   
                           
                            </div>


                        </div>

            