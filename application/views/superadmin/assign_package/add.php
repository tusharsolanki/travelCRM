<?php
/*
    @Description: Admin add/edit page
    @Author: Mohit Trivedi
    @Date: 01-09-2014

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
.ui-multiselect{width:100%!important;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('admin_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(!empty($editRecord)){ echo "Assign Package"; } else { echo "Assign Package"; }?>  </h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">Admin Name
              <span class="val">*</span></label>
				 <select name="admin_name[]" class="selectBox" multiple="multiple" id='admin_name' data-bvalidator="required">
				  <?php foreach($admin_data as $row) { ?>
				  <option value="<?=$row['id']?>" > <?=$row['admin_name']?>  (<?=$row['email_id']?>)</option>
				  <?php }?>
				  </select>
             </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">Package Name
              <span class="val">*</span></label>
			  	<select name="package_name" class="selectBox" id='package_name'>
                  <option value="-1">Select Package</option>
				  <?php foreach($package_data as $row) { ?>
				  <option value="<?=$row['id']?>" > <?=$row['package_name']?> </option>
				  <?php }?>
				</select>
             </div>
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
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" class="btn btn-secondary" value="Save" title="Save"onclick="return showloading();" name="submitbtn" />
 <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
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
function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}

$("select#admin_name").multiselect({
	selectedList: 4 ,
	header: "Select Admin",
	noneSelectedText: "Select Admin",
	//selectedText: function(numChecked, numTotal, checkedItems){
	  //return numChecked + ' of ' + numTotal + ' checked';
   //}
}).multiselectfilter();
$("select#package_name").multiselect({
		multiple: false,
		header: "Category",
		noneSelectedText: "Category",
		selectedList: 1
}).multiselectfilter();

function showloading()
{
	var admin_name = $("#admin_name").multiselect("widget").find(":checkbox").filter(':checked').length;
	if(admin_name == 0)
	{
		//alert("Please Enter Templ Detail");
		$.confirm({'title': 'Alert','message': " <strong> Please select at least one admin."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;
	}
	else if($('#package_name').val() == '-1')
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select package."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;
	}
		$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
}
</script>