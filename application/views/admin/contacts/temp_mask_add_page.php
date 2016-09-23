<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 

$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>
<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('contact_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	<?php /*?><h3 class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </h3><?php */?>
     <div class="portlet">
      <div class="portlet-header">
      
      
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('contact_add_table_head');}else{ echo $this->lang->line('contact_edit_table_head'); }?> 
       </h3>
<span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>       
       
	    
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="col-sm-12">
        <ul class="nav nav-tabs" id="myTab1">
         <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="Contact Information" data-toggle="tab" href="#home"><?=$this->lang->line('contact_add_table_tab1_head');?></a> </li>
		 <?php if(!empty($editRecord[0]['id'])){ ?>
         <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Contact Photo and Documents" data-toggle="tab" href="#profile"><?=$this->lang->line('contact_add_table_tab2_head');?></a> </li>
         <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Extra Information" data-toggle="tab" href="#profilenew"><?=$this->lang->line('contact_add_table_tab3_head');?></a> </li>
		 <?php } ?>
         <!--<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" id="myTabDrop1" href="javascript:;">Dropdown <b class="caret"></b>
							</a>

							<ul aria-labelledby="myTabDrop1" role="menu" class="dropdown-menu">
								<li><a data-toggle="tab" tabindex="-1" href="#dropdown1">@fat</a></li>
								<li><a data-toggle="tab" tabindex="-1" href="#dropdown2">@mdo</a></li>
							</ul>
						</li>-->
         
        </ul>
        <div class="tab-content" id="myTab1Content"> 
         <div <?php if($tabid == '' || $tabid == 1){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" >
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-7">
            <div class="row">
             <div class="col-sm-4">
              <label for="text-input"><?=$this->lang->line('contact_add_prefix');?></label>
			  <select class="form-control parsley-validated" name="slt_prefix" id="slt_prefix">
               <option value="">Please Select</option>
               <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Mr.'){ echo "selected"; }?> value="Mr.">Mr.</option>
			   <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Ms.'){ echo "selected"; }?> value="Ms.">Ms.</option>
			   <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Mrs.'){ echo "selected"; }?> value="Mrs.">Mrs.</option>
              </select>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-4 form-group">
              <label for="text-input"><?=$this->lang->line('contact_add_fname');?><span class="mandatory_field margin-left-5px">*</span></label>
              <input id="txt_first_name" name="txt_first_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name']; }?>" data-required="true">
             </div>
			 <div class="col-sm-4">
              <label for="text-input"><?=$this->lang->line('contact_add_mname');?></label>
              <input id="txt_middle_name" name="txt_middle_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['middle_name'])){ echo $editRecord[0]['middle_name']; }?>">
             </div>
             <div class="col-sm-4">
              <label for="text-input"><?=$this->lang->line('contact_add_lname');?></label>
              <input id="txt_last_name" name="txt_last_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['last_name'])){ echo $editRecord[0]['last_name']; }?>">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_company');?></label>
              <input id="txt_company_name" name="txt_company_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_name'])){ echo $editRecord[0]['company_name']; }?>">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_title1');?></label>
              <input id="txt_company_post" name="txt_company_post" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_post'])){ echo $editRecord[0]['company_post']; }?>">
             </div>
            </div>
            <div class="row form-group">
             <div class="col-sm-12 checkbox">
              <label class="">
              Is Contact Lead
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
            </div>
            <div class="row add_emailtype add_email_address_div">
			
			<div class="col-sm-4">
			  <label for="validateSelect"><?=$this->lang->line('common_label_email_type');?><span class="mandatory_field margin-left-5px">*</span></label>
			 </div>
			 <div class="col-sm-4">
			  <label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?><span class="mandatory_field margin-left-5px">*</span></label>
			 </div>
			 <div class="col-sm-2 text-center icheck-input-new">
			  <div class="">
			   <label><?=$this->lang->line('common_default');?><span class="mandatory_field margin-left-5px">*</span></label>
			  </div>
			 </div>
			 <div class="col-sm-1 text-center icheck-input-new">
			  <div class="">
			   <label>&nbsp;</label>
			  </div>
			 </div>
			
			<?php if(!empty($email_trans_data) && count($email_trans_data) > 0){
			 		foreach($email_trans_data as $rowtrans){ ?>
			 	
				<div class="delete_email_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
					<div class="col-sm-4 form-group">
					  <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
					  <input type="hidden" name="email_type_trans_id[]" id="email_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
					  <select class="form-control parsley-validated contact_module" name="slt_email_typee[]" id="slt_email_typee" data-required="true">
					   <option value="">Please Select</option>
					   <?php if(!empty($email_type)){
								foreach($email_type as $row){?>
									<option <?php if(!empty($rowtrans['email_type']) && $rowtrans['email_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
								<?php } ?>
					   <?php } ?>
					  </select>
					 </div>
					 <div class="col-sm-4 form-group">
					  <!--<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>-->
					  <input id="txt_email_addresse" name="txt_email_addresse[]" class="form-control parsley-validated" type="email" value="<?php if(!empty($rowtrans['email_address'])){ echo $rowtrans['email_address']; }?>" data-parsley-type="email">
					 </div>
					 <div class="col-sm-2 text-center icheck-input-new">
					  <div class="form-group">
					   <!--<label><?=$this->lang->line('common_default');?></label>-->
					   <div class="radio">
						<label class="">
						<div class="margin-left-48">
						 <input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> data-required="true">
						</div>
						</label>
					   </div>
					  </div>
					 </div>
					 <div class="col-sm-1 text-center icheck-input-new">
					  <div class="">
					   <!--<label>&nbsp;</label>-->
					    <?php if($rowtrans['is_default'] != '1') { ?>
					   <a class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" title="Delete Email" onclick="return ajaxdeletetransdata('delete_email_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
					   <?php } ?>
					  </div>
					 </div>
				</div>
				<?php } ?>	
			 <?php }else{ ?>
			
				 <div class="col-sm-4 form-group">
				  <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
				  <select class="form-control parsley-validated contact_module" name="slt_email_type[]" id="slt_email_type" data-required="true">
				   <option value="">Please Select</option>
				   <?php if(!empty($email_type)){
							foreach($email_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
				 </div>
				 <div class="col-sm-4 form-group">
				  <!--<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>-->
				  <input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-required="true" data-parsley-type="email">
				 </div>
				 <div class="col-sm-2 text-center icheck-input-new">
				  <div class="form-group">
				   <!--<label><?=$this->lang->line('common_default');?></label>-->
				   <div class="radio">
					<label class="">
					<div class="margin-left-48">
					 <input type="radio" class=""  name="rad_email_default" checked="checked"  data-required="true">
					</div>
					</label>
				   </div>
				  </div>
				 </div>
				 <div class="col-sm-1 text-center icheck-input-new">
				  <div class="">
				   <!--<label>&nbsp;</label>-->
				   <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
				  </div>
				 </div>
			 
			 <?php } ?>
			
            </div>
            
			<div class="row">
             <div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);" title="Add Email" class="text_color_red text_size add_email_address"><i class="fa fa-plus-square"></i> Add Email Address</a> </div>
            </div>
			
			<!--Email Complete-->
			
            <div class="row add_emailtype add_phone_number_div">
			
			 <div class="col-sm-4">
              <label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?><span class="mandatory_field margin-left-5px">*</span></label>
             </div>
             <div class="col-sm-4">
              <label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?><span class="mandatory_field margin-left-5px">*</span></label>
             </div>
             <div class="col-sm-2 text-center icheck-input-new">
              <div class="">
               <label><?=$this->lang->line('common_default');?><span class="mandatory_field margin-left-5px">*</span></label>
              </div>
             </div>
             <div class="col-sm-1 text-center icheck-input-new">
              <div class="">
               <label>&nbsp;</label>
               <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
              </div>
             </div>
			 
			 <?php if(!empty($phone_trans_data) && count($phone_trans_data) > 0){
			 		foreach($phone_trans_data as $rowtrans){ ?>	
					
					<div class="delete_phone_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
						
						<div class="col-sm-4 form-group">
						  <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
						  <input type="hidden" name="phone_type_trans_id[]" id="phone_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						  <select class="form-control parsley-validated" name="slt_phone_typee[]" id="slt_phone_type" data-required="true">
						   <option value="">Please Select</option>
						   <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
										<option <?php if(!empty($rowtrans['phone_type']) && $rowtrans['phone_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
									<?php } ?>
						   <?php } ?>
						  </select>
						 </div>
						 <div class="col-sm-4 form-group">
						  <!--<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>-->
						  <input id="txt_phone_no" name="txt_phone_noe[]" onkeypress="return isNumberKey(event);" maxlength="12" data-type="number_mask" data-maxlength="12" class="form-control parsley-validated" type="text" value="<?php if(!empty($rowtrans['phone_no'])){ echo $rowtrans['phone_no']; }?>" onKeyUp="javascript:return mask(this.value,this,'3,7','-');" onBlur="javascript:return mask(this.value,this,'3,7','-');">
						 </div>
						 <div class="col-sm-2 text-center icheck-input-new">
						  <div class="">
						   <!--<label><?=$this->lang->line('common_default');?></label>-->
						   <div class="radio">
							<label class="">
							<div class="margin-left-48">
							 <input type="radio" class=""   name="rad_phone_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> data-required="true" >
							</div>
							</label>
						   </div>
						  </div>
						 </div>
						 <div class="col-sm-1 text-center icheck-input-new">
						  <div class="">
						   <!--<label>&nbsp;</label>-->
						   <?php if($rowtrans['is_default'] != '1') { ?>
						   <a class="btn btn-xs btn-primary mar_top_con_my"  title="Delete Phone" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_phone_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
						   <?php } ?>
						  </div>
						 </div>
						
					</div>
					
				<?php } ?>
			<?php }else{ ?>
			
             <div class="col-sm-4 form-group">
              <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
              <select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type" data-required="true">
               <option value="">Please Select</option>
               <?php if(!empty($phone_type)){
			   			foreach($phone_type as $row){?>
               				<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
						<?php } ?>
			   <?php } ?>
              </select>
             </div>
             <div class="col-sm-4 form-group">
              <!--<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>-->
              <input id="txt_phone_no" onkeypress="return isNumberKey(event);" maxlength="12"  data-type="number_mask" name="txt_phone_no[]" class="form-control parsley-validated" type="text" data-required="true"  onKeyUp="javascript:return mask(this.value,this,'3,7','-');" onBlur="javascript:return mask(this.value,this,'3,7','-');">
             </div>
             <div class="col-sm-2 text-center icheck-input-new">
              <div class="form-group">
               <!--<label><?=$this->lang->line('common_default');?></label>-->
               <div class="radio">
                <label class="">
                <div class="margin-left-48">
				
                 <input type="radio" class=""   name="rad_phone_default" checked="checked" data-required="true">
                </div>
                </label>
               </div>
              </div>
             </div>
             <div class="col-sm-1 text-center icheck-input-new">
              <div class="">
               <!--<label>&nbsp;</label>-->
               <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
              </div>
             </div>
			 
			 <?php } ?>
			 
            </div>
			
            <div class="row">
			<div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);"  title="Add Email" class="text_color_red text_size add_phone_number"><i class="fa fa-plus-square"></i> Add Phone No.</a> </div>
            </div>
            <div class="row add_address_div">
			
			<?php if(!empty($address_trans_data) && count($address_trans_data) > 0){
			 		foreach($address_trans_data as $rowtrans){ ?>	
					
					<div class="delete_address_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
						<div class="col-sm-3 columns">
						  <input type="hidden" name="address_type_trans_id[]" id="address_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						  <select class="form-control parsley-validated" name="slt_address_typee[]" id="slt_address_type">
						   <option value="">Please Select</option>
						   <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
										<option <?php if(!empty($rowtrans['address_type']) && $rowtrans['address_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
									<?php } ?>
						   <?php } ?>
						  </select>
						 </div>
						 <div class="col-sm-6 columns">
						  <div class="row">
						   <textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1e[]" class="form-control parsley-validated"><?php if(!empty($rowtrans['address_line1'])){ echo $rowtrans['address_line1']; }?></textarea>
						  </div>
						  <div class="row">
						   <input type="text" placeholder="Address Line 2" name="txtarea_address_line2e[]" id="txtarea_address_line2" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['address_line2'])){ echo $rowtrans['address_line2']; }?>">
						  </div>
						  <div class="row">
						   <div class="col-sm-5 nopadding">
							<input type="text" placeholder="City" id="txt_city" name="txt_citye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['city'])){ echo $rowtrans['city']; }?>">
						   </div>
						   <div class="col-sm-3 nopadding">
							<input type="text" placeholder="State" id="txt_state" name="txt_statee[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['state'])){ echo $rowtrans['state']; }?>">
						   </div>
						   <div class="col-sm-4 nopadding form-group">
							<input type="text" placeholder="Zip Code" id="txt_zip_code" name="txt_zip_codee[]" maxlength="5" data-minlength="5" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['zip_code'])){ echo $rowtrans['zip_code']; }?>">
						   </div>
						  </div>
						  <div class="row">
						   <input type="text" placeholder="Country" id="txt_country" name="txt_countrye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['country'])){ echo $rowtrans['country']; }?>">
						  </div>
						 </div>
						 <div class="col-sm-2">
						  <a class="btn nomargin btn-xs btn-primary mar_top_con_my" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_address_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
						 </div>
						 <div> </div>
					</div>
					
				<?php } ?>
			<?php }else{ ?>
			
             <div class="col-sm-3 columns">
              <select class="form-control parsley-validated" name="slt_address_type[]" id="slt_address_type">
               <option value="">Please Select</option>
               <?php if(!empty($address_type)){
			   			foreach($address_type as $row){?>
               				<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
						<?php } ?>
			   <?php } ?>
              </select>
             </div>
             <div class="col-sm-6 columns">
              <div class="row">
               <textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1[]" class="form-control parsley-validated"></textarea>
              </div>
              <div class="row">
               <input type="text" placeholder="Address Line 2" name="txtarea_address_line2[]" id="txtarea_address_line2" class="form-control parsley-validated">
              </div>
              <div class="row">
               <div class="col-sm-5 nopadding">
                <input type="text" placeholder="City" id="txt_city" name="txt_city[]" class="form-control parsley-validated">
               </div>
               <div class="col-sm-3 nopadding">
                <input type="text" placeholder="State" id="txt_state" name="txt_state[]" class="form-control parsley-validated">
               </div>
               <div class="col-sm-4 nopadding form-group">
			    <input type="text" placeholder="Zip Code" id="txt_zip_code" name="txt_zip_code[]" maxlength="5" data-minlength="5" class="form-control parsley-validated">
               </div>
              </div>
              <div class="row">
               <input type="text" placeholder="Country" id="txt_country" name="txt_country[]" class="form-control parsley-validated">
              </div>
             </div>
             <div class="col-sm-2">
              <!--<button class="btn nomargin btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
             </div>
             <div> </div>
			 
			 <?php } ?>
			 
            </div>
            <div class="row">
             <div class="col-sm-12 topnd_margin"> <a class="text_color_red text_size add_new_address"  title="Add Address" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Address</a> </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
			  <textarea name="txtarea_notes" id="txtarea_notes" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['notes'])){ echo $editRecord[0]['notes']; }?></textarea>
             </div>
            </div>
           </div>
           <div class="col-sm-5">
            <div class="add_website">
             <div>
              <div class="row add_website_div">
			  
			   
			   <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>
               </div>
               <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('contact_add_website');?></label>
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                </div>
               </div>
			   
			   <?php if(!empty($website_trans_data) && count($website_trans_data) > 0){
			 		foreach($website_trans_data as $rowtrans){ ?>
					
						<div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
							<div class="col-sm-5">
							<input type="hidden" class="form-control" id="txt_website_typeid" name="txt_website_typeid[]" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
							<input type="text" class="form-control parsley-validated" id="txt_website_typee" name="txt_website_typee[]" value="<?php if(!empty($rowtrans['website_type'])){ echo $rowtrans['website_type']; }?>">
						   </div>
						   <div class="col-sm-5 form-group">
							<input type="url" class="form-control parsley-validated" id="txt_website_namee" name="txt_website_namee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }?>" data-parsley-type="url">
						   </div>
						   <div class="col-sm-1 text-center icheck-input-new">
							<div class="">
							 <a title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_website_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
							</div>
						   </div>
						</div>
					
				<?php } ?>
			   <?php } else { ?>
			  
               <div class="col-sm-5">
                <!--<label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>-->
                <input type="text" class="form-control parsley-validated" id="txt_website_type" name="txt_website_type[]">
               </div>
               <div class="col-sm-5 form-group">
                <!--<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>-->
                <input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]" data-parsley-type="url">
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                 <!--<label>&nbsp;</label>-->
                 <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
                </div>
               </div>
			   
			   <?php } ?>
			   
              </div>
              <div class="row">
               <div class="col-sm-12 topnd_margin"> <a title="Add Website" class="text_color_red text_size add_new_website" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Website</a> </div>
              </div>
             </div>
             <div>
              <div class="row add_social_profile_div">
			  
			   <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>
               </div>
               <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('contact_add_website');?></label>
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                 <!--<label>&nbsp;</label>-->
                </div>
               </div>
			   
			   <?php if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
			 		foreach($profile_trans_data as $rowtrans){ ?>
					
					  <div class="delete_social_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
						<div class="col-sm-5">
							<input type="hidden" class="form-control" id="slt_profile_typeid" name="slt_profile_typeid[]" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
							<select class="form-control parsley-validated" name="slt_profile_typee[]" id="slt_profile_typee">
							   <option value="">Please Select</option>
							   <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
											<option <?php if(!empty($rowtrans['profile_type']) && $rowtrans['profile_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
										<?php } ?>
							   <?php } ?>
							</select>
						   </div>
						   <div class="col-sm-5 form-group">
							<input type="text" class="form-control parsley-validated" id="txt_social_profilee" name="txt_social_profilee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }?>"  data-parsley-type="url">
						   </div>
						   <div class="col-sm-1 text-center icheck-input-new">
							<div class="">
							 <a title="Delete Social Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_social_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
							</div>
						   </div>
						</div>
					
				<?php } ?>
			   <?php } else { ?>
			  
               <div class="col-sm-5">
                <!--<label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>-->
				<select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">
				   <option value="">Please Select</option>
				   <?php if(!empty($profile_type)){
							foreach($profile_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
				</select>
               </div>
               <div class="col-sm-5 form-group">
                <!--<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>-->
                <input type="url" class="form-control parsley-validated" id="txt_social_profile" name="txt_social_profile[]"  data-parsley-type="url">
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                 <!--<label>&nbsp;</label>
                 <button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
                </div>
               </div>
			   
			   <?php } ?>
			   
              </div>
              <div class="row">
               <div class="col-sm-12 topnd_margin"> <a title="Add Social Profile" class="text_color_red text_size add_new_social_profile" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Social Profile</a> </div>
              </div>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12 topnd_margin">
              <h2 class="text_color_red text_size"><?=$this->lang->line('common_label_contact_type');?></h2>
             </div>
             <div class="col-sm-8">
              <label for="validateSelect"><?=$this->lang->line('contact_add_source');?></label>
              <select class="form-control parsley-validated" name="slt_contact_source" id="slt_contact_source">
				   <option value="">Please Select</option>
				   <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option <?php if(!empty($editRecord[0]['contact_source']) && $editRecord[0]['contact_source'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <div class="form-group">
               <div><label class="nomargin"><?=$this->lang->line('common_label_contact_type');?></label></div>
			   
			   <?php
			   		$selectedcontacttypes = array(); 
			   		if(!empty($contact_trans_data)){
							foreach($contact_trans_data as $row){ 
							
								$selectedcontacttypes[] = $row['contact_type_id'];
							
							} ?>
				<?php } ?>
               
			   <?php if(!empty($contact_type)){
							foreach($contact_type as $row){?>

							   <div class="checkbox nopadding margin-left-20 clear">
								<label class="">
								<div class="">
								 <input <?php if(in_array($row['id'],$selectedcontacttypes)){ echo 'checked="checked"';}?> type="checkbox" class="" name="chk_contact_type_id[]" value="<?=$row['id'];?>">
								</div>
								<?=$row['name'];?>
								</label>
							   </div>

               				<?php } ?>
				<?php } ?>
				
              </div>
             </div>
            </div>
            
			<div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_status');?></label>
              <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status">
				   <option value="">Please Select</option>
				   <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option <?php if(!empty($editRecord[0]['contact_status']) && $editRecord[0]['contact_status'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
             </div>
            </div>
			
			<div class="row add_tag_div">
			
			 <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>
             </div>
			 
			 <?php if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
					
				   <div class="delete_tag_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
					<div class="col-sm-8">
						<input type="hidden" name="tag_type_trans_id[]" id="tag_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tage[]" value="<?php if(!empty($rowtrans['tag'])){ echo $rowtrans['tag']; }?>">
					</div>
					<div class="col-sm-1 text-center icheck-input-new">
					 <div class="">
					  <a title="Delete Tag" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_tag_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
					 </div>
				   	</div>
				   </div>
					
				<?php } ?>
			<?php }else{ ?>
			
             <div class="col-sm-8">
              <!--<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>-->
              <input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]">
             </div>
			 
			 <?php } ?>
			 
            </div>
            <div class="row">
             <div class="col-sm-12 topnd_margin"> <a class="text_color_red text_size add_new_tag" href="javascript:void(0);" title="Add Tags"><i class="fa fa-plus-square"></i> Add Tags</a> </div>
            </div>
            <!--<div class="row add_communication_plan_div">
			
			 <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_interaction_plan');?></label>
             </div>
			 
			 <?php if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
			 		foreach($communication_trans_data as $rowtrans){ ?>
					
						<div class="delete_communication_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
							<div class="col-sm-8">
							 <input type="hidden" name="communication_trans_id[]" id="communication_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
							 <select class="form-control parsley-validated" name="slt_communication_plan_ide[]" id="slt_communication_plan_ide">
								   <option value="">Please Select</option>
								   <?php if(!empty($communication_plans)){
											foreach($communication_plans as $row){?>
												<option <?php if(!empty($rowtrans['interaction_plan_id']) && $rowtrans['interaction_plan_id'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=$row['description']?></option>
											<?php } ?>
								   <?php } ?>
							  </select>
							 </div>
							 <div class="col-sm-1 text-center icheck-input-new">
								<div class="">
								 <a class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" title="Add Communication" onclick="return ajaxdeletetransdata('delete_communication_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
								</div>
							 </div>
						</div>
					
				<?php } ?>
			<?php } else { ?>
			
             <div class="col-sm-8">
		
              <label for="text-input"><?=$this->lang->line('contact_add_communication_plan');?></label>
              <select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id">
				   <option value="">Please Select</option>
				   <?php if(!empty($communication_plans)){
				   		
							foreach($communication_plans as $row){?>
								<option value="<?=$row['id']?>"><?=$row['description']?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
             </div>
			 
			 <?php } ?>
			 
            </div>
            <div class="row">
             <div class="col-sm-12 topnd_margin"> <a title="Add Communication" class="text_color_red text_size add_new_communication_plan" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Communication</a> </div>
			 
            </div>-->
			
			<div class="col-sm-8">
				<div class="row">
					<label class="pull-left margin-top-5px"><?=$this->lang->line('user_assign_msg_contact');?></label>
					
					<select class="form-control parsley-validated" name="slt_user" id="slt_user">
				   	<option value="">Users</option>
				   	<?php if(!empty($user_list)){
							foreach($user_list as $row){?>
								<option value="<?=$row['id']?>" <?php if(!empty($user_add_list[0]['user_id']) && $user_add_list[0]['user_id'] == $row['id']){ echo "selected"; }?>><?=ucwords($row['first_name']." ".$row['middle_name']." ".$row['last_name']);?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
				</div>
			</div>
           </div>
		   
           
          <div class="col-sm-12 pull-left text-center margin-top-10">
<!--<a class="btn btn-secondary" href="javascript:void(0)">Save Contact</a>-->
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" title="Save Contact" class="btn btn-secondary" value="Save Contact" onclick="return setdefaultdata();" name="submitbtn" />
<input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="return setdefaultdata();" name="submitbtn" />
 <a class="btn btn-primary" title="cancel" href="javascript:history.go(-1);">Cancel</a>
         </div>
         
          </form>
          
         </div>
         <div <?php if($tabid == 2){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profile">
          
            
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>ajax" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  <input id="doc_id" name="doc_id" type="hidden" value="">
		  
		  	<div class="col-sm-12">
				
				<div class="add_emailtype autooverflow">
					<div class="col-sm-7">
					
						<label for="text-input"><?=$this->lang->line('contact_add_contact_pic');?></label>
					
						<div class="browse"> <span class="text"> </span>
						  <div class="browse_btn">
							<div class="file_input_div">
							  <input type="button" value="Browse" class="file_input_button"  />
							  <input type="file" alt="1" name="contact_pic" id="contact_pic" onchange="showimagepreview(this)" class="file_input_hidden"/>
							</div>
						  </div>
						  <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
						</div>
						<p> <span class="txt">&nbsp;</span>
                        	<?php  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_big_img_path').$editRecord[0]['contact_pic'])){
							?>
						  <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/> <a class="img_delete" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
						  <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['contact_pic'])){
				?>
						  <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" /> <a class="img_delete" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
						  <?
				}else{
				?>
						  <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
						 <? } } ?>
						
				
						</p>
					</div>
				</div>
				
			</div>
			
			<div class="col-sm-12" id="documenets">
				
				<div class="add_emailtype autooverflow">
					
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_documents');?></label>
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_document_type');?></label>
							  <select class="form-control parsley-validated" name="slt_doc_type" id="slt_doc_type">
							   <option value="">Please Select</option>
							   <?php if(!empty($document_type)){
										foreach($document_type as $row){?>
											<option <?php if(!empty($rowtrans['doc_type']) && $rowtrans['doc_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=$row['name']?></option>
										<?php } ?>
							   <?php } ?>
							  </select>
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('contact_add_document_name');?></label>
							  <input id="txt_doc_name" name="txt_doc_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['doc_name'])){ echo $editRecord[0]['doc_name']; }?>">
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input"><?=$this->lang->line('common_label_desc');?></label>
							  <textarea id="txtarea_doc_desc" name="txtarea_doc_desc" class="form-control parsley-validated"><?php if(!empty($rowtrans['doc_desc'])){ echo $rowtrans['doc_desc']; }?></textarea>
							</div>
							
							<div class="col-sm-7 form-group">
							  <label for="text-input" class="fleft"><?=$this->lang->line('contact_add_upload_file');?></label>
							  <div class="browse_btn clear">
								  <div class="file_input_div">
									<input type="button" value="Browse" class="file_input_button" />
								   
									<input type="file" alt="1" name="doc_file" id="doc_file" class="file_input_hidden"/>
									<input type="hidden" name="hiddenFiledoc" id="hiddenFiledoc" value="" />
								  </div>
								   <span id="priview_doc"></span>
							</div>
								
							</div>
							<div class="col-sm-7 form-group margin-top-10">
							  <input title="Save and Add More Document" type="submit" class="btn btn-secondary" value="Save and Add More Document" onclick="return setsubmitidtab2(3);" id="submitbtn" name="submitbtn2" />
							</div>

										
						</div>
					</div>
					
					<div class="col-sm-12 clear appendajaxdata toppadding">
					
						<?php $this->load->view('admin/contacts/contact_document_ajax'); ?>
						
					</div>
					
				</div>
				
			</div>
			
			
		    
			<div class="col-sm-12 pull-left text-center">
			
		  		<input type="hidden" id="contacttab" name="contacttab" value="2" />
				<input type="hidden" id="submitvaltab2" name="submitvaltab2" value="1" />
				<input type="submit" title="Save Contact" class="btn btn-secondary" value="Save Contact" onclick="setsubmitidtab2(1);" id="savecontacttab2" name="submitbtn" />
				<input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="setsubmitidtab2(2);" name="submitbtn" />
				<a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
				
         	</div>
			
			</form>
			
         </div>
         <div <?php if($tabid == 3){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="profilenew">
          
		  	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
	
			  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
			  
			  <div class="col-sm-7">
			  	<div class="row">
				 <div class="col-sm-8">
				  <label for="text-input"><?=$this->lang->line('contact_add_birth_date');?></label>
				  <input id="txt_birth_date" name="txt_birth_date" readonly="" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['birth_date']) && $editRecord[0]['birth_date'] != '0000-00-00' && $editRecord[0]['birth_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['birth_date'])); }?>">
				 </div>
				</div>
				<div class="row">
				 <div class="col-sm-8">
				  <label for="text-input"><?=$this->lang->line('contact_add_anniversary_date');?></label>
				  <input id="txt_anniversary_date" name="txt_anniversary_date" readonly="" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['anniversary_date']) && $editRecord[0]['anniversary_date'] != '0000-00-00' && $editRecord[0]['anniversary_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['anniversary_date'])); }?>">
				 </div>
				</div>
		  	</div>
			
			<div class="col-sm-12 pull-left text-center margin-top-10">
			  <input type="hidden" id="contacttab" name="contacttab" value="3" />
			  <input type="submit" title="Save Contact" class="btn btn-secondary" value="Save Contact" />
			  <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);">Cancel</a>
			</div>
			  
			</form>
		  
         </div>
        </div>
       </div>
      </div>
      <!-- /.portlet-content --> 
      
     </div>
    </div>
   </div>
  </div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
 </div>
<script type="text/javascript">
	$('body').on('click','.add_email_address',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_email_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated contact_module" name="slt_email_type[]" id="slt_email_type" data-required="true">';
               inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($email_type)){
										foreach($email_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>';
              inlinehtml += '<input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 text-center icheck-input-new">';
              inlinehtml += '<div class="form-group">';
               //inlinehtml += '<label><?=$this->lang->line('common_default');?></label>';
               inlinehtml += '<div class="radio">';
                inlinehtml += '<label class="">';
                inlinehtml += '<div class="margin-left-48">';
                 inlinehtml += '<input type="radio" class=""  name="rad_email_default" data-required="true">';
                inlinehtml += '</div>';
                inlinehtml += '</label>';
               inlinehtml += '</div>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_email_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
		
		/*$('.add_email_address_div').hide();
		$('.add_email_address_div').append(inlinehtml).fadeIn('slow');*/
		
		$('.add_email_address_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows" style="display:none;"></div>';
		$(liData).appendTo('.add_email_address_div').fadeIn('slow');
	
		jQuery('.new-rows').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_email_div_button',function(e){
	
		var removediv = $(this).closest('.remove_email_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_phone_number',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_phone_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type" data-required="true">';
               inlinehtml += '<option value="">Please Select</option>';
						   <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
										inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
									<?php } ?>
						   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>';
              inlinehtml += '<input id="txt_phone_no" onkeypress="return isNumberKey(event);" maxlength="12" data-type="number_mask" name="txt_phone_no[]" class="form-control parsley-validated" type="text" onKeyUp="javascript:return mask(this.value,this,'3,7','-');" onBlur="javascript:return mask(this.value,this,'3,7','-');>';
			 inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 text-center icheck-input-new">';
              inlinehtml += '<div class="form-group">';
               //inlinehtml += '<label><?=$this->lang->line('common_default');?></label>';
               inlinehtml += '<div class="radio">';
                inlinehtml += '<label class="">';
                inlinehtml += '<div class="margin-left-48">';
                 inlinehtml += '<input type="radio"  class=""   name="rad_phone_default" data-required="true">';
                inlinehtml += '</div>';
                inlinehtml += '</label>';
               inlinehtml += '</div>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_phone_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
		
		$('.add_phone_number_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows1" style="display:none;"></div>';
		$(liData).appendTo('.add_phone_number_div').fadeIn('slow');
	
		jQuery('.new-rows1').html(inlinehtml, 500);*/
	});
	
	$('body').on('click','.delete_phone_div_button',function(e){
		
		var removediv = $(this).closest('.remove_phone_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_website',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_website_div padding-top-10 clear autooverflow">';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>';
			inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_website_type" name="txt_website_type[]">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5 form-group">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>';
			inlinehtml += '<input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]"  data-parsley-type="url">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my delete_website_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_website_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows2" style="display:none;"></div>';
		$(liData).appendTo('.add_website_div').fadeIn('slow');
	
		jQuery('.new-rows2').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_website_div_button',function(e){
		
		var removediv = $(this).closest('.remove_website_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_social_profile',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_social_profile_div padding-top-10 clear autooverflow">';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>';
			inlinehtml += '<select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">';
			   inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
			inlinehtml += '</select>';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>';
			inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_social_profile" name="txt_social_profile[]">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Social WebSite" class="btn btn-xs btn-primary mar_top_con_my delete_social_profile_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_social_profile_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows3" style="display:none;"></div>';
		$(liData).appendTo('.add_social_profile_div').fadeIn('slow');
	
		jQuery('.new-rows3').html(inlinehtml, 500);*/
	
	});
	
	$('body').on('click','.delete_social_profile_div_button',function(e){
		
		var removediv = $(this).closest('.remove_social_profile_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_tag',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_tag_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-8">';
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>';
		  inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]">';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Tag" class="btn btn-xs btn-primary mar_top_con_my delete_tag_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_tag_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows4" style="display:none;"></div>';
		$(liData).appendTo('.add_tag_div').fadeIn('slow');
	
		jQuery('.new-rows4').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_tag_div_button',function(e){
		
		var removediv = $(this).closest('.remove_tag_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_address',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_address_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-3 columns">';
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_address_type[]" id="slt_address_type">';
		   inlinehtml += '<option value="">Please Select</option>';
						   <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
										inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
									<?php } ?>
						   <?php } ?>
		  inlinehtml += '</select>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-6 columns">';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1[]" class="form-control parsley-validated"></textarea>';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<input type="text" placeholder="Address Line 2" name="txtarea_address_line2[]" id="txtarea_address_line2" class="form-control parsley-validated">';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<div class="col-sm-5 nopadding">';
			inlinehtml += '<input type="text" placeholder="City" id="txt_city" name="txt_city[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-3 nopadding">';
			inlinehtml += '<input type="text" placeholder="State" id="txt_state" name="txt_state[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-4 nopadding">';
			inlinehtml += '<input type="text" placeholder="Zip Code" id="txt_zip_code" maxlength="5" data-minlength="5"  name="txt_zip_code[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<input type="text" placeholder="Country" id="txt_country" name="txt_country[]" class="form-control parsley-validated">';
		  inlinehtml += '</div>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-2">';
		  inlinehtml += '<button class="btn nomargin btn-xs btn-primary mar_top_con_my delete_address_div_button"> <i class="fa fa-times"></i> </button>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div> </div>';
		inlinehtml += '</div>';
		
		$('.add_address_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows5" style="display:none;"></div>';
		$(liData).appendTo('.add_address_div').fadeIn('slow');
	
		jQuery('.new-rows5').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_address_div_button',function(e){
		
		var removediv = $(this).closest('.remove_address_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_communication_plan',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_communication_plan_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-8">';
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_communication_plan');?></label>';
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id">';
			   inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($communication_plans)){
										foreach($communication_plans as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['description']?></option>';
										<?php } ?>
							   <?php } ?>
		  inlinehtml += '</select>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_communication_plan_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_communication_plan_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows6" style="display:none;"></div>';
		$(liData).appendTo('.add_communication_plan_div').fadeIn('slow');
	
		jQuery('.new-rows6').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_communication_plan_div_button',function(e){
		
		var removediv = $(this).closest('.remove_communication_plan_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
		
	});
	
	function ajaxdeletetransdata(functionname,id)
	{	
		var id1 = $('#id').val();
		$.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': 'special',	
								'action': function(){
								
										$.ajax({
											type: "post",
											url: '<?php echo $this->config->item('admin_base_url')?><?=$viewname;?>/'+functionname+'/'+id,
											data: {'id1':id1}, 
											success: function(msg1) 
											{
												$('.'+functionname+id).remove();
												
											}
										});	
								
									}},
					 	'No'	: {'class'	: ''}
					 }
				});
		
		return false;
	}
	
	function setdefaultdata()
	{
		var returndata = 0;
		
		// get all the inputs into an array.
		var $inputs = $('.add_email_address_div :input[type=email]');
		var $inputs1 = $('.add_phone_number_div :input[type=text]');
	
		// not sure if you wanted this, but I thought I'd add it.
		// get an associative array of just the values.
		var unique_values = {};
		$inputs.each(function() {
		
			if ( ! unique_values[this.value] ) {
				unique_values[this.value] = true;
			} else {
				// We have duplicate values!
					$.confirm({'title': 'Alert','message': " <strong> Same email id used multiple times. Please insert different email ids. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same email id used multiple times. Please insert different email ids.');
				returndata = 1;
			}
			
		});
		
		var unique_values1 = {};
		$inputs1.each(function() {
		
			if ( ! unique_values1[this.value] ) {
				unique_values1[this.value] = true;
			} else {
				// We have duplicate values!
				$.confirm({'title': 'Alert','message': " <strong> Same phone no used multiple times. Please insert different phone no. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same phone no used multiple times. Please insert different phone no.');
				returndata = 1;
			}
			
		});
		
		if(returndata == 1)
			return false;
		
		emailchkval = $('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').closest("div.col-sm-2").siblings('div.col-sm-4').find('input[type=email]').val();
		$('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').val(emailchkval);
		
		phonechkval = $('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').closest("div.col-sm-2").siblings('div.col-sm-4').find('input[type=text]').val();
		$('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').val(phonechkval);
	}
	
</script>
<script type="text/javascript">
$(function() {
	$( "#txt_birth_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		maxDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	
	$( "#txt_anniversary_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		maxDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
</script>
<script type="text/javascript">
	$(function(){
/*		var image=$('#hiddenFile').val();
        var btnUpload=$('#contact_pic');

		new AjaxUpload(btnUpload, {
			type: 'post',
			data:{image:image},
			action: '<?=$this->config->item('admin_base_url').$viewname."/upload_image";?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
			//alert(JSON.stringify(file));
			//alert(this.files[0]);
			if (! (ext && /^(jpg|png|jpeg|gif|bmp)$/.test(ext))){ 
					$.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					//alert('Please upload jpg | jpeg | png | bmp | gif file only');
					return false;
				}
				$('#uploadPreview1').attr('width','16');
				$('#uploadPreview1').attr('height','16');
				$('#uploadPreview1').attr('src','<?=$this->config->item('image_path').'ajax-loader.gif'?>');
			},
			onComplete: function(file, response){
				$('#hiddenFile').val(response);
				$('#uploadPreview1').attr('width','100');
				$('#uploadPreview1').attr('height','100');
				$('#uploadPreview1').attr('src','<?=base_url().$this->config->item('temp_small_img_path')?>'+response);
			}
		});
*/		
		var btnUpload1=$('#doc_file');
		new AjaxUpload(btnUpload1, {
			type: 'post',
			data:{},
			action: '<?=$this->config->item('admin_base_url').$viewname."/upload_document";?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(txt|doc|pdf|docx|csv|xls|xlsx)$/.test(ext))){ 
                    // extension is not allowed 
					$.confirm({'title': 'Alert','message': " <strong> You can upload only txt,doc,docx,pdf,csv,xls,xlsx. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					//alert('You can upload only txt,doc,docx,pdf,csv,xls,xlsx.');
					return false;
				}
				
				$('#priview_doc').html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
			},
			onComplete: function(file, response){
			var data = jQuery.parseJSON(response);
				var result=data.document_name.split('-');
				if(result.length > 1)
				{
					var arrayindex = jQuery.inArray( result[0] , result );
					
					if(arrayindex >= 0)
					{
						result.splice( arrayindex, 1 );
					}
				}
				$('#priview_doc').text(result.join('-'));	
				$('#hiddenFiledoc').val(data.document_name);
			}
		});
		
	});
	
	var frm = $('#<?php echo $viewname;?>ajax');
    frm.submit(function (ev) {
	
		$("#savecontacttab2").hide();
	
		try{
		
			$.ajax({
					type: frm.attr('method'),
					url: frm.attr('action'),
					data: frm.serialize(),
					success: function (data) {
						
						$(".appendajaxdata").html(data);
						frm[0].reset();
						$("#priview_doc").text('');
						$("#hiddenFiledoc").val('');
						$("#hiddenFile").val('');
						
						var chkval = $('#submitvaltab2').val();
						var cid = $('#id').val();
						
						if(chkval == 1)
							window.location.href = '<?=base_url().'admin/'.$viewname;?>';
						else if(chkval == 3)
							
							$(".toppadding").html(data);
							
						else
							window.location.href = '<?=base_url().'admin/'.$viewname.'/edit_record/';?>'+cid+'/3';
						
					}
			});
			
		}
		catch(e){ alert('Something went wrong.Please try again.');window.location.reload();}
		
		$("#savecontacttab2").show();

        ev.preventDefault();
    });
	
	//delete image
	function delete_image(name,divid)
	{
		$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete image?",'buttons': {'Yes': {'class': 'special',
'action': function(){
			//loading('Checking');
				 //$('#preloader').html('Deleting...');
		var id=$('#id').val();
		 $.ajax({
			type: 'post',
			data:{id:id,name:name},
			url: '<?=$this->config->item('admin_base_url').$viewname."/delete_image";?>',
			success:function(msg){
					if(msg == 'done')
					{
					$('.img_delete').hide();
			      	$('#'+divid).attr('src','<?=base_url('images/no_image.jpg')?>');
				  }
				}//succsess
			});//ajax
			
			}},'No'	: {'class'	: ''}}});
	}
	
	function editdoctransdata(id)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:{id:id},
			url: '<?=$this->config->item('admin_base_url').$viewname."/get_doc_trans_data";?>',
			success:function(msg){
					if(msg == 'error')
					{
						alert('Something went wrong.');
					}
					else
					{
						$("#doc_id").val(msg.id);
						$("#slt_doc_type").val(msg.doc_type);
						$("#txt_doc_name").val(msg.doc_name);
						$("#txtarea_doc_desc").val(msg.doc_desc);
						$("#hiddenFiledoc").val(msg.doc_file);
						
						var result=msg.doc_file.split('-');
						if(result.length > 1)
						{
							var arrayindex = jQuery.inArray( result[0] , result );
							
							if(arrayindex >= 0)
							{
								result.splice( arrayindex, 1 );
							}
						}
						
						$("#priview_doc").text(result.join('-'));
					}
				}//succsess
			});//ajax
	}
	
	function setsubmitidtab2(id)
	{
		$("#submitvaltab2").val(id);
	}
	
	 function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
	/*$(".colorPicker").colorPicker({

		onSelect: function(ui, c){
			ui.css("background", c);
		}
	});*/
</script>
<script type="text/javascript">
function showimagepreview(input) 
{
	if (input.files && input.files[0]) 
	{
		var filerdr = new FileReader();
		filerdr.onload = function(e) {
		$('#uploadPreview1').attr('src', e.target.result);
	}
	filerdr.readAsDataURL(input.files[0]);
	}
}
 function mask(str,textbox,loc,delim){

        var locs = loc.split(','); 

        for (var i = 0; i <= locs.length; i++){

            for (var k = 0; k <= str.length; k++){

                if (k == locs[i]){

                    if (str.substring(k, k+1) != delim){

                        str = str.substring(0,k) + delim + str.substring(k,str.length);
                    }
                }
            }
        }
        textbox.value = str;
    }

</script>