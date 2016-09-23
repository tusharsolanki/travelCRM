<?php 
/*
    @Description: Agent Weightage list
    @Author     : Sanjay Moghariya
    @Date       : 30-10-14
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
?>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('agent_rr_weightage_list_head');?></h1>
    </div>
    <div id="content-container">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-table"></i><?=$this->lang->line('dashboard_agent_rr_weightage_list_head');?></h3>

                        <?php /*<span class="pull-right"><a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('superadmin_base_url')?>leads_dashboard"><?php echo $this->lang->line('common_back_title')?></a> </span> */ ?>
                        <!--<span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back" id="back"><?php echo $this->lang->line('common_back_title')?></a> </span>-->
                    </div>
                    <!-- /.portlet-header -->

                    <div class="portlet-content">
                        
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
                                        <!--<button class="btn btn-danger howler" data-type="danger" title="Delete Weightage" onclick="deletepopup1('0');">Delete Weightage</button>-->
                                    </div>
                                    <div class="col-sm-6">
                                        <?php /*
                                        <a class="btn btn-secondary pull-right btn-success howler" title="Edit" href="<?=base_url('superadmin/'.$viewname.'/add_record');?>">Edit</a>
                                         */?>
                                    </div>
                                </div>
                                <div class="table_large-responsive">
                                <div id="common_div">
                                    <?=$this->load->view('superadmin/'.$viewname.'/ajax_list')?>
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
            url: "<?php echo base_url();?>superadmin/user_rr_weightage/"+uri_segment,
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
            url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_delete_all';?>",
            dataType: 'json',
            async: false,
            data: {'myarray':myarray,'single_remove_id':id},
            success: function(data){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>superadmin/user_rr_weightage/"+data,
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
            var msg = 'Are you sure want to delete '+name+'?';
        }
        $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong></strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                delete_all(id);
        }},'No'	: {'class'	: 'special'}}});
    } 
</script>