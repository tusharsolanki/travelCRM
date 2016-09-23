<?php
/*
    @Description: Valuation Searched Add/Edit
    @Author     : Sanjay Moghariya
    @Date       : 03-12-2014

*/?>

<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
    $tabid = $this->router->uri->segments[5];
else
    $tabid = 1;
	
$formAction = !empty($editRecord)?'update_valuation_searched_data':'insert_valuation_searched_data'; 
$path = $viewname.'/'.$formAction;
?>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('valuation_searched_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i> <?php 
                            if(!empty($editRecord)){ echo $this->lang->line('valuation_searched_edit_header'); } 
                            else{ echo $this->lang->line('valuation_searched_add_head'); }?> </h3>
                        <span class="float-right margin-top--15"><a href="javascript:void(0)" title="Back" onclick="history.go(-1)" class="btn btn-secondary" id="back">Back</a> </span>
                    </div>
    
                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="home">
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" data-validate="parsley" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate>
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <input id="id" name="sel_contact_id" type="hidden" value="<?php if(!empty($sel_contact_id)){ echo $sel_contact_id; }?>">
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <?php
                                                    $address = '';
                                                    if(!empty($editRecord[0]['search_address'])) $address = $editRecord[0]['search_address'].',';
                                                    if(!empty($editRecord[0]['city'])) $address .= $editRecord[0]['city'].' ';
                                                    if(!empty($editRecord[0]['state'])) $address .= $editRecord[0]['state'].' ';
                                                    if(!empty($editRecord[0]['zip_code'])) $address .= $editRecord[0]['zip_code'];
                                                    ?>
                                                    <label for="text-input"><?=$this->lang->line('contact_joomla_val_searched_address');?></label>
                                                    <input id="txt_search_address" readonly name="txt_search_address" placeholder="" class="form-control parsley-validated" type="text" value="<?php echo htmlentities(trim($address,','));?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('contact_joomla_val_searched_domain');?></label>
                                                    <input id="txt_domain" readonly name="txt_domain" placeholder="" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['domain'])){ echo $editRecord[0]['domain'];}?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="">
                                                    <div class="col-sm-6"> <label for="text-input"><?= $this->lang->line('contact_joomla_val_searched_timeline')?></label></div>
                                                    <div class="col-sm-6 checkbox">
                                                        <label class="">
                                                            Weekly
                                                            <div class="float-left margin-left-15">
                                                                <input type="radio" value="Weekly" class=""  id="timeline" name="report_timeline" <?php if(!empty($editRecord[0]['report_timeline']) && $editRecord[0]['report_timeline'] == 'Weekly'){ echo 'checked="checked"'; }?> <?php if(empty($editRecord[0]['report_timeline'])){ echo "checked='checked'"; }?>>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="clear">
                                                    <div class="col-sm-6">
                                                    </div>         
                                                    <div class="col-sm-6 checkbox">
                                                        <label class="">
                                                            Monthly
                                                            <div class="float-left margin-left-15">
                                                                <input type="radio" value="Monthly" class=""  id="timeline" name="report_timeline" <?php if(!empty($editRecord[0]['report_timeline']) && $editRecord[0]['report_timeline'] == 'Monthly'){ echo 'checked="checked"'; }?>>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="">
                                                    <div class="col-sm-6"> <label for="text-input"><?= $this->lang->line('contact_joomla_val_searched_send_report')?></label></div>
                                                    <div class="col-sm-6 checkbox">
                                                        <label class="">
                                                            Yes
                                                            <div class="float-left margin-left-15">
                                                                <input type="radio" value="Yes" class=""  id="send_report" name="send_report" <?php if(!empty($editRecord[0]['send_report']) && $editRecord[0]['send_report'] == 'Yes'){ echo 'checked="checked"'; }?> <?php if(empty($editRecord[0]['send_report'])){ echo "checked='checked'"; }?>>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="clear">
                                                    <div class="col-sm-6"></div>         
                                                    <div class="col-sm-6 checkbox">
                                                        <label class="">
                                                            No
                                                            <div class="float-left margin-left-15">
                                                                <input type="radio" value="No" class=""  id="send_report" name="send_report" <?php if(!empty($editRecord[0]['send_report']) && $editRecord[0]['send_report'] == 'No'){ echo 'checked="checked"'; }?>>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <input type="hidden" id="contacttab" name="contacttab" value="1" />
                                            <input type="submit" class="btn btn-secondary" title="Save" value="Save" name="submitbtn" />
                                            <a class="btn btn-primary" href="javascript:history.go(-1);" title="Cancel">Cancel</a>
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
  
 </div>
 
<script type="text/javascript">
    $(document).ready(function() {
	
    });
</script>