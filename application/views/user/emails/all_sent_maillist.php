<?php 
    /*
        @Description: user task list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$viewname1='emails';
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Tracking Details</h3>
      </div>
      <div class="modal-body">
      	<div class="row">
         <div id="temp_dev" class="text-center error"></div>
         <div class="col-sm-12">
          <label for="text-input"> Contact Name : </label> <span class="contact_name"> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> </span>
         </div>
        </div>
        <div class="row">
         <div class="col-sm-12">
          <label for="text-input"> Title (Subject) : </label> <span class="subject"> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> </span>
         </div>
        </div>
      	<div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Status : </label> <span class="email_status"> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> </span>
                 </div>
               </div> 
        <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Total Opens : </label> <span class="total_opens"> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> </span>
                 </div>
               </div>
        <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Total Plays : </label> <span class="total_plays"> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> </span>
                 </div>
               </div>
        <div class="row">
                 <div class="col-sm-12">
                  <label for="text-input"> Total Clicks : </label> <span class="total_clicks"> <img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" /> </span>
                 </div>
               </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

 <div id="content">
  <div id="content-header">
   <h1>Email Sent History</h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Email Sent History</h3>
	   <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
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
                <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
			 <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
            </label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          <!--<a class="btn  pull-right btn-success howler" href="<?=base_url('user/'.$viewname.'/add');?>">Add Email Campaign</a>-->
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname1.'/ajax_all_sent_maillist')?>
		
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
		var id = $("#id").val();
		$.ajax({
			type: "POST",
			/*url: "<?php echo base_url();?>user/<?=$viewname?>/all_sent_mail/"+id,*/
                        url: "<?php echo base_url();?>user/<?=$viewname?>/all_sent_mail/"+uri_segment,
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

function email_tracking(track_id,id)
{
	//alert($(".subject_"+id).text());
	$('.contact_name').html($(".contact_name_"+id).text());
	$('.subject').html($(".subject_"+id).text());
	$(".email_status").html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
	$(".total_opens").html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
	$(".total_plays").html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
	$(".total_clicks").html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
	$.ajax({
	type: "POST",
	dataType:'json',
	url: "<?=$this->config->item('user_base_url')?><?=$viewname?>/emailTracking",
	data: {
	track_id:track_id
	},
	beforeSend: function() {
				
			  },
		success: function(result){
			if(result.status == 'failure')
			{
				$("#temp_dev").css('display','block');
				$("#temp_dev").html('<?=$this->lang->line('common_bombbomb_credential_msg')?>');
				$("#temp_dev").fadeOut(4000);	
			}
			$(".email_status").html(result.email_status);
			$(".total_opens").html(result.total_opens);
			$(".total_plays").html(result.total_plays);
			$(".total_clicks").html(result.total_clicks);
		}
	});	
}
</script>