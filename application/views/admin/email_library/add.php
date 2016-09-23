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
.ui-multiselect{width:100% !important;}
input:focus{cursor:auto;}
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
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('emaillibrarytemplate_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('emaillibrarytemplate_add_head'); } 
	   else{ echo $this->lang->line('emaillibrarytemplate_edit_head'); }?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-12 col-lg-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?><span class="val">*</span></label>
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
     
             <!-- <div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
              
            </div>
           <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?><span class="val">*</span></label>
         <input id="txt_template_subject" name="txt_template_subject" placeholder="e.g. Subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo htmlentities($editRecord[0]['template_subject']); }?>" data-required="true">
          </div>
          </div>
          
        </div>
        <div class="col-sm-12 clear">
          <div class="row">
          
          <div class="col-sm-8 form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('label_emailmessage');?>
                  <span class="val">*</span>
                  </label>
                  <textarea name="email_message" id="email_message" ><?=!empty($editRecord[0]['email_message'])?$editRecord[0]['email_message']:''; ?>
</textarea>
                  <script type="text/javascript">
					CKEDITOR.replace('email_message',
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
               
               <select  ondblclick="addfieldtoeditor();" id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox" >
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
            <div class="unsubscribe">
            <input <?php if(isset($editRecord[0]['is_unsubscribe']) && $editRecord[0]['is_unsubscribe']=='1'){ echo 'checked="checked"';}?> checked="checked" type="checkbox" class="" name="is_unsubscribe" value="1">
            <label>Insert Unsubscribe Link</label>
          </div></div>
                
                
             <!--   <div class="row">
                        <div class="col-xs-12 mrgb2 icheck-input-new">
                        <div class="col-xs-2">
                        </div>
                        <div class="col-xs-5">
                        <input type="radio" data-required="true" class="radio_box" name="email_send_type" value="1" checked="checked" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '1'){ echo 'checked="checked"'; }?> >
                        <label>
                                <?=$this->lang->line('label_autoresponder');?>
                                </label>
                        </div>
                        
                        
          				<div class="col-xs-5">
                        <input type="radio" data-required="true" class="radio_box" name="email_send_type" value="2" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2'){ echo 'checked="checked"'; }?> >              <label>
                                <?=$this->lang->line('label_newsletter');?>
                                </label>
                        </div>
                        </div>
              <div class="row">
              <div class="col-sm-12 form-group" id="event">
              <div class="col-sm-4">
              <label for="text-input"><?=$this->lang->line('label_events');?></label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='email_event' id='email_event'>
              <option value="-1">Events</option>
                 <?php if(isset($event) && count($event) > 0){
							foreach($event as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['email_event']) && $editRecord[0]['email_event'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['name']);?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
     		</div>
          
            </div>
            </div>-->
             
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
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="hidden" name="last_id" value="" id="last_id" />
<input type="submit" class="btn btn-secondary-green" title="Save Template" value="Save Email Template" onclick="return setdefaultdata();" name="submitbtn" id="el_submitbtn" />
<?php if($this->uri->segment(4) == 'iframe'){ ?>
  <a class="btn btn-primary" title="Cancel" onclick="close_popup()" id="elp_cancel">Cancel</a>
<?php } else { ?>
  <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);" id="el_cancel">Cancel</a>
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
 
 $("select#email_event").multiselect({
		 multiple: false,
		 header: "Events",
		 noneSelectedText: "Events",
		 selectedList: 1
	}).multiselectfilter();
	
	 function setdefaultdata()
	 {
	 	
		var ck_edit = CKEDITOR.instances.email_message.getData();
		if(ck_edit == "")
		{
			//alert("Please Enter Templ Detail");
			$.confirm({'title': 'Alert','message': " <strong> Please enter email message details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
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
                        /*$('#el_submitbtn').attr('disabled','disabled');
                        $('#elp_cancel').attr('disabled','disabled');
                        $('#el_cancel').attr('disabled','disabled');*/
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

CKEDITOR.on( 'instanceReady', function( event ) {
    event.editor.on( 'focus', function() {
	//console.log($(this).attr('id') + ' just got focus!!');
	window.last_focus = $(this).attr('id');
	$('#last_id').val($(this).attr('id'));
    });
});

$('input:text').on('focus', function() { 
//console.log($(this).attr('id') + ' just got focus!!');
window.last_focus = $(this).attr('id');
$('#last_id').val($(this).attr('id'));
});	  
function addfieldtoeditor() {
var a=$('#last_id').val();
		//alert(a);
		if(a == 'txt_template_subject')
		{
			var text_length = $('#txt_template_subject').val().length;
			if(text_length < 180){
			var a=document.<?php echo $viewname;?>.txt_template_subject,b=document.<?php echo $viewname;?>.slt_customfields;
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
					else if(document.<?php echo $viewname;?>.txt_template_subject.selectionStart||document.<?php echo $viewname;?>.txt_template_subject.selectionStart=="0"){
						b=document.<?php echo $viewname;?>.txt_template_subject.selectionEnd;
						d=document.<?php echo $viewname;?>.txt_template_subject.value;
						a.value = d.substring(0,document.<?php echo $viewname;?>.txt_template_subject.selectionStart)+c+d.substring(b,d.length);
						
					}
					else
					{ 
						a.insertText(c);
						sql_box_locked=false
					}
				}
				$('#txt_template_subject').keyup();
		}
	
	}
	 if(a == 'cke_1' || a == '')
	{
				var a=CKEDITOR.instances['email_message'],b=document.<?php echo $viewname;?>.slt_customfields;
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
				else if(CKEDITOR.instances['email_message'].selectionStart||CKEDITOR.instances['email_message'].selectionStart=="0"){
					b=CKEDITOR.instances['email_message'].selectionEnd;
					d=CKEDITOR.instances['email_message'].value;
					a.insertText(d.substring(0,CKEDITOR.instances['email_message'].selectionStart)+c+d.substring(b,d.length));
				}
				else
				{ 
					a.insertText(c);
					sql_box_locked=false
				}
		}

	}

}
</script>

<!--<script type="text/javascript">
function addfieldtoeditor()
	{
		var a=CKEDITOR.instances['email_message'],b=document.<?php echo $viewname;?>.slt_customfields;
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
				else if(CKEDITOR.instances['email_message'].selectionStart||CKEDITOR.instances['email_message'].selectionStart=="0"){
					b=CKEDITOR.instances['email_message'].selectionEnd;
					d=CKEDITOR.instances['email_message'].value;
					a.insertText(d.substring(0,CKEDITOR.instances['email_message'].selectionStart)+c+d.substring(b,d.length));
				}
				else
				{ 
					a.insertText(c);
					sql_box_locked=false
				}
		}
	}
</script>
-->
<script type="text/javascript">
function addfieldtoeditor1()
	{

		var text_length = $('#txt_template_subject').val().length;
		if(text_length < 100){
		var a=document.<?php echo $viewname;?>.txt_template_subject,b=document.<?php echo $viewname;?>.slt_customfields;
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
				else if(document.<?php echo $viewname;?>.txt_template_subject.selectionStart||document.<?php echo $viewname;?>.txt_template_subject.selectionStart=="0"){
					b=document.<?php echo $viewname;?>.txt_template_subject.selectionEnd;
					d=document.<?php echo $viewname;?>.txt_template_subject.value;
					a.value = d.substring(0,document.<?php echo $viewname;?>.txt_template_subject.selectionStart)+c+d.substring(b,d.length);
					
				}
				else
				{ 
					a.insertText(c);
					sql_box_locked=false
				}
		}
			$('#txt_template_subject').keyup();
	  }
	}
	
	

</script>
    

<script>
$(document).ready(function(){

	$('.radio_box').change(function(){
    
	 val = $('input[name=email_send_type]:checked', '#email_library').val();
	
	  if (val == 1)
      {
		$("#event").show();
      }
      else
      {
        $("#event").hide();
      }
	  
    })
	
	<?php if(!empty($editRecord[0]['email_send_type'])){ ?>

		$('.radio_box').trigger('change');
	
	<?php } ?>
});

$('body').on('click','.add_new_category',function(e){
	$(".add_title").html('Add New Category');
	var frameSrc = '<?php echo $this->config->item('admin_base_url')?>marketing_library_masters/add_record/iframe';
	//$('iframe').attr("src",frameSrc);
	$(".view_page").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="490" width="99.6%"></iframe>');
});

function selectnewcategory()
{
	$.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_category';?>",
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

<script>
// Hide Insert Unsubscribe Link 
		$(".unsubscribe").hide();
</script>