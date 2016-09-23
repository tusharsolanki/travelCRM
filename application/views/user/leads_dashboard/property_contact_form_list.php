<?php	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$contact_id = $this->router->uri->segments[4];
?>
<div id="content">
    <div id="content-header"><h1><?=$this->lang->line('joomla_contact_form_list_head');?></h1></div>
    <div id="content-container">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3><i class="fa fa-table"></i><?=$this->lang->line('joomla_contact_form_list_head');?></h3>
                        <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
                    </div>
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
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-11">
                                        <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                            <label>
                                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                                <input type="text" name="searchtext" title="Search Joomla Contact Form"id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                                                <button class="btn btn-secondary howler" title="Search Joomla Contact Form" data-type="danger" onclick="contact_search('changesearch');">Search</button>
                                                <button class="btn btn-secondary howler" title="View All Joomla Contact Form" data-type="danger" onclick="clearfilter_contact();">View All</button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row dt-rt">
                                </div>
                                <div id="common_div">
                                    <?=$this->load->view('user/'.$viewname.'/property_contact_form_ajax_list')?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Property Contact form Popup -->
<div aria-hidden="true" style="display: none;" id="property_contact_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_property_contact_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title"><?=$this->lang->line('contact_joomla_tab_property_contact')?></h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 property_contact_popup">
                    <div class="text-center">
                        <img src="<?=base_url()?>images/ajaxloader.gif" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
    
    $('body').on('click','.property_contact_popup_btn',function(e){
        $(".property_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
        var search_id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/property_contact_popup';?>",
            data: {'search_id':search_id},
            success: function(html){
                $(".property_contact_popup").html(html);	
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".property_cotnact_popup").html('Something went wrong.');
            }
        });
    });
	
    function contact_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url')?>leads_dashboard/property_contact_form/<?=$contact_id?>/"+uri_segment,
            data: {
                result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
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
                result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
</script>