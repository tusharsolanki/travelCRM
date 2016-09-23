<?php 
$viewname = $this->router->uri->segments[2];
$path_update_view = $viewname.'/update_view';

?>
<style>
.height_fix{ height:350px;}
.ui-multiselect{width:50% !important; margin-left: 14px !important;}
.ui-multiselect-menu{width:22% !important; }
.smart-drip-plan-con-box{  height: 275px !important;}

</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
	<div class="row add_communication_plan_div">

			 <ul id="myTab10" class="nav nav-tabs">
				 <li  class="active"> <a href="#assigned_plans" data-toggle="tab" title="Assigned Plans">Assigned Plans </a> </li>

				<li> <a href="#add_plans" data-toggle="tab" title="Add Plans">
				   + Add Plans  <i class="icon-remove-sign"></i></a> </li>
			</ul>
			
			 <?php 
				 $plan_list_array = array();
				 if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
						foreach($communication_trans_data as $rowtrans){
							$plan_list_array[] = $rowtrans['interaction_plan_id'];
					}}		
			?>
				<div id="myTab10Content" class="tab-content">
					
				   <div class="smart-drip-plan-con-box row tab-pane fade in active"  id="assigned_plans">
                   	<div class="col-sm-8"> <strong>Assigned communication plan By Admin</strong>
                        <ul>
                          <?php
				foreach($admin_interection_plan as $row)
				{
					?>
                          <li>
                            <?=$row['plan_name']?>
                          </li>
                          <?	
				}
				?>
                        </ul>
                        <br />
                        <label for="text-input">
                        <strong>
                          <?="Assigned ".$this->lang->line('contact_add_interaction_plan')." By Agent";?>
                          </strong>
                        </label>
                      </div>
                       
					 <?php  if(!empty($communication_plans)){
					foreach($communication_plans as $row){
					 if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ ?>
					<div class="smart-drip-plan-con">
					<div class="col-sm-9"><?=$row['plan_name'];?></div>
					   </div>
					<?php }}}
                                        if(empty($plan_list_array)) { ?>
                                            <div class="smart-drip-plan-con">
                                                <div class="col-sm-9"><?php echo 'Plan not assigned..!!';?></div>
                                            </div>

                                        <?php }
                                        ?>
					
					</div>
					
				   <div class="row tab-pane fade in" id="add_plans">
					 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_update_view;?>" novalidate >
					 
					<select class="form-control parsley-validated" name="slt_communication_plan[]" id="slt_communication_plan" multiple="multiple">
		   <!--<option value="">Please Select</option>-->
		   <?php if(!empty($communication_plans)){
					foreach($communication_plans as $row){?>
						<option <?php if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ echo "selected";} ?> value="<?=$row['id']?>"><?=$row['plan_name']?></option>
					<?php } ?>
		   <?php } ?>
	  </select>
			   <input type="hidden" id="viewtab" name="viewtab" value="list_plan" />
			   <input type="hidden" id="id" name="id" value="<?=$contact_id?>" />
<input type="submit" title="Save Contact" class="btn btn-secondary" value="Assign Communication Plan" onclick="return setdefaultdata();" name="submitbtn" />
					</form>
					</div>
			
				</div>
	</div>
<script>
$("select#slt_communication_plan").multiselect({
		header: "Select Communication",
		noneSelectedText: "Select Communication",
		selectedList: 1
	}).multiselectfilter();	
</script>							 