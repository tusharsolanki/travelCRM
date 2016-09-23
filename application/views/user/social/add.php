<?php
/*
    @Description: SMS campaign add/edit page
    @Author: Sanjay Chabhadiya
    @Date: 06-08-2014

*/?>
<?php 
//$viewname = $this->router->uri->segments[2];
$viewname = !empty($this->router->uri->segments[2])?$this->router->uri->segments[2]:'';
$platformid = !empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';
/*if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;*/
	
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

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />
<!--time picker-->
<link rel="stylesheet" href="<?php echo base_url();?>css/datepicker_css/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.timepicker.js"></script>
<!-- Select Contact To -->

<div aria-hidden="true" style="display: none;" id="basicModal_to" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
        
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_to" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
            <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_to();">Search Contacts</a>
            <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_to();">View All</button>
          </div>
        </div>
        <?php /*?><div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type' id='contact_type' onchange="contact_ser_to();">
                	<option value="">Contact Type</option>
                    <?php if(!empty($contact_list)){
                    		foreach($contact_list as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='slt_contact_source' id='slt_contact_source' onchange="contact_ser_to();">
                	<option value="">Contact Source</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status" onchange="contact_ser_to();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div><?php */?>
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
            <div class="col-sm-10">
              <label id="count_selected_to"></label>
              | <a class="text_color_red text_size add_email_address" onclick="remove_selection_to();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a> </div>
          </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 contact_to">
          <div class="table-responsive">
            <div id="contact_div">
              <?php $this->load->view('user/sms/contact_to');?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- End -->
<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
            <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search();">Search Contact Type</a>
            <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
          </div>
        </div>
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
            <div class="col-sm-10">
              <label id="count_selected"></label>
              | <a class="text_color_red text_size add_email_address" onclick="remove_selection();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a> </div>
          </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="table-responsive">
            <?php $this->load->view('user/sms/add_contact_popup_ajax');?>
          </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('phonecallscript_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3>
              <?=!empty($editRecord)?'Update':'Add'?>
              Social Media Campaign
              <?php
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?>
            </h3>
            <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
          <div class="portlet-content">
            <div class="">
              <div class="tab-content" id="myTab1Content">
                <div class="tab-pane fade in active" id="home">
                  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                    <div class="row">
                      <div class="col-sm-8">
                        <div class="row">
                          <div class="col-sm-12">
                            <label for="text-input">
                            <?=$this->lang->line('platform_label_name');?> <span class="val">*</span>
                            </label>
                          </div>
                          <div class="col-sm-12 form-group">
                          <?php if(!empty($platformid) && ($platformid =='all')) { ?> 
						  <select class="form-control parsley-validated" multiple="multiple" name='platform[]' id='platform' onchange="divhideshow(this.value)" data-required="true">
                              <?php if(!empty($profile_type)){
								foreach($profile_type as $row){
								if($row['id'] != '1')
								{
								?>
                              <option selected="selected" value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == $row['id']){ echo "selected=selected"; } ?>>
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php  } }
                             } ?>
                            </select>
						  <? } else {?>
                            <select class="form-control parsley-validated" name='platform' id='platform' onchange="divhideshow(this.value)" data-required="true">
                            <? if(empty($platformid)){?>
                              <option value="">Please Select</option>
                              <? } ?>
                              <?php if(!empty($profile_type)){
								foreach($profile_type as $row){
								if(!empty($platformid) && $platformid == '3') {
								if($row['id'] == '3')
								{
								?>
                              <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == $row['id']){ echo "selected=selected"; } ?>>
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php } }
							  	elseif(!empty($platformid) && $platformid == '2') {
								if($row['id'] == '2')
								{
								?>
                              <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == $row['id']){ echo "selected=selected"; } ?>>
                              <?=ucwords($row['name']);?>
                              </option>
                              <?php } }
							    else {
								?>
                                <?
								if($row['id'] == '1')
								{
								?>
                                 <?php if(!empty($this->modules_unique_name) && (in_array('facebook_post',$this->modules_unique_name))){?>
                              <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == $row['id']){ echo "selected=selected"; } ?>>
                              <?=ucwords($row['name']);?>
                              </option>
                              <? } ?>
                              <? } ?>
                               <?
								if($row['id'] == '2')
								{
								?>
                                <?php if(!empty($this->modules_unique_name) && (in_array('twitter',$this->modules_unique_name) || in_array('all_channels',$this->modules_unique_name))){?>
                              <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == $row['id']){ echo "selected=selected"; } ?>>
                              <?=ucwords($row['name']);?>
                              </option>
                              <? } ?>
                              <? } ?>
                               <?
								if($row['id'] == '3')
								{
								?>
                                 <?php if(!empty($this->modules_unique_name) && (in_array('linkedin',$this->modules_unique_name) || in_array('all_channels',$this->modules_unique_name))){?>
                              <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == $row['id']){ echo "selected=selected"; } ?>>
                              <?=ucwords($row['name']);?>
                              </option>
                              <? } ?>
                                <? } ?>
                              <?php }  }
                             } ?>
                            </select>
                            <? } ?>
                          </div>
                        </div>
                        <?php
            if((!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == '3') || (!empty($platformid) && ($platformid == '3' || $platformid =='all')))
			{$ldis='style="display:block;"';}
			else
			{$ldis='style="display:none;"';}
			
			?>
             <div  class="linked_page" <?=$ldis?>>
                          <div class="row">
                            <div class="col-sm-12">
                              <label for="text-input">Select Company Page</label>
                            </div>
                            <div class="col-sm-12 form-group">
                              <select class="form-control parsley-validated" name='linkedin_page' id='linkedin_page' >
                                <?php if(!empty($linkedin_page)){
							/*for($i=0;$i<count($linkedin_page);$i++)
			{*/?>
                                <option value="<?=$linkedin_page['id'].'_'.trim($linkedin_page['name'])?>" <?php if(!empty($editRecord[0]['page_name']) && $editRecord[0]['page_name'] == trim($linkedin_page['name'])){ echo "selected=selected"; } ?>>
                                <?=trim($linkedin_page['name'])?>
                                </option>
                                <?php /* }*/ ?>
                                <?php } 
                                else { ?>
                                <option value="">No page available</option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
						 <?php
								if((!empty($editRecord[0]['platform']) && $editRecord[0]['platform'] == '2')  || (!empty($platformid) && ($platformid == '2' || $platformid =='all')))
								{$tdis='style="display:block;"';}
								else
								{$tdis='style="display:none;"';}
							
								?>
								 <div  class="twitter_page" <?=$tdis?>>
									<div class="row">
											 <div class="col-sm-12 ">
											  <label for="text-input">Twitter Handle</label>
											  </div>
											  <div class="col-sm-12 form-group">
											  <select class="form-control parsley-validated" name='screen_name' id='screen_name'  >
											 
											  <?
											  if(!empty($profile_user_data)){?>
											  <option <?php if(!empty($editRecord[0]['page_name']) && $editRecord[0]['page_name'] == trim($profile_user_data)){ echo "selected=selected"; } ?> value="<?php echo $profile_user_data;?>"><?php echo $profile_user_data;?></option>
											  <?
											  } 
											  ?>
											<?php /*?> <? if(isset($profile_trans_data) && count($profile_trans_data) > 0){
															foreach($profile_trans_data as $row1){
																if(!empty($row1['twitter_handle'])){?>
												<option <?php if(!empty($editRecord[0]['page_name']) && $editRecord[0]['page_name'] == trim($row1['twitter_handle'])){ echo "selected=selected"; } ?> value="<?php echo $row1['twitter_handle'];?>"><?php echo $row1['twitter_handle'];?></option>
												<?php 		}
															}
															 
														} 
													
														 if(isset($profile_trans_data) && empty($profile_user_data)) {
                                              ?>
                                               <option value="">No page available</option>
                                              <?php } ?><?php */?>
                                               <?php if(isset($profile_user_data) && empty($profile_user_data)) {
                                              ?>
                                               <option value="">No page available</option>
                                              <?php } ?>
											  </select>
											  </div>
						 
								  <!--<div class="col-sm-6">
								  <select class="selectBox" name='slt_subcategory' id='subcategory'>
								  </select>
								  <span id="category_loader"></span>
								  </div>-->
								</div>
								</div>
                        <?php /*?><div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('email_to');?> <span class="val">*</span> : </label>
              <input id="email_to" name="email_to" class="form-control parsley-validated" type="text" value=""   data-required="true" placeholder="Enter Name">
		<script type="text/javascript">
			$(document).ready(function() {
			var platformid=$("#platform").val();
			
			 if(platformid == '3')
				   {
					   
				   $("#email_to").tokenInput([ 
				  
				<?php foreach($contact_linkedin as $row){ ?>
					{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
				<?php } ?>
				],
				{prePopulate:[
					<?php 
					if(isset($email_to)) {
					foreach($email_to as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
					<?php } } ?>
					
				],onAdd: function (item) {
					var str1 = item.id;
					if(!isNaN(str1))
					{
						//var myvalue = str.substr(3);
						add_contact_to(str1);
					}
				},
				onDelete: function (item) {
					var str = item.id;
					//var char = str.substr(0,2);
					if(isNaN(str))
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}
					else
					{
						remove_contact_to(item.id);
					}
				
				}, 
				preventDuplicates: true,
				hintText: "Enter Contact Name",
                noResultsText: "No Contact Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
				  }
				   if(platformid == '1')
				   {
				   $("#email_to").tokenInput([ 
				  
				<?php foreach($contact_fb as $row){ ?>
					{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
				<?php } ?>
				],
				{prePopulate:[
					<?php 
					if(isset($email_to)) {
					foreach($email_to as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
					<?php } } ?>
					
				],onAdd: function (item) {
					var str1 = item.id;
					if(!isNaN(str1))
					{
						//var myvalue = str.substr(3);
						add_contact_to(str1);
					}
				},
				onDelete: function (item) {
					var str = item.id;
					//var char = str.substr(0,2);
					if(isNaN(str))
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}
					else
					{
						remove_contact_to(item.id);
					}
			
				}, 
				preventDuplicates: true,
				hintText: "Enter Contact Name",
                noResultsText: "No Contact Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
				  }
				  if(platformid == '2')
				   {
				   $("#email_to").tokenInput([ 
				  
				<?php foreach($contact as $row){ ?>
					{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
				<?php } ?>
				],
				{prePopulate:[
					<?php 
					if(isset($email_to)) {
					foreach($email_to as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
					<?php } } ?>
					
				],onAdd: function (item) {
					var str1 = item.id;
					if(!isNaN(str1))
					{
						//var myvalue = str.substr(3);
						add_contact_to(str1);
					}
				},
				onDelete: function (item) {
					var str = item.id;
					//var char = str.substr(0,2);
					if(isNaN(str))
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}
					else
					{
						remove_contact_to(item.id);
					}
				
				}, 
				preventDuplicates: true,
				hintText: "Enter Contact Name",
                noResultsText: "No Contact Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
				  }
					
				
				<?php /*?>$("#email_to").tokenInput([ 
				<?php foreach($contact as $row){ ?>
					{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
				<?php } ?>
				],
				{prePopulate:[
					<?php 
					if(isset($email_to)) {
					foreach($email_to as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?> (<?=$row['email_address']?>)"},
					<?php } } ?>
					<?php if(isset($contact_type_to)) {
					foreach($contact_type_to as $row){ ?>
						{id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
					<?php } } ?>
				],onAdd: function (item) {
					var str1 = item.id;
					if(!isNaN(str1))
					{
						//var myvalue = str.substr(3);
						add_contact_to(str1);
					}
				},
				onDelete: function (item) {
					var str = item.id;
					//var char = str.substr(0,2);
					if(isNaN(str))
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}
					else
					{
						remove_contact_to(item.id);
					}
				
				}, 
				preventDuplicates: true,
				hintText: "Enter Contact Name",
                noResultsText: "No Contact Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
		});
		</script>
        <?php if(($formAction == 'insert_data' && empty($this->router->uri->segments[4])) || $formAction == 'update_data') { ?>
			 <a href="#basicModal_to" class="text_color_red text_size" id="contact_to_email" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
		  <?php } ?>
             </div>
            </div><?php */?>
                        <div id="facebook_hide_div">
                          <div class="row">
                            <div class="col-sm-12">
                              <label for="text-input">
                              <?=$this->lang->line('common_label_category');?>  </label>
                            </div>
                            <div class="col-sm-12">
                              <select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)">
                                <option value="-1">Category</option>
                                <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?> ><?php echo ucwords($row1['category']);?></option>
                                <?php 		}
							}
						} ?>
                              </select>
                            </div>
                            <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                              <label for="text-input">
                              <?=$this->lang->line('template_label_name');?></label>
                              <select class="selectBox" name='template_name' id='template_name'>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="select-multi-input">
                            <?=$this->lang->line('label_social_msg');?>
                            <span class="val">*</span> </label>
                            <textarea name="social_message" class="form-control parsley-validated" id="social_message" ><?=!empty($editRecord[0]['social_message'])?$editRecord[0]['social_message']:'';?>
</textarea>
                            <script type="text/javascript">
												CKEDITOR.replace('social_message',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                          </div>
                          <?php /*?><div class="row">
             <div class="col-sm-12 form-group">
              <label for="select-multi-input">
                 	SMS Message <span class="val">*</span> : 
                  </label>
                <textarea name="sms_message" id="sms_message" class="form-control parsley-validated" data-required="true" onkeypress="return keypress(event);" ><?=!empty($editRecord[0]['sms_message'])?$editRecord[0]['sms_message']:'';?>
</textarea>

             </div>
            </div><?php */?>
                          <?php /*?><div class="row">
             <div class="col-sm-12 form-group">
           	<label id="textarea_feedback"></label>
		   </div></div><?php */?>
                          <div class="row">
                            <div class="col-sm-12">
                              <label for="select-multi-input">
                              <fieldset class="schedule_campaign">
                              <legend>Schedule Campaign</legend>
                              <div class="schedule_detail">
                                <p>When would you like to send this Campaign?</p>
                                <div class="col-sm-12 pull-left">
                                  <div class="float-left margin-left-15">
                                    <label class="checkbox">
                                    Now
                                    <div class="col-sm-3">
                                      <input type="radio" value="1" class="" id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '1' || (!empty($send_now) && $send_now == 'send')){ echo "checked=checked"; } ?>>
                                    </div>
                                    </label>
                                  </div>
                                </div>
                                <div class="col-sm-12 pull-left">
                                  <div class="float-left margin-left-15">
                                    <label class="checkbox">
                                    Date
                                    <div class="col-sm-2">
                                      <input type="radio" name="chk_is_lead" id="chk_is_lead" class="" value="2"  <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '2' && $send_now == ''){ echo "checked=checked"; } ?>  />
                                    </div>
                                    </label>
                                  </div>
                                  <div class="col-sm-12 col-lg-5 col-md-8 sms_add_record">
                                    <input name="send_date" placeholder="Date" id="send_date" class="form-control parsley-validated" type="text" <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '2') { ?>value="<?=!empty($editRecord[0]['social_send_date'])?$editRecord[0]['social_send_date']:''?>" <?php } ?> readonly="readonly"/>
                                  </div>
                                  <div class="col-sm-12 col-lg-4 col-md-9 sms_add_record">
                                    <label class="col-sm-3 col-lg-3 mrg28">Time</label>
                                    <input name="send_time" id="send_time" placeholder="Time" readonly="readonly" type="text" <?php if(!empty($editRecord[0]['social_send_type']) && $editRecord[0]['social_send_type'] == '2') { ?> value="<?=!empty($editRecord[0]['social_send_time'])?date("h:i A", strtotime($editRecord[0]['social_send_time'])):''?>" <?php } ?> />
                                    <button type="button" class="timepicker_disable_button_trigger timepick col-sm-1 col-sm-3 col-md-2" onclick="send_time_focus()"><img src="<?=base_url('images/timecal.png')?>" alt="..." title="..."/></button>
                                  </div>
                                </div>
                              </div>
                              </fieldset>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php /*?><div class="col-sm-4">
            
               <label>Custom Field</label>
            <div class="custom_field">
               
              
               <select ondblclick="addfieldtoeditor();" id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox">
             <?php if(isset($tablefield_data) && count($tablefield_data) > 0){ ?>
             <?php foreach($tablefield_data as $row){ 
															if(!empty($row['name'])){
															$pattern = $this->config->item('email_param_pattern');
															if(empty($pattern))
																$pattern = "{(%s)}";
															$fieldval = !empty($row['name'])?$row['name']:$row['name'];
															 ?>
             <option title="<?php echo $fieldval;?>" value="<?php echo sprintf($pattern, $fieldval);?>"> <?php echo $fieldval;?> </option>
             <?php }
														 } ?>
             <?php } ?>
            </select>
               
                </select>
                 <input class="btn btn-secondary" type="button" name="submitbtn" onclick="addfieldtoeditor();" value="Insert Field">
               
            </div>
           </div><?php */?>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <input type="hidden" id="email_to_contact_type" name="email_to_contact_type" value="<?=!empty($contact_type_to_selected)?$contact_type_to_selected:''?>" />
                          <input type="hidden" id="email_contact_to" name="email_contact_to" value="<?=!empty($select_to)?$select_to:''?>" />
                          <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 pull-left text-center margin-top-10">
                      <input type="hidden" id="contacttab" name="contacttab" value="1" />
                      <input id="page_name" name="page_name" type="hidden" value="<?php if(!empty($editRecord[0]['page_name'])){ echo $editRecord[0]['page_name']; }?>">
                      <input type="submit" class="btn btn-secondary send_now" value="Send Now" title="Send Now" onclick="return sendnow();" name="submitbtn" />
                      <input type="submit" class="btn btn-secondary-green save_data" value="Save Campaign" onclick="return sendnow();" title="Save Campaign" name="submitbtn" />
                      <input type="submit" class="btn btn-secondary save_data" value="Save Template As" onclick="return sendnow();" title="Save Template As" name="submitbtn" />
                      <a class="btn btn-primary" href="javascript:history.go(-1);" title="Close">Close</a>
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
</div>
<?php
$callback_url         =   base_url().'user/social/fbconnection/?fbTrue=true';
$facebook_api_key= !empty($fb_deatils[0]['fb_api_key'])?$fb_deatils[0]['fb_api_key']:$this->config->item('facebook_api_key');
$facebook_secret_key = !empty($fb_deatils[0]['fb_secret_key'])?$fb_deatils[0]['fb_secret_key']:$this->config->item('facebook_secret_key');

// print_r($subcategory);
 ?>
<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId      : '728901530477590', // replace your app id here
	channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', 
	status     : true, 
	cookie     : true, 
	xfbml      : true  
		});
	};
	
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
	}(document));

function sendnow()
{
var appid='<?=$facebook_api_key?>';
var callback_url='<?=$callback_url?>';
	var plarform = $("#platform").val();
	if(plarform == 1)
	{ 
	
	window.open('https://www.facebook.com/dialog/oauth?client_id='+appid+'&redirect_uri='+callback_url+'&scope=email,user_about_me,offline_access,publish_stream,publish_actions,manage_pages', "MsgWindow", "width=700, height=500");
	return false;
	}
	if(plarform == 3 || plarform == 2)
	{
		var ck_edit = CKEDITOR.instances.social_message.getData();
		if(ck_edit == "")
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter post content details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
		else
		{
			var html=CKEDITOR.instances.social_message.getSnapshot();
			var dom=document.createElement("DIV");
			dom.innerHTML=html;
			var plain_text=(dom.textContent || dom.innerText);
			CKEDITOR.instances.social_message.setData(plain_text);
			
			//create and set a 128 char snippet to the hidden form field
			//var snippet=plain_text.substr(0,140);
			if($('input:radio[name=chk_is_lead]:checked').val() == '2')
			{
				//alert($('input:radio[name=chk_is_lead]:checked').val() == '2');
				if($("#send_date").val() == '')
				{
					$.confirm({'title': 'Alert','message': " <strong> Please select specific date"+"<strong></strong>",
								'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
								 $('#send_date').focus();
								}},  }});
					return false;
				}
                                
				
			}
			else if(plarform == '2' && $("#screen_name").val() == null)
			{
					$.confirm({'title': 'Alert','message': " <strong> You did not have any twitter handle."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
			}
			else if(plarform == '3' && $("#linkedin_page").val() == null)
			{
				$.confirm({'title': 'Alert','message': " <strong> You did not have any company page."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
			}
			else
			{
                            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                            }
				return true;
			}	
		}
	}
	else
	{
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		}
	}
	
	
}
function FBLogin(){
	url = '<?php echo $this->config->item('user_base_url').$viewname.'/fb_connection';?>';
	 $.ajax({
				 type: "POST",
				 //dataType: 'json',
				 url: url,
				 data: {login:'login'},
				 cache: false,
				 beforeSend: function() {
					//	$('#facebook_chat_history').block({ message: 'Loading...' }); 
				  },
				 success: function(data){
				 }
				});
}

function FBLogin_send(){
	
	FB.login(function(response){
		if(response.authResponse)
		{
		return false;
				var contact = "<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';?>"; 
				
				 $.ajax({
					 type: "POST",
					 url: "<?=base_url().'user/'.!empty($fb_path)?$fb_path:''?>",
					 data: {login:'login',contact:contact},
					 cache: false,
	 				 beforeSend: function() {
							$('.loding_win').block({ message: 'Loading...' }); 
						  },
					 success: function(data){
					//alert(data);
					//data = new Array();
					//data = ['100000327018571','100001598523327'];
					if(data != '' && data != 0)
					{	
						FB.ui({
						  method: 'send',
						  to:data,
						  link: 'http://topsdemo.in/',
						});
					}
					else
					{
						$.confirm({'title': 'Alert','message': " <strong> This contact is not your friend "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
						   
					}
					//$("#basicModal_3").dialog("close");
					$( ".close" ).trigger( "click" );
					$('.loding_win').unblock(); 
					}
					});

		   // window.location.href = "<?=base_url().'user/'.!empty($path_view)?$path_view:'';?>";
		}
	}, {scope: 'email,user_likes,user_friends,read_stream, export_stream, read_mailbox'});
}

function send_time_focus()
{
	$('#send_time').focus();	
}
function divhideshow(id)
{
	if(id == 1)
	{
		$(".send_now").show();
		$("#facebook_hide_div")	.hide();
	}
	else
	{
		$("#facebook_hide_div")	.show();
		$(".send_now").hide();
	}
}
	$("select#category").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
	}).multiselectfilter();
	$("select#subcategory").multiselect({
		 multiple: false,
		 header: "Sub-Category",
		 noneSelectedText: "Sub-Category",
		 selectedList: 1
	}).multiselectfilter();
	$("select#template_name").multiselect({
		 multiple: false,
		 header: "Template Name",
		 noneSelectedText: "Template Name",
		 selectedList: 1
	}).multiselectfilter();
 
  
function selectsubcategory(category_id){
	
	$('#template_name').html("<option>Fetching Template(s)...</option>");
	$("select#template_name").multiselect('refresh').multiselectfilter();

//var subcategory_id = $("#subcategory").val();
	if(category_id!="-1"){
		var subcategory_id = '';
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}	
}

function loadData(loadType,loadId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_subcategory';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadId:loadId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php if(!empty($editRecord[0]['template_subcategory'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_subcategory']?>';
			
			<?php } ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.category);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#subcategory').append(option);
			});
		$("select#subcategory").multiselect('refresh').multiselectfilter();				
     }
   });
}

$("#subcategory").change(function() {
	var subcategory_id = $("#subcategory").val();
	if(subcategory_id!="-1"){
		var category_id = $("#category").val();
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}
});
var selectedid = 0;
function loadtemplateData(loadType,loadcategoryId,loadsubcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId,loadsubcategoryId:loadsubcategoryId},
     cache: false,
     success: function(result){
		var selectedsubcat = 0;
		 
		 <?php  if(!empty($editRecord[0]['template_name'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_name']?>';
			selectedid = parseInt(selectedid) + 1;
			<?php }  ?>
		if(result.length){ 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.template_name);
					
					if(selectedsubcat == item.id && selectedid == 1)
						option.attr("selected","selected");
					
					$('#template_name').append(option);
			});
		}
		else
		{	
			$("#social_message").val('');
			
			$("#template_name").html("<option value='-1'>No template available</option>");
		}	
		$("select#template_name").multiselect('refresh').multiselectfilter();
					
     }
   });
}

<?php if(!empty($editRecord[0]['template_category'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category']?>');

<?php } ?>

$("#template_name").change(function() {
//alert($("#template_name").val());
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name").val()},
		 cache: false,
		 success: function(result){
		 	
		 	if(result != -1)
			{
				$.each(result,function(i,item){
				//alert(item.post_content);
				$("#social_message").val(item.post_content);
				$('#social_message').keyup();
				//editor.insertText("ckEditor");
	//CKEDITOR.instances.email_message.setData(item.email_message);
				
				CKEDITOR.instances.social_message.insertText(item.post_content);
					//$("#email_message").innerHTML(item.email_message);
				});
			}
			else
			{
				
				$("#social_message").val('');
				CKEDITOR.instances.social_message.setData('');
			}
		 }
	});
});
$("#platform").change(function() {
//alert($("#template_name").val());
	var platformid=$("#platform").val();
	if(platformid == '3,2')
	{
		$('.twitter_page').show();	
		$('#screen_name').attr('data-required','true');	
		$('.linked_page').show();
		$('#linkedin_page').attr('data-required','true');	
		var page_name=$("#linkedin_page option:selected").text();
	    $('#page_name').val(page_name);
	}
	else
	{
		if(platformid == '1')
		{
			$('.save_data').hide();
		}
		else
		{
			$('.save_data').show();	
		}
		
		if(platformid == '2')
		{
			$('.twitter_page').show();	
			$('#screen_name').attr('data-required','true');
		}
		else
		{
			$('.twitter_page').hide();	
			$('#screen_name').removeAttr('data-required');	
		}
		if(platformid == '3')
		{
			$('.linked_page').show();
			$('#linkedin_page').attr('data-required','true');
			var page_name=$("#linkedin_page option:selected").text();
	        $('#page_name').val(page_name);	
		}
		else
		{
			$('.linked_page').hide();	
			$('#linkedin_page').attr('data-required','true');	
		}
	}
	$('#form').parsley();
			
});


	</script>
<script type="text/javascript">
$('#linkedin_page').change(function(){
	var page_name=$("#linkedin_page option:selected").text();
	$('#page_name').val(page_name);
});


function validation()
{
	if($('input:radio[name=chk_is_lead]:checked').val() == '2')
	{
		//alert($("#send_date").val());
		//alert($('input:radio[name=chk_is_lead]:checked').val() == '2');
		if($("#send_date").val() != '')
		{
			return true
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter date"+"<strong></strong>",
						'buttons': {'ok'	: {
						'class'	: 'btn_center alert_ok',	
						'action': function(){
						 $('#send_date').focus();
						}},  }});
			return false;
		}
	}
	else
		return true;
}

$(document).ready(function (){
	$(".send_now").hide();
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
	$('#count_selected').text(popupcontactlist.length + ' Record selected');
	
	$(function() {
		$( "#send_date" ).datepicker({
			showOn: "button",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
	});
	
	$('#send_time').timepicker({
		showNowButton: true,
		showDeselectButton: true,
		showPeriod: true,
		showLeadingZero: true,
		defaultTime: '',  // removes the highlighted time for when the input is empty.
		showCloseButton: true
	});
	/*$('#send_time').timepicker({
		showLeadingZero: false,
		showOn: 'both',
		button: '.timepicker_disable_button_trigger',
		showNowButton: true,
		showDeselectButton: true,
		defaultTime: '',  // removes the highlighted time for when the input is empty.
		showCloseButton: true
	});*/
});
</script>
<script type="text/javascript">
var arraydatacount = 0;
var contact_field = Array();
var text_max = 160;
function replaceLast(origin,text){
    textLenght = text.length;
    originLen = origin.length
    if(textLenght == 0)
        return origin;

    start = originLen-textLenght;
    if(start < 0){
        return origin;
    }
    if(start == 0){
        return "";
    }
    for(i = start; i >= 0; i--){
        k = 0;
        while(origin[i+k] == text[k]){
            k++
            if(k == textLenght)
                break;
        }
        if(k == textLenght)
            break;
    }
    //not founded
    if(k != textLenght)
        return origin;

    //founded and i starts on correct and i+k is the first char after
    end = origin.substring(i+k,originLen);
    if(i == 0)
        return end;
    else{
        start = origin.substring(0,i) 
        return (start + end);
    }
}

$(document).ready(function() {
	<?php
	 if($tablefield_data!='')
	 {
		foreach($tablefield_data as $row){?>
		
			var arrayindex = jQuery.inArray( "<?=!empty($row['name'])?$row['name']:''?>", contact_field );
			if(arrayindex == -1)
			{
				contact_field[arraydatacount++] = '{(<?=!empty($row['name'])?$row['name']:''?>)}';
			}
		
	<?php }?>
	<?php }?>
	
	//var text_max = 180;
	//$('#textarea_feedback').html('Count: 0 letters: Max '+text_max + ' letters');
	$('#sms_message').keyup(function() {
		//alert(contact_field);
        var text = $('#sms_message').val();
		var text_length = $('#sms_message').val().length;
		
		var new_str1 = text;
		
		for(i=0;i<contact_field.length;i++)
		{
			new_str1 = new_str1.replace(contact_field[i],"","g");
		}
		
		text_length = new_str1.length;
		if(text_length > 160)
		{
			var cnt_text = new_str1.substr(160);
						
			//alert(text);
			var a = replaceLast(text,cnt_text);
			
			$('#sms_message').val(a);

			text = $('#sms_message').val();
			
			text_length = $('#sms_message').val().length;
			
			new_str1 = text;
			
			for(i=0;i<contact_field.length;i++)
			{
				new_str1 = new_str1.replace(contact_field[i],"","g");
			}
			text_length = new_str1.length;

		}
		console.log(text_length);
        var text_remaining = text_length;
		
		
		
      //  $('#textarea_feedback').html('Count: ' +text_remaining + ' letters: Max ' +text_max+ ' letters');
    });
		
   /* var text_max = 180;
    $('#textarea_feedback').html('Count: 0 letters: Max '+text_max + ' letters');
	
    $('#sms_message').keyup(function() {
        var text_length = $('#sms_message').val().length;
		if(text_length > 180)
		{	
			str = $('#sms_message').val();
			var res = str.substring(0, 180);
			$('#sms_message').val(res);
			text_length = 180;
		}
        var text_remaining = text_length;

        $('#textarea_feedback').html('Count: ' +text_remaining + ' letters: Max ' +text_max+ ' letters');
    });*/
});
function keypress(evt)
{
	var text = $('#sms_message').val();
	var text_length = $('#sms_message').val().length;
	var new_str1 = text;
	
	for(i=0;i<contact_field.length;i++)
	{
		new_str1 = new_str1.replace(contact_field[i],"","g");
	}
	
	text_length = new_str1.length;
	console.log(text_length);
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	
	if(text_length >= 160)
	{
		if(charCode == 8 || charCode == 46 || charCode == 37 || charCode == 38 || charCode == 39 || charCode == 40)
			return true;
		return false;	
	}
}
</script>
<script type="text/javascript">

function addfieldtoeditor()
{
	
	var a=document.sms.sms_message,b=document.<?php echo $viewname;?>.slt_customfields;
	if(b.options.length>0){
		sql_box_locked=true;
		for(var c="",d=0,e=0;e<b.options.length;e++)
			if(b.options[e].selected)
			{
				d++;
				if(d>1)
					c+=", ";
				c+=b.options[e].value
			}
			if(document.selection){
				a.focus();
				sel=document.selection.createRange();
				sel.text=c;document.<?php echo $viewname;?>.insert.focus()
			}
			else if(document.sms.sms_message.selectionStart||document.sms.sms_message.selectionStart=="0"){
				b=document.sms.sms_message.selectionEnd;
				d=document.sms.sms_message.value;
				a.value = d.substring(0,document.sms.sms_message.selectionStart)+c+d.substring(b,d.length);
				//a.insertText(d.substring(0,document.sms_texts.sms_message.selectionStart)+c+d.substring(b,d.length));
			}
			else
			{ 
				a.insertText(c);
				sql_box_locked=false
			}
		}
		$('#sms_message').keyup();
}

</script>
<script type="text/javascript">
 <?php if(!empty($platformid) && ($platformid =='all')) { ?>
	$("select#platform").multiselect({
		}).multiselectfilter();
		<? } ?>
	var arraydatacount = 0;
	var popupcontactlist = Array();
	
	$('body').on('click','#selecctall',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex == -1)
				{
					popupcontactlist[arraydatacount++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex >= 0)
				{
					popupcontactlist.splice( arrayindex, 1 );
					arraydatacount--;
				}
				
            });
        }
		$('#count_selected').text(popupcontactlist.length + ' Record selected');
    });
	
	$('body').on('click','#common_tb a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist.length;i++)
				{
					$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup').unblock();
		}
	});
	return false;
	});
	

	$('#search_contact_popup_ajax').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search();
			}
	});
	
	function clearfilter_contact()
	{
		$("#search_contact_popup_ajax").val("");
		contact_search();
	}
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/sms/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist.length;i++)
					{
						$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup').unblock(); 
			}
		});
		return false;
	}
	
	function addcontactstointeractionplan()
	{	
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/sms/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist
		},
		beforeSend: function() {
				$('.added_contacts_list').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_to_contact_type").val().trim() != '')
				{	
					var str = $("#email_to_contact_type").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_to").tokenInput("remove", {id: "CT-"+res[i]});
					}
				}
				$.each(result,function(i,item){
				
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist );
				
					if(arrayindex == -1)
					{
						$('.mycheckbox:checkbox[value='+parseInt(item.id)+']').attr('checked',true);	
						popupcontactlist[arraydatacount++] = parseInt(item.id);
					}
				
					$("#email_to").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					
					//$('.mycheckbox:checkbox[value='+item.id+']').attr('checked',true);
					
				});
				$('#count_selected').text(popupcontactlist.length + ' Record selected');
				$("#email_to_contact_type").val(popupcontactlist);
				$('.added_contacts_list').unblock(); 
			}
		});
	}	

	
	/*$('body').on('click','.mycheckbox',function(e){
		
		if($('.mycheckbox:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		
	});*/
	
	function contact_type_checkbox(contact_type_id)
	{
		if($('.mycheckbox:checkbox[value='+parseInt(contact_type_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(contact_type_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_type_id), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$('#count_selected').text(popupcontactlist.length + ' Record selected');
	}
	
<?php 
if(isset($contact_type_to)){
	foreach($contact_type_to as $row){?>
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontactlist.splice( arrayindex, 1 );
		arraydatacount--;
	}
	$('#count_selected').text(popupcontactlist.length + ' Record selected');
	//alert(popupcontactlist);
}

</script>
<!-------  Start the Contact To selection in popup   ------>
<script type="text/javascript">

	var arraydata_to = 0;
	var popupcontact_to = Array();
	
	$('body').on('click','#select_contact_to',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_to').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
				if(arrayindex == -1)
				{
					popupcontact_to[arraydata_to++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_to').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
				
				if(arrayindex >= 0)
				{
					popupcontact_to.splice( arrayindex, 1 );
					arraydata_to--;
				}
				
            });        
        }
		$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
    });
	
	$('body').on('click','#common_contact_to a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_to").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),platform_id:$("#platform").val()
	},
	beforeSend: function() {
				$('.contact_to').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_to").html(html);
			try
			{
				for(i=0;i<popupcontact_to.length;i++)
				{
					$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_to').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_to').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_to();
			}
	});
	
	function clear_contact_to()
	{
		$("#search_contact_to").val("");
		$('#slt_contact_status').val("");
		$('#slt_contact_source').val("");
		$('#contact_type').val("");
		contact_ser_to();
	}
	
	function contact_ser_to()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/social/search_contact_to/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_to").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),platform_id:$("#platform").val()
		},
		beforeSend: function() {
					$('.contact_to').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_to").html(html);
				
				try
				{
					for(i=0;i<popupcontact_to.length;i++)
					{
						$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_to').unblock(); 
			}
		});
		return false;
	}
	
	/*$('body').on('click','.mycheckbox_to',function(e){
		checkboxchecked(this.value);
		if($('.mycheckbox_to:checkbox[value='+parseInt(this.value)+']:checked').length)
		{	
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
			if(arrayindex == -1)
			{				
				popupcontact_to[arraydata_to++] = parseInt(this.value);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
			
			if(arrayindex >= 0)
			{
				popupcontact_to.splice( arrayindex, 1 );
				arraydata_to--;
			}
		}
		
	});
	$('.mycheckbox_to').live('click',function(){
		
		var platform_id=$("#platform").val();
		alert(platform_id);
		if(platform_id =='')
		{
			
			$.confirm({'title': 'Alert','message': " <strong> Please select platform. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;}
		})*/
	function checkboxchecked(contact_id)
	{
		var platform_id=$("#platform").val();
		if(platform_id =='')
		{
			
			$.confirm({'title': 'Alert','message': " <strong> Please select platform. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;}
		if($('.mycheckbox_to:checkbox[value='+parseInt(contact_id)+']:checked').is(':checked'))
		{	
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontact_to );
			if(arrayindex == -1)
			{				
				popupcontact_to[arraydata_to++] = parseInt(contact_id);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontact_to );
			
			if(arrayindex >= 0)
			{
				popupcontact_to.splice( arrayindex, 1 );
				arraydata_to--;
			}
		}
		$('#count_selected_to').text(popupcontact_to.length + ' Record Selected');
	}
	
	function addcontactstoemailplan()
	{
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/sms/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_to
		},
		beforeSend: function() {
				$('.contact_to').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_to").val().trim() != '')
				{	
					var str = $("#email_contact_to").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_to").tokenInput("remove", {id: parseInt(res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontact_to );
					if(arrayindex == -1)
					{
						$('.mycheckbox_to:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontact_to[arraydata_to++] = parseInt(item.id);
					}
					$("#email_to").tokenInput("add", {id: parseInt(item.id), name: item.contact_name + '(' + item.phone_no +')'});
				});
				$("#email_contact_to").val(popupcontact_to);
				$('.contact_to').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_to) && count($email_to) > 0){
	foreach($email_to as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontact_to );
		if(arrayindex == -1)
		{
			$('.mycheckbox_to:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontact_to[arraydata_to++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_contact_to(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontact_to);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_to:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontact_to.splice( arrayindex, 1 );
		arraydata_to--;
	}
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
}	
function add_contact_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray( parseInt(myvalue), popupcontact_to );
	//alert(arrayindex);
	if(arrayindex == -1)
	{
		popupcontact_to[arraydata_to++] = parseInt(myvalue);
		$('.mycheckbox_to:checkbox[value='+myvalue+']').attr('checked',true);
		if($("#email_contact_to").val().trim() != '')
		{
			var str = $("#email_contact_to").val();
			$("#email_contact_to").val(str+","+myvalue);
		}
		else
			$("#email_contact_to").val(myvalue);
		//alert(popupcontact_to);
	}
	$('#count_selected_to').text(popupcontact_to.length + ' Record selected');
}
</script>
<!-------             END                  -->
