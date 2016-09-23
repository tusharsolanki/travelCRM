<?php
/*
    @Description: Joomla Dashboard
    @Author     : Sanjay Moghariya
    @Date       : 14-11-2014

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
<?php if($this->uri->segment(4) == 'iframe'){ ?>
#sidebar{ display:none;}
#header,#site-logo,.dropdown,#footer,#back{ display:none !important;}
#content{ margin-left:0;}
<?php } ?>
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
      <?=$this->lang->line('phonecallscript_header');?>
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php if(empty($editRecord)){ echo $this->lang->line('label_templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('label_templete_add_head'); } 
	   else{ echo $this->lang->line('label_templete_edit_head'); }?>
            </h3>
				<span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back" id="back">Back</a> </span>
          </div>
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
                <div class="row tab-pane fade in active" id="home">
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
                  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                  <div class="col-sm-8">
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="text-input">
                        <?=$this->lang->line('template_label_name');?>&nbsp;<span class="valid">*</span>
                        </label>
                        <input id="txt_template_name" name="txt_template_name" placeholder="e.g. Template Name" class="form-control parsley-validated" type="text" value="<?php if(isset($insert_data)){
			   if(!empty($editRecord[0]['template_name'])){ echo htmlentities($editRecord[0]['template_name']).'-copy'; }}
			   else
			   {
				   if(!empty($editRecord[0]['template_name'])){ echo htmlentities($editRecord[0]['template_name']); }
			   }
			   ?>" data-required="true">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="text-input">
                        <?=$this->lang->line('common_label_category');?> <span class="val">*</span> 
                        </label>
                      </div>
                      <div class="col-sm-6">
                        <select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)" >
                          <option value="-1">Category</option>
                          <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                          <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['category']);?></option>
                          <?php 		}
							}
						} ?>
                        </select>
                      </div>
                      <!--<div class="col-sm-6">
                        <select class="selectBox" name='slt_subcategory' id='subcategory'>
                        </select>
                        <span id="category_loader"></span> </div>-->
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="text-input">
                        <?=$this->lang->line('size_label_name');?>&nbsp;<span class="valid">*</span> <!--(In Inch) (In Inch Between 1 To 22) (e.g. 1.4564 Or 15.2145 Or 16)-->
                        </label>
                      </div>
                      <div class="col-sm-6 form-group">
                          <select name='size_type' id='size_type' class="form-control selectBox  parsley-validated"  data-required="true">
                          	<option value="">Select Size</option>
                              <option value="1" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='1')){echo "Selected"; }?> >Avery 5159</option>
                              <option  value="2" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='2')){echo "Selected"; }?>>Avery 5160</option>
                              <option value="3" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='3')){echo "Selected"; }?>>Avery 5161</option>
                              <option value="4" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='4')){echo "Selected"; }?>>Avery 5162</option>
                              <option value="5" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='5')){echo "Selected"; }?> >Avery 5163</option>
                              <option value="6" <? if((!empty($editRecord[0]['size_type'])) && ($editRecord[0]['size_type']=='6')){echo "Selected"; }?>>Avery 5164</option>    
                          </select>
                      </div>
                      
                      
                    <!--  <div class="col-sm-5 form-group">
                        <input id="txt_size_w" placeholder="Width" onkeypress="return isNumberKeyDecimal(event)" name="txt_size_w" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['size_w'])){ echo $editRecord[0]['size_w']; }?>" data-required="true">
                      </div>
					  <div class="col-sm-1">
					   		<strong>X</strong>
					   </div>
                      <div class="col-sm-6 form-group">
                        <input id="txt_size_h" placeholder="Height" onkeypress="return isNumberKeyDecimal(event)" name="txt_size_h" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['size_h'])){ echo $editRecord[0]['size_h']; }?>" data-required="true">
                        <span id="category_loader"></span> </div>-->
                    </div>
                    <div class="form-group">
                      <label for="select-multi-input">
                      <?=$this->lang->line('label_templete_title');?>&nbsp;<span class="valid">*</span>
                      </label>
                      <textarea name="label_content" class="form-control parsley-validated" id="label_content" ><?=!empty($editRecord[0]['label_content'])?$editRecord[0]['label_content']:'';?>
</textarea>
                      <script type="text/javascript">
												CKEDITOR.replace('label_content',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                    </div>
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
            
            
                 <input class="btn btn-secondary" type="button" name="submitbtn1" onclick="addfieldtoeditor();" title="Insert Field" value="Insert Field">
               
            </div>
                  
                  
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                      </div>
                    </div>
                  </div>
                  </div>
					
					<input id="template_type" name="template_type" type="hidden" value="Label">
					                  
                  <div class="col-sm-12 pull-left text-center margin-top-10">
                    <!--<input type="hidden" id="contacttab" name="contacttab" value="1" />-->
                    <input type="submit" class="btn btn-secondary" value="Save Label Template" onclick="return setdefaultdata();" name="submitbtn" title="Save Template" />
                    <?php if($this->uri->segment(4) != 'iframe'){ ?>
                    <input type="submit" class="btn btn-secondary" value="Perform Mail Out" onclick="return setdefaultdata();" name="mailout" title="Mail Out" />
                    <?php } if($this->uri->segment(4) == 'iframe'){ ?>
                      	<a class="btn btn-primary" title="Cancel" onclick="close_popup()">Cancel</a>
                    <?php } else { ?>
                    	<a class="btn btn-primary" href="javascript:history.go(-1);" title="Cancel">Cancel</a> </div>
                    <?php } ?>
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
	/*$("select#size_type").multiselect({
		 multiple: false,
		 header: "Size Type",
		 noneSelectedText: "Size Type",
		 selectedList: 1
	}).multiselectfilter();*/
	
	
	 function setdefaultdata()
	 {
	 	
		var ck_edit = CKEDITOR.instances.label_content.getData();
		if(ck_edit == "")
		{
			//alert("Please Enter Templ Detail");
			$.confirm({'title': 'Alert','message': " <strong> Please enter label templates details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
		else if($("#category").val() == '-1')
		{
			//alert("Please Enter Templ Detail");
			$.confirm({'title': 'Alert','message': " <strong> Please select category."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
	 }
 
 /*
function isNumberKeyDecimal(evt)
{
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31 
	&& (charCode < 48 || charCode > 57))
	 return false;

  return true;
}*/
  
function isNumberKeyDecimal(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode > 31	&& (charCode < 48 || charCode > 57)){
			//setTimeout(function() { $('#txt_size_w').focus(); }, 3000);
			return false;
	}
	else
	{
		if($('#txt_size_w').val() != ""){
		$('#txt_size_w').blur(
			function(e) {
			var number_temp = $('#txt_size_w').val();
		
			if(number_temp > 22)
			{
				$.confirm({'title': 'Alert','message': " <strong> Please enter proper width."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#txt_size_w').val('');
				setTimeout(function() { $('#txt_size_w').focus(); }, 3000);
				return false;
			}else
			{
				var number = number_temp.split(".");
				if(e.keyCode==8)
				{}
				else
				{
					if(number.length>0)
					{
							if(((number[0].length > 0) || (number[0].length > 1)) && (number[1].length > 4)){
								$.confirm({'title': 'Alert','message': " <strong> Please enter proper width."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
							$('#txt_size_w').val('');
							setTimeout(function() { $('#txt_size_w').focus(); }, 3000);
							}    
							else{
								return true;
							}
						}
				}
			}
		
		//return false;
	});
	}else{
		$('#txt_size_h').blur(
			function(e) {
			var number_temp = $('#txt_size_h').val();
			
				if(number_temp > 22)
				{
					$.confirm({'title': 'Alert','message': " <strong> Please enter proper height."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					$('#txt_size_h').val('');
					setTimeout(function() { $('#txt_size_h').focus(); }, 3000);
					return false;
				}
				else
				{
					var number = number_temp.split(".");
					if(e.keyCode==8)
					{}
					else
					{
						if(number.length>0)
						{
								if(((number[0].length > 0) || (number[0].length > 1)) && (number[1].length > 4))
								{
									$.confirm({'title': 'Alert','message': " <strong> Please enter proper height."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
									$('#txt_size_h').val('');
									setTimeout(function() { $('#txt_size_h').focus(); }, 3000);
								}    
								else
								{
									return true;
								}
							}
					}
				}
				//return false;
			});
		}
	}
}  
  
function selectsubcategory(id){
 if(id!="-1"){
  
   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
   loadData('category',id);
 }else{
   
   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
   $("select#subcategory").multiselect('refresh').multiselectfilter();
   
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

<?php if(!empty($editRecord[0]['template_category'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category']?>');

<?php } ?>

	</script>
<script type="text/javascript">
function addfieldtoeditor()
	{
		var a=CKEDITOR.instances['label_content'],b=document.<?php echo $viewname;?>.slt_customfields;
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
				else if(CKEDITOR.instances['label_content'].selectionStart||CKEDITOR.instances['label_content'].selectionStart=="0"){
					b=CKEDITOR.instances['label_content'].selectionEnd;
					d=CKEDITOR.instances['label_content'].value;
					a.insertText(d.substring(0,CKEDITOR.instances['label_content'].selectionStart)+c+d.substring(b,d.length));
				}
				else
				{ 
					a.insertText(c);
					sql_box_locked=false
				}
		}
	}
	


</script>