<?php 
    /*
        @Description: user InterCommunications list
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
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
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
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('interaction_plans_archive');?></h3>
	   <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
      </div>
      <!-- /.portlet-header -->
      <div class="portlet-content">
       <div class="table-responsive">
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
           <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." />
			<button class="btn btn-success howler" data-type="danger" onclick="contact_search();" title="Search">Search</button>
			<button class="btn btn-success howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
		</label>
           </div>
		  </div>
         
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
           <button class="btn btn-danger howler" data-type="danger" onclick="active_plan('0');" title="Un-Archive">Un-Archive</button>
          </div>
          <div class="col-sm-6">
		 	<a class="btn  pull-right btn-success howler" href="<?=base_url('user/'.$viewname);?>" title="Back to List">Back to List</a>
         
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname.'/ajax_list_archive')?>
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
	function pubunpub_data(id)
{
	url = "<?php echo  $this->config->item('user_base_url').$viewname; ?>/publish_record/"+id;
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
<script>
		 //function for search data
		 function delete_record()
		 {
		 	/*$.confirm({
			'title': 'Logout','message': " <strong> Are you sure you want to logout?",'buttons': {'Yes': {'class': 'special',
			'action': function(){
					$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>user/contact/",
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
			contact_search();
		}
		function changepages()
		{
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
				url: "<?php echo base_url();?>user/interaction_plans/view_archive",
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
						contact_search();
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
	function active_plan(name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0')
			{
			$.confirm({'title': 'Alert','message': " <strong> Please select communication plan "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});	
			//alert('Select Contacts');
			return false;}
			else
			{
				$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to add record(s) to list "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							active_all_plans();
						}},'No'	: {'class'	: 'special'}}});
				//active_all_plans();
			}
	} 
	function active_plan_single(name,id)
    {   
		if(id.length > 50)
		{
			id = unescape(id).substr(0, 50)+'...';
		var msg = 'Are you sure want to add "'+id +'" to list';
		}
		else
		{
			var msg = 'Are you sure want to add "'+unescape(id) +'" to list';
		}
		
           $.confirm({'title': 'CONFIRM','message': " <strong> "+ msg +" <strong>?</strong>",'buttons': {'Yes': {'class': '',
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
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_Active_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':myarray,'single_active_id':name},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
			success: function(data1){
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/interaction_plans/view_archive/"+data1,
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
				return false;
			},error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
		});
	}
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
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/interaction_plans/view_archive",
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
				var msg = 'Are you sure want to delete "'+name+'"';
				}
				else
				{
					var msg = 'Are you sure want to delete "'+unescape(name)+'"';
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	'action': function(){
				delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 

	$('body').on('click','.view_contacts_btn',function(e){
	
			planid = $(this).attr('data-id');
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_contacts_of_interaction_plan';?>",
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

</script>
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
		  
		  <?php /*?><?php $this->load->view('user/interaction_plans/view_contact_popup');?><?php */?>
		  
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>