


<?php
/*
    @Description: Template add/edit page
    @Author: Mohit Trivedi
    @Date: 06-08-2014

*/?>

<?php 
$viewname = $this->router->uri->segments[1];
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

<style>
.ui-multiselect{width:100% !important;}
</style>
<div id="content" class="content_nomar">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet portlet1">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> Unsubscribe from our mailing list </h3>
       
	  </div>
    
      <div class="portlet-content">
      	<?=$this->load->view('unsubscribe/unsubscribe_link/add');?>
     </div>
    </div>
   </div>
  </div>
  
 </div>
 <?php
// print_r($subcategory);
 ?>