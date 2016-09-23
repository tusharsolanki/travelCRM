<?php 
    /*
        @Description: superadmin InterCommunications list
        @Author: Mit Makwana
        @Date: 14-07-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
?>
 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('interaction_plans_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('interaction_plans');?></h3>
      </div>
      <!-- /.portlet-header -->
      <div class="portlet-content">
       <div class="">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
		<?php if(!empty($msg)){?>
					<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
          <div class="col-sm-1">
           
          </div>
          <div class="col-sm-12">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
             <input class="" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                        <button class="btn btn-success howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
			<button class="btn btn-success howler" data-type="danger" onclick="clearfilter_contact();" title="View All" title="View All">View All</button>
		</label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-4 col-lg-4">
           <button class="btn btn-danger howler" data-type="danger" onclick="active_plan('0');" title="Add To Archive List">Add To Archive List</button>
          </div>
          <div class="col-sm-8 col-lg-8">
		  
		  <a title="Add Communication" class="btn  pull-right btn-secondary-green howler margin-left-5px" href="<?=base_url('superadmin/'.$viewname.'/add_record');?>">Add Communication</a>
		  <a title="View Archive" class="btn  pull-right btn-success howler" href="<?=base_url('superadmin/'.$viewname.'/view_archive');?>">View Archive</a>
          
          </div>
         </div>
         <div id="common_div" class="table-responsive2">
         <?=$this->load->view('superadmin/'.$viewname.'/ajax_list')?>
         </div>
        </div>
       </div>
       <!-- /.table-responsive --> 
       
      </div>
      <!-- /.portlet-content --> 
      
     </div>
    </div>
   </div>
  </div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
 </div>
 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	function active_plan(name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0')
			{
			$.confirm({'title': 'Alert','message': " <strong> Please select communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			//alert('Select Contacts');
			return false;}
			else
			{
				$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							active_all_plans();
						}},'No'	: {'class'	: 'special'}}});
				
			}
	} 
 
	function active_plan_single(name,id)
    {
		
			if(id.length > 50)
				{
				var msg = 'Are you sure want to delete '+unescape(id).substr(0, 50)+'...';
				
				}
				else
				{
					var msg =unescape(id);
				}
 
		$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
		'action': function(){
				active_all_plans(name);
		}},'No'	: {'class'	: 'special'}}});
    } 
	function active_all_plans(name)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  i++;
			});
			
			if(name != '0')
			{
				var single_active_id = name;
			}
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_Inactive_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':myarray,'single_active_id':name},
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
			success: function(data){
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>superadmin/interaction_plans/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
				},beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div").html(html);
						$('#common_div').unblock(); 
					}
				});
				return false;
			},error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
		});
	}
	
	function pubunpub_data(count1,id)
{
	if(count1 == 1)
	{
		url = "<?php echo  $this->config->item('superadmin_base_url').$viewname; ?>/publish_record/"+id;
		
	}
	else
	{
		url = "<?php echo  $this->config->item('superadmin_base_url').$viewname; ?>/unpublish_record/"+id;
		
	}
	$.ajax({
			type: "POST",
			url :url,
			async: false,
			success: function(data){
			
				$("#view_archive_"+id).hide();
				
			
			}
	});
}

</script>
</script>
<script>
		 //function for search data
		 function delete_record()
		 {
		 	/*$.confirm({
			'title': 'Logout','message': " <strong> Are you sure you want to logout?",'buttons': {'Yes': {'class': 'special',
			'action': function(){
					$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>superadmin/contact/",
				data: {
				result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
			},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					  },
				success: function(html){
				 	$("#common_div").html(html);
					$('#common_div').unblock(); 
				}
			});
			}},'No'	: {'class'	: ''}}});*/	 
		 }
		function clearfilter_contact()
		{
			$("#searchtext").val("");
			contact_search('all');
		}
		function changepages()
		{
			contact_search('');
		}
	  function applysortfilte_contact(sortfilter,sorttype)
		{
			$("#sortfield").val(sortfilter);
			$("#sortby").val(sorttype);
			contact_search('changesorting');
		}
		function contact_search(allflag)
		{
            var uri_segment = $("#uri_segment").val();    
			$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>superadmin/interaction_plans/"+uri_segment,
				data: {
				result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
			},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					  },
				success: function(html){
				 	$("#common_div").html(html);
					$('#common_div').unblock(); 
				}
			});
			return false;
		}
		 $(document).ready(function(){
		  $('#searchtext').keyup(function(event) 
		  {
			  /*if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}*/
				
				if (event.keyCode == 13) {
						contact_search('changesearch');
				}
			//return false;
			});
			
			});
         $('body').on('click','#common_tb a.paginclass_A',function(e){
		// $("#common_tb a.paginclass_A").click(function() {
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div").html(html);
					$.unblockUI();
                }
            });
            return false;

        });
	$('body').on('click','#selecctall',function(e){		
	//$('#selecctall').click(function(event) {  //on click
     if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  i++;
			});
			if(id != '0')
			{
				var single_remove_id = id;
			}
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>superadmin/interaction_plans/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
				},
				beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div").html(html);
						$('#common_div').unblock(); 
					}
				});
				return false;
			}
		});
	}
	
	function deletepopup1(id,name)
	{      
			
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0' && id == '0')
			{return false;}
			
	   		if(id == '0')
			{
				var msg = 'Are you sure want to delete record(s)';
			}
			else
			{
				if(name.length > 50)
				{
					name = unescape(name).substr(0, 50)+'...';
				var msg = 'Are you sure want to delete '+name+'?';
				}
				else
				{
					var msg =unescape(name);
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	'action': function(){
				delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 

	$('body').on('click','.view_contacts_btn',function(e){
	
			$(".view_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	
			planid = $(this).attr('data-id');
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/view_contacts_of_interaction_plan';?>",
			data: {'interaction_plan':planid},
			success: function(html){
				$(".view_contact_popup").html(html);	
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  	//console.log(textStatus, errorThrown);
			  	$(".view_contact_popup").html('Something went wrong.');
			}
			});
	});
	
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
					contact_search();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
				});
			
			}},'No'	: {'class'	: 'special'}}});
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
					contact_search();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
			});
			
		}},'No'	: {'class'	: 'special'}}});
			
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
						contact_search();
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
						//$(".view_contact_popup").html('Something went wrong.');
					}
					});
				}
			
			}},'No'	: {'class'	: 'special'}}});
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
				contact_search();
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
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Assigned Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_contact_popup">
          
		  <div class="text-center">
		  	<img src="<?=base_url()?>images/ajaxloader.gif" />
		  </div>
		  
		  <?php /*?><?php $this->load->view('superadmin/interaction_plans/view_contact_popup');?><?php */?>
		  
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
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
});

function premium_plan_released(id)
{
	$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to release communication <strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
			window.location = '<?=$this->config->item('superadmin_base_url').$viewname; ?>/released_premium_plan/'+id
	}},'No'	: {'class'	: 'special'}}});
}
</script>