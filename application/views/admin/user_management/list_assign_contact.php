<?php 
    /*
        @Description: Admin contact list
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
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
		<div id="temp_dev1" class="col-sm-12 text-center error"></div>
        <div id="common_div" class="dataTables_wrapper">
			<?php $this->load->view('admin/'.$viewname.'/ajax_list_assign_contact')?>
		</div>
		<div id="temp_dev" class="col-sm-12 text-center error"></div>
		 <div id="common_div1" class="dataTables_wrapper">
		 	
			<?php $this->load->view('admin/'.$viewname.'/ajax_list_select_contact')?>
		</div>
		
         <?php /*?><?php $this->load->view('admin/'.$viewname.'/ajax_list_assign_contact')?>
		 <?php $this->load->view('admin/'.$viewname.'/ajax_list_select_contact')?><?php */?>
		
			
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  

 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
	function export_confirm()
		{      
			$.confirm({'title': 'CONFIRM','message': " <strong> Would you like to export contact(s) to CSV "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			var url='<?=base_url('admin/'.$viewname.'/export');?>';
			 window.location= url;
			}},'No'	: {'class'	: 'special'}}});
} 
</script>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000);
	  $("#temp_dev").css('display','none');  
    });
	
	
		
	//$('#selecctall').click(function(event) {  //on click
	</script>
	
<script>
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
$('body').on('click','#selecctall1',function(e){
     if(this.checked) { // check select status
         $('.mycheckbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox1').each(function() { //loop through each checkbox
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
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/user_management/",
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
					var msg = 'Are you sure want to delete '+unescape(name)+'?';
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 

function pubunpub_data(count1,id)
{
	if(count1 == 1)
	{
		url = "<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_record/"+id;
		html = '<a onclick="pubunpub_data(0,'+id+');" href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-check-circle"></i></a> &nbsp;';
		html1 = 'Active';
	}
	else
	{
		url = "<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_record/"+id;
		html = '<a onclick="pubunpub_data(1,'+id+');" href="javascript:void(0);" class="btn btn-xs btn-primary"><i class="fa fa-times-circle"></i></a> &nbsp;';
		html1 = 'Inactive';
	}
	$.ajax({
			type: "POST",
			url :url,
			async: false,
			success: function(data){
			
				$(".pubunpub_span_"+id).html(html);
				$(".status_span_"+id).html(html1);
			
			}
	});
}


	
</script>

<script>
		//assign contact remove		 
		 function delete_assign_record(id)
		{
			var perpage_no1 = $('#perpage_no1').val();
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/delete_assign_record/'.$this->uri->segment(4);?>",
			dataType: 'json',
			async: false,
			data: {'id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/user_management/edit_record/<?php echo $this->uri->segment(4).'/2/'; ?>"+data,
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
	
	function deletepopup_assign_contact(id,name)
	{      
			if(name.length > 50)
				name = name.substr(0, 50)+'...';
			var msg = 'Are you sure want to delete '+unescape(name);
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_assign_record(id);
						}},'No'	: {'class'	: 'special'}}});
	}
	
	function delete_assign_record1(id)
		{
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/delete_assign_record';?>",
			dataType: 'json',
			async: false,
			data: {'id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/user_management/edit_record/<?php echo $this->uri->segment(4); ?>",
					data: {
					result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
				},
				beforeSend: function() {
							$('#common_div1').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div1").html(html);
						$('#common_div1').unblock(); 
					}
				});
				return false;
			}
		});
	}
	
	function deletepopup_assign_contact1(id,name)
	{      
			if(name.length > 50)
				name = name.substr(0, 50)+'...';
			var msg = 'Are you sure want to delete '+unescape(name);
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_assign_record1(id);
						}},'No'	: {'class'	: 'special'}}});
	}
	
	
	</script>
	
	
	<script>
	$('body').on('click','#common_tb_u a.paginclass_A',function(e){
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
		
		$('body').on('click','#common_tb1 a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div1").html(html);
					$.unblockUI();
                }
            });
            return false;
        });
	function contact_search()
	{
		var perpage = $("#perpage").val();
		$.ajax({
			type: "POST",
url: "<?php echo base_url();?>admin/user_management/edit_record/<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:0; ?>/",
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
		},
		beforeSend: function() {
					//$('#common_div').block({ message: 'Loading...' }); 
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
	
</script>
<script>
	
	 $(document).ready(function(){
		  /*$('#searchtext1').keyup(function(event) 
		  {
		  if (event.keyCode == 13) {
			
						contact_search1();
				}
			//return false;
		  });*/
	});
	
	function clearfilter_contact1()
	{
		$("#searchtext1").val("");
		contact_search1();
	}
	
	function changepages1()
	{
		contact_search1();	
	}
	
  	function applysortfilte_contact1(sortfilter1,sorttype1)
	{
		
		$("#sortfield1").val(sortfilter1);
		$("#sortby1").val(sorttype1);
		contact_search1();
	}
	function contact_search1()
	{
		var searchstr = $('#searchtext1').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/user_management/edit_record/<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:0;?>/",
			data: {
			result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
		},
		beforeSend: function() {
					$('#common_div1').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#common_div1").html(html);
				if(searchstr != '')
				{
					$("#searchtext1").val(searchstr);
				}
				$('#common_div1').unblock(); 
			}
		});
		return false;
	}
	
	function check_assign_contact()
	{      
			var boxes = $('input[name="check[]"]:checked');
			var user_id = $('#slt_user_type1').val();
			
			/*if(user_id == '')
			{
				alert('Please Select User')
				$('#slt_user_type1').focus();
				return false;
			}
			else*/ 
			if(boxes.length == '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select contact(s) to assign. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
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
		var user_id = $('#slt_user_type1').val();
			
			var perpage_no = $("#perpage_no").val()
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').'user_management/assign_contact';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'user_id':user_id},
			success: function(data){
				
				$.ajax({
					type: "POST",
					async: false,
					url: "<?php echo base_url();?>admin/user_management/edit_record/<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'0'; ?>/2/"+perpage_no,
					data: {
					result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
				},
				beforeSend: function() {
							$('#common_div1').block({ message: 'Loading...' }); 
							//$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div1").html(html);
						//$("#common_div").html(html);
						$('#common_div1').unblock(); 
						//$('#common_div').unblock(); 
						contact_search();
					}
				});
				
				if(data == '1')
				{
				$("#temp_dev").css('display','block');
					$("#temp_dev").html('Contact has been assigned');
					$("#temp_dev").fadeOut(4000);
				}
				else
				{
					$("#temp_dev").css('display','block');
					$("#temp_dev").html('Some Contact already assigned');
					$("#temp_dev").fadeOut(4000);
				}
				
				return false;
			}
		});
	}
// new code for contact assign by mohit trivedi for assign contact tab contact assign to other user...
//date:- Oct11,2014

	function check_assign_contact1()
	{      
			var boxes = $('input[name="check[]"]:checked');
			var user_id = $('#slt_user_type2').val();
			if(boxes.length == '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select contacts "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall1').focus();
				return false;
			}
			else if(user_id == '')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#slt_user_type2').focus();
				return false;
			}
			else
			{
				assign_contact1();
			}
						
	} 
	function assign_contact1()
	{      
		
		var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  
				  i++;
			});
			var user_id = $('#slt_user_type2').val();
			var perpage_no = $("#perpage_no").val()
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').'user_management/assign_contact1';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'user_id':user_id},
			success: function(data){
				
				$.ajax({
					type: "POST",
					async: false,
					url: "<?php echo base_url();?>admin/user_management/edit_record/<?=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'0';?>/2/"+perpage_no,
					data: {
					result_type:'ajax',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
				},
				beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div").html(html);
						$('#common_div').unblock(); 
						contact_search();
					}
				});
				
				if(data == '1')
				{
				$("#temp_dev1").css('display','block');
					$("#temp_dev1").html('Contact has been assigned');
					$("#temp_dev1").fadeOut(4000);
				}
				else
				{
					$("#temp_dev1").css('display','block');
					$("#temp_dev1").html('Some Contact already assigned');
					$("#temp_dev1").fadeOut(4000);
				}
				
				return false;
			}
		});

// end of code			
			
	}
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
	
</script>