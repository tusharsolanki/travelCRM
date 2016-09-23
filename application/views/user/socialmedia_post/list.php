<?php 
    /*
        @Description: user social media post list
        @Author: Mohit Trivedi
        @Date: 06-08-14
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
/*if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;*/
?>
 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('socialmedia_post_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('socialmedia_posttemplate_list_head');?></h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
      <ul class="nav nav-tabs" id="myTab1">
              <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('marketing_social_librery_my');?>" data-toggle="tab" href="#mydata" onclick="load_view('my_data');">
                <?=$this->lang->line('marketing_social_librery_my');?>
                </a> </li>
              <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('marketing_social_librery_default');?>" data-toggle="tab" href="#defaultdata" onclick="load_view('my_default');">
                <?=$this->lang->line('marketing_social_librery_default');?>
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
              <div class="<?php if($tabid == '' || $tabid == 1){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mydata" >
                <div class="table_large-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         
         <div class="row dt-rt">
          <div class="col-sm-1">
           
          </div>
          <div class="col-sm-11">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
             <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                				<input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                
                            <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search();" title="Search">Search</button>
                <button class="btn btn-secondary howler" title="View All Template" data-type="danger" onclick="clearfilter_contact();">View All</button>
            </label>
           </div>
           <div class="dataTables_length mrg-right-10" id="DataTables_Table_0_filter">
                <label>
                
                    <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="contact_search('changesearch');" id="selected_cat" name="selected_cat">
                     <option value="">Select Category</option>
                      <?php if(isset($category) && count($category) > 0){
                            foreach($category as $row1){
                                if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($selected_cat) && $selected_cat == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['category']);?></option>
                <?php 		}
                            }
                        } ?>
                     </select>
                     
                  </label>
            </div>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
           <?php if(!empty($this->modules_unique_name) && in_array('social_media_posts_delete',$this->modules_unique_name)){?>
           <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Template">Delete Template</button>
           <? } ?>
           </div>
          <div class="col-sm-6">
           <?php if(!empty($this->modules_unique_name) && in_array('social_media_posts_add',$this->modules_unique_name)){?>
          <a title="Add Template" class="btn pull-right btn-secondary-green howler" href="<?=base_url('user/'.$viewname.'/add_record');?>">Add Template</a>
          <? } ?>
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname.'/ajax_list')?>
         </div>
        </div>
       </div>
              </div>
              <!--End tab 1 -->
              <div class="<?php if($tabid == 2){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="defaultdata" >
                <div class="table_large-responsive">
                  <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                      <div class="col-sm-1"> </div>
                      <div class="col-sm-11">
                        <div class="dataTables_filter" id="DataTables_Table_0_filter">
                          <label>
                            <input class="" type="hidden" name="uri_segment1" id="uri_segment1" value="<?=!empty($uri_segment1)?$uri_segment1:'0'?>">
                            <input type="text" name="default_searchtext" id="default_searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search..." value="<?=!empty($default_searchtext)?htmlentities($default_searchtext):''?>">
                            <button class="btn btn-secondary howler" data-type="danger" onclick="default_search();" title="Search">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" onclick="default_clearfilter_contact();" title="View All">View All</button>
                          </label>
                        </div>
                        <div class="dataTables_length mrg-right-10" id="DataTables_Table_0_filter">
                            <label>
                            
                                <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="default_search();" id="default_selected_cat" name="default_selected_cat">
                                 <option value="">Select Category</option>
                                  <?php if(isset($category) && count($category) > 0){
                                        foreach($category as $row1){
                                            if(!empty($row1['id'])){?>
                            <option value="<?php echo $row1['id'];?>" <?php if(!empty($default_selected_cat) && $default_selected_cat == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['category']);?></option>
                            <?php 		}
                                        }
                                    } ?>
                                 </select>
                                 
                              </label>
                        </div>
                      </div>
                    </div>
                    
                    <div id="default_common_div">
                      <?=$this->load->view('user/'.$viewname.'/default_ajax_list')?>
                    </div>
                  </div>
                </div>
              </div>
              <!--End tab 2 --> 
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
 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000);
	 load_view(<?=$tabid?>); 
    });
	function load_view(id)
    {
		if(id == 'my_data' || id == '1') {
            $('#selected_view').val('1');
            $("#my_default").hide();
        }
        else if(id == 'my_default' || id == '2') {
            $('#selected_view').val('2');
            $("#my_data").hide();
        }
        var selected_view = $('#selected_view').val();
		$.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/selectedview_session';?>",
            data: {selected_view:$('#selected_view').val()},
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
            success: function(html){
                if(selected_view == '2')
                {
                    $("#my_default").show();
                    $("#div_msg1").fadeOut(4000);
                }    
                else {
                    $("#my_data").show();
                    $("#div_msg").fadeOut(4000); 
                }
				$.unblockUI();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    //$(".view_contact_popup").html('Something went wrong.');
            }
        });
        
    }	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/socialmedia_post/"+uri_segment,
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
		$("#selected_cat").val('');
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
					url: "<?php echo base_url();?>user/socialmedia_post/",
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
<script>
function default_search(allflag1)
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/socialmedia_post/",
			data: {
			result_type:'default_ajax',default_searchreport:$("#default_searchreport").val(),default_perpage:$("#default_perpage").val(),default_searchtext:$("#default_searchtext").val(),default_sortfield:$("#default_sortfield").val(),default_sortby:$("#default_sortby").val(),allflag1:allflag1,default_selected_cat:$("#default_selected_cat").val()
		},
		beforeSend: function() {
					$('#default_common_div').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#default_common_div").html(html);
				$('#default_common_div').unblock(); 
			}
		});
		return false;
	}
	
	 $(document).ready(function(){
		  $('#default_searchtext').keyup(function(event) 
		  {
				if (event.keyCode == 13) {
						default_search();
				}
		  });
	});
	
	function default_clearfilter_contact()
	{
		$("#default_selected_cat").val('');
		$("#default_searchtext").val("");
		default_search('all');
	}
	
	function default_changepages()
	{
		default_search('');	
	}
	
  	function default_applysortfilte_contact(sortfilter,sorttype)
	{
		$("#default_sortfield").val(sortfilter);
		$("#default_sortby").val(sorttype);
		default_search('changesorting');
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#default_common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'default_ajax',default_searchreport:$("#default_searchreport").val(),default_perpage:$("#default_perpage").val(),default_searchtext:$("#default_searchtext").val(),default_sortfield:$("#default_sortfield").val(),default_sortby:$("#default_sortby").val(),default_selected_cat:$("#default_selected_cat").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#default_common_div").html(html);
					$.unblockUI();
                }
            });
            return false;
        });
$('body').on('click','.view_form_btn',function(e){
	
			var id = $(this).attr('data-id');
			var temp_name = $('#temp_name_'+id).text();
			var temp_category = $('#temp_category_'+id).text();
			var temp_subject = $('#temp_subject_'+id).text();
			var temp_platform = $('#temp_platform_'+id).text();
			var temp_desc = $('#temp_desc_'+id).html();
				var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Template Name</label></td><td> : </td><td align="left">'+temp_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Category</label></td><td> : </td><td align="left">'+temp_category+'</td></tr><tr><td align="left"><label align="left" for="text-input">Title (Subject)</label></td><td> : </td><td align="left">'+temp_subject+'</td></tr><tr><td align="left"><label align="left" for="text-input">Platform</label></td><td> : </td><td align="left">'+temp_platform+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Template Message</label></td><td> : </td><td>&nbsp;</td></tr><tr><td align="left" colspan="3">'+temp_desc+'</td></tr></table>';
			$("#row_data").html(form_data);
	});
	 function Popup() 
    {
		var tmp_data = $('#row_data').html();
		//alert(size_h);
		if(tmp_data != '')
		{
			var mywindow = window.open('', '+finlaOutputPrint+', 'height=400,width=600');
			mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head><title>Data</title>');
			/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
			mywindow.document.write('<style type="text/css" media="print">@page{size: auto; margin: 0mm; }body{          background-color:#FFFFFF; ;margin: 15px; }</style>');
			mywindow.document.write('</head><body>');
			mywindow.document.write(tmp_data);
			mywindow.document.write('</body></html>');
	
			mywindow.print();
			if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) //IF IE > 10
			{
				mywindow.document.execCommand('print', false, null);
			}
			
			mywindow.close();
			$( ".close_contact_select_popup" ).trigger( "click" );
	
			return true;
		}else{
			return false;
		}
    }	
</script>