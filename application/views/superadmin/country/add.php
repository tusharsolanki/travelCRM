<?php
/*
    @Description: Country add/edit page
    @Author     : Sanjay Moghariya
    @Date       : 26-12-2014
*/?>

<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
$this->session->unset_userdata('country_add_session');
?>

<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('country_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('country_add_head');}
                         else{ echo $this->lang->line('country_edit_head'); }?> </h3>
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
                                        </div><?php } ?>
    	 
                                </div>
                                <div class="row tab-pane fade in active" id="home">
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" data-validate="parsley" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url')?><?php echo $path?>" novalidate onkeypress="return event.keyCode != 13;">
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('country_name');?>
                                                        <span class="val">*</span></label>
                                                    <input id="country" name="country" placeholder="e.g. USA" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['country'])){ echo $editRecord[0]['country']; } else if (!empty($country_name)) { echo $country_name; }?>" onblur="check_country(this.value);" data-required="true">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
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
    function check_country(name)
    {
	$.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/check_country';?>",
            dataType: 'json',
            async: false,
            data: {'name':name,'id':<?=!empty($editRecord)?$editRecord[0]['id']:'0'?>},
            success: function(data){
                if(data == '1')
                {
                    $('#country_name').focus();
                    $('#submit').attr('disabled','disabled');

                    $.confirm({'title': 'Alert','message': " <strong> County Name Already Existing..!! "+"<strong></strong>",
                        'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
                            $('#country_name').focus();
                            $('#submit').removeAttr('disabled');
                        }}}
                    });
                }
            }
        });
        return false;
    }
</script>