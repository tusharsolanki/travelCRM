<?php 
/*
    @Description: Joomla property cron list
    @Author     : Sanjay Moghariya
    @Date       : 18-11-14
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
$tabid = !empty($tabid)?$tabid:'1';
?>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('joomla_property_cron_header');?></h1>
    </div>
    <div id="content-container">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-table"></i><?=$this->lang->line('joomla_property_cron_header');?></h3>
                    </div>
                    <!-- /.portlet-header -->

                    <div class="portlet-content">
                        <ul class="nav nav-tabs" id="myTab1">
                            <li <?php if($tabid == '' || $tabid == '1'){?> class="active" <?php } ?> > <a title="<?=$this->lang->line('joomla_property_cron_current')?>" data-toggle="tab" href="#from_current_site" onclick="load_view('1');"><?=$this->lang->line('joomla_property_cron_current')?></a> </li>
                            <li <?php if($tabid == '2'){?> class="active" <?php } ?>> <a title="<?=$this->lang->line('joomla_property_cron_crm')?>" data-toggle="tab" href="#from_crm" onclick="load_view('2');"><?=$this->lang->line('joomla_property_cron_crm')?></a> </li>
                        </ul>
                        <input type ="hidden" id="selected_view" name="selected_view">
                        <div class="tab-content" id="myTab1Content">
                            <div <?php if($tabid == '' || $tabid == '1' ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="from_current_site" > 
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
                                            <button class="btn btn-danger howler" data-type="danger" title="Delete Valuation Report" onclick="deletepopup1('0');">Delete Valuation Report</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <a class="btn btn-secondary pull-right btn-success howler margin-left-5px" title="View valuation reports based on contacts" href="<?=base_url('admin/'.$viewname.'/assigned_contact_list_web');?>">Contact wise List</a>
                                            <a class="btn btn-secondary-green pull-right btn-success howler" title="Add Valuation Report" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add Valuation Report</a>
                                        </div>
                                    </div>
                                    <div id="common_div">
                                        <?=$this->load->view('admin/'.$viewname.'/ajax_list')?>
                                    </div>
                                </div>
                            </div> <!-- /tab1 --> 
                            <!-- tab2- From CRM --> 
                            <div <?php if($tabid == '2' ){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="from_crm" > 
                                <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                                    <div class="row dt-rt">
                                        <?php if(!empty($msg1)){?>
                                        <div class="col-sm-12 text-center" id="div_msg1"><?php echo '<label class="error">'.urldecode ($msg1).'</label>';
                                        $newdata = array('msg'  => '');
                                        $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
                                    </div>
                                    <div class="row dt-rt">
                                        <div class="col-sm-1">
                                        </div>
                                        <div class="col-sm-11">
                                            <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                                <label>
                                                    <input class="" type="hidden" name="uri_segment1" id="uri_segment1" value="<?=!empty($uri_segment1)?$uri_segment1:'0'?>">
                                                    <input type="text" name="searchtext1" id="searchtext1" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext1)?$searchtext1:''?>">
                                                    <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search1('changesearch');">Search</button>
                                                    <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact1();">View All</button>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row dt-rt">
                                        <div class="col-sm-6">
                                            <button class="btn btn-danger howler" data-type="danger" title="Delete Valuation Report" onclick="deletepopupcrm('0');">Delete Valuation Report</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <a class="btn btn-secondary pull-right btn-success howler margin-left-5px" title="View valuation reports based on contacts" href="<?=base_url('admin/'.$viewname.'/assigned_contact_list_crm');?>">Contact wise List</a>
                                            <a class="btn btn-secondary-green pull-right btn-success howler" title="Add Valuation Report" href="<?=base_url('admin/'.$viewname.'/add_record1');?>">Add Valuation Report</a>
                                        </div>
                                    </div>
                                    <div id="common_div1">
                                        <?=$this->load->view('admin/'.$viewname.'/ajax_list_crm')?>
                                    </div>
                                </div>
                            </div>
                            <!-- /tab2 --> 
                        </div>
                        <!-- /.tab-content --> 
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
         load_view(<?=$tabid?>);
    });
    
    function load_view(id)
    {
        if(id == '1') {
            $('#selected_view').val('1');
            contact_search('');
        }
        else if(id == '2') {
            $('#selected_view').val('2');
            contact_search1('');
        }
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/selectedview_session';?>",
            data: {selected_view:$('#selected_view').val()},
                beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            },
            success: function(html){
                if($('#selected_view').val() == '2')
                {
                    $("#div_msg1").fadeOut(4000);
                }
                else {
                    $("#div_msg").fadeOut(4000); 
                }
                $.unblockUI(); 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
					$.unblockUI(); 
                    //$(".view_contact_popup").html('Something went wrong.');
            }
        });
        
    }
	
    function contact_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>admin/joomla_property_cron/"+uri_segment,
            data: {
                result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,selected_view:$('#selected_view').val()
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
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),selected_view:$('#selected_view').val()
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
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_all';?>",
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
                    url: "<?php echo base_url();?>admin/joomla_property_cron/"+data,
                    data: {
                        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:'',selected_view:$('#selected_view').val()
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
            //var msg = 'Are you sure want to delete '+name+'?';
        }
        $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                delete_all(id);
            }},'No'	: {'class'	: 'special'}}
        });
    } 
    ////* For CRM Tab*/////
    function contact_search1(allflag)
    {
        var uri_segment = $("#uri_segment1").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>admin/joomla_property_cron/property_cron_crm_index/"+uri_segment,
            data: {
                result_type:'ajax1',perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),allflag:allflag,selected_view:$('#selected_view').val()
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
	
    $(document).ready(function(){
        $('#searchtext1').keyup(function(event) 
        {
            if (event.keyCode == 13) {
                contact_search1('changesearch');
            }
        });
    });
	
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
	
    //$("#common_tb a.paginclass_A").click(function() {
    $('body').on('click','#common_tb1 a.paginclass_A',function(e){
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type:'ajax1',searchreport:$("#searchreport1").val(),perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),selected_view:$('#selected_view').val()
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
		
    //$('#selecctall').click(function(event) {  //on click
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
	
    function delete_allcrm(id)
    {
        var myarray = new Array;
        var i=0;
        var boxes = $('input[name="check1[]"]:checked');
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
            //async: false,
            data: {'myarray':myarray,'single_remove_id':id},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
            success: function(data){
				$.unblockUI();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>admin/joomla_property_cron/property_cron_crm_index//"+data,
                    data: {
                        result_type:'ajax1',perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),allflag:'',selected_view:$('#selected_view').val()
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
            },error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
        });
    }
	
    function deletepopupcrm(id,name)
    {      
        var boxes = $('input[name="check1[]"]:checked');
        if(boxes.length == '0' && id== '0')
        {
            $.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
            $('#selecctall1').focus();
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
            //var msg = 'Are you sure want to delete '+name+'?';
        }
        $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                delete_allcrm(id);
            }},'No'	: {'class'	: 'special'}}
        });
    } 
</script>