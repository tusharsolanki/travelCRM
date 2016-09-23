<?php 
/*
    @Description: Joomla property cron list contact wise
    @Author     : Sanjay Moghariya
    @Date       : 02-01-2015
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
$path_per_1 = 'contacts/insert_conversations';
?>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('joomla_property_cron_contact_header');?></h1>
    </div>
    <div id="content-container">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-table"></i><?=$this->lang->line('joomla_property_cron_contact_header');?></h3>
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
                                                <input type="text" name="searchtext" id="searchtext" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                                                <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch');">Search</button>
                                                <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row dt-rt">
                                    <div class="col-sm-6">
                                        <button class="btn btn-danger howler" data-type="danger" title="Delete Contact from Market Watch" onclick="deletepopup1('0');">Delete Contact</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <a class="btn btn-secondary pull-right btn-success howler" title="Market Watch" href="<?=base_url('admin/'.$viewname);?>"><?=$this->lang->line('joomla_property_cron_header_left')?></a>
                                    </div>
                                </div>
                                <div id="common_div">
                                    <?=$this->load->view('admin/'.$viewname.'/assigned_contact_ajax_list')?>
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
 <a id="log_call_id" style="display:none;" title="Add Conversations Log" href="#basicModal_conversation"  data-toggle="modal" >Click</a>
 <div aria-hidden="true" style="display: none;" id="basicModal_conversation" class="new_call_log_popup modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 class="modal-title">Add Call Log</h3>
      </div>
        <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>_call_log" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_1;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		 
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="display:none;">
              <td>Action Type:
			  </td>
              <td>
               <select id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option <?php if($row['id'] == 4) echo 'selected="selected"'; ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>
                <textarea class="form-control" name="description" id="description"></textarea>
              </td>
            </tr>
            <tr>
              <td>Disposition:</td>
              <td>
                 <select id="disposition_type" name="disposition_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($disposition_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
                 <input type="hidden" name="from_joomla_view" value="1" />
				 <input id="contact_id1" name="contact_id" type="hidden" value="">
				 <input type="hidden" name="from_joomla_view" value="3" />
              </td>
            </tr>
          </table>
		  
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="submit" id="activitylog" class="btn btn-secondary" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">Add Call Log</button>
        <!--<button type="button" class="btn btn-primary">Cancel</button>-->
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
    function show_action(id,contact_id)
    {
        if(id != '')
        {
            $("#contact_id1").val(contact_id);
            $("#"+id).trigger('click');
        }	
    }
    function contact_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>admin/joomla_property_cron/assigned_contact_list/"+uri_segment,
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
	
    function delete_all(id,contact_id)
    {
        var myarray = new Array;
        var contactid_array = new Array;
        var i=0;
        var boxes = $('input[name="check[]"]:checked');
        $(boxes).each(function(){
            var chk_data = this.value;
            var res = chk_data.split("-"); 
            myarray[i]=res[0];
            contactid_array[i] = res[1];
            i++;
        });
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_contact_from_vreport';?>",
            dataType: 'json',
            //async: false,
            data: {'myarray':myarray,'contactid_array':contactid_array,'single_remove_id':id,'contact_id':contact_id},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
            success: function(data){
				$.unblockUI();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>admin/joomla_property_cron/assigned_contact_list/"+data,
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
	
    function deletepopup1(id,contact_id,name)
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
            var msg = 'Are you sure want to delete "'+unescape(name) +'"';
            //var msg = 'Are you sure want to delete '+name+'?';
        }
        $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                delete_all(id,contact_id);
            }},'No'	: {'class'	: 'special'}}
        });
    } 
</script>