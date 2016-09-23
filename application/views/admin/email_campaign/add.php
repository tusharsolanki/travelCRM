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

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); }?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="row">
           <div class="col-sm-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?></label>
              <input id="txt_template_name" name="txt_template_name" class="form-control parsley-validated" type="text" value="<?php if(isset($insert_data)){
			   if(!empty($editRecord[0]['template_name'])){ echo ucfirst(strtolower($editRecord[0]['template_name'])).'-copy'; }}
			   else
			   {
				   if(!empty($editRecord[0]['template_name'])){ echo ucfirst(strtolower($editRecord[0]['template_name'])); }
			   }
			   ?>" data-required="true">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?></label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)" >
              <option value="-1">Category</option>
                 <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
     
              <div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>
              
            </div>
           <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?></label>
         <input id="txt_template_subject" name="txt_template_subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo ucfirst(strtolower($editRecord[0]['template_subject'])); }?>" data-required="true">
          </div>
          </div>
          </div>
          <div class="col-sm-4">
            
            <div class="attachfiel">
               <label>Attach File :</label>
             <div class="fileUpload btn btn-primary">
        <span>Browse</span>
        <input type="file" class="upload" />
</div>
            </div>
          </div>
          
            </div>
            <div class="row">
          <div class="col-sm-8">
          <div class="form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('calling_label_script');?>
                  </label>
                  <textarea name="calling_script" id="calling_script" ><?=!empty($editRecord[0]['calling_script'])?$editRecord[0]['calling_script']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('calling_script',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                </div>
                <div class="row">
                <div class="col-sm-5">
               <label for="text-input">Signature</label>
              <select id="slt_contact_status" name="slt_contact_status" class="form-control parsley-validated">
				   <option value="">Please Select</option>
				   								<option value="5">Client</option>
															<option value="4">Future Prospects</option>
															<option value="3">New Lead</option>
															<option value="1">Prospects</option>
											   			  </select>
                                                          </div>
</div>
             <div class="row">
                <div class="col-sm-12">
                  <fieldset class="schedule_campaign">
                     <legend>Schedule Campaign</legend>
                       <div class="schedule_detail">
                         <p>Send this Campagin</p> 
                         <div class="col-sm-12 pull-left"><label class="checkbox">
              Now :
              <div class="float-left margin-left-15">
               <input type="checkbox" name="chk_is_lead" id="chk_is_lead" class="" value="1">
              </div>
              </label></div> 
             <div class="col-sm-12 pull-left"> <label class="checkbox">
              Date Time :
              <input name="" type="text" />
              <input name="" type="text" />
              <div class="float-left margin-left-15">
               <input type="checkbox" name="chk_is_lead" id="chk_is_lead" class="" value="1">
               
              </div>
              </label> </div>                       
                       </div>
                        
                  </fieldset>
                </div>
             </div>
          </div>
          <div class="col-sm-4">
            
               <label>Custom Field</label>
            <div class="custom_field">
               
               <select>
                <option>fvedfdvdv</option>
               
                </select>
                 <input class="btn btn-secondary" type="submit" name="submitbtn" onclick="return setdefaultdata();" value="Insert Field">
               
            </div>
            <div class="unsubscribe">
            <input class="btn btn-secondary" type="submit" value="Insert Unsubscribe Link" onclick="return setdefaultdata();" name="submitbtn">
          </div></div>
          </div>
            <!--<div class="row">
             <div class="col-sm-12">
              <div class="form-group">
               
			   
			  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
		      </div>
             </div>
            </div>-->
               </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="submit" class="btn btn-secondary" value="Send Now" onclick="return setdefaultdata();" name="submitbtn" />
<input type="submit" class="btn btn-secondary" value="Save Campaign" onclick="return setdefaultdata();" name="submitbtn" />
<input type="submit" class="btn btn-secondary" value="Save Template As" onclick="return setdefaultdata();" name="submitbtn" />
 <a class="btn btn-primary" href="javascript:history.go(-1);">Close</a>
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
