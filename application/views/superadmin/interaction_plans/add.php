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
    <h1>
      <?=$this->lang->line('interaction_plans_header');?>
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>
			<?php if(empty($editRecord)){ echo $this->lang->line('interaction_add_table_head');}else { echo $this->lang->line('interaction_edit_table_head');}?>
            </h3>
            <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
          </div>
          <!-- /.portlet-header -->
          <div class="portlet-content">
            <div class="table-responsive">
              <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
                  <div class="row dt-rb form">
                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                    <div class="col-sm-7">
					
					<?php if(!empty($editRecord[0]) && !empty($editRecord[0]['total_interactions'])){ ?>
					
					<div class="row" style="display:none;">
                        <div class="col-xs-12 mrgb2">
                        <fieldset class="edit_main_div">
                        <legend class="edit_title">Run Interintraction Plan</legend><div class="cf"></div>
                        <div class="col-lg-4">
                       <div class="edit_btn">
					      
					   	   <?php if(!empty($editRecord[0]['plan_status_name']) && (strtolower($editRecord[0]['plan_status_name']) == 'active')){ ?>
								<a href="javascript:void(0);" class="btn btn-xs btn-success mrgr1 no_cursor" title="Play"><i class="fa fa-play"></i></a>
							<?php }else{?>
							
								<?php if(!empty($editRecord[0]['plan_status_name']) && (strtolower($editRecord[0]['plan_status_name']) == 'stop')){ ?>
									<a href="javascript:void(0);"  class="btn btn-xs btn-secondary-new mrgr1 play_interaction_plan" data-group="stop" data-id="<?=$editRecord[0]['id']?>" title="Play"><i class="fa fa-play"></i></a>
								<?php }else{ ?>
									<a href="javascript:void(0);"  class="btn btn-xs btn-secondary-new mrgr1 play_interaction_plan" data-group="" data-id="<?=$editRecord[0]['id']?>" title="Play"><i class="fa fa-play"></i></a>
								<?php } ?>
							
								<!--<a href="javascript:void(0);"  class="btn btn-xs btn-secondary-new mrgr1 play_interaction_plan" data-id="<?=$editRecord[0]['id']?>" title="Play"><i class="fa fa-play"></i></a>-->
							<?php } ?>
							
							<?php if(!empty($editRecord[0]['plan_status_name']) && (strtolower($editRecord[0]['plan_status_name']) == 'paused')){ ?>
								<a href="javascript:void(0);"  class="btn btn-xs btn-warning mrgr1 no_cursor" title="Paused"> <i class="fa fa-pause"></i></a>
							<?php }else{?>
								<a href="javascript:void(0);"  class="btn btn-xs btn-secondary-new mrgr1 pause_interaction_plan" data-id="<?=$editRecord[0]['id']?>" title="Pause" > <i class="fa fa-pause"></i></a>
							<?php } ?>
							
							<?php if(!empty($editRecord[0]['plan_status_name']) && (strtolower($editRecord[0]['plan_status_name']) == 'stop')){ ?>
								<a href="javascript:void(0);"  class="btn btn-xs btn-primary no_cursor"  title="Stop"> <i class="fa fa-stop"></i></a>
							<?php }else{?>
								<a href="javascript:void(0);"  class="btn btn-xs btn-secondary-new stop_interaction_plan" data-id="<?=$editRecord[0]['id']?>" title="Stop"> <i class="fa fa-stop"></i></a>
							<?php } ?>
					   </div>
                        </div>
                             <div class="col-lg-8">
                      <div class="edit_date"> 
					  	<!--<span>Paused From 12/14/2013</span>
                        <span>Paused beetween 12/14/2013 to 12/14/2013</span>
                        <span>Paused beetween 12/14/2013 to 12/14/2013</span>-->
						<?php if(!empty($interaction_plan_time_trans)){
							for($i=0;$i<count($interaction_plan_time_trans);$i++){ ?>
							<span>
								<?php 
								if($interaction_plan_time_trans[$i]['interaction_time_type'] == 2){ 
									echo "Paused : ".date($this->config->item('common_datetime_format'),strtotime($interaction_plan_time_trans[$i]['interaction_time']));
								}
								if($interaction_plan_time_trans[$i]['interaction_time_type'] == 3){ 
									echo "Stop : ".date($this->config->item('common_datetime_format'),strtotime($interaction_plan_time_trans[$i]['interaction_time']));
								}
								if($interaction_plan_time_trans[$i]['interaction_time_type'] == 4){ 
									echo "Play : ".date($this->config->item('common_datetime_format'),strtotime($interaction_plan_time_trans[$i]['interaction_time']));
								}
								?>
							</span>
						<?php }} ?>
					  </div>
                        </div>
                        </fieldset>
                                             
                      </div>
                   </div>
					
				<?php } ?>
					
                      <div class="row">
                        <div class="col-xs-12 mrgb2 form-group">
                          <label for="text-input">
                          <?=$this->lang->line('interaction_plan_name');?>
                          :<span class="mandatory_field">*</span></label>
                          <input type="text" id="txt_plan_name" name="txt_plan_name" placeholder="e.g. Communication Name" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['plan_name'])){ echo htmlentities($editRecord[0]['plan_name']); }?>" placeholder="" data-required="true">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2">
                          <label for="text-input">
                          <?=$this->lang->line('interaction_description');?>
                          :</label>
                          <textarea id="txtarea_description"  placeholder="e.g. Description" name="txtarea_description" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['description'])){ echo $editRecord[0]['description']; }?>
</textarea>
                        </div>
                      </div>
                      <div class="row" <?php if(empty($editRecord)){ echo "style='display:none;'";}?> >
                        <div class="col-xs-12 mrgb2">
                          <label for="text-input">
                          <?=$this->lang->line('interaction_add_status');?>
                          :</label>
                          <input id="txt_interaction_status" name="txt_interaction_status" class="form-control parsley-validated" type="text" readonly value="<?php if(!empty($editRecord[0]['plan_status_name'])){ echo $editRecord[0]['plan_status_name'];}else{if(!empty($interaction_plan_status[0]['name'])){ echo $interaction_plan_status[0]['name']; }}?>">
                          <input id="interaction_plan_status_id" name="interaction_plan_status_id" type="hidden" value="<?php if(!empty($interaction_plan_status[0]['id'])){ echo $interaction_plan_status[0]['id']; }?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2">
                          <label for="text-input">
                          <?=$this->lang->line('interaction_target_audience');?>
                          :</label>
                          <textarea placeholder="e.g. Target Audience" id="txtarea_target_audience" name="txtarea_target_audience" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['target_audience'])){ echo $editRecord[0]['target_audience']; }?>
</textarea>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2 icheck-input-new">
                          <!-- <strong class="assign_title"></strong>-->
                          <div class="form-group">
                            <label>
                            <?=$this->lang->line('interaction_plan_start_date');?>
                            </label>
							
							<?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01' && (date('m/d/Y') > date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])))){ }else{ ?>
							
                            <div class="">
                              <label class="">
                              <div>
                                <input type="radio" data-required="true" class="fleft" name="plan_start_date" value="1" <?php if(!empty($editRecord[0]['plan_start_type']) && $editRecord[0]['plan_start_type'] == '1'){ echo 'checked="checked"'; }?> checked="checked" onclick="hide_start_date(this.value);">
                                <label class="radio_label">
                                <?=$this->lang->line('interaction_concacts_assignment_date');?>
                                </label>
                              </div>
                              </label>
                            </div>
							
							<?php } ?>
                            
							
							<?php if(empty($editRecord[0]['plan_start_type']) || $editRecord[0]['plan_start_type'] == '2'){ ?>
							
							<!--<div class="form-group">-->
                            <div class="">
                              <label class="">
                              <div>
                                <input type="radio" data-required="true" class="fleft" id="plan_start_date" name="plan_start_date" value="2" <?php if(!empty($editRecord[0]['plan_start_type']) && $editRecord[0]['plan_start_type'] == '2'){ echo 'checked="checked"'; }?> onclick="hide_start_date(this.value);">
                                <label class="radio_label">
                                <?=$this->lang->line('interaction_start_date');?>
                                </label>
                              </div>
                              </label>
                              <div class="col-sm-12 col-lg-12 txt_plan_start_date" style="display:none;">
                                <input id="txt_start_date" placeholder="Specific Date" name="txt_start_date" class="form-control col-sm-6 parsley-validated" type="text" 
								 <?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01' && (date('m/d/Y') > date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])))){ echo " readonly ";} ?>
								 value="<?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])); }?>" readonly="readonly">
                              </div>
                            </div>
                            <!--</div>-->
							
							<?php } ?>
							
							
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div class="col-sm-12 topnd_margin1"> <strong class="assign_title">Assign Users</strong> <a data-toggle="modal" class="text_color_red text_size" href="#basicModal"><i class="fa fa-plus-square"></i> Select Users</a> </div>
                      <div class="col-sm-12 added_contacts_list">
                        
						<?php $this->load->view('superadmin/interaction_plans/selected_contact_ajax')?>
						
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center margin-top-10">
                    
					<input type="submit" class="btn btn-secondary-green" value="Save Communication" name="submitbtn" onclick="return checkcontactcount();" />
                     <input type="submit" class="btn btn-secondary" value="Save & Add Action" name="submitbtn_action" onclick="return checkcontactcount();" />
					<input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" />
                    <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
                </form>
              </div>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.portlet-content -->
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
<?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01' && (date('m/d/Y') > date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])))){}else{ ?>
$(document).ready(function(){
	$( "#txt_start_date" ).datepicker({
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
<?php } ?>
</script>
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
        
        <!--<div class="row dt-rt">
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
        </div>-->
        
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
		  <?php $this->load->view('superadmin/interaction_plans/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Assign to Communication</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
});

	
	<?php if(!empty($editRecord[0]['plan_start_type']) && $editRecord[0]['plan_start_type'] == '2') { ?>
		hide_start_date(<?=$editRecord[0]['plan_start_type']?>);
	<?php } ?>
	function hide_start_date(value)
	{
		if(value == 1)
			$(".txt_plan_start_date").hide();
		else
			$(".txt_plan_start_date").show();	
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
		$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
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
			url: "<?php echo base_url();?>superadmin/interaction_plans/search_contact_ajax/",
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
			url: "<?php echo base_url();?>superadmin/interaction_plans/add_contacts_to_interaction_plan/",
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
	
	/*$('body').on('click','.mycheckbox',function(e){
		
		if($('.mycheckbox:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
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
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(contact_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
	}
	
	//function removeempfromlist(contactid)
	$('body').on('click','.remove_selected_contact',function(e){
		var myvalue = $(this).data("id");
		var plan = $(this).data("group");
		var mytr = $(this).closest("tr");
		
		$.confirm({
		'title': 'Delete','message': " <strong> Are you sure want to remove contact from communication?",'buttons': {'Yes': {'class': '',
		'action': function(){
			
			/*alert(myvalue);*/
			var arrayindex = jQuery.inArray( myvalue, popupcontactlist );
			//alert(myvalue+'-'+JSON.stringify(popupcontactlist));
			//alert(arrayindex);
			if(arrayindex >= 0)
			{
				$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
				mytr.remove();
			}
			$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');
			
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/delete_contact_from_plan';?>",
				data: {'interaction_plan':plan,'contact_id':myvalue},
				success: function(html){
					//$(".view_contact_popup").html(html);	
					//contact_search();
					//window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
			});
				
		}},'No'	: {'class'	: 'special'}}});
		
		
		
		return false;
	});
	
	$("#interaction_plans").submit(function(e) {
	  
	  	checkcontactcount();
		
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
	  
	});
	
	function checkcontactcount()
	{
		if($('#plan_start_date').is(':checked') && $("#txt_start_date").val().trim() == '')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select start date. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#txt_start_date').focus();
				return false;
		}
		else
		{
			$('#finalcontactlist').val(popupcontactlist);
			return true;
		}
		//alert(JSON.stringify(popupemplist));
		//$('#finalcontactlist').val(popupcontactlist);
		//return true;
	}

<?php 
if(!empty($editRecord[0]['id']) && !empty($contacts_data)){
	foreach($contacts_data as $row){?>
	
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>
</script>
<script type="text/javascript">
	
	$('body').on('click','.pause_interaction_plan',function(e){
	
			planid = $(this).attr('data-id');
			
			$.confirm({
		'title': 'Confirm Message','message': " <strong> Are you sure want to pause communication?",'buttons': {'Yes': {'class': '',
		'action': function(){
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/pause_interaction_plan';?>",
			data: {'interaction_plan':planid},
			success: function(html){
				//$(".view_contact_popup").html(html);	
				//contact_search();
				window.location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  	console.log(textStatus, errorThrown);
			  	//$(".view_contact_popup").html('Something went wrong.');
			}
			});
			
		}},'No'	: {'class'	: 'special'}}});
			
			return false;
			
	});
	
	$('body').on('click','.stop_interaction_plan',function(e){
	
	planid = $(this).attr('data-id');
	
	$.confirm({
		'title': 'Confirm Message','message': " <strong> Are you sure want to stop communication?",'buttons': {'Yes': {'class': '',
		'action': function(){
		
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/stop_interaction_plan';?>",
				data: {'interaction_plan':planid},
				success: function(html){
					//$(".view_contact_popup").html(html);	
					window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
			});
			
		}},'No'	: {'class'	: 'special'}}});
			
			return false;
			
	});
	
	$('body').on('click','.play_interaction_plan',function(e){
	
			planid = $(this).attr('data-id');
			
			if_stop = $(this).attr('data-group');
			
			$.confirm({
		'title': 'Confirm Message','message': " <strong> Are you sure want to play communication?",'buttons': {'Yes': {'class': '',
		'action': function(){
		
			if(if_stop == 'stop')
			{
				//alert('show popup');
				$('#complate_interaction_plan_a').trigger('click');
				$('#hid_current_plan_id').val(planid);
			}
			else
			{
				$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/play_interaction_plan';?>",
				data: {'interaction_plan':planid},
				success: function(html){
					//$(".view_contact_popup").html(html);	
					//contact_search();
					window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
				});
			}
			
		}},'No'	: {'class'	: 'special'}}});
			
			return false;
			
	});
	
	$('body').on('click','.save_interaction_plan_popup',function(e){
		
		planid = $('#hid_current_plan_id').val();
		startdate = $('#r_next_interaction_start_date').val();
		
		$('#basicModal_for .modal-body').block({ message: 'Loading...' }); 
		
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/play_interaction_plan';?>",
			data: {'interaction_plan':planid,'startdate':startdate},
			success: function(html){
				//$(".view_contact_popup").html(html);	
				$('.close_plan_popup').trigger('click');
				//contact_search();
				window.location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('.close_plan_popup').trigger('click');
				console.log(textStatus, errorThrown);
				//$(".view_contact_popup").html('Something went wrong.');
			}
		});
		
		$('#basicModal_for .modal-body').unblock();
		
	});
	
</script>
<a style="display:none;" id="complate_interaction_plan_a" href="#basicModal_for" data-toggle="modal" ></a>
<div aria-hidden="true" style="display: none;" id="basicModal_for" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_plan_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Reschedule Communication</h3>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
		
		<input type="hidden" value="" id="hid_current_plan_id" name="hid_current_plan_id" />
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
		  	
			<tr>
              <td></td>
              <td class="form-group">
			   Plan Start Date:
               <input id="r_next_interaction_start_date" name="r_next_interaction_start_date" class="form-control parsley-validated" readonly="readonly" type="text" value="">
              </td>
            </tr>
			
          </table>
		 
        </div>
		<div class="col-sm-12 text-center mrgb4">
			<input type="submit" value="Save" class="btn btn-secondary save_interaction_plan_popup">
		  </div>
      </div>
      
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<script type="text/javascript">
$(function(){
	
	$( "#r_next_interaction_start_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	
	/*$('body').on('click','.add_contact_to_array',function(e){

		value = $(this).attr('data-id');
		
		//alert(value);
	
		//$('.mycheckbox:checkbox[value="' + value + '"]').attr('checked','checked');
		
		//$(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
	
		$('.mycheckbox:checkbox[value="' + value + '"]').trigger('click');
		
	});*/
	
});


</script>