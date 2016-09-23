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

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('admin_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(!empty($editRecord)){ echo "Edit "; } else { echo "Add "; }?> Package  </h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-12">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">Package Name
              <span class="val">*</span></label>
              <input id="package_name" name="package_name" placeholder="e.g. Package Name" class="form-control parsley-validated" type="text" value="<?=$editRecord[0]['package_name']?htmlentities($editRecord[0]['package_name']):'';?>" data-required="true">
             </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">Email Counter
              <span class="val">*</span></label>
			  <input id="email_counter" name="email_counter" placeholder="Number" class="form-control parsley-validated" type="text" value="<?=$editRecord[0]['email_counter']?$editRecord[0]['email_counter']:'';?>" data-required="true" onkeypress="return isNumberKey(event)" maxlength="9" />
			   
             </div>
            </div>

            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">SMS Counter
              <span class="val">*</span></label>
             <input id="sms_counter" name="sms_counter" placeholder="Number" class="form-control parsley-validated" type="text" value="<?=$editRecord[0]['sms_counter']?$editRecord[0]['sms_counter']:'';?>" data-required="true" onkeypress="return isNumberKey(event)" maxlength="9" />
			  </div>
            </div>

            <!--<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">Contacts Counter
              <span class="val">*</span></label>
               <input type="contacts_counter" name="contacts_counter" id="text" placeholder="Number" class="form-control parsley-validated" data-required="true" value="<?=$editRecord[0]['contacts_counter']?$editRecord[0]['contacts_counter']:'';?>" onkeypress="return isNumberKey(event)" />
			  </div>
            </div>-->

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
<input type="submit" class="btn btn-secondary-green" value="Save" title="Save"onclick="return setdefaultdata();" name="submitbtn" />
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
function setdefaultdata()
	{
		 if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
	}

</script>