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
       <h3> <i class="fa fa-tasks"></i> <?php  echo "Sent Email Details";
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         <div class="tab-pane fade in active" id="home">
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
           <div class="row">
           <div id="temp_dev" class="col-sm-12 text-center error"></div>
           <div class="col-sm-8">
               <div class="row">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('email_to');?> : </label>
                  <?=!empty($datalist[0]['first_name'])?ucfirst(strtolower($datalist[0]['first_name'])):''?> <?=!empty($datalist[0]['last_name'])?ucfirst(strtolower($datalist[0]['last_name'])):''?>
                 <?php if(!empty($datalist[0]['email_address'])) 
                        echo '('.$datalist[0]['email_address'].')';
                       elseif(!empty($datalist[0]['default_email_address']))
                        echo '('.$datalist[0]['default_email_address'].')';
                 ?>
                 </div>
                </div> 
               <?php if($viewname == 'emails') { ?>
               <div class="row">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('email_cc');?> : </label>
                  <?=!empty($email_cc)?$email_cc:''?>
                   
                 </div>
                </div> 
               <div class="row">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('email_bcc');?> : </label>
                  <?=!empty($email_bcc)?$email_bcc:''?>
                 </div>
               </div>
               <?php } ?>
               <?php if($viewname == 'bomb_emails') { ?>
               <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Status : </label> <span class="email_status"> <?php if(!empty($datalist[0]['info'])) { ?> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> <?php } else echo "Not Sent"; ?> </span>
                 </div>
               </div> 
               <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Total Opens : </label> <span class="total_opens"> <?php if(!empty($datalist[0]['info'])) { ?> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> <?php } else echo "NA"; ?> </span>
                 </div>
               </div>
               <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Total Plays : </label> <span class="total_plays"> <?php if(!empty($datalist[0]['info'])) { ?> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> <?php } else echo "NA"; ?> </span>
                 </div>
               </div>
               <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Total Clicks : </label> <span class="total_clicks"> <?php if(!empty($datalist[0]['info'])) { ?> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> <?php } else echo "NA"; ?> </span>
                 </div>
               </div>
               <?php } ?>
               <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"><?=$this->lang->line('common_label_category');?> :</label>
                  </div>
                  <div class="col-sm-6">
                    <?=!empty($datalist[0]['category'])?ucfirst(strtolower($datalist[0]['category'])):''?>
                  </div>
                    
                  <div class="col-sm-6">
                   <?=!empty($datalist[0]['subcategory'])?ucfirst(strtolower($datalist[0]['subcategory'])):''?>
                  <span id="category_loader"></span>
                  </div>
                </div>
               <div class="row">
                 <div class="col-sm-12 form-group">
                  <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
                  <?=!empty($datalist[0]['template_name'])?ucfirst(strtolower($datalist[0]['template_name'])):''?>
                 </div>
                </div>
               <div class='row mycalclass'>
                 <div class="col-sm-12 form-group">
                     <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?> : </label>
                    <?=!empty($datalist[0]['template_subject'])?$datalist[0]['template_subject']:''?>
                 </div>
               </div>
            </div>
		   <?php if(!empty($datalist[0]['attachment_name'])) {
		  	$attachment = explode(", ",$datalist[0]['attachment_name']);
		  	?>
           <div class="col-sm-4">
            <div class="attachfiel">
               <label>Attach File :</label>
			 <?php
			 	for($i=0;$i<count($attachment);$i++)
				{
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
	  				<?=!empty($datalist[0]['email_message'])?$datalist[0]['email_message']:'';?><br />
					<?=!empty($datalist[0]['full_signature'])?$datalist[0]['full_signature']:'';?>
              
                </div>
               </div>
            <div class="col-sm-12 pull-left text-center margin-top-10">
 			<!--<a class="btn btn-primary" href="<?php echo $this->config->item('admin_base_url').$viewname; ?>" title="Close">Close</a>-->
			<?php
			if($this->uri->segment(6) != '')
			{
			?>
				<a class="btn btn-primary" href="<?php echo $this->config->item('admin_base_url').$viewname."/all_sent_mail/".$pagingid; ?>" title="Back">Close</a>
			<?php } else { ?>
			<a class="btn btn-primary" onclick="history.go(-1)" href="<?php echo $this->config->item('admin_base_url').$viewname."/sent_email/".$this->uri->segment(4)."/".$pagingid;?>" title="Back">Close</a>
			<?php } ?>
         </div>
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
$("#temp_dev").css('display','none');
function download_file(str)
{
	var url='<?=base_url('admin/'.$viewname.'/download_form')?>/'+str;
	window.location= url;
	//$.fileDownload('<?=$this->config->item('attachment_file')?>'+str);
	//window.open('<?=$this->config->item('attachment_file')?>'+str,"_blank");
}
<?php if(!empty($datalist[0]['info'])) { ?>
$.ajax({
	type: "POST",
	dataType:'json',
	url: "<?=$this->config->item('admin_base_url')?><?=$viewname?>/emailTracking",
	data: {
	track_id:'<?=$datalist[0]['info']?>'
},
beforeSend: function() {
	
	},
	success: function(result){
		if(result.status == 'failure')
		{
			$("#temp_dev").css('display','block');
			$("#temp_dev").html('<?=$this->lang->line('common_bombbomb_credential_msg')?>');
			$("#temp_dev").fadeOut(4000);	
		}
		$(".email_status").html(result.email_status);
		$(".total_opens").html(result.total_opens);
		$(".total_plays").html(result.total_plays);
		$(".total_clicks").html(result.total_clicks);
	}
});
<?php } ?>
</script>

