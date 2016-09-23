<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;

?>
<div id="content">
<div id="content-header">
   <h1><?=$this->lang->line('interaction_plans_add_head');?></h1>
  </div>
 <div id="content-container">
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-header">
              <h3> <i class="fa fa-table"></i><?=$this->lang->line('interaction_add_table_head');?> </h3>
            </div>
            <!-- /.portlet-header -->
            
            <div class="portlet-content">
              <div class="table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                  <div class="row dt-rb">
                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate >
                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-xs-12 mrgb2">
                          <label for="text-input"><?=$this->lang->line('interaction_plan_name');?>:</label>
                          <input type="text" id="txt_plan_name" name="txt_plan_name" class="form-control parsley-validated" value="<?php if(!empty($editRecord[0]['plan_name'])){ echo $editRecord[0]['plan_name']; }?>" placeholder="Enter Communication Name">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2">
                          <label for="text-input"><?=$this->lang->line('interaction_description');?>:</label>
                           <textarea placeholder="Description" id="txtarea_description" name="txtarea_description" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['description'])){ echo $editRecord[0]['description']; }?></textarea>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2">
                            <label for="text-input"><?=$this->lang->line('interaction_add_status');?>:</label>
                            <input id="txt_interaction_status" name="txt_interaction_status" class="form-control parsley-validated" type="text" readonly value="<?php if(!empty($interaction_plan_status[0]['name'])){ echo $interaction_plan_status[0]['name']; }?>">
                            <input id="interaction_plan_status_id" name="interaction_plan_status_id" type="hidden" value="<?php if(!empty($interaction_plan_status[0]['id'])){ echo $interaction_plan_status[0]['id']; }?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2">
                          <label for="text-input"><?=$this->lang->line('interaction_target_audience');?>:</label>
                          <textarea placeholder="Target Audience" id="txtarea_target_audience" name="txtarea_target_audience" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['target_audience'])){ echo $editRecord[0]['target_audience']; }?></textarea>

                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 mrgb2 icheck-input-new"> <strong class="assign_title"><?=$this->lang->line('interaction_plan_start_date');?>:</strong>
                          <div class="form-group">
                            <div class="radio">
                              <label class="checkbox">
                              <div class="iradio_minimal-blue icheck-input parsley-success" style="position: relative;">
                                <div class="iradio_minimal-blue icheck-input parsley-validated" style="position: relative;">
                                    <input type="radio" data-required="true" class="icheck-input parsley-validated" name="plan_start_date" value="1" <?php if(!empty($editRecord[0]['plan_start_type']) && $editRecord[0]['plan_start_type'] == '1'){ echo 'checked="checked"'; }?> style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;">
                                    </ins>
                                </div>
                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
                              </label>
                              <label><?=$this->lang->line('interaction_concacts_assignment_date');?></label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="radio">
                              <label class="checkbox">
                              <div class="iradio_minimal-blue icheck-input parsley-success" style="position: relative;">
                                <div class="iradio_minimal-blue icheck-input parsley-validated" style="position: relative;">
                                    <input type="radio" data-required="true" class="icheck-input parsley-validated" name="plan_start_date" value="2" <?php if(!empty($editRecord[0]['plan_start_type']) && $editRecord[0]['plan_start_type'] == '2'){ echo 'checked="checked"'; }?>  style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;">
                                    </ins>
                                </div>
                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
                              </label>
                              <label><?=$this->lang->line('interaction_start_date');?></label>
                              <div class="input-group date ui-datepicker mrgt3">
                                <input id="txt_start_date" name="txt_start_date" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])); }?>" readonly="readonly">

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  
                    <div class="col-sm-6">
                      <div class="col-sm-12 topnd_margin1"> <strong class="assign_title">Assign Contact Lead</strong> <a class="text_color_red text_size" href="#"><i class="fa fa-plus-square"></i> Select Contacts</a> </div>
                      <div class="col-sm-12">
                        <table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
                          <thead>
                            <tr role="row">
                              <th width="32%" aria-label="Rendering engine: activate to sort column ascending" aria-sort="descending" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" data-filterable="true" data-sortable="true" data-direction="desc" rowspan="1" colspan="1">Name</th>
                              <th width="47%" aria-label="Browser: activate to sort" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" data-direction="asc" data-filterable="true" data-sortable="true" rowspan="1" colspan="1">Company Name</th>
                              <th width="21%" aria-label="Platform(s): activate to sort " aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" data-filterable="true" data-sortable="true" rowspan="1" colspan="1">Intraction</th>
                            </tr>
                          </thead>
                            <tbody aria-relevant="all" aria-live="polite" role="alert">
                            <tr class="odd">
                              <td class="sorting_1"><a data-toggle="modal" href="#basicModal">Steven</a></td>
                              <td class="sorting_2">Verve System</td>
                              <td class="text-center">
                                <button class="btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>
                              </td>
                            </tr>
                            <tr class="even">
                              <td class="sorting_1"><a href="#">Nancy</a></td>
                              <td class=" sorting_2">&nbsp;</td>
                              <td class="text-center">
                                <button class="btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>
                              </td>
                            </tr>
                            <tr class="odd">
                              <td class="sorting_1"><a href="#">Jennifer</a></td>
                              <td class="sorting_2">Kaizen Developer</td>
                              <td class="text-center">
                                <button class="btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>
                              </td>
                            </tr>
                            <tr class="even">
                              <td class="  sorting_1"><a href="#">Richard</a></td>
                              <td class="  sorting_2">&nbsp;</td>
                              <td class="text-center">
                                <button class="btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>
                              </td>
                            </tr>
                            <tr class="odd">
                              <td class="  sorting_1"><a href="#">Bulmer</a></td>
                              <td class="  sorting_2">&nbsp;</td>
                              <td class="text-center">
                                <button class="btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center">
                    <input type="submit" class="btn btn-success" value="Create InterCommunication" name="submitbtn" />
                  </div>
                  </form>
                </div>
              </div>
              <!-- /.table-responsive --> 
              
            </div>
            <!-- /.portlet-content --> 
            
          </div>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$( "#txt_start_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		maxDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
 
   //Datepicker show on select redio button
  $(".mrgt3").hide();
    $('input[type="radio"]').click(function(){
      //alert($(this).attr("value"));
      if($(this).attr("value")=="2"){
          $(".mrgt3").show();
      }else{
          $(".mrgt3").hide();
      }
  });
});
</script>

