<?php
/*
        @Description: Superadmin Map Joomla
        @Author: Ami Bhatti
        @Date: 08-10-14
    */
?>
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

//pr($editRecord1);exit;

?>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('map_joomla_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('map_joomla_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('map_joomla_add_head'); } 
	   else{ echo $this->lang->line('map_joomla_edit_head'); }?>
            </h3>
            <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span> </div>
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
                <div class="row tab-pane fade in active" id="home">
                  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate onkeypress="return event.keyCode != 13;">
                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                    <div class="col-sm-12 col-lg-8">
                      <div class="row">
                        <div class="col-sm-12 form-group">
                          <label for="text-input">Select Admin <span class="val">*</span></label>						  
                          <select class="form-control parsley-validated" id='lw_admin_id' name="lw_admin_id" data-required="true">
                            <option value="" > -- Select Admin -- </option>
                            <?php foreach($admin_name as $row) { ?>
                            <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['lw_admin_id'])) { if($editRecord[0]['lw_admin_id'] == $row['id']){echo "selected";} } ?>>
                            <?=$row['admin_name']?> (<?=$row['email_id']?>)
                            </option>
                            <?php }?>
                          </select>
						  
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12 form-group">
                          <label for="text-input"> Domain Name <span class="val">*</span></label>
                          <input id="domain" name="domain" class="form-control parsley-validated" onblur="check_domain(this.value);"   data-type="map_url" data-required="true" value="<?php if(!empty($editRecord[0]['domain'])){ echo $editRecord[0]['domain'];}?>" data-parsley-type="map_url"  placeholder="e.g. http://xyz.com">
                          <input name="old_domain" type="hidden" value="<?php if(!empty($editRecord[0]['domain'])){ echo $editRecord[0]['domain'];}?>">
                          <input name="old_admin_id" type="hidden" value="<?php if(!empty($editRecord[0]['lw_admin_id'])){ echo $editRecord[0]['lw_admin_id'];}?>">
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
					
                    <div class="col-sm-12 pull-left text-center margin-top-10">
                      <input type="hidden" id="contacttab" name="contacttab" value="1" />
                      <input type="submit" class="btn btn-secondary-green" value="Save" title="Save" id="submit" name="submitbtn" />
                      <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
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
function check_domain(domain)
{
	//alert(domain);
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_domain';?>",
				dataType: 'json',
				async: false,
				data: {'domain':domain,'id':<?=!empty($editRecord)?$editRecord[0]['id']:'0'?>},
				beforeSend: function() {
					$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'})
				},
				success: function(data){
					if(data == '1')
					{
						$('#domain').focus();
						$('#submit').attr('disabled','disabled');
						$.confirm({'title': 'Alert','message': " <strong> This domain already exist! Please select other domain "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function()
						{
							/*$('#domain').focus();*/
							$('#domain').focus();
							$('#submit').removeAttr('disabled');
							$.unblockUI();
						}}}});
						
					}
					else
						$.unblockUI();
				}
			});
			return false;
}
</script>
<script type="text/javascript">

$('#submit').on('click',function()
{
var domain = $('#domain').val();
check_domain(domain);

});

</script>


