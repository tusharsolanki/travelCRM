<?php
$viewname = $this->uri->segment(2);
?>
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />

<div class="row add_communication_plan_div">

			 <ul id="myTab10" class="nav nav-tabs">
				 <li  class="active"> <a href="#assigned_plans" data-toggle="tab" title="Assigned Plans">Assigned Contacts</a> </li>

				<li> <a href="#add_plans" data-toggle="tab" title="Add Plans">
				   Contact Select <i class="icon-remove-sign"></i></a> </li>
			</ul>
			
			 <?php 
				 $plan_list_array = array();
				 if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
						foreach($communication_trans_data as $rowtrans){
							$plan_list_array[] = $rowtrans['interaction_plan_id'];
					}}		
			?>
				<div id="myTab10Content" class="tab-content">
			
					<div class="smart-drip-plan-con-box1 tab-pane fade in active"  id="assigned_plans">
						<?=$this->load->view('admin/interaction_plans/view_contact_popup');?>
					</div>
					
				   <div class="row tab-pane fade in" id="add_plans">
                   <div class="con_srh">
                      <div class="main">
                        <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax1" class="form-control inputsrh pull-left" name="search_contact_popup_ajax1">
                       <a class="btn btn-success a_search_contacts2 mrg13" href="javascript:void(0);">Search Contacts</a>
                       <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact2();">View All</button>
                       </div>
                    </div>
					 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').$viewname;?>/update_data" novalidate >
					<input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                    <input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" /> 
                    
                    
                    <input id="txt_plan_name" name="txt_plan_name" type="hidden" value="<?=!empty($editRecord[0]['plan_name'])?$editRecord[0]['plan_name']:''?>">
                    <input id="txtarea_description" name="txtarea_description" type="hidden" value="<?=!empty($editRecord[0]['description'])?htmlentities($editRecord[0]['description']):''?>">
                    <input id="txtarea_target_audience" name="txtarea_target_audience" type="hidden" value="<?=!empty($editRecord[0]['target_audience'])?htmlentities($editRecord[0]['target_audience']):''?>">
                    <input id="plan_start_date" name="plan_start_date" type="hidden" value="<?=!empty($editRecord[0]['plan_start_type'])?htmlentities($editRecord[0]['plan_start_type']):''?>">
                    <input id="txt_start_date" name="txt_start_date" type="hidden" value="<?=!empty($editRecord[0]['start_date'])?$editRecord[0]['start_date']:''?>">
                    <div class="row dt-rt">
                      <div class="col-sm-12 table-responsive">
                        <div class="col-sm-4">
                            <select class="form-control parsley-validated" name='contact_type' id='contact_type' onchange="contact_search3();">
                                <option value="">Contact Type</option>
                                <?php if(!empty($contact_type)){
                                        foreach($contact_type as $row){ ?>
                                            <option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                                        <?php } 
                                     } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control parsley-validated" name='slt_contact_source' id='slt_contact_source' onchange="contact_search3();">
                                <option value="">Contact Source</option>
                                <?php if(!empty($source_type)){
                                        foreach($source_type as $row){?>
                                            <option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                                        <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                         <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status" onchange="contact_search3();">
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
                            {
                                    onAdd: function (item) {
                                    contact_search3();
                                },onDelete: function (item) {
                                    contact_search3();
                                },
                                preventDuplicates: true,
                                hintText: "Enter Tag Name",
                                noResultsText: "No Tag Found",
                                searchingText: "Searching...",
                                theme: "facebook"
                            }
                            );
                            
                            //$("#token-input-search_tag").attr("placeholder","Search Tag");
                            
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
                      <div class="new_contact_popup">
                       	<?php 
							$contact_list = array();
							//pr($contact_listdata);exit;
							$data['contact_list'] = $contact_listdata;
							echo $this->load->view('admin/interaction_plans/contact_popup_ajax',$data);
						?>
                      </div>
                    </div>
                    <div class="col-sm-12 text-center mrgb4"></div>
                    <div class="col-sm-12 text-center mrgb4">
					<input type="submit" title="Assign to Communication" class="btn btn-secondary" value="Assign to Communication" name="submitbtn" />
                    </div>
					</form>
					</div>
			
				</div>
	</div>
<script>
var arraydatacount = 0;
var popupcontactlist = Array();
$('body').on('click','#selectall',function(e){	
 
	if(this.checked) { // check select status
	 $('.mycheckbox2').each(function() { //loop through each checkbox
	 
			this.checked = true;  //select all checkboxes with class "mycheckbox2" 
			
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			
			if(arrayindex == -1)
			{
				popupcontactlist[arraydatacount++] = parseInt(this.value);
			}
						 
		});
	}else{
		$('.mycheckbox2').each(function() { //loop through each checkbox
		
			this.checked = false; //deselect all checkboxes with class "mycheckbox2"
			
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

$('body').on('click','#contact_common_tab a.paginclass_A',function(e){
		$.ajax({
			type: "POST",
			url: $(this).attr('href'),
			data: {
			result_type:'ajax_page_contact_popup',searchtext:$("#search_contact_popup_ajax1").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),search_tag:$("#search_tag").val()
		},
		beforeSend: function() {
					$('.new_contact_popup').block({ message: 'Loading...' });
				  },
			success: function(html){
			   
				$(".new_contact_popup").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist.length;i++)
					{
						$('.mycheckbox2:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
					}
				}
				catch(e){}
				
				$('.new_contact_popup').unblock();
			}
		});
		return false;
	});
	
$('#search_contact_popup_ajax1').keyup(function(event) 
{
		if (event.keyCode == 13) {
			contact_search3();
		}
});

$('body').on('click','.a_search_contacts2',function(e){
	contact_search3();
});
// view All Recored
function clearfilter_contact2()
{
	$("#search_tag").tokenInput("clear");
	$("#search_contact_popup_ajax1").val("");
	$('#slt_contact_status').val("");
	$('#slt_contact_source').val("");
	$('#contact_type').val("");
	contact_search3();
}
function changepages3()
{
	contact_search3();
}

function applysortfilte_contact2(sortfilter,sorttype)
{
	$("#sortfield3").val(sortfilter);
	$("#sortby3").val(sorttype);
	contact_search3();
}


function contact_search3()
{
	//alert($("#sortfield3").val());
	$.ajax({
		type: "POST",
		url: "<?php echo base_url();?>admin/interaction_plans/search_contact_ajax/",
		data: {
		result_type:'ajax_page_contact_popup',searchtext:$("#search_contact_popup_ajax1").val(),perpage:$("#perpage3").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield3").val(),sortby:$("#sortby3").val(),search_tag:$("#search_tag").val()
	},
	beforeSend: function() {
				$('.new_contact_popup').block({ message: 'Loading...' }); 
			  },
		success: function(html){
			
			$(".new_contact_popup").html(html);
			
			try
			{
				for(i=0;i<popupcontactlist.length;i++)
				{
					$('.mycheckbox2:checkbox[value='+popupcontactlist[i]+']').attr('checked',true);
				}
			}
			catch(e){}
			
			$('.new_contact_popup').unblock(); 
		}
	});
	return false;
}

function addcontactstointeractionplan()
{
	$.ajax({
		type: "POST",
		url: "<?php echo base_url();?>admin/interaction_plans/add_contacts_to_interaction_plan/",
		data: {
		result_type:'ajax_page_contact_popup',contacts:popupcontactlist
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

function checkbox_checked(contact_id)
{
	if($('.mycheckbox2:checkbox[value='+parseInt(contact_id)+']:checked').length)
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

$("#interaction_plans").submit(function(e) {
  $('#finalcontactlist').val(popupcontactlist);
});
<?php 
if(!empty($editRecord[0]['id']) && !empty($contacts_data)){
	foreach($contacts_data as $row){?>
	
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox2:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>
$('#count_selected_to').text(popupcontactlist.length + ' Record Selected');


</script>