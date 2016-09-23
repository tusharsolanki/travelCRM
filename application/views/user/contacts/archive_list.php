<?php 
    /*
        @Description: user contact list
        @Author: Niral Patel
        @Date: 07-05-14
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
   <h1><?=$this->lang->line('contact_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Archived <?=$this->lang->line('Contact_list_head');?></h3>
	   <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>   
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table_large-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
				<?php if(!empty($msg)){?>
					<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
					
					<div id="temp_dev" class="col-sm-12 text-center error"></div>
    	 <div class="col-sm-12 col-lg-7">
			   <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Contacts">Delete Contacts</button>
               <button class="btn btn-success howler" data-type="danger" onclick="archivecontactdata('0');" title="Un-Archive">Un-Archive</button>
                <a title="Back to List" class="btn btn-success howler" href="<?php echo $this->config->item('user_base_url')?><?php echo $viewname;?>">Back to List</a> 				
          </div>
          
          <div class="col-lg-5 col-sm-12 col-xs-12">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                <input class="" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search Contacts">Search</button>
                <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All Contacts">View All</button>
            </label>
           </div>
          </div>
          
          
         </div>
         <!--<div class="row dt-rt">
          
          <div class="col-sm-11">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
             <input class="col-xs-7" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search...">
			 <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search();">Search</button>
            </label>
           </div>
          </div>
         </div>-->
         
         
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname.'/archive_ajax_list')?>
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
	function export_confirm()
		{      
			$.confirm({'title': 'CONFIRM','message': " <strong> Would you like to export contact(s) to CSV "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			var url='<?=base_url('user/'.$viewname.'/export');?>';
			 window.location= url;
			}},'No'	: {'class'	: 'special'}}});
} 
</script>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
	 $("#temp_dev").css('display','none'); 
	 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/contacts/view_archive/"+uri_segment,
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
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
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
		
	//$('#selecctall').click(function(event) {  //on click
	$('body').on('click','#selecctall',function(e){
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
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':myarray,'single_remove_id':id,'archive':'archive'},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
			success: function(data){
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/contacts/view_archive/"+data.pagingid,
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
	function check_assign_contact()
	{      
			var boxes = $('input[name="check[]"]:checked');
			var user_id = $('#slt_user_type').val();
			
			if(boxes.length == '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select contacts "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select Contacts')
				$('#selecctall').focus();
				return false;
			}
			else if(user_id == '')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select User')
				$('#slt_user_type').focus();
				return false;
			}
			else
			{
				assign_contact();
			}
						
	} 
	function assign_contact()
	{      
		var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  
				  i++;
			});
		var user_id = $('#slt_user_type').val();
			
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/assign_contact';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'user_id':user_id},
			success: function(data_temp){
				if(data_temp.msg == '1')
				{
				$("#temp_dev").css('display','block');
					$("#temp_dev").html('Contact(s) assigned successfully.');
					$("#temp_dev").fadeOut(4000);
				}
				else
				{
					$("#temp_dev").css('display','block');
					$("#temp_dev").html('Contact(s) are already assigned to another user.');
					$("#temp_dev").fadeOut(4000);
				}
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/contacts/view_archive/"+data_temp.page,
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
			if(boxes.length == '0' && id== '0')
			{
				
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select Contacts')
				$('#selecctall').focus();
				return false;
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to delete record(s)';
			}
			else
			{
				if(name.length > 50)
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+name+'"';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+unescape(msg)+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 
	
	function archivecontactdata(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0' && id== '0')
			{
				
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to un-archive. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select Contacts')
				$('#selecctall').focus();
				return false;
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to add record(s) to list';
			}
			else
			{
				if(name.length > 50)
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to add "'+name +'" to list';
				//var msg = 'Are you sure want to add '+name+' to list';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+unescape(msg)+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							archive_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 
	
	function archive_all(id)
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
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_add_to_active_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
			
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/contacts/view_archive/"+data.pagingid,
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


</script>