<?php
/*
    @Description: Template add/edit page
    @Author: Mit Makwana
    @Date: 12-08-2014

*/?>
<?php 

header('Cache-Control: max-age=900');

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
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Script</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup">
		 <div id="row_data">
         </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('mail_out_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('mail_out_header');}
	   else if(!empty($insert_data)){ echo $this->lang->line('mail_out_header'); } 
	   else{ echo $this->lang->line('mail_out_header'); }?>
            </h3>
            <span class="float-right margin-top--15"><a id="back" title="Back" href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary">Back</a> </span>
          </div>
          <div class="portlet-content">
		  
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
			  
                <div class="row tab-pane fade in active" id="home">
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').'mail_out/mail_out_print'?>"  data-validate="parsley" novalidate>
                  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
				  	
                  	<div class="col-sm-8">
						<div class="row form-group">
							<label for="text-input">Mail Out Type&nbsp;<span class="valid">*</span></label>
							<select class="form-control parsley-validated" name='mail_out_type' id='mail_out_type' data-required="true">
							<option value="">Select Mail Out Type</option>
                                <? if(in_array('letter_add',$this->modules_unique_name)){ ?>
								<option value="Letter" <?php if(!empty($template_type) && $template_type == 'Letter') echo 'selected=selected'; ?> >Letter</option>
                                <? } ?>
								 <? if(in_array('envelope_add',$this->modules_unique_name)){ ?>
                                <option value="Envelope" <?php if(!empty($template_type) && $template_type == 'Envelope') echo 'selected=selected'; ?> >Envelope</option>
                                <? } ?>
                                 <? if(in_array('label_add',$this->modules_unique_name)){ ?>
								<option value="Label" <?php if(!empty($template_type) && $template_type == 'Label') echo 'selected=selected'; ?> >Label</option>
                                <? } ?>
							</select>
						</div>
						<div class="row">
						  
                          <div class="">
						  <label for="text-input">
							<?=$this->lang->line('common_label_category');?>
							</label>
							<select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)">
							  <option value="">Category</option>
							  <?php if(isset($category) && count($category) > 0){
								foreach($category as $row1){
									if(!empty($row1['id'])){?>
							  <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['category']);?></option>
							  <?php 		}
								}
							} ?>
							</select>
							
						  
						 <!-- <div class="col-sm-6">
						  
							<select class="selectBox" name='slt_subcategory' id='subcategory'>
							</select>
							<span id="category_loader"></span> </div>-->
                          
						</div>
                        </div>
						<div class="row form-group">
							<label for="text-input">Template&nbsp;<!--<span class="valid">*</span>--></label>
							<select id="template" name="template" class="form-control parsley-validated">
							   <option value="">Select Template</option>
							   <?php 
/*							   
							   if(!empty($template_data) && count($template_data) > 0){
									foreach($template_data as $envelope_row){
									
									?>
							  		<option value="<?php echo $envelope_row['id'];?>" <?php if(!empty($envelope_row['template_name']) && $envelope_row['template_name'] == $template_name){ echo "selected=selected"; } ?>>
										<?php echo ucwords($envelope_row['template_name']);?>
									</option>
							  		<?php 
									}
								}*/ ?>
							</select>
						</div>
						<div class="row">
							<label for="text-input">Sort By</label>
							<select id="sort_by" name="sort_by" class="form-control parsley-validated" data-required="true" onchange="contact_search()">
							   <option value="first_name">First Name</option>
							   <option value="last_name">Last Name</option>
							   <option value="company_name">Company Name</option>
							</select>
						</div>
                     <div class="row envelope_label">
                        <div class="form-group">
                            <label for="text-input"><?=$this->lang->line('size_type');?>&nbsp;<span class="valid">*</span></label>
                            <select class="radio_box form-control parsley-validated" name='template_type_radio' id='template_type_radio' data-required="true">
                                
                                <option value="1" <?php if(!empty($editRecord[0]['template_type']) && $editRecord[0]['template_type'] == '1'){ echo 'selected="selected"'; }?> ><?=$this->lang->line('template_type_fix');?></option>
                                <option value="2" <?php if(!empty($editRecord[0]['template_type']) && $editRecord[0]['template_type'] == '2'){ echo 'selected="selected"'; }?> ><?=$this->lang->line('template_type_custom');?></option>
                                
                            </select>
                        </div>
                  	</div>
                    <div class="row">
                      <div class="form-group" id="fix">
                          <label for="text-input"><?=$this->lang->line('size_label_name');?>&nbsp;<span class="valid">*</span></label>
                          <select class="selectBox" name='template_size_id' id='template_size_id'>
                              <option value="1">10</option>
                          </select>
                      </div>
                    </div>
                    <div class="row" id="fix1">
                    	<div class="">
                            <label for="text-input">
                            <?=$this->lang->line('size_label_name');?>&nbsp;<span class="valid">*</span> <!--(In Inch) (In Inch Between 1 To 22) (e.g. 1.4564 Or 15.2145 Or 16)-->
                            </label>
                      	</div>
                        <div class="form-group">
                              <select name='size_type' id='size_type' class="form-control selectBox  parsley-validated"  data-required="true">
                                <option value="">Select Size</option>
                                  <option value="1" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='1')){echo "Selected"; }?> >Avery 5159 ( 4" x 1.5" )</option>
                                  <option  value="2" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='2')){echo "Selected"; }?>>Avery 5160 ( 2.62" x 1" )</option>
                                  <option value="3" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='3')){echo "Selected"; }?>>Avery 5161 ( 4" x 1" )</option>
                                  <option value="4" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='4')){echo "Selected"; }?>>Avery 5162 ( 4" x 1.33" )</option>
                                  <option value="5" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='5')){echo "Selected"; }?> >Avery 5163 ( 4" x 2" )</option>
                                  <option value="6" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='6')){echo "Selected"; }?>>Avery 5164 ( 4" x 3.33" )</option>    
                              </select>
                          </div>
                    </div>
                    <div class="row" id="custom">
                      <div class="">
                        <label for="text-input">
                        <?=$this->lang->line('size_label_name');?>&nbsp;<span class="valid">*</span> (In Inch Between 1 To 22) (e.g. 1.4564 Or 15.2145 Or 16)
                        </label>
                      </div>
					  
                     	<div class="col-sm-5 form-group">
                        <input id="txt_size_w" placeholder="Width" class="form-control parsley-validated"  onkeypress="return isNumberKeyDecimal(event)" name="txt_size_w" type="text" value="<?php if(!empty($editRecord[0]['size_w'])){ echo $editRecord[0]['size_w']; }?>" data-required="true" >
                      </div>
					   	<div class="col-sm-1 text-center">
					   		<strong>X</strong>
					   </div>
                      	<div class="col-sm-6 form-group">
                        	<input type="text" id="txt_size_h" placeholder="Height"  name="txt_size_h" onkeypress="return isNumberKeyDecimal(event)" class="form-control parsley-validated"  value="<?php if(!empty($editRecord[0]['size_h'])){ echo $editRecord[0]['size_h']; }?>" data-required="true">
                        	<span id="category_loader"></span>
						</div>
					  
                    </div>
					<div class="row">
                      <div class="col-sm-12 topnd_margin1"><div class="row"> <strong class="assign_title">Assign Contacts </strong> <a href="#basicModal" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contacts</a> </div></div>
                      <div class="col-sm-12 added_contacts_list"><div class="row">
						<?php $this->load->view('admin/mail_out/selected_contact_ajax')?>
                      </div></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                      </div>
                    </div>
                  </div>
               	  <div class="clear">
                  <div class="row">
					
                    <div class="col-sm-8 form-group">
                      <label for="select-multi-input">
                      Script &nbsp;<span class="valid">*</span>
                      </label>
                      <textarea name="message_content" class="form-control parsley-validated" id="message_content"><?=!empty($editRecord[0]['envelope_content'])?$editRecord[0]['envelope_content']:'';?> <?=!empty($editRecord[0]['label_content'])?$editRecord[0]['label_content']:'';?> <?=!empty($editRecord[0]['letter_content'])?$editRecord[0]['letter_content']:'';?>
</textarea>
                      <script type="text/javascript">
												CKEDITOR.replace('message_content',
												 {
													fullPage : false,
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});
																										
											</script>
                    </div>
                    
                    <div class="col-sm-4">
            
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
             <option title="<?php echo $fieldval;?>" value="<?php echo sprintf($pattern, $fieldval);?>"> <?php echo ucwords($fieldval);?> </option>
             																<?php }
													} ?>
             															<?php } ?>
            </select>
                 <input class="btn btn-secondary" type="button" title="Insert Field" name="submitbtn1" onclick="addfieldtoeditor();" value="Insert Field">
            </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
						<input id="template_type" name="template_type" type="hidden" value="Envelope">
                      </div>
                    </div>
                  </div>
                  </div>
                    
                  </div>
             </div>
                  </div>
                  <div class="col-sm-12 pull-left text-center margin-top-10">
                    <!--<input type="hidden" id="contacttab" name="contacttab" value="1" />-->
                    <a class="btn btn-secondary view_form_btn" data-toggle="modal" title="Preview"  href="#basicModal1" data-id="<?php echo  $row['id'] ?>">Preview</a>
                    <input type="submit" class="btn btn-secondary-green" value="Print"  name="submitbtn" id="mo_submitbtn" onclick="return checkcontactcount();"/>
					<input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" />
                    
                    <!--<input type="reset" class="btn btn-primary" value="Reset"  name="submitbtn" /> -->
                    <a href="javascript:history.go(-1);" class="btn btn-primary" id="mo_cancel">Cancel</a>
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
<?php
// print_r($subcategory);
 ?>
 <!-- Assign To Contact Lead START-->
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Contact Select</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="#"  onclick="contact_search();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type' id='contact_type' onchange="contact_search();">
                	<option value="">Contact Type</option>
                    <?php if(!empty($contact_type)){
                    		foreach($contact_type as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='slt_contact_source' id='slt_contact_source' onchange="contact_search();">
                	<option value="">Contact Source</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status" onchange="contact_search();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div>
        
        <div class="con_srh clear">
        <div class="col-sm-3">Search Tag</div>
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_tag" class="form-control inputsrh pull-left" name="search_tag">
            <script type="text/javascript">
			 $(document).ready(function() {
				$("#search_tag").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: '<?=$row['tag']?>', name: "<?=$row['tag']?>"},
					<?php } } ?>
				],
				{onAdd: function (item) {
					contact_search();
				},onDelete: function (item) {
					contact_search();
				},
				preventDuplicates: true,
				hintText: "Enter Tag Name",
                noResultsText: "No Tag Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
			});
			</script>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_to"></label> | 
              <a class="text_color_red text_size add_email_address" onclick="remove_selection_to();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="">
		  <?php $this->load->view('admin/mail_out/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- Assign To Contact Lead END-->
<script type="text/javascript">
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
	$("select#template_size_id").multiselect({
		 multiple: false,
		 header: "",
		 noneSelectedText: "",
		 selectedList: 1
	}).multiselectfilter();
 
function isNumberKeyDecimal(evt)
{
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31 
	&& (charCode < 48 || charCode > 57))
	 return false;

  return true;
}
  
function selectsubcategory(id){
 if(id!="-1"){
   //$("#subcategory").html("<option value='-1'>Sub-Category</option>");
   $('#mail_out_type').change();
 }else{
   $("#template").html("<option value='-1'>Select Template</option>");
   $("select#template").multiselect('refresh').multiselectfilter();
   
 }
}

function loadData(loadType,loadId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_subcategory';?>",
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
		$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
    });
	
	$('body').on('click','#common_tb a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),sortfield:$("#sort_by").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),perpage:$("#perpage").val(),search_tag:$("#search_tag").val()
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
		$("#search_tag").tokenInput("clear");
		$("#search_contact_popup_ajax").val("");
		$('#slt_contact_status').val("");
		$('#slt_contact_source').val("");
		$('#contact_type').val("");
		$('#sortfield').val("");
		$('#sortby').val("");
		$("#perpage").val('');
		contact_search();
	}
	
	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		contact_search();
	}
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/mail_out/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),sortfield:$("#sort_by").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),perpage:$("#perpage").val(),search_tag:$("#search_tag").val()
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
			url: "<?php echo base_url();?>admin/mail_out/add_contacts_to_mail_out/",
			data: {
			result_type:'ajax',contacts:popupcontactlist
		},
		beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
				  },
			success: function(html){
				
				$(".added_contacts_list").html(html);
				$('.added_contacts_list').unblock(); 
			}
		});
	}	
	
	
	
	
$(document).ready(function(){
	$(".envelope_label").hide();
	$("#custom").hide();
	$("#fix").hide();
	$("#fix1").hide();
	$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
	
	$('#mail_out_type').change(function(){ 
			var mail_type=$('#mail_out_type').val();
			if(mail_type == 'Envelope')
			{
				$(".envelope_label").show();
				$('.radio_box').change();
				var pro_url = "<?php echo $this->config->base_url(); ?>admin/mail_out/get_envelope/"+mail_type+"/";
			}else if(mail_type == 'Label'){
				$(".envelope_label").show();
				$('.radio_box').change();
				var pro_url = "<?php echo $this->config->base_url(); ?>admin/mail_out/get_envelope/"+mail_type+"/";
			}else{
				$(".envelope_label").hide();
				$('.radio_box').change();
				var pro_url = "<?php echo $this->config->base_url(); ?>admin/mail_out/get_envelope/"+mail_type+"/";
			}
			
			if($('#mail_out_type').val() != '')
			{	
				var selectedtemplate = 0;
				$('#template').html("<option>Fetching Template(s)...</option>");
				$.ajax({  
					type: "post",
					url:pro_url,
					data: {'template_type':mail_type,slt_category:$("#category").val()},
						success: function(data)
						{	
							//alert(data);
							if(data.length != 0)
							{
								var myObject = eval(data);
								var html = '<option value="">Select Template</option>';
								<?php if(!empty($template_name)){ ?>
									selectedtemplate = '<?=$template_name?>';	
								<?php } ?>
								for (var i=0;i< myObject.length;i++)
								{
									if(selectedtemplate == myObject[i].template_name)
										html += '<option value="'+myObject[i].id+'" selected=selected>'+myObject[i].template_name+'</option>';
									else
										html += '<option value="'+myObject[i].id+'">'+myObject[i].template_name+'</option>';
								} 
							}
							else
								var html = '<option value="">Select Template</option>';
							$('#template').html(html);
						} 
				}); 
			}
			
	}).change();
	$('.radio_box').change(function(){
	
	 var val = $('#template_type_radio').val();
	 var mail_type = $('#mail_out_type').val();
	 if(mail_type.trim() != '')
	 {
		 $('#size_type').attr('disabled','disabled');
		 $('#txt_size_h').attr('disabled','disabled');
		 $('#txt_size_w').attr('disabled','disabled');
		  if(val == 1 && mail_type != 'Letter')
		  {
			if(mail_type == 'Label')
			{
				$("#fix").hide();
				$("#fix1").show();
				$('#size_type').removeAttr('disabled');
			}
			else
			{
				$("#fix1").hide();
				$("#fix").show();
			}
			$("#custom").hide();
			$("#<?=$viewname?>").parsley().destroy();
			$("#<?=$viewname?>").parsley();
		  }
		  else
		  {
			$("#fix").hide();
			$("#fix1").hide();
			$("#custom").show();
			$('#txt_size_w').removeAttr('disabled');
			$('#txt_size_h').removeAttr('disabled');
			$("#<?=$viewname?>").parsley().destroy();
			$("#<?=$viewname?>").parsley();
		  }
	 }
    }).change();
	/*function myonchangefunc()
	{
		//alert(1);
			var mail_type=$('#mail_out_type').val();
			
			if(mail_type == 'Envelope')
			{
				var pro_url = "<?php echo $this->config->base_url(); ?>admin/mail_out/get_envelope/"+mail_type+"/";				
			}else if(mail_type == 'Label'){
				var pro_url = "<?php echo $this->config->base_url(); ?>admin/mail_out/get_envelope/"+mail_type+"/";
			}else{
				var pro_url = "<?php echo $this->config->base_url(); ?>admin/mail_out/get_envelope/"+mail_type+"/";
			}
			
			if($('#mail_out_type').val() != '')
			{	
				$.ajax({  
					type: "post",
					url:pro_url,
					data: {'template_type':mail_type},
						url:pro_url,				
						success: function(data)
						{	
							//alert(data);
							if(data.length != 0)
							{
								var myObject = eval(data);
								var html = '<option value="">Select Template</option>';
								for (var i=0;i< myObject.length;i++)
								{
									html += '<option value="'+myObject[i].id+'">'+myObject[i].template_name+'</option>';
								} 
							}
							else
								var html = '<option value="">Select Template</option>';
							$('#template').html(html);
						} 
				}); 
			}
	}*/
	//alert('load');
	//myonchangefunc();
	
	//$('#mail_out_type').trigger("change");
	
	});
	
	/*$('body').on('click','.mycheckbox',function(e){
		
		if($('.mycheckbox:checkbox[value='+this.value+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( this.value, popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = this.value;
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( this.value, popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		
	});*/
	
	function checkbox_checked(contact_id)
	{
		if($('.mycheckbox:checkbox[value='+parseInt(contact_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(contact_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
	}
	
	//function removeempfromlist(contactid)
	$('body').on('click','.remove_selected_contact',function(e){
	
		var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
		//alert(this.value+'-'+JSON.stringify(popupcontactlist));
		//alert(arrayindex);
		/**/
		var btn = this;
		var id = this.value;
		var msg = 'Are you sure want to delete record';
		if(arrayindex >= 0)
		{
	
			$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '','action': function(){
	   						$('.mycheckbox:checkbox[value='+id+']').attr('checked',false);
							popupcontactlist.splice( arrayindex, 1 );
							arraydatacount--;
							$(btn).closest("tr").remove();
							$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
							//alert(this);
							//deletedata(arrayindex,id);

						}},'No'	: {'class'	: 'special'}}});
		}
		return false;
	});
	
	function checkcontactcount()
	{
		//alert(JSON.stringify(popupemplist));
		var ck_edit = CKEDITOR.instances.message_content.getData();
		/*if($("#category").val().trim() == '')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select category."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;		
		}
		else */
		if($('#<?php echo $viewname?>').parsley().isValid() && ck_edit.trim() == '')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter script."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
		else
		{
                    if ($('#<?php echo $viewname?>').parsley().isValid()) {
                        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                        /*$('#mo_submitbtn').attr('disabled','disabled');
                        $('#mo_cancel').attr('disabled','disabled');*/
                    }
			$('#finalcontactlist').val(popupcontactlist);
			return true;
		}
	}

<?php if(!empty($editRecord[0]['template_category'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category']?>');

<?php } ?>
$('#template').change(function(){
	$.ajax({  
		type: "post",
		dataType: 'json',
		url:"<?=$this->config->item('admin_base_url')?>mail_out/ajax_templatename/",
		data: {'template_type':$('#mail_out_type').val(),template_id:$("#template").val()},
			success: function(result)
			{
				if(result != -1)
				{
					$.each(result,function(i,item){
						if($('#mail_out_type').val() == 'Letter')
							CKEDITOR.instances.message_content.setData(item.letter_content);
						else if($('#mail_out_type').val() == 'Envelope')
							CKEDITOR.instances.message_content.setData(item.envelope_content);
						else if($('#mail_out_type').val() == 'Label')
							CKEDITOR.instances.message_content.setData(item.label_content);
						$('#txt_size_w').val(item.size_w);
						$('#txt_size_h').val(item.size_h);
						//alert(item);
					});
				}
			} 
	});
});

$('body').on('click','.view_form_btn',function(e){
	$("#row_data").html(CKEDITOR.instances.message_content.getData());
});
</script>
<script type="text/javascript">

function addfieldtoeditor()
{
	var a=CKEDITOR.instances['message_content'],b=document.<?php echo $viewname;?>.slt_customfields;
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
			else if(CKEDITOR.instances['message_content'].selectionStart||CKEDITOR.instances['message_content'].selectionStart=="0"){
				b=CKEDITOR.instances['message_content'].selectionEnd;
				d=CKEDITOR.instances['message_content'].value;
				a.insertText(d.substring(0,CKEDITOR.instances['message_content'].selectionStart)+c+d.substring(b,d.length));
			}
			else
			{ 
				a.insertText(c);
				sql_box_locked=false
			}
	}
}
</script>
