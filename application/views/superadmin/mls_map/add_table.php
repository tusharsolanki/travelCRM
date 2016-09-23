<?php
/*
    @Description: MLS add/edit page
    @Author     : Niral Patel
    @Date       : 26-12-2014
*/?>

<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
$formAction = !empty($editRecord)?'insert_master_table':'insert_master_table'; 
if(isset($insert_data))
{
$formAction ='insert_master_table'; 
}
$path = $viewname.'/'.$formAction;
$this->session->unset_userdata('state_add_session');
?>
<style>
.ui-multiselect{width:100% !important;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
    <div id="content-header">
        <h1>Assign Tables for <?=!empty($mapping_name)?$mapping_name:''?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i>Assign tables for <?=!empty($mapping_name)?$mapping_name:''?></h3>
                         <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back">Back</a> </span>
                    </div>
                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row dt-rt">
                                    <?php if(!empty($msg)){?>
                                        <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                            $newdata = array('msg'  => '');
                                            $this->session->set_userdata('message_session', $newdata);?> 
                                        </div>
                                    <?php } ?>
    	 
                                </div>
                                <div class="row tab-pane fade in active" id="home">
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate onkeypress="return event.keyCode != 13;">
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Select Master Table<span class="val">*</span></label>
                                                    <select class="form-control parsley-validated" name="master_table" id="master_table" data-required="true" onchange="load_child_table();">
                                                       <option value="">Please Select Table</option>
                                                        <?php foreach($load_tables as $row){ ?>
                                                           <option <?php if(!empty($mls_tables_data[0]['main_table']) && $mls_tables_data[0]['main_table'] == $prefix.$row['Tables_in_'.$db_name]){ echo 'selected="selected"'; } ?> value="<?=!empty($row['Tables_in_'.$db_name])?$prefix.$row['Tables_in_'.$db_name]:''?>"><?=!empty($row['Tables_in_'.$db_name])?$row['Tables_in_'.$db_name]:''?></option>
                                                         <?php } ?>                                                                                                     
                                                    </select>
                                                     
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input">Select Child Table</label>
                                                    
                                                    <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='child_db[]' id='child_db' >
                                                      
                                                     <?php if(isset($load_tables) && count($load_tables) > 0){
                                                        
                                                        foreach($load_tables as $row){
                                                          ?>
                                                        <option value="<?=!empty($row['Tables_in_'.$db_name])?$prefix.$row['Tables_in_'.$db_name]:''?>"><?=!empty($row['Tables_in_'.$db_name])?$row['Tables_in_'.$db_name]:''?></option>
                                                        <?php                                   }
                                                         } ?> 
                                                      </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input id="mls_id" name="mls_id" type="hidden" value="<?php echo $this->uri->segment('4');?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="submit" id="submit" class="btn btn-secondary" value="Save" title="Save"onclick="showloading();" name="submitbtn" />
                                             <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

 <script type="text/javascript">
     $(document).ready(function(){
        $("#div_msg").fadeOut(4000); 
    });
    function showloading()
    {
        if ($('#<?php echo $viewname?>').parsley().isValid()) {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
       }
    }
    function check_state(state_name)
    {
        var country_id = $('#country').val();
	$.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_state';?>",
            dataType: 'json',
            async: false,
            data: {'state_name':state_name,'country_id':country_id,'id':<?=!empty($editRecord)?$editRecord[0]['id']:'0'?>},
            success: function(data){
                if(data == '1')
                {
                    $('#state').focus();
                    $('#submit').attr('disabled','disabled');

                    $.confirm({'title': 'Alert','message': " <strong> State name already exist in selected country..!! "+"<strong></strong>",
                        'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
                            $('#state').focus();
                            $('#submit').removeAttr('disabled');
                        }}}
                    });
                }
            }
        });
        return false;
    }
     $("select#child_db").multiselect({
        }).multiselectfilter();
     <?php
        if(!empty($mls_tables_data))
        {
            ?>
            load_child_table();
            <?
        }
     ?>
    function load_child_table()
    {
        $('#child_db')
            .empty()
            /*.append('<option value="">Select Child Table</option>')*/
        ;
        var selected_val=$("#master_table option:selected").val();
        $("#master_table option").each(function()
        {
            var value = $(this).val();
            var text = $(this).text();
            if(selected_val != value && value !='')
            {

                var option = $('<option />');
                option.attr('value', value).text(text);
                <?php 
                if(!empty($child_tables))
                {
                   ?>
                   var child_tables = <?php echo json_encode($child_tables); ?>;
                   if($.inArray(value,child_tables) !== -1)
                   {
                        option.attr("selected","selected");
                    }
                   <? 
                }
                ?>
                //
                $('#child_db').append(option);
            }
        });
        $("select#child_db").multiselect('refresh').multiselectfilter();
    }
</script>