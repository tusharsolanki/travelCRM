<?php
/*
    @Description: Template add/edit page
    @Author: Mohit Trivedi
    @Date: 06-08-2014

*/?>

<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
?>

<div id="content">
  <div id="content-header">
   <h1></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php  echo "Email Details";
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?=$viewname?>/interaction_mailsms" novalidate>
           <div class="row">
           <div class="col-sm-8">
		    <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('email_to');?> : </label> <label for="text-input"><?=!empty($datalist[0]['first_name'])?ucfirst(strtolower($datalist[0]['first_name'])):''?> <?=!empty($datalist[0]['last_name'])?ucfirst(strtolower($datalist[0]['last_name'])):''?>(<?=!empty($datalist[0]['email_address'])?($datalist[0]['email_address']):''?>)</label>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?> : </label> <label for="text-input"><?=!empty($datalist[0]['category'])?ucfirst(strtolower($datalist[0]['category'])):''?></label>
			  </div>
            </div>
			<div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			  <label for="text-input"><?=!empty($datalist[0]['template_name'])?ucfirst(strtolower($datalist[0]['template_name'])):''?></label>
             </div>
            </div>
            <div class='row mycalclass'>
         		<div class="col-sm-12 form-group">
                 <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?> : </label>
                 <label for="text-input"><input id="txt_template_subject" name="txt_template_subject" class="form-control parsley-validated" type="text" value="<?=!empty($datalist[0]['template_subject'])?($datalist[0]['template_subject']):''?>" data-required="true" placeholder="e.g. Email Subject"></label>
          		</div>
          	</div>
           </div>
		  <?php if(!empty($datalist[0]['attachment_name'])) {
		  	//echo $datalist[0]['attachment_name']; exit;
		  	$attachment = explode(", ",$datalist[0]['attachment_name']);
		  	?>
          <div class="col-sm-4">
            
            <div class="attachfiel">
               <label>Attach File :</label>
			 <?php
			 	for($i=0;$i<count($attachment);$i++)
				{
					//echo $this->config->item('upload_file').$attachment[$i];
					//echo $attachment[$i];
					if(file_exists($this->config->item('upload_file').$attachment[$i])){
			 ?>
			 	<a href="javascript:void(0)" title="" onclick="download_file('<?=$attachment[$i]?>');"> <?=$attachment[$i]?></a><br />
			<?php
					}	
				}
			 ?>
			
		
			</div>
            
          </div>
          <?php } ?>
          
          <input type="hidden" name="video_id" id="video_id" value="<?=!empty($datalist[0]['video_id'])?$datalist[0]['video_id']:''?>"  />
          
          <?php if(!empty($datalist[0]['video_id'])) { ?>
          <div class="col-sm-4">
            <div class="attachfiel">
              <!-- <label>Attach File :</label>-->
               <div class="videopic">
                     <img src="<?=!empty($datalist[0]['thumb_url'])?$datalist[0]['thumb_url']:base_url('images/no_image.jpg');?>" height="150" width="150" />
               </div>
               <div align="center">
               	<?=!empty($datalist[0]['video_title'])?$datalist[0]['video_title']:''?>
               </div>
              
			</div>
          </div>
          <?php } ?>
            </div>
            <div class="row">
          <div class="col-sm-8">
          <div class="form-group">
                  <label for="select-multi-input">
                 	Email Message : 
                  </label>
                  
                  <textarea name="email_message" id="email_message" class="form-control parsley-validated" >
                  <?=!empty($datalist[0]['email_message'])?($datalist[0]['email_message']):'';?><br />
                  </textarea>
                  <script type="text/javascript">
						CKEDITOR.replace('email_message',
						 {
							fullPage : false,
							
							//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
							
							baseHref : '<?=$this->config->item('ck_editor_path')?>',
							filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
							filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
						}, {width: 200});												
				</script>
              
                </div>
               </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
 			<!--<a class="btn btn-primary" href="<?php echo $this->config->item('admin_base_url').$viewname; ?>" title="Close">Close</a>-->
            <input type="hidden" name="interaction_plan_id" id="interaction_plan_id" value="<?php echo $this->uri->segment(5);?>"/>
            <input type="hidden" name="interaction_id" id="interaction_id" value="<?=!empty($datalist[0]['interaction_id'])?($datalist[0]['interaction_id']):''?>"/>
            <input type="hidden" name="id" id="id" value="<?=!empty($datalist[0]['id'])?($datalist[0]['id']):''?>"/>
            <input type="submit" class="btn btn-secondary" id="send_now" value="Send Now" title="Send Now" name="submitbtn" />
			<a class="btn btn-primary" onclick="history.go(-1)" href="<?php echo $this->config->item('admin_base_url').$viewname."/interaction_plan_queued_list/".$this->uri->segment(5); ?>" title="Close">Close</a>
            
         </div>
         
          </form>
         
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
  
 </div>
</div>
 <?php
// print_r($subcategory);
 ?>
 <script>
 function download_file(str)
 {
 	var url='<?=base_url('admin/'.$viewname.'/download_form')?>/'+str;
	window.location= url;
 	//$.fileDownload('<?=$this->config->item('attachment_file')?>'+str);
 	//window.open('<?=$this->config->item('attachment_file')?>'+str,"_blank");
 }
 </script>

