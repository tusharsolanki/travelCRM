<?php 
    /*
        @Description: user lead capturing list
        @Author: Mohit Trivedi
        @Date: 13-09-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<style>
.view_embedform_popup1{ overflow:auto; height:400px;}
</style>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>

<div id="content">
  <div id="content-header">
    <h1>
     <?=$this->lang->line('lead_capturing_header');?>
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>
              <?=$this->lang->line('lead_capturing_header');?>
            </h3>
            <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" id="back" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
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
    	 
         </div>
         <div class="row dt-rt">
          <div class="col-sm-1">
           
          </div>
          <div class="col-sm-11">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
            </label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
           <?php if(!empty($this->modules_unique_name) && in_array('form_builder_delete',$this->modules_unique_name)){?>
           <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Form">Delete Form</button>
           <? } ?>
          </div>
          <div class="col-sm-6">
           <?php if(!empty($this->modules_unique_name) && in_array('form_builder_add',$this->modules_unique_name)){?>
          <a class="btn  pull-right btn-secondary-green howler" href="<?=base_url('user/'.$viewname.'/add_record');?>" title="Add Form">Add Form</a>
          <? } ?>
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname.'/ajax_list')?>
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
 <!----popup data-------->
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Embed Form</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup text-center">
		 <div id="row_data">
         </div>
		<div id="previewformdata">
		</div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
 <!----popup data end-------->
<!---view form data--->
<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade popup_main_div">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
         <h3 class="modal-title">Preview Form</h3>
      </div>
      <div class="modal-body view_embedform_popup1">
        <div class="text-center">
		  	<img src="<?=base_url()?>images/ajaxloader.gif" />
		</div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-----view form data end---->




<!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> --> 
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/lead_capturing/"+uri_segment,
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
				
				if (event.keyCode == 13) {
						contact_search('changesearch');
				}
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
			data: {'myarray':myarray,'single_remove_id':id},
				beforeSend: function() {
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		  },
			success: function(data){
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/lead_capturing/"+data,
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
			},error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
		});
	}
	
	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0' && id== '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
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
				{
					name = unescape(name).substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+name+'"';
				}
				else
				{
					var msg = 'Are you sure want to delete "'+unescape(name)+'"';
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 


</script>
<script type="text/javascript">
	$('body').on('click','.view_form_btn',function(e){
	
			formid = $(this).attr('data-id');
			var form_data = '<table><tr><td align="left"><label for="text-input">iFrame Code:</label></td><td><textarea rows="5" cols="50" class="form-control parsley-validated" onclick="this.focus();this.select();this.copy();"><iframe src="<?php echo $this->config->item('base_url');?>lead_capturing_form/'+formid+'" height="100%" width="100%" frameborder="0" style="boder:none;"></iframe></textarea></td></tr><tr><td align="left"><label for="text-input">Link:</label></td><td><textarea rows="5" cols="50" class="form-control parsley-validated"  onclick="this.focus();this.select();"><a href="<?php echo $this->config->item('base_url');?>lead_capturing_form/'+formid+'" title="New Form">Click Here</a></textarea></td></tr></table>';
			$("#row_data").html(form_data);
	});

</script>
<script type="text/javascript">
	$('body').on('click','.view_form_btndata',function(e){
			formid = $(this).attr('data-id');
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_form_data';?>",
			data: {'id':formid},
			beforeSend: function() {
						$(".popup_main_div .view_embedform_popup1").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
					  },
			success: function(html){
				$(".view_embedform_popup1").html(html);
                                $('.dynamic_dml').hide(); 
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  	$(".view_embedform_popup1").html('Something went wrong.');
			}
			});
	});
        $('body').on('click','#basicModal1',function(e){
            $('.dynamic_dml').show();
        });
</script>
