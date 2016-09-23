<?php
/*
    @Description: Template add/edit page
    @Author: Mohit Trivedi
    @Date: 12-08-2014

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

<style>
.ui-multiselect{width:100% !important;}
input:focus{cursor:auto;}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('customfield_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('customfield_add_head'); } 
	   else{ echo $this->lang->line('customfield_edit_head'); }?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('common_label_name');?><span class="val">*</span></label>
              <input id="txt_template_name" name="txt_template_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['name'])){ echo ucfirst(strtolower($editRecord[0]['name'])); }
			   ?>" data-required="true">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('mudule_name');?></label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='slt_module[]' id='slt_module' multiple="multiple">
             
                <option value="1" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '1'){ echo "selected=selected"; } ?>>Email Campaign</option>
              <option value="2" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '2'){ echo "selected=selected"; } ?>>Sms Campaign</option>
              <option value="3" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '3'){ echo "selected=selected"; } ?>>Email Library </option>
              <option value="4" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '4'){ echo "selected=selected"; } ?>>Envelope Library </option>
			  <option value="5" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '5'){ echo "selected=selected"; } ?>>Sms</option>
              <option value="6" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '6'){ echo "selected=selected"; } ?>>Label Library</option>
              <option value="7" <?php if(!empty($editRecord[0]['module_id']) && $editRecord[0]['module_id'] == '7'){ echo "selected=selected"; } ?>>Letter Library</option>

</select>
              </div>
     
              
            </div>
            
            
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
               
			   
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>
               </div>
               
                
          </div>	
               
               
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="hidden" name="last_id" value="" id="last_id" />
<input type="submit" class="btn btn-secondary" title="Save Custom Field" value="Save Custom Field" onclick="return setdefaultdata();" name="submitbtn" />
 <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a>
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

<script type="text/javascript">
	$("select#slt_module").multiselect({
		 multiple: true,
		 header: "Custom Field",
		 noneSelectedText: "Custom Field",
		 selectedList: 1
	}).multiselectfilter();
	</script>	

