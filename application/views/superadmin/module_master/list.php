<?php 
    /*
        @Description: superadmin module_master list
        @Author: Mohit Trivedi
        @Date: 12-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));

?>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('module_master_header');?>
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>
              <?=$this->lang->line('module_master_header');?>
            </h3>
            
            <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
            <ul class="nav nav-tabs" id="myTab1">
              <li class="active"> <a title="Contact Information" data-toggle="tab" href="#mydata" onclick="load_view('my_data');">
                <?=$this->lang->line('module_master_header');?>
                </a> </li>
            </ul>
            <input type ="hidden" id="selected_view" name="selected_view">
            <div class="row dt-rt">
              <?php if(!empty($msg)){?>
              <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div>
              <?php } ?>
              
            </div>
            <div class="tab-content" id="myTab1Content">
              <div class="" id="mydata" >
                <div class="table_large-responsive">
                  <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                      <div class="col-sm-1"> </div>
                      <div class="col-sm-11">
                        <div class="dataTables_filter" id="DataTables_Table_0_filter">
                          <label>
                            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                            <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                            <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
                          </label>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row dt-rt">
                      <div class="col-sm-6">
                        <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Template">Delete Template</button>
                      </div>
                      <div class="col-sm-6"> <a class="btn  pull-right btn-secondary-green howler" href="<?=base_url('superadmin/'.$viewname.'/add_record');?>" title="Add Template">Add Module</a> </div>
                    </div>
                    <div id="common_div">
                      <?=$this->load->view('superadmin/'.$viewname.'/ajax_list')?>
                    </div>
                  </div>
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
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Template Details</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup text-center">
          <div id="row_data"> </div>
          <input type="submit" class="btn btn-secondary" value="Print" onClick="Popup()" name="print" />
          <div id="previewformdata"> </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- #content --> 
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000);
    });
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>superadmin/module_master/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,selected_cat:$("#selected_cat").val()
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
		$("#selected_cat").val("");
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
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),selected_cat:$("#selected_cat").val()
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
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_delete_all';?>",
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
					url: "<?php echo base_url();?>superadmin/module_master/",
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
				var msg = 'Are you sure want to delete Record(s)';
			}
			else
			{
				if(name.length > 50)
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete  "'+name +'"';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 


</script> 
