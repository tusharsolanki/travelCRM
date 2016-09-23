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
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.form.min.js"></script>
<!--     BombBomb Add Video    -->
<script type="text/javascript">
//var flag = 0;
var save_video_id = '<?=!empty($editRecord[0]['video_id'])?$editRecord[0]['video_id']:''?>';
$(document).ready(function() { 
	var options = { 
			target:   '',   // target element(s) to be updated with server response 
			//beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,
			error: error,  // post-submit callback 
			resetForm: true       // reset the form after successful submit 
		};
		
	$('body').on('click','#save',function(e){
		 $('#MyUploadForm').submit();
	});	
	$('#MyUploadForm').submit(function() {
			if($("#email").val().trim() != '' && $("#pw").val().trim() != '')
			{
				if ($('#MyUploadForm').parsley().isValid()) {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> It will take some time to upload video. Please wait...'});
					$(this).ajaxSubmit(options);
					//alert(res);
					return false;
				}
				else
					return false;
			}
			else
			{
				$.confirm({'title': 'Alert','message': " <strong> Please connect your BombBomb account under setting. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
		}}}});
				return false;
			}
			// always return false to prevent standard browser submit and page navigation
		}); 
});

function error(data)
{
    console.log(data.status);
	if(data.status == 500)
	{
		$.confirm({'title': 'Alert','message': " <strong> Something went wrong. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
			$.unblockUI();
		}}}});
	}
	return false;
}

function afterSuccess(data)
{
	$("#priview_doc").text('');
	$("#name").val('');
	$("#description").val('');
	$("#videoFile").val('');
	if(data.trim() != '')
	{
		data = JSON.parse(data);
		//console.log(data.status);
		/*data = JSON.parse(data.info);
		console.log(data.info);*/
		//alert(data.video_id);
		$.each(data,function(i,item){ 
			video_id = item.video_id;
		});
		$.unblockUI();
		$("#existing_video").html('');
		$( ".existing_video_click" ).trigger( "click" );
		getVideo(video_id);
	}
	else
	{
		$.unblockUI(video_id);	
	}
}
function getVideo(video_id)
{
	$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/VideoList';?>",
		 data: {video_id : video_id},
		 beforeSend: function() {
			$("#existing_video").addClass('holds-the-iframe');
		},
		 success: function(html){
			 $('#existing_video').html(html);
			 $("#existing_video").removeClass('holds-the-iframe');
		 }
    });
}
</script>

<div aria-hidden="true" style="display: none;" id="VideoModal" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
       <button type="button" class="close close_bombbomb_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
       <div class="">
        <ul id="myTab10" class="nav nav-tabs">
            <li  class="active"> <a href="#existing_video" class="existing_video_click" data-toggle="tab" title="Choose an existing video from BombBomb">Choose an existing video from BombBomb</a> </li>
            <li> <a href="#video_upload" data-toggle="tab" title="">Upload a New Video <i class="icon-remove-sign"></i></a> </li>
        </ul>
        <div id="myTab10Content" class="tab-content">
            <div class="smart-drip-plan-con-box1 tab-pane fade in active holds-the-iframe" id="existing_video">
				<?php //$this->load->view('user/'.$viewname.'/VideoList') ?>
            </div>
            
            <div class="row tab-pane fade in uploadvideomain" id="video_upload">
             <form class="form parsley-form" enctype="multipart/form-data" name="MyUploadForm" id="MyUploadForm" method="POST" accept-charset="utf-8" action="http://app.bombbomb.com/app/api/api.php?method=AddVideo" novalidate >
                <input type="hidden" name="email" id="email" value="<?=!empty($username)?$username:''?>">
                <input type="hidden" name="pw" id="pw" value="<?=!empty($password)?$password:''?>">
                <div class="col-sm-12 form-group">
                    <label for="text-input">Video Name<span class="val">*</span></label>
                    <input id="name" name="name" placeholder="e.g. Subject" class="form-control parsley-validated" type="text" value="" data-required="true">
                </div>
                <div class="col-sm-12 form-group">
                    <label for="text-input">Description</label>
                    <input type="text" name="description" id="description" class="form-control parsley-validated">
                </div>
                <div class="col-sm-8 form-group">
                    <label for="text-input">Video<span class="val">*</span></label>
         			<div style="vertical-align:top;" class="ajax-upload-dragdrop"><div class="upload" style="position: relative; overflow: hidden; cursor: default;">
            <a class="btn btn-secondary-green">Upload</a><input type="file" name="videoFile" id="videoFile" style="position: absolute; cursor: pointer; top: 0px; width: 93px; height: 46px; left: 0px; z-index: 100; opacity: 0;" class="form-control parsley-validated" data-required="true"></div></div>
                    <span id="priview_doc"></span>
                </div>
                
                
         	</form>
            <div class="col-lg-12 clear form-group text-center">
                    <input type="button" name="save" id="save" value="Save" class="btn btn-secondary">
                    <a class="btn btn-primary" title="Cancel" onclick="bombbomb_close_popup()" id="elp_cancel">Cancel</a>
                </div>
           </div>
        </div>
      </div>
	 </div>
   </div>
   <div class="modal-body view_page">
			
   </div>
 </div>
    <!-- /.modal-content -->
</div>
<!--     END    -->

<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('bomdlibrarytemplate_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('bomdlibrarytemplate_add_head'); } 
	   else{ echo $this->lang->line('bomdlibrarytemplate_edit_head'); }?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate>
		  	<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
            <input id="video_id" name="video_id" type="hidden" value="<?=!empty($editRecord[0]['video_id'])?$editRecord[0]['video_id']:''?>">
            <input id="thumb_url" name="thumb_url" type="hidden" value="<?=!empty($editRecord[0]['thumb_url'])?$editRecord[0]['thumb_url']:''?>">
            <input id="video_title" name="video_title" type="hidden" value="<?=!empty($editRecord[0]['video_title'])?$editRecord[0]['video_title']:''?>">
           <div class="col-sm-8">
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
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo $row1['category'];?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
            </div>
           <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?><span class="val">*</span></label>
         <input id="txt_template_subject" name="txt_template_subject" placeholder="e.g. Subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo htmlentities($editRecord[0]['template_subject']); }?>" data-required="true">
          </div>
          </div>        
     </div>
     	<div class="col-sm-4">
              	<a href="#VideoModal" id="VideoModal" data-toggle="modal"> 
                    <div class="text-center font_20px">
                    <img class="img-responsive imgradius" src="<?=base_url()?>images/bomb_image.jpg" alt="Click Here to Add Video" title="Click Here to Add Video"/>
                    <span>Click Here to Add Video</span></div>
            	</a>
            <br />
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
               
               <select  id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox" ondblclick="addfieldtoeditor();" >
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
				 }
             } ?>
            </select>
			<input class="btn btn-secondary" type="button" name="submitbtn1" onclick="addfieldtoeditor();"  title="Insert Field" value="Insert Field">
            </div>
            </div>    
            <div class="col-sm-8 form-group"></div>
            
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
<input type="hidden" name="last_id" value="" id="last_id" />
<input type="submit" class="btn btn-secondary-green" title="Save Template" value="Save Email Template" onclick="return setdefaultdata();" name="submitbtn" />
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
 
 	 function setdefaultdata()
	 {
	 	
		var ck_edit = CKEDITOR.instances.email_message.getData();
		if(ck_edit == "")
		{
			$.confirm({'title': 'Alert','message': " <strong> Please enter email message details."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}
		else if($("#category").val() == '-1')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select category."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
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
function addfieldtoeditor()
{
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
						//a.insertText(d.substring(0,document.sms_texts.sms_message.selectionStart)+c+d.substring(b,d.length));
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
	 if(a == 'cke_1'  || a == '')
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
// Hide Insert Unsubscribe Link 
$(".unsubscribe").hide();
function selectVideo()
{
	var myarray = new Array;
	var i=0;
	var html = '';
	var boxes = $('input[name="select_video[]"]:checked');
	var thumb_url = '';
	//alert(boxes);
	$(boxes).each(function(){
		thumb_url = $('input[value="'+this.value+'"]:checked').attr('data-id');
		html += '<img class="responsive_image" height="320" id="'+this.value+'" name="bb_video" src="'+thumb_url+'" style="display:block" width="500" /><br/><br/>';
		 $('input[value="'+this.value+'"]:checked').attr('checked',false);
	});
	if(html.trim() != '')
	{
		CKEDITOR.instances.email_message.insertHtml(html, function() {
    		CKEDITOR.instances.email_message.focus();
		});
	}
	$(".close").trigger('click');
}
<?php if(!empty($editRecord[0]['video_id'])){ ?>
	getVideo('<?=$editRecord[0]['video_id']?>');
<?php } else { ?>
	getVideo();
<?php } ?>
function delete_video()
{
	$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete video?",'buttons': {'Yes': {'class': '',
'action': function(){
		if(save_video_id != '')
		{
			var id=$('#id').val();
			 $.ajax({
				type: 'post',
				data:{id:id,name:name},
				url: '<?=$this->config->item('user_base_url').$viewname."/delete_image";?>',
				success:function(msg){
						if(msg == 'done')
						{
							$('.video_data').html('');
							$('#video_id').val('');
							$("#thumb_url").val('');
							$("#video_title").val('');
							save_video_id = '';
					  }
					}//succsess
				});//ajax
		}
		else
		{
			$('#video_id').val('');
			$("#thumb_url").val('');
			$("#video_title").val('');
			$('.video_data').html('');
		}
		}},'No'	: {'class'	: 'special'}}});
}
$("#videoFile").change(function() {
	$('#priview_doc').text(this.value);
});
function bombbomb_close_popup()
{
	$(".close_bombbomb_select_popup").trigger('click');
}
</script>