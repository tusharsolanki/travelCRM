<?php 
    /*
        @Description: user task list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
jQuery(document).ready(function(){
	try{
		var id = parent.$("#slt_interaction_type").val();
		parent.selecttemplate(id,'selected')	
	 }
	 catch(err) {
        // Handle error(s) here
    }
	parent.parent.$('.close_contact_select_popup').trigger('click');
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>

<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('phonecallscript_header');?>
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> Past Social Media Posts</h3>
            <span class="float-right margin-top--15">
            	<a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back" id="back">Back</a>
            </span>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
            <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
              <div class="row dt-rt">
                <?php if(!empty($msg)){?>
                <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div>
                <?php } ?>
              </div>
              <div class="row dt-rt">
                <div class="">
                  <div class="col-sm-4 col-lg-2 col-md-2 col-xs-11">
                    <div class="dataTables_filter pull-left" id="DataTables_Table_0_filter">
                      <label>
                        <?php /*?> <a href="<?=base_url('user/'.$viewname.'/queued_list');?>" class="btn btn-secondary howler" title="Queued Social" > Queued Social </a>
                        <a href="<?=base_url('user/'.$viewname.'/all_sent_social');?>" class="btn btn-secondary howler" title="Sent Social" > Sent Social </a><?php */?> </label>
                    </div>
                  </div>
                  <div class="col-sm-8 col-lg-10 col-md-10 col-xs-12">
                    <div class="dataTables_filter" id="DataTables_Table_0_filter">
                      <label>
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                        <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                        <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                        <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row dt-rt">
                <div class="col-sm-6">
                   <? if(in_array('facebook_post_delete',$this->modules_unique_name) || in_array('twitter_delete',$this->modules_unique_name) || in_array('linkedin_delete',$this->modules_unique_name)){ ?>
                  <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Social Campaign">Delete Social Campaign</button>
                  <? } ?>
                </div>
                <div class="col-sm-6">
                  <? if(in_array('facebook_post_add',$this->modules_unique_name) || in_array('twitter_add',$this->modules_unique_name) || in_array('linkedin_add',$this->modules_unique_name)){ ?>
                 <a class="btn  pull-right btn-secondary-green howler" href="<?=base_url('user/'.$viewname.'/add_record');?>" title="Add Social Campaign">Add Social Media Campaign</a> 
                 <? } ?>
                 </div>
              </div>
              <div class="table_large-responsive">
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
			url: "<?php echo base_url();?>user/Social/"+uri_segment,
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
			data: {'myarray':myarray,'single_remove_id':id},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
			success: function(data){
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/Social/"+data,
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
				{
					name = unescape(name).substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+name+'"';
				}
				else
				{
					var msg = 'Are you sure want to delete "'+unescape(name)+'"';
				}

				//var msg = 'Are you sure want to delete '+name+'';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 


</script>