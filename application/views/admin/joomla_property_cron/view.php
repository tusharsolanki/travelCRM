<?php
/*
    @Description: View property cron data 
    @Author     : Sanjay Moghariya
    @Date       : 18-11-2014
*/?>

<?php 
$viewname = $this->router->uri->segments[2];
$task_id = $this->router->uri->segments[4];
if(!empty($this->router->uri->segments[5]))
    $tabid = $this->router->uri->segments[5];
?>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('joomla_property_cron_view_head');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header"> 
                        <h3> <i class="fa fa-tasks"></i><?php echo $this->lang->line('joomla_property_cron_view_head'); ?></h3>
                        <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary margin-left-5px" title="Back">Back</a> </span>
                    </div>
                    <!-- /.portlet-header -->

                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="home">


                                    
                                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">

                                    <div class="col-sm-12 form-group">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label><?=$this->lang->line('joomla_property_cron_name');?></label>
                                            </div>
                                            <div class="col-sm-1"> : </div>

                                            <div class="col-sm-6"><?=ucfirst($editRecord[0]['name']);?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label><?=$this->lang->line('joomla_property_address');?></label>
                                            </div>
                                            <div class="col-sm-1"> : </div>
                                          <div class="col-sm-6">
                                              <?php
                                              $addr = !empty($editRecord[0]['neighborhood'])?$editRecord[0]['neighborhood']:'';
                                              $addr .= !empty($editRecord[0]['city'])?', '.$editRecord[0]['city']:'';
                                              $addr .= !empty($editRecord[0]['zip_code'])?' '.$editRecord[0]['zip_code']:'';
                                              echo $addr;
                                              ?>
                                          </div>
                                        </div>
                                        <?php /*
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label><?=$this->lang->line('joomla_property_cron_state');?></label>
                                            </div>
                                            <div class="col-sm-1"> : </div>
                                            <div class="col-sm-6"><?= !empty($editRecord[0]['state'])?($editRecord[0]['state']): '-';?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label><?=$this->lang->line('joomla_property_cron_country');?></label>
                                            </div>
                                            <div class="col-sm-1"> : </div>
                                            <div class="col-sm-6"><?= !empty($editRecord[0]['country'])?($editRecord[0]['country']): '-';?></div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-sm-3">
                                                <label><?=$this->lang->line('joomla_property_cron_neighbor');?></label>
                                            </div>
                                            <div class="col-sm-1"> : </div>
                                            <div class="col-sm-6"><?= !empty($editRecord[0]['neighborhood'])?($editRecord[0]['neighborhood']): '-';?></div>
                                        </div>
                                         */ ?>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label><?=$this->lang->line('joomla_property_cron_crontype');?></label>
                                            </div>
                                            <div class="col-sm-1"> : </div>
                                            <div class="col-sm-6"><?= !empty($editRecord[0]['cron_type'])?($editRecord[0]['cron_type']): '-';?></div>
                                        </div>
                                    </div>
                                    <div class="form-group">         
                                        <div class="col-sm-8">
                                            <label for="text-input"><?=$this->lang->line('common_label_assignuser').':';?></label>
                                            <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th width="50%"><?=$this->lang->line('user_label_name')?></th>
                                                        <th width="50%"><?=$this->lang->line('common_label_email')?></th>
                                                     </tr>

                                                   <?php if(!empty($datalist) && count($datalist)>0){
                                                            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                                            foreach($datalist as $row){?>
                                                                <tr <? if($i%2==1){ ?>class="bgtitle" <? }?>>
                                                                    <td class=""><?php if($row['contact_name']!='') { echo ucwords($row['contact_name']);}else{ echo ucwords($row['contact_name']);}?> </td>
                                                                    <td><?php if(!empty($row['email_id'])) { echo $row['email_id']; } else { echo "-"; } ?></td>
                                                                </tr>
                                                            <?php }
                                                        } else {?>

                                                            <tr>
                                                                  <td colspan="2" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                                                            </tr>

                                                    <?php } ?>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>      
                                    <div class="row"></div>
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

