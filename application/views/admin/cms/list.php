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
?>
<div id="content">
    <div id="content-header"><h1><?=$this->lang->line('cms_list_head');?></h1></div>
    <div id="content-container">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3><i class="fa fa-table"></i><?=$this->lang->line('cms_list_head');?></h3>
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
                                                <input type="text" name="searchtext" title="Search CMS"id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                                                <button class="btn btn-secondary howler" title="Search CMS" data-type="danger" onclick="contact_search('changesearch');">Search</button>
                                                <button class="btn btn-secondary howler" title="View All CMS" data-type="danger" onclick="clearfilter_contact();">View All</button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row dt-rt">
                                    <div class="col-sm-6">
                                        <button class="btn btn-danger howler" title="Delete CMS" data-type="danger" onclick="deletepopup1('0');">Delete</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <a title="<?=$this->lang->line('cms_add_head')?>" class="btn pull-right btn-secondary-green howler" href="<?=base_url('admin/'.$viewname.'/add_record');?>"><?=$this->lang->line('cms_add_head')?></a>
                                    </div>
                                </div>
                                <div id="common_div">
                                    <?=$this->load->view('admin/'.$viewname.'/ajax_list')?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
    function contact_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url')?>cms/"+uri_segment,
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
                    url: "<?php echo base_url();?>admin/cms/"+data,
                    data: {
                        result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
            return false;
        }
        if(id == '0')
        {
            var msg = 'Are you sure want to delete record(s)';
        }
        else
        {
            if(name.length > 50)
                name = name.substr(0, 50)+'...';
            var msg = 'Are you sure want to delete "'+unescape(name)+'"';
        }
        $.confirm({'title': 'CONFIRM','message': " <strong> "+unescape(msg)+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
                delete_all(id);
        }},'No'	: {'class'	: 'special'}}});
    } 

    function status_change(status,id,menu_title)
    {
        var path='';
        if(menu_title.length > 50)
            menu_title = menu_title.substr(0, 50)+'...';
        if(status == 0)
        {
            path = "<?= $this->config->item('admin_base_url').$viewname; ?>/unpublish_record/"+id;
            var msg = 'Are you sure want to unpublish "'+unescape(menu_title)+'"';
        }else
        {
            path = "<?= $this->config->item('admin_base_url').$viewname; ?>/publish_record/"+id;
            var msg = 'Are you sure want to  publish "'+unescape(menu_title)+'"';
        }

        $.confirm({'title': 'CONFIRM','message': " <strong> "+unescape(msg)+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                $.ajax({
                    type: "POST",
                    url: path,
                    dataType: 'json',
                    beforeSend: function() {
                    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                    },
                    success: function(data){
                        $.unblockUI();
                        $.ajax({
                            type: "POST",
                            url: "<?php echo $this->config->item('admin_base_url');?>cms/"+data,
                            data: {
                                result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
        }},'No'	: {'class'	: 'special'}}});
    }
</script>