<?php
/*
    @Description: SMS Auto Response add/edit page
    @Author: Nishant Rathod
    @Date: 04-05-2015
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
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('smsautoresponse_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('smsautoresponse_add_head'); } 
	   else{ echo $this->lang->line('smsautoresponse_edit_head'); }?> </h3>
	   	<span class="float-right margin-top--15"><a href="javascript:void(0)" title="Back" onclick="history.go(-1)" class="btn btn-secondary" id="back">Back</a> </span>

	  </div>
    
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" data-validate="parsley" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-12 col-lg-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> <span class="val">*</span></label>
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
              <!--<div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>-->
              
            </div>
           
         </div>
        <div class="col-sm-12 clear">
          <div class="row">
           
          <div class="col-sm-8 form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('sms_label_message');?>
                  <span class="val">*</span></label>
                  <textarea name="sms_message" id="sms_message" placeholder="e.g. Message" data-required="true" class="form-control parsley-validated"  onkeypress="return keypress(event);"><?=!empty($editRecord[0]['sms_message'])?htmlentities($editRecord[0]['sms_message']):'';?>
</textarea>
               <div id="textarea_feedback"></div>
                </div>
            
            <div class="form-group">
             <div class="col-sm-12 checkbox">
              <label class="">
              Publish
              <div class="float-left margin-left-15">
                <input <?php if(!empty($editRecord[0]['publish_flag']) && $editRecord[0]['publish_flag']=='1'){ echo 'checked="checked"';}?> type="checkbox" class="" name="publish_flag" value="1" />
              </div>
              </label>
             </div>
            </div>
          
              	<div class="col-sm-8 form-group" id="event">
              		<label for="text-input"><?=$this->lang->line('label_events');?></label>
          				<select class="selectBox" name='sms_event' id='sms_event'>
          				<option value="-1">Events</option>
             			<?php if(isset($event) && count($event) > 0){
							foreach($event as $row1){
								if(!empty($row1['id'])){ ?>
            						<option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['sms_event']) && $editRecord[0]['sms_event'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['name']);?></option>
            					<?php }
								}
							} ?>
          				</select>	
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
            </div>
          
            </div>
             </div>
           </div>

            <div class="row clear">
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
<input type="submit" class="btn btn-secondary-green" title="Save Template" value="Save SMS Auto Response" onclick="return setdefaultdata();" name="submitbtn" id="st_submitbtn" />
			<?php if($this->uri->segment(4) == 'iframe'){ ?>
                <a class="btn btn-primary" title="Cancel" onclick="close_popup()" id="stp_cancel">Cancel</a>
            <?php } else { ?>
 				<a class="btn btn-primary" href="javascript:history.go(-1);" title="Cancel" id="st_cancel">Cancel</a>
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
	 $("select#sms_event").multiselect({
		 multiple: false,
		 header: "Events",
		 noneSelectedText: "Events",
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

</script>
<script type="text/javascript">
var arraydatacount = 0;
var popupcontactlist = Array();
var text_max = 160;
 
 /*String.prototype.reverse = function () {
    return this.split('').reverse().join('');
};

String.prototype.replaceLast = function (what, replacement) {
    return this.reverse().replace(new RegExp(what.reverse()), replacement.reverse()).reverse();
};
 */
/*String.prototype.replaceLast = function (what, replacement) {
    return this.split(' ').reverse().join(' ').replace(new RegExp(what), replacement).split(' ').reverse().join(' ');
};*/
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

/*String.prototype.replaceLast = function (what, replacement) {
    return this.reverse().replace(new RegExp(what.reverse()), replacement.reverse()).reverse();
};*/
$(document).ready(function() {
	<?php
		 if($tablefield_data!='')
		 {
			foreach($tablefield_data as $row){?>
			
				var arrayindex = jQuery.inArray( "<?=!empty($row['name'])?$row['name']:''?>", popupcontactlist );
				if(arrayindex == -1)
				{
					popupcontactlist[arraydatacount++] = '{(<?=!empty($row['name'])?$row['name']:''?>)}';
				}
			
		<?php }?>
		<?php }?>
    $('#textarea_feedback').html('Count: 0 letters: max '+text_max + ' letters');
	$('#sms_message').keyup(function() {
        var text = $('#sms_message').val();
		var text_length = $('#sms_message').val().length;
		
		var new_str1 = text;
		
		for(i=0;i<popupcontactlist.length;i++)
		{
			new_str1 = new_str1.replace(popupcontactlist[i],"","g");
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
			
			for(i=0;i<popupcontactlist.length;i++)
			{
				new_str1 = new_str1.replace(popupcontactlist[i],"","g");
			}
			text_length = new_str1.length;

		}
		console.log(text_length);
        var text_remaining = text_length;
		
		
		
        $('#textarea_feedback').html('Count: ' +text_remaining + ' letters: max ' +text_max+ ' letters');
    });
	$("#sms_message")
		.bind("drop", function(e) {
			setTimeout(function(){$('#sms_message').keyup()},1);
	});
});

function keypress(evt)
{
		var text = $('#sms_message').val();
		var text_length = $('#sms_message').val().length;
		var new_str1 = text;
	
		for(i=0;i<popupcontactlist.length;i++)
		{
			new_str1 = new_str1.replace(popupcontactlist[i],"","g");
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
		// var text_remaining = text_length;

       // $('#textarea_feedback').html('Count: ' +text_remaining + ' letters: Max ' +text_max+ ' letters');
}

function setdefaultdata()
{
	if($("#category").val() == '-1')
	{
		//alert("Please Enter Templ Detail");
		$.confirm({'title': 'Alert','message': " <strong> Please select category."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;
	}
        else
        {
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                /*$('#st_submitbtn').attr('disabled','disabled');
                $('#st_cancel').attr('disabled','disabled');
                $('#stp_cancel').attr('disabled','disabled');*/
            }
        }
}

</script>
<script type="text/javascript">
function addfieldtoeditor()
{
	//var text_length = $('#sms_message').val().length;
	//alert(text_length);
	//if(text_length < 180){
	var a=document.sms_texts_response.sms_message,b=document.<?php echo $viewname;?>.slt_customfields;
	if(b.options.length>0){
		sql_box_locked=true;
		for(var c="",d=0,e=0;e<b.options.length;e++)
			if(b.options[e].selected)
			{
				d++;
				if(d>1)
					c+="";
				c+=b.options[e].value
			}
			if(document.selection){
				a.focus();
				sel=document.selection.createRange();
				sel.text=c;document.<?php echo $viewname;?>.insert.focus()
			}
			else if(document.sms_texts_response.sms_message.selectionStart||document.sms_texts_response.sms_message.selectionStart=="0"){
				b=document.sms_texts_response.sms_message.selectionEnd;
				d=document.sms_texts_response.sms_message.value;
				a.value = d.substring(0,document.sms_texts_response.sms_message.selectionStart)+c+d.substring(b,d.length);
				//a.insertText(d.substring(0,document.sms_texts_response.sms_message.selectionStart)+c+d.substring(b,d.length));
			}
			else
			{ 
				a.insertText(c);
				sql_box_locked=false
			}
		}
		//alert(a.value);
		//keypress();
		$('#sms_message').keyup();
//	}
}
	
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