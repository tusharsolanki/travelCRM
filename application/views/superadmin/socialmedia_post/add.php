<?php
/*
    @Description: Template add/edit page
    @Author: Mohit Trivedi
    @Date: 06-08-2014

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
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title add_title">Add New Template</h3>
      </div>
      <div class="modal-body view_page">
			
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('socialmedia_post_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('socialmedia_posttemplate_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('socialmedia_posttemplate_add_head'); } 
	   else{ echo $this->lang->line('socialmedia_posttemplate_edit_head'); }?> </h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-12 col-lg-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?>
              <span class="val">*</span></label>
              <input id="txt_template_name" name="txt_template_name" placeholder="e.g. Template Name" class="form-control parsley-validated" type="text" value="<?php if(isset($insert_data)){
			   if(!empty($editRecord[0]['template_name'])){ echo htmlentities($editRecord[0]['template_name'].'-copy'); }}
			   else
			   {
				   if(!empty($editRecord[0]['template_name'])){ echo htmlentities($editRecord[0]['template_name']); }
			   }
			   ?>" data-required="true">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?> <span class="val">*</span> </label>
			  </div>
              <div class="col-sm-12">
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
			 <?php if($this->uri->segment(4) != 'iframe'){ ?>
              <div class="col-sm-6">
                 <a href="#basicModal" class="text_color_red text_size add_new_category" id="basicModal" data-toggle="modal"><i class="fa fa-plus-square"></i> Add Category </a>
              </div>
             <?php } ?>
              
              <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
              
            </div>
            <div class="row form-group">
             <div class="col-sm-12 checkbox">
              <label class="">
             	Publish
              <div class="float-left margin-left-15">
                <input <?php if(!empty($editRecord[0]['publish_flag']) && $editRecord[0]['publish_flag']=='1'){ echo 'checked="checked"';}?> type="checkbox" class="" name="publish_flag" value="1">
              </div>
              </label>
             </div>
            </div>
             <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('platform_label_name');?></label>
			  </div>

<?php /*?>              <div class="col-sm-6">
              <select class="form-control parsley-validated" name='platform[]' id='platform' multiple="multiple">
                <option value="Facebook"<?php if(isset($editRecord1) && is_array($editRecord1) && in_array('Facebook',$editRecord1)){ echo "selected";} ?>>Facebook</option>
                <option value="Twitter"<?php if(isset($editRecord1) && is_array($editRecord1) && in_array('Twitter',$editRecord1)){ echo "selected";}?>>Twitter</option>
                <option value="Linkedin"<?php if(isset($editRecord1) && is_array($editRecord1) && in_array('Linkedin',$editRecord1)){ echo "selected";}?>>Linkedin</option>
              </select>
              </div>
<?php */?>			  
             <div class="col-sm-4 browse-img-box btn-facebook">
			  <label>
              <input type="checkbox" name="platform[]" value="Facebook"<?php if(isset($editRecord1) && is_array($editRecord1) && in_array('Facebook',$editRecord1)){ echo "checked=checked";} ?> class="plaform_img_class"/><i class="fa fa-facebook" style="font-size:50px"></i>
              </label>
              </div>

             <div class="col-sm-4 browse-img-box btn-twitter">
			  <label>
              <input type="checkbox" name="platform[]" value="Twitter" <?php if(isset($editRecord1) && is_array($editRecord1) && in_array('Twitter',$editRecord1)){ echo "checked=checked";}?> class="plaform_img_class" /><i class="fa fa-twitter" style="font-size:50px"></i>
              </label>
              </div>

             <div class="col-sm-4 browse-img-box btn-linkedin">
			  <label>
              <input type="checkbox" name="platform[]" value="Linkedin" <?php if(isset($editRecord1) && is_array($editRecord1) && in_array('Linkedin',$editRecord1)){ echo "checked=checked";}?> class="plaform_img_class"/><i class="fa fa-linkedin" style="font-size:50px"></i>
              </label>
              </div>


  </div>
           <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?><span class="val">*</span></label>
         <input id="txt_template_subject" name="txt_template_subject" placeholder="e.g. Subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo htmlentities($editRecord[0]['template_subject']); }?>" data-required="true">
          </div>
          </div>
         
          
          <div class="form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('post_content_label_script');?><span class="val">*</span>
                  </label>
                  <textarea name="post_content" class="form-control parsley-validated" id="post_content" ><?=!empty($editRecord[0]['post_content'])?$editRecord[0]['post_content']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('post_content',
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
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
               
			   
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>
               
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" class="btn btn-secondary-green" value="Save Template"  title="Save Template"onclick="return setdefaultdata();" name="submitbtn" id="smp_submitbtn" />
 <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);" id="smp_cancel">Cancel</a>
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
	$("select#platform").multiselect({
		header: "Select platform",
		noneSelectedText: "Select platform",
	}).multiselectfilter();
	
	 function setdefaultdata()
	 {
	 	
		var ck_edit = CKEDITOR.instances.post_content.getData();
		if(ck_edit == "")
		{
			//alert("Please Enter Templ Detail");
			$.confirm({'title': 'Alert','message': " <strong> Please enter post content details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
		else if($("#category").val() == '-1')
		{
			//alert("Please Enter Templ Detail");
			$.confirm({'title': 'Alert','message': " <strong> Please select category."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
                else
                {
                    if ($('#<?php echo $viewname?>').parsley().isValid()) {
                        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                        /*$('#smp_submitbtn').attr('disabled','disabled');
                        $('#smp_cancel').attr('disabled','disabled');*/
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
     url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_subcategory';?>",
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

$('body').on('click','.add_new_category',function(e){
	$(".add_title").html('Add New Category');
	var frameSrc = '<?php echo $this->config->item('superadmin_base_url')?>marketing_library_masters/add_record/iframe';
	//$('iframe').attr("src",frameSrc);
	$(".view_page").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="490" width="99.6%"></iframe>');
});

function selectnewcategory()
{
	$.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('superadmin_base_url').'email_library/ajax_category';?>",
     dataType: 'json',
	 data: {},
     cache: false,
     success: function(result){ 
			$('#category').empty();
			var option = $('<option />');
			option.attr('value','-1').text('Category');
			$('#category').append(option);
		 	if(result != null)
			{
				$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.category);
					if(i == 0)
						option.attr("selected","selected");
					$('#category').append(option);
				});
				$("select#category").multiselect('refresh').multiselectfilter();
     		}
	  }
   });
}

</script>
