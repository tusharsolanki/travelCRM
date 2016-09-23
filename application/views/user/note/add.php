<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;

?>
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />


<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('user_add_notes_msg');?>
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>
			<?=$this->lang->line('user_add_notes_msg')?>
            </h3>
            
          </div>
         
          <!-- /.portlet-header -->
          <div class="portlet-content">
          	 <?php if(!empty($msg)){?>
         <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
         $newdata = array('msg'  => '');
         $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
            <div class="">
              <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
                  <div class="row dt-rb form">
                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                    <div class="col-sm-6">
					
                      
                      <div class="row">
                        <div class="col-xs-12 mrgb2  form-group">
                          <label for="text-input">
                          <?=$this->lang->line('user_add_notes');?>
                          :<span class="mandatory_field">*</span></label>
                          <textarea  data-required="true" placeholder="e.g. Notes" id="note" name="note" class="form-control parsley-validated">
</textarea>
                        </div>
                      </div>
                      
                      
                      
                    </div>
                    <div class="col-sm-6">
                      <div class="col-sm-12 topnd_margin1"> <strong class="assign_title">Assign Contacts</strong> <a data-toggle="modal" class="text_color_red text_size" href="#basicModal"><i class="fa fa-plus-square"></i> Select Contacts</a> </div>
                      <div class="col-sm-12 added_contacts_list table-responsive">
                        
						<?php $this->load->view('user/note/selected_contact_ajax')?>
						
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center margin-top-10">
                    
					<input type="submit" class="btn btn-secondary-green" value="Save Note" name="submitbtn" onclick="return checkcontactcount();" />
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

$(document).ready(function(){
		 $("#div_msg").fadeOut(4000);
});

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
          <div class="table">
		  <?php $this->load->view('user/interaction_plans/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Assign to Notes</button>
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
                result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),perpage:$("#perpage").val(),search_tag:$("#search_tag").val()
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
		$("#search_tag").tokenInput("clear");
		$("#search_contact_popup_ajax").val("");
		$("#sortfield").val("");
		$("#sortby").val("");
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
			url: "<?php echo base_url();?>user/note/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),perpage:$("#perpage").val(),search_tag:$("#search_tag").val()
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
			url: "<?php echo base_url();?>user/note/add_contacts_to_interaction_plan/",
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
		'title': 'Delete','message': " <strong> Are you sure want to remove contact from notes?",'buttons': {'Yes': {'class': '',
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
				url: "<?php echo $this->config->item('user_base_url').$viewname.'/delete_contact_from_plan';?>",
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
	});
	
	function checkcontactcount()
	{
		//alert(JSON.stringify(popupemplist));
		//if($("#plan_start_date").val() == 2)
		if(popupcontactlist== '')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select record(s). "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#txt_start_date').focus();
				return false;
		}
		else
		{
                    if ($('#<?php echo $viewname?>').parsley().isValid()) {
                        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                    }
			$('#finalcontactlist').val(popupcontactlist);
			return true;
		}
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



