<?php 
    /*
        @Description: user InterCommunications list
        @Author: Mit Makwana
        @Date: 14-07-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));

?>

<script language="javascript">
<?php if(!empty($premium_plan_update)) {/* ?>
$.confirm({'title': 'CONFIRM','message': " <strong>  Are you sure ? <strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
	window.location = '<?php echo $this->config->item('user_base_url').$viewname;?>/premium_plan_update';
}},'No'	: {'class'	: 'special'}}});
<?php*/ } ?>

$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Template Details</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup text-center">
		 <div id="row_data">
         </div>
		 <input type="submit" class="btn btn-secondary" value="Print" onClick="Popup()" name="print" />
		<div id="previewformdata">
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
   <h1><?=$this->lang->line('interaction_plans_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Mail Blast</h3>
       <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      <div class="portlet-content">
        <div class="tab-content" id="myTab1Content"> 

            
				<div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
              <div class="row dt-rt">
               <?php if(!empty($msg)){?>
                                             <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                             $newdata = array('msg'  => '');
                                             $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
               
               <div class="col-sm-12">
                <div class="dataTables_filter pull-right" id="DataTables_Table_0_filter">
                 <label>
                     <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                    <input class="" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                    <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                    <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All" title="View All">View All</button>
                     </label>
                </div>
               </div>
              </div>
              <div class="row dt-rt">
               <div class="col-sm-4 col-xs-6 col-lg-6">
                  <? if(in_array('letter_delete',$this->modules_unique_name) || in_array('envelope_delete',$this->modules_unique_name) || in_array('label_delete',$this->modules_unique_name)){ ?>
                <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Add To Archive List">Delete Mail Blast</button>
                 <? } ?>
               </div>
               <div class="col-sm-8 col-xs-6 col-lg-6">
 <? if(in_array('letter_add',$this->modules_unique_name) || in_array('envelope_add',$this->modules_unique_name) || in_array('label_add',$this->modules_unique_name)){ ?>
					  <a title="Add Communication" class="btn  pull-right btn-secondary-green howler " href="<?=base_url('user/'.$viewname.'/add_record');?>">New Mail Blast</a>
                   <? } ?>     

               </div>
              </div>
              <div id="common_div" class="table_large-responsive">
              <?=$this->load->view('user/'.$viewname.'/ajax_list')?>
              </div>
             </div>
           
            <!-- Premium Plan Tab -->
            
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
         $("#div_msg1").fadeOut(4000);
    });
    

</script>
</script>
<script>
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
				url: "<?php echo base_url();?>user/<?=$viewname?>/"+uri_segment,
				data: {
				result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,selected_view:$('#selected_view').val()
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
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:'',selected_view:$('#selected_view').val()
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
					url: "<?php echo base_url();?>user/mail_out/"+data,
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
			if(boxes.length == '0' && id == '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
				return false;}
			
	   		if(id == '0')
			{
				var msg = 'Are you sure want to delete record(s)';
			}
			else
			{
				if(name.length > 50)
				{
					name = unescape(name).substr(0, 50)+'...';
					var msg = 'Are you sure want to delete '+name;
				}
				else
				{
					var msg = 'Are you sure want to delete '+unescape(name);
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
		  
		  <?php /*?><?php $this->load->view('user/interaction_plans/view_contact_popup');?><?php */?>
		  
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
/*Premium Plan*/
function clearfilter_contact1()
{
    $("#searchtext1").val("");
    contact_search1('all');
}
function changepages1()
{
    contact_search1('');
}
function applysortfilte_contact1(sortfilter,sorttype)
{
    $("#sortfield1").val(sortfilter);
    $("#sortby1").val(sorttype);
    contact_search1('changesorting');
}
function contact_search1(allflag)
{
    var uri_segment = $("#uri_segment1").val();

    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/<?=$viewname?>/"+uri_segment,
        data: {
            result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),allflag1:allflag,selected_view:$('#selected_view').val()
        },
        beforeSend: function() {
                $('#premium_common_div').block({ message: 'Loading...' }); 
        },
        success: function(html){
                $("#premium_common_div").html(html);
                $('#premium_common_div').unblock(); 
        }
    });
    return false;
}
$(document).ready(function(){
    $('#searchtext1').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search1('changesearch');
        }
        //return false;
    });
});
$('body').on('click','#common_tb1 a.paginclass_A',function(e){
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),allflag1:'',selected_view:$('#selected_view').val()
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#premium_common_div").html(html);
            $.unblockUI();
        }
    });
    return false;
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
function delete_all1(id)
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
            url: "<?php echo base_url();?>user/<?=$viewname?>/"+data,
            data: {
                result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),allflag1:'',selected_view:$('#selected_view').val()
            },
            beforeSend: function() {
                    $('#premium_common_div').block({ message: 'Loading...' }); 
              },
            success: function(html){
                    $("#premium_common_div").html(html);
                    $('#premium_common_div').unblock(); 
            }
        });
        return false;
    }
});
}
function deletepopup2(id,name)
{      
    var boxes = $('input[name="check[]"]:checked');
    if(boxes.length == '0' && id == '0')
    {
			$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
		return false;}

    if(id == '0')
    {
        var msg = 'Are you sure want to delete record(s)';
    }
    else
    {
        if(name.length > 50)
		{
			name = unescape(name).substr(0, 50)+'...';

		var msg = 'Are you sure want to delete '+name+'';
		}
		else
		{
			var msg = 'Are you sure want to delete '+unescape(name)+'';
		}
    }
    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
            delete_all1(id);
        }},'No'	: {'class'	: 'special'}}
    });
}
$('body').on('click','.view_form_btn',function(e){
	
			var id = $(this).attr('data-id');
			var temp_name = $('#temp_name_'+id).text();
			var temp_category = $('#temp_category_'+id).text();
			var temp_size = $('#temp_size_'+id).html();
			var temp_desc = $('#temp_desc_'+id).html();
			
			var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Template Name</label></td><td> : </td><td align="left">'+temp_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Category</label></td><td> : </td><td align="left">'+temp_category+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Size</label></td><td> : </td><td align="left">'+temp_size+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Template Message</label></td><td> : </td><td>&nbsp;</td></tr><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';
			$("#row_data").html(form_data);
	});
	$('body').on('click','.view_form_btn1',function(e){
	
			var id = $(this).attr('data-id');
			var temp_name = $('#temp_name_'+id).text();
			var temp_category = $('#temp_category_'+id).text();
			var template_type_ = $('#template_type_'+id).text();
			var temp_size_ = $('#temp_size_'+id).html();
			var temp_desc = $('#temp_desc_'+id).html();
			var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Template Name</label></td><td> : </td><td align="left">'+temp_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Category</label></td><td> : </td><td align="left">'+temp_category+'</td></tr><tr><td align="left"><label align="left" for="text-input">Size Type</label></td><td> : </td><td align="left">'+template_type_+'</td></tr><tr><td align="left"><label align="left" for="text-input">Size</label></td><td> : </td><td align="left">'+temp_size_+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Template Message</label></td><td> : </td><td>&nbsp;</td></tr><tr><td align="left" colspan="3">'+temp_desc+'</td></tr></table>';
			$("#row_data").html(form_data);
	});
	$('body').on('click','.view_form_btn2',function(e){
	
			var id = $(this).attr('data-id');
			var temp_name = $('#temp_name_'+id).text();
			var temp_category = $('#temp_category_'+id).text();
			var temp_size = $('#temp_size_'+id).text();
			var temp_desc = $('#temp_desc_'+id).html();
			
			
			var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Template Name</label></td><td> : </td><td align="left">'+temp_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Category</label></td><td> : </td><td align="left">'+temp_category+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Size</label></td><td> : </td><td align="left">'+temp_size+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Template Message</label></td><td> : </td><td>&nbsp;</td></tr><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';
			$("#row_data").html(form_data);
	});
    function Popup() 
    {
		var tmp_data = $('#row_data').html();
		//alert(size_h);
		if(tmp_data != '')
		{
			var mywindow = window.open('', '+finlaOutputPrint+', 'height=400,width=600');
			mywindow.document.write('<html><head><title>Data</title>');
			/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
			mywindow.document.write('</head><body>');
			mywindow.document.write(tmp_data);
			mywindow.document.write('</body></html>');
	
			mywindow.print();
			mywindow.close();
	
			return true;
		}else{
			return false;
		}
    }


</script>