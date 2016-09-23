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
.ui-multiselect {
	width:100% !important;
}
input:focus {
	cursor:auto;
}
<?php if($this->uri->segment(4) == 'iframe') {
?> #sidebar {
display:none;
}
#header, #site-logo, .dropdown, #footer, #back {
display:none !important;
}
#content {
margin-left:0;
}
<?php
}
?>
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('module_master_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('module_master_add_head');}
	   				else if(!empty($insert_data)){ echo $this->lang->line('module_master_add_head'); } 
	  				 else{ echo $this->lang->line('module_master_edit_head'); }?>
            </h3>
            <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
              <div class="row tab-pane fade in active" id="home">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
                <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="text-input">
                        <?=$this->lang->line('module_label_name');?>
                        <span class="val">*</span></label>
                      <input id="module_name" name="module_name" placeholder="e.g. Module Name" class="form-control parsley-validated" type="text" value="<?php if(isset($insert_data)){
						   if(!empty($editRecord[0]['module_name'])){ echo $editRecord[0]['module_name'].'-copy'; }}
						   else
						   {
							   if(!empty($editRecord[0]['module_name'])){ echo $editRecord[0]['module_name']; }
						   }
						   ?>" data-required="true">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="text-input">
                        <?=$this->lang->line('module_unique_label_name');?>
                        <span class="val">*</span></label>
                      <input id="module_unique_name" <? if(!empty($editRecord)){ echo 'readonly="readonly" disabled="disabled"';}?> name="module_unique_name" placeholder="e.g. Module Name" class="form-control parsley-validated" type="text" value="<?php if(isset($insert_data)){
						   if(!empty($editRecord[0]['module_unique_name'])){ echo $editRecord[0]['module_unique_name'].'-copy'; }}
						   else
						   {
							   if(!empty($editRecord[0]['module_unique_name'])){ echo $editRecord[0]['module_unique_name']; }
						   }
						   ?>" data-required="true">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="text-input">
                        <?=$this->lang->line('module_parent_label');?>
                       </label>
                      <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='module_parent' id='module_parent'>
                        <!--<option value=''>Select Employee</option>-->
                        <?php if(isset($modulelist) && count($modulelist) > 0){
				
							foreach($modulelist as $row){
								if(!empty($row['id'])){?>
                        <option value='<?php echo $row['id'];?>' <?php if(!empty($editRecord[0]['module_parent']) && ($editRecord[0]['module_parent'] == $row['id'])){ echo "selected";}?> >
                        <?=$row['module_name']?>
                        </option>
                        <?php 		}
							}
						} ?>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="text-input">
                        <?=$this->lang->line('module_right_label');?>
                        </label>
                        <?
                        if(!empty($editRecord[0]['module_right']))
						{ 
							$module_rights=explode(',',$editRecord[0]['module_right']);
						}
						?>
                          <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='module_right[]' id='module_right'>
                             <option <? if(!empty($module_rights) && in_array('view',$module_rights)){echo 'selected="selected"';} ?>  value="view">View</option>
                             <option <? if(!empty($module_rights) && in_array('add',$module_rights)){echo 'selected="selected"';} ?> value="add">Add</option>
                             <option <? if(!empty($module_rights) && in_array('edit',$module_rights)){echo 'selected="selected"';} ?> value="edit">Edit</option>
                             <option <? if(!empty($module_rights) && in_array('delete',$module_rights)){echo 'selected="selected"';} ?> value="delete">Delete</option>
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
                </div>
                <div class="col-sm-12 pull-left text-center margin-top-10">
                  <input type="submit" class="btn btn-secondary-green" title="Save" value="Save" onclick="return setdefaultdata();" name="submitbtn" />
                  <?php if($this->uri->segment(4) == 'iframe'){ ?>
                  <a class="btn btn-primary" title="Cancel" onclick="close_popup()">Cancel</a>
                  <?php } else { ?>
                  <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a>
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
<script type="text/javascript">
	//load multi select 
	$("select#module_right").multiselect({
		}).multiselectfilter();
	$("select#module_parent").multiselect({
		 multiple: false,
		 header: "Parent Module",
		 noneSelectedText: "Parent Module",
		 selectedList: 1
	}).multiselectfilter();
</script> 
