<?php
/*
    @Description: Task add/edit page
    @Author: Mohit Trivedi
    @Date: 04-08-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$editRecordId = $this->router->uri->segments[4];
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
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);">Search Contacts</a>
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
          <div class="table-responsive">
		  <?php $this->load->view('admin/'.$viewname.'/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Assign to Task</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>



<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('task_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('task_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('task_add_head'); } 
	   else{ echo $this->lang->line('task_edit_head'); }?> </h3>
	   <span class="float-right margin-top--15">
       <?php
	   	if(!empty($editRecord))
		{?>
			<a title="View Contact" class="btn btn-secondary" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?=$editRecordId?>"><?php echo $this->lang->line('common_view_title')?></a>
		<?php }
	   ?>
       
       <a href="javascript:void(0)" onclick="history.go(-1)" title="Back" class="btn btn-secondary">Back</a> </span>
	    
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
         
          <form class="form parsley-form" data-validate="parsley" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-12 col-lg-8">
            <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('task_label_name');?><span class="val">*</span></label>
              <input id="txt_task_name" name="txt_task_name" class="form-control parsley-validated" type="text" value="<?php 
			  if(isset($insert_data)){
			  if(!empty($editRecord[0]['task_name'])){			  
			  echo htmlentities($editRecord[0]['task_name']).'-copy'; }}
			  else{
			  if(!empty($editRecord[0]['task_name'])){			  
			  echo htmlentities($editRecord[0]['task_name']);}}?>" data-required="true" placeholder="e.g. Task Title">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_desc');?></label>
			  <textarea name="txtarea_desc" placeholder="e.g. Task Descripton" id="txtarea_desc" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['desc'])){ echo htmlentities($editRecord[0]['desc']); }?></textarea>
             </div>
            </div>

 			 
            <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('taskdate_label_name');?><span class="val">*</span></label>
          <input id="txt_task_date" name="txt_task_date" class="form-control parsley-validated calendarclass" type="text" value="<?php if(!empty($editRecord[0]['task_date']) && $editRecord[0]['task_date'] != '0000-00-00' && $editRecord[0]['task_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['task_date'])); }?>" data-required="true" readonly="readonly" placeholder="Specific Date">
          </div>
          </div>
         <!-- <div class="row">
         	<div class="col-sm-12 topnd_margin1"> <strong class="assign_title">Assign Contacts Lead(s)</strong> <a data-toggle="modal" class="text_color_red text_size" href="#basicModal"><i class="fa fa-plus-square"></i> Select Contacts</a> </div>
           	<div class="col-sm-12 added_contacts_list">
				<?php $this->load->view('admin/'.$viewname.'/selected_contact_ajax')?>	
            </div>
         </div>-->
          <div class="row">
             <div class="col-sm-12">
             
              <label for="text-input"><?=$this->lang->line('common_label_assignuser');?><span class="val">*</span></label>
			  <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='slt_user[]' id='slt_user'>
                <!--<option value=''>Select Employee</option>-->
              <?php if(isset($userlist) && count($userlist) > 0){
				
							foreach($userlist as $row){
								if(!empty($row['id'])){?>
                <option value='<?php echo $row['id'];?>' <?php if(isset($slt_user) && is_array($slt_user) && in_array($row['id'],$slt_user)){ echo "selected";}?> ><?php if($row['admin_name']!='') { echo ucwords($row['admin_name']." (".$row['email_id'].")");}else{ echo ucwords($row['user_name']." (".$row['email_id'].")");}?></option>
                <?php 		}
							}
						} ?>
              </select>
             </div>
            </div>
            
         
         
<div class="row form-group">

             <div class="col-sm-12 checkbox">
              <label class="">
              Task Completed
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_completed" name="is_completed" <?php if(!empty($editRecord[0]['is_completed']) && $editRecord[0]['is_completed'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
            </div>
            
 <div class="row form-group">
 <div class="">
 <div class="col-sm-3">
 Reminders
 </div>
             <div class="col-sm-3 checkbox">
              <label class="">
              Email Before
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_email" name="is_email" <?php if(!empty($editRecord[0]['is_email']) && $editRecord[0]['is_email'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
             <div class="col-sm-6 col-lg-3">
              <input id="email_time_before" name="email_time_before" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['email_time_before'])){ echo htmlentities($editRecord[0]['email_time_before']); }?>" onkeypress="return isNumberKey(event)" placeholder="Number">
             </div>
             <div class="col-sm-6 col-lg-3">
               <select class="form-control parsley-validated" name="email_time_type" id="email_time_type">
              
               <option <?php if(!empty($editRecord[0]['email_time_type']) && $editRecord[0]['is_email'] == '1' && $editRecord[0]['email_time_type'] == '1'){ echo "selected"; }?> value="1">Hour</option>
			   <option <?php if(!empty($editRecord[0]['email_time_type']) && $editRecord[0]['is_email'] == '1' && $editRecord[0]['email_time_type'] == '2'){ echo "selected"; }?> value="2">Day</option>
			   </select>
             </div>

             </div>
             <div class="clear">
 <div class="col-sm-3">
 </div>         
             <div class="col-sm-3 checkbox">
              <label class="">
              Pop-Up Before
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_popup" name="is_popup" <?php if(!empty($editRecord[0]['is_popup']) && $editRecord[0]['is_popup'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
             <div class="col-sm-6 col-lg-3">
              <input id="popup_time_before" name="popup_time_before" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['popup_time_before'])){ echo $editRecord[0]['popup_time_before']; }?>" onkeypress="return isNumberKey(event)"  placeholder="Number">
             </div>
             <div class="col-sm-6 col-lg-3">
               <select class="form-control parsley-validated" name="popup_time_type" id="popup_time_type">
              <option <?php if(!empty($editRecord[0]['popup_time_type']) && $editRecord[0]['is_popup'] == '1' && $editRecord[0]['popup_time_type'] == '1'){ echo "selected"; }?> value="1">Hour</option>
			   <option <?php if(!empty($editRecord[0]['popup_time_type']) && $editRecord[0]['is_popup'] == '1' && $editRecord[0]['popup_time_type'] == '2'){ echo "selected"; }?> value="2">Day</option>
			   </select>
             </div>

			</div>
             </div>
            </div>
           
          
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
           
          <div class="col-sm-12 pull-left text-center margin-top-10">
<!--<a class="btn btn-secondary" href="#">Save Contact</a>-->
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" />
<input type="submit" class="btn btn-secondary-green" value="Save Task" title="Save Task" onclick="return select_box();" name="submitbtn" id="task_submitbtn" />
 <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);" id="task_cancel">Cancel</a>
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
$(document).ready(function(){
	$( "#txt_task_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
</script>
<script type="text/javascript">
	   $("select#slt_user").multiselect({
		}).multiselectfilter();
		
function select_box()
{
	var abc = $("#slt_user").multiselect("widget").find(":checkbox").filter(':checked').length;
	/*if(popupcontactlist != '')
	{
		if(abc > 1)
		{
			$.confirm({'title': 'Alert','message': " <strong> Contacts assign to only one user. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;
		}
	}*/
	if(abc > 0)
	{
            
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                /*$('#task_submitbtn').attr('disabled','disabled');
                $('#task_cancel').attr('disabled','disabled');*/
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            }
		$('.parsley-form').submit();
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select atleast one user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;

	}
}
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
});
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
                result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val()
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
	
	$('body').on('click','.a_search_contacts',function(e){
		contact_search();
	});
	// view All Recored
	function clearfilter_contact()
	{
		$("#search_contact_popup_ajax").val("");
		$('#slt_contact_status').val("");
		$('#slt_contact_source').val("");
		$('#contact_type').val("");
		contact_search();
	}
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/task/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val()
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
			url: "<?php echo base_url();?>admin/task/add_contacts_to_interaction_plan/",
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
	
	function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

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
		var myvalue = $(this).data("id");
		var task_id = $(this).data("group");
		var mytr = $(this).closest("tr");

		$.confirm({
		'title': 'Delete','message': " <strong> Are you sure want to remove contact from task?",'buttons': {'Yes': {'class': '',
		'action': function(){
			
			var arrayindex = jQuery.inArray( parseInt(myvalue), popupcontactlist );
			if(arrayindex >= 0)
			{
				$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
				mytr.remove();
			}
			$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
			
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/delete_contact_from_task';?>",
				data: {'task_id':task_id,'contact_id':myvalue},
				success: function(html){
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
				
		}},'No'	: {'class'	: 'special'}}});
		
		
		
		return false;
	});
	
	$("#task").submit(function(e) {
	  
	  	checkcontactcount();
	  
	});
	
	function checkcontactcount()
	{
		$('#finalcontactlist').val(popupcontactlist);
		return true;
	}

<?php 
if(!empty($editRecord[0]['id']) && !empty($contacts_data)){
	foreach($contacts_data as $row){?>
	
		var arrayindex = jQuery.inArray( "<?=!empty($row['contact_id'])?$row['contact_id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox:checkbox[value='+<?=!empty($row['contact_id'])?$row['contact_id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['contact_id'])?$row['contact_id']:''?>;
		}
	
<?php }
}
?>
</script>