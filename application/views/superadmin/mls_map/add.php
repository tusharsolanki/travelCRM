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
//pr($editRecord);
$formAction = !empty($editRecord[0]['id'])?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
$this->session->unset_userdata('state_add_session');
?>

<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('state_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i>Database Credentials</h3>
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
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('mls_name');?></label>
                                                    <? /* <select class="form-control parsley-validated" name="mls_id" id="mls_id" data-required="true">
                                                       <option value="">Select MLS</option>
                                                        <?php foreach($mls_data as $row){
                                                       if(!empty($editRecord[0]['mls_id']) && $editRecord[0]['mls_id'] == $row['id'])
                                                       { ?>
                                                        <option selected="selected" value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                                        <?php }
                                                        else
                                                        {
                                                            if(!empty($mls_id) && in_array($row['id'], $mls_id))
                                                            {}else{
                                                            ?>
                                                                <option value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                                            <?
                                                            }
                                                        }
                                                         }?>
                                                        
                                                    </select> <? */ ?>
                                                    <?=!empty($editRecord[0]['mls_name'])?$editRecord[0]['mls_name']:''?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('mapping_name');?><span class="val">*</span></label>
                                                    <input id="mapping_name" name="mapping_name" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['mapping_name'])?$editRecord[0]['mapping_name']:''?>" data-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('db_host_name');?><span class="val">*</span></label>
                                                    <input id="mls_hostname" name="mls_hostname" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['mls_hostname'])?$editRecord[0]['mls_hostname']:''?>" data-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('db_user_name');?><span class="val">*</span></label>
                                                    <input id="mls_db_username" name="mls_db_username" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['mls_db_username'])?$editRecord[0]['mls_db_username']:''?>" data-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('db_password');?></label>
                                                    <input id="mls_db_password" name="mls_db_password" class="form-control parsley-validated" type="password" value="<?=!empty($editRecord[0]['mls_db_password'])?$editRecord[0]['mls_db_password']:''?>" >
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('db_database_name');?><span class="val">*</span></label>
                                                    <input id="mls_db_name" name="mls_db_name" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['mls_db_name'])?$editRecord[0]['mls_db_name']:''?>"  data-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('image_url');?></label>
                                                    <input id="mls_image_url" data-type="map_url" data-parsley-type="map_url" name="mls_image_url" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['mls_image_url'])?$editRecord[0]['mls_image_url']:''?>" >
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('comment');?></label>
                                                    <input id="mls_comment" name="mls_comment" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['mls_comment'])?$editRecord[0]['mls_comment']:''?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input id="id" name="id" type="hidden" value="<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:''?>">
                                                    <input id="mls_id" name="mls_id" type="hidden" value="<?=!empty($editRecord[0]['mls_id'])?$editRecord[0]['mls_id']:''?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="submit" id="submit" class="btn btn-secondary" value="Save" title="Save"onclick="return showloading();" name="submitbtn" />
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
</script>