<?php
/*
    @Description: view send SMS
    @Author: Sanjay Chabhadiya
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
       <h3> <i class="fa fa-tasks"></i> <?php  echo "Sent SMS Details";
	   ?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
           <div class="row">
           <div class="col-sm-8">
		   <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('email_to');?> : </label>
             <label for="text-input"><?=!empty($datalist[0]['first_name'])?ucwords($datalist[0]['first_name']):''?> <?=!empty($datalist[0]['last_name'])?ucwords($datalist[0]['last_name']):''?><?php if(!empty($datalist[0]['phone_no'])) echo '('.$datalist[0]['phone_no'].')';?></label>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?> :</label>
			  </div>
              <div class="col-sm-6">
             	<label for="text-input"><?=!empty($datalist[0]['category'])?ucwords($datalist[0]['category']):''?></label>
              </div>
              <div class="col-sm-6">
             <label for="text-input"><?=!empty($datalist[0]['subcategory'])?ucwords($datalist[0]['subcategory']):''?></label>
              <span id="category_loader"></span>
              </div>
              
            </div>
			 <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			  <label for="text-input"><?=!empty($datalist[0]['template_name'])?ucwords($datalist[0]['template_name']):''?></label>
             </div>
            </div>
          </div>
        </div>
         <div class="row">
          <div class="col-sm-8">
          <div class="form-group">
                  <label for="select-multi-input">
                 	SMS Message : 
                  </label>
	  				<?=!empty($datalist[0]['sms_message'])?ucwords($datalist[0]['sms_message']):'';?>
                </div>
               </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
 			<!--<a class="btn btn-primary" href="<?php echo $this->config->item('admin_base_url').$viewname; ?>" title="Close" >Close</a>-->
			<?php
			if($this->uri->segment(6) != '')
			{
			?>
				<a class="btn btn-primary" onclick="history.go(-1)" href="<?php echo $this->config->item('admin_base_url').$viewname."/all_sent_sms/".$pagingid; ?>" title="Back">Close</a>
			<?php } else { ?>
			<a class="btn btn-primary" onclick="history.go(-1)" href="<?php echo $this->config->item('admin_base_url').$viewname."/sent_sms/".$this->uri->segment(4)."/".$pagingid;?>" title="Back">Close</a>
			<?php } ?>
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

