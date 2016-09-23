<?php
/*
        @Description: Work Time Configuration Add page
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Work Time Configuration Add 
        @Date       : 14-08-2014
*/
	
    $viewname = $this->router->uri->segments[2]; 
    isset($work_time) ? $loadcontroller='insert_data' : $loadcontroller='insert_data';
    $path_insert = $viewname."/".$loadcontroller;
    isset($user_leave) ? $loadcontroller='insert_data' : $loadcontroller='insert_data';
    $path_status = $viewname."/".$loadcontroller;
	isset($special_rules) ? $loadcontroller='insert_data' : $loadcontroller='insert_data';
    $path_status = $viewname."/".$loadcontroller;
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<!--time picker-->
<link rel="stylesheet" href="<?php echo base_url();?>css/datepicker_css/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.timepicker.js"></script>
<div id="content">
  <div id="content-header">
    <h1>Work Time Configuration</h1>
  </div>
  <div id="content-container">
    <div class="content_right_part">
      <div class="chart_bg1 tbl_border">
        <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_insert?>" class="form parsley-form" >
          <div class="col-md-12">
            <div class="portlet">
              <div class="portlet-header">
                <h3> <?php echo $this->lang->line('common_label_worktime')?> </h3>
                <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span> </div>
              <div class="portlet-content">
                <div class="col-md-7">
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_mon" value="1" <?php if($work_time[0]['if_mon']=='1'){echo 'checked="checked"';} ?> />
                      Monday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="mon_start_time" id="mon_start_time" value="<?php if($work_time[0]['if_mon']=='1' && !empty($work_time[0]['mon_start_time'])){ echo date("h:i A", strtotime($work_time[0]['mon_start_time'])); }?>" placeholder="Start Time"/>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="mon_end_time" id="mon_end_time" value="<? if($work_time[0]['if_mon']=='1' && !empty($work_time[0]['mon_end_time'])){echo date("h:i A", strtotime($work_time[0]['mon_end_time']));} ?>" placeholder="End Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="button" style="width:auto;" class="btn btn-secondary" title="Copy To All" value="Copy to All" name="copytoall" onclick="copyall();">
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_tue" value="1"  <?php if($work_time[0]['if_tue']=='1'){echo 'checked="checked"';} ?>>
                      Tuesday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="tue_start_time" id="tue_start_time" value="<?php if($work_time[0]['if_tue']=='1' && !empty($work_time[0]['tue_start_time'])){echo date("h:i A", strtotime($work_time[0]['tue_start_time']));}?>" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="tue_end_time" id="tue_end_time" value="<?php  if($work_time[0]['if_tue']=='1' && !empty($work_time[0]['tue_end_time'])){echo date("h:i A", strtotime($work_time[0]['tue_end_time']));} ?>" placeholder="End Time" />
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_wed" value="1" <?php if($work_time[0]['if_wed']=='1'){echo 'checked="checked"';} ?>>
                      Wednesday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="wed_start_time" id="wed_start_time" value="<?php if($work_time[0]['if_wed']=='1' && !empty($work_time[0]['wed_start_time'])){echo date("h:i A", strtotime($work_time[0]['wed_start_time']));} ?>" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="wed_end_time" id="wed_end_time" value="<?php if($work_time[0]['if_wed']=='1' && !empty($work_time[0]['wed_end_time'])){echo date("h:i A", strtotime($work_time[0]['wed_end_time']));} ?>" placeholder="End Time" />
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_thu" value="1"<?php if($work_time[0]['if_thu']=='1'){echo 'checked="checked"';} ?>>
                      Thursday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="thu_start_time" id="thu_start_time" value="<?php if($work_time[0]['if_thu']=='1' && !empty($work_time[0]['thu_start_time'])){echo date("h:i A", strtotime($work_time[0]['thu_start_time']));} ?>" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="thu_end_time" id="thu_end_time" value="<?php if($work_time[0]['if_thu']=='1' && !empty($work_time[0]['thu_end_time'])){echo date("h:i A", strtotime($work_time[0]['thu_end_time']));} ?>" placeholder="End Time" />
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_fri" value="1" <?php if($work_time[0]['if_fri']=='1'){echo 'checked="checked"';} ?> >
                      Friday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="fri_start_time" id="fri_start_time" value="<?php if($work_time[0]['if_fri']=='1' && !empty($work_time[0]['fri_start_time'])){ echo date("h:i A", strtotime($work_time[0]['fri_start_time']));} ?>" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="fri_end_time" id="fri_end_time" value="<?php if($work_time[0]['if_fri']=='1' && !empty($work_time[0]['fri_end_time'])){ echo date("h:i A", strtotime($work_time[0]['fri_end_time']));} ?>" placeholder="End Time" />
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_sat" value="1" <?php if($work_time[0]['if_sat']=='1'){echo 'checked="checked"';} ?>>
                      Saturday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="sat_start_time" id="sat_start_time" value="<?php if($work_time[0]['if_sat']=='1' && !empty($work_time[0]['sat_start_time'])){ echo date("h:i A", strtotime($work_time[0]['sat_start_time']));} ?>" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="sat_end_time" id="sat_end_time" value="<?php  if($work_time[0]['if_sat']=='1' && !empty($work_time[0]['sat_end_time'])){ echo date("h:i A", strtotime($work_time[0]['sat_end_time'])) ;}?>" placeholder="End Time" />
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-lg-3 col-md-12">
                      <input type="checkbox" class="mycheckbox" name="if_sun" value="1" <?php if($work_time[0]['if_sun']=='1'){echo 'checked="checked"';} ?>>
                      Sunday</div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text"  class="form-control parsley-validated" name="sun_start_time" id="sun_start_time" value="<?php if($work_time[0]['if_sun']=='1' && !empty($work_time[0]['sun_start_time'])){echo date("h:i A", strtotime($work_time[0]['sun_start_time']));} ?>" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                      <input type="text" class="form-control parsley-validated" name="sun_end_time" id="sun_end_time" value="<?php if($work_time[0]['if_sun']=='1' && !empty($work_time[0]['sun_end_time'])){echo date("h:i A", strtotime($work_time[0]['sun_end_time']));} ?>" placeholder="End Time" />
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="col-md-12 addmoresubcatdata">
                    <div class="row">
                      <fieldset class="edit_main_div hight_fileset append_data">
                        <legend class="edit_title">On Leave</legend>
                        <?php
                                 	if(!empty($user_leave))
									{
									for($i=0;$i<count($user_leave);$i++){ ?>
                        <div class="space"></div>
                        <div id="flash_sub_cat"></div>
                        <div id="show"></div>
                        <div class="col-md-12 row">
                          <div class="col-md-5 col-lg-5 col-sm-4">
                            <input type="text" class="form-control parsley-validated from_datepicker" name="e_from_date[]" id="e_from_date<?php echo $user_leave[$i]['id'];?>" value="<?php echo  date($this->config->item('common_date_format'),strtotime($user_leave[$i]['from_date'])); ?>" placeholder="From Date" readonly="readonly" />
                          </div>
                          <div class="col-md-5 col-lg-5 col-sm-4 row">
                            <input type="text" class="form-control parsley-validated to_datepicker" name="e_to_date[]"  id="e_to_date<?php echo $user_leave[$i]['id'];?>" value="<?php echo  date($this->config->item('common_date_format'),strtotime($user_leave[$i]['to_date'])); ?>" placeholder="To Date" readonly="readonly"/>
                          </div>
                          <div class="col-md-2 margin-top-2 float-left pd12"> <a href="javascript:void(0);" onclick="get_submit_leave(<?php echo $user_leave[$i]['id'] ?>,<?php echo $i; ?>)" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup('<?=addslashes('Leave From '.$user_leave[$i]['from_date'].' TO '.$user_leave[$i]['to_date'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('user_base_url').$viewname;?>/delete_leave_record/<?php echo  $user_leave[$i]['id'] ?>');"> <i class="fa fa-times"></i> </a> </div>
                        </div>
                        <?php } }?>
                        <div class="col-md-12 row">
                          <div class="col-md-5 col-lg-5 col-sm-4">
                            <input type="text" class="form-control from_datepicker"  name="from_date[]" id="from_date" placeholder="From Date" readonly="readonly"/>
                          </div>
                          <div class="col-md-5 col-lg-5 col-sm-4 row">
                            <input type="text" class="form-control  to_datepicker" name="to_date[]" id="to_date" placeholder="To Date"  readonly="readonly"/>
                          </div>
                          <div class="col-md-2 pd13"> <a id="addScnt_status" class="btn btn-xs btn-success addnewfielddropdown" title="Add More Field" href="javascript:void(0);"><i class="fa fa-plus"></i></a> </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 clear addmoresubcatdata1">
                  <div class="row">
                    <fieldset class="edit_main_div hight_fileset append_data1">
                      <legend class="edit_title">Special Rules</legend>
                      <?php
                                 	if(!empty($special_rules))
									{
										
									for($i=0;$i<count($special_rules);$i++){ ?>
                      <div class="space1"></div>
                      <div id="flash_sub_cat1"></div>
                      <div id="show1"></div>
                      <div class="col-md-2 clear">
                        <select class="form-control parsley-validated" name="e_nth_day" id="e_nth_day<?php echo $special_rules[$i]['id'];?>">
                          <option value="">Please Select</option>
                          <option <?php if(!empty($special_rules[$i]['nth_day']) && $special_rules[$i]['nth_day'] == '1'){ echo "selected"; }?> value="1">First</option>
                          <option <?php if(!empty($special_rules[$i]['nth_day']) && $special_rules[$i]['nth_day'] == '2'){ echo "selected"; }?> value="2">Second</option>
                          <option <?php if(!empty($special_rules[$i]['nth_day']) && $special_rules[$i]['nth_day'] == '3'){ echo "selected"; }?> value="3">Third</option>
                          <option <?php if(!empty($special_rules[$i]['nth_day']) && $special_rules[$i]['nth_day'] == '4'){ echo "selected"; }?> value="4">Fourth</option>
                          <option <?php if(!empty($special_rules[$i]['nth_day']) && $special_rules[$i]['nth_day'] == '5'){ echo "selected"; }?> value="5">Last</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <select class="form-control parsley-validated" name="e_nth_date" id="e_nth_date<?php echo $special_rules[$i]['id'];?>">
                          <option value="">Please Select</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '1'){ echo "selected"; }?> value="1">Day</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '2'){ echo "selected"; }?> value="2">Weekday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '3'){ echo "selected"; }?> value="3">Weekend</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '4'){ echo "selected"; }?> value="4">Monday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '5'){ echo "selected"; }?> value="5">Tuesday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '6'){ echo "selected"; }?> value="6">Wednesday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '7'){ echo "selected"; }?> value="7">Thursday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '8'){ echo "selected"; }?> value="8">Friday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '9'){ echo "selected"; }?> value="9">Saturday</option>
                          <option <?php if(!empty($special_rules[$i]['nth_date']) && $special_rules[$i]['nth_date'] == '10'){ echo "selected"; }?> value="10">Sunday</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <div class="col-md-6"> Of every month </div>
                        <div class="col-md-6">
                          <input type="radio" name="e_rule_type<?php echo $special_rules[$i]['id'] ?>"  id="e_rule_type<?php echo $special_rules[$i]['id'];?>" <?php if($special_rules[$i]['rule_type']=='1'){echo 'checked="checked"';} ?> value="1" />
                          Off </div>
                      </div>
                      <div class="col-md-2"> </div>
                      <div class="col-md-2"> </div>
                      <div class="col-md-1"> </div>
                      <div class="col-md-2 clear"> </div>
                      <div class="col-md-2"> </div>
                      <div class="col-md-3">
                        <div class="col-md-6"> </div>
                        <div class="col-md-6">
                          <input type="radio" name="e_rule_type<?php echo $special_rules[$i]['id'] ?>"  id="e_rule_types<?php echo $special_rules[$i]['id'];?>" value="2" <?php if($special_rules[$i]['rule_type']=='2'){echo 'checked="checked"';} ?> />
                          Has Special Time </div>
                      </div>
                      <div class="col-md-2">
                        <input type="text"  class="form-control parsley-validated" name="e_start_time" id="e_start_time<?php echo $special_rules[$i]['id'];?>" value="<?php if($special_rules[$i]['rule_type']=='2'){ echo  date("h:i A", strtotime($special_rules[$i]['start_time'])); }?>" placeholder="Start Time" />
                      </div>
                      <div class="col-md-2">
                        <input type="text"  class="form-control parsley-validated" name="e_end_time" id="e_end_time<?php echo $special_rules[$i]['id'];?>" value="<?php if($special_rules[$i]['rule_type']=='2'){ echo date("h:i A", strtotime($special_rules[$i]['end_time'])) ;}?>" placeholder="End Time" />
                      </div>
                      <div class="col-md-1"> <a href="javascript:void(0);" onclick="get_submit_rules(<?php echo $special_rules[$i]['id'] ?>,<?php echo $special_rules[$i]['id']; ?>)" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup('<?=addslashes('Rule No. '.$special_rules[$i]['id'])?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('user_base_url').$viewname;?>/delete_rules_record/<?php echo $special_rules[$i]['id'] ?>');"> <i class="fa fa-times"></i> </a> </div>
                      <?php
							}}
							?>
                      <div class="col-md-2 clear">
                        <select class="form-control parsley-validated" name="nth_day[]" id="nth_day">
                          <option value="">Please Select</option>
                          <option value="1">First</option>
                          <option value="2">Second</option>
                          <option value="3">Third</option>
                          <option value="4">Fourth</option>
                          <option value="5">Last</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <select class="form-control parsley-validated" name="nth_date[]" id="nth_date">
                          <option value="">Please Select</option>
                          <option value="1">Day</option>
                          <option value="2">Weekday</option>
                          <option value="3">Weekend</option>
                          <option value="4">Monday</option>
                          <option value="5">Tuesday</option>
                          <option value="6">Wednesday</option>
                          <option value="7">Thursday</option>
                          <option value="8">Friday</option>
                          <option value="9">Saturday</option>
                          <option value="10">Sunday</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <div class="col-md-6"> Of every month </div>
                        <div class="col-md-6">
                          <input type="radio" name="rule_type0" checked="checked"  id="rule_type" value="1" />
                          Off </div>
                      </div>
                      <div class="col-md-3">
                        <div class="col-md-6"> </div>
                        <div class="col-md-6"> </div>
                      </div>
                      <div class="col-md-1"> </div>
                      <div class="col-md-2 clear"> </div>
                      <div class="col-md-2"> </div>
                      <div class="col-md-3">
                        <div class="col-md-6"> </div>
                        <div class="col-md-6">
                          <input type="radio" name="rule_type0"  id="rule_type" value="2" />
                          Has Special Time </div>
                      </div>
                      <div class="col-md-2">
                        <input type="text"  class="form-control parsley-validated" name="start_time[]" id="start_time" placeholder="Start Time"/>
                      </div>
                      <div class="col-md-2">
                        <input type="text"  class="form-control parsley-validated" name="end_time[]" id="end_time" placeholder="End Time" />
                      </div>
                      <div class="col-md-1"> <a  id="addScnt_status1" class="btn btn-xs btn-success addnewfielddropdown" title="Add More Field" href="javascript:void(0);"><i class="fa fa-plus"></i></a> </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 pull-left text-center margin-top-10">
                    <input type="hidden" id="contacttab" name="contacttab" value="1" />
                    <input type="submit" class="btn btn-secondary-green" value="Save" title="Save" onclick="return setdefaultdata();" name="submitbtn" />
                    <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	function setdefaultdata()
	 {
		if ($('#<?php echo $viewname?>').parsley().isValid()) {
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			
		}
	 }
	var catsubcatcount = 0;
    $(function() {
		$('body').on('click', '#addScnt_status', function(){
		
		innerhtml = '';
		
		innerhtml += '<div class="col-md-12 row padding-top-2 clear catsubcatdiv_'+catsubcatcount+'">';
			innerhtml += '<div class="col-md-5 col-lg-5 col-sm-4">';
			innerhtml += '<input type="text" class="form-control parsley-validated from_datepicker" id="from_date'+catsubcatcount+'" data-required="required" name="from_date[]" placeholder="From Date" readonly="readonly" /></div>';
			innerhtml += '<div class="col-md-5 col-lg-5 col-sm-4 row">';
			innerhtml += '<input type="text" class="form-control parsley-validated to_datepicker"  id="to_date'+catsubcatcount+'" data-required="required" name="to_date[]" placeholder="To Date" readonly="readonly" /></div>';
		
			innerhtml += '<div class="col-md-2 pd13"> <button value="catsubcatdiv_'+catsubcatcount+'" class="btn btn-xs btn-primary remScnt"><i class="fa fa-times"></i></button></div>';
		innerhtml += '</div>';
		
		$(innerhtml).appendTo('.append_data');
		
		$(function() {
		var fromdateid = catsubcatcount;
		$('#from_date'+fromdateid).datepicker({
            showTimePicker: true,
            //minDate: 0,
            dateFormat: "mm/dd/yy",
            altFieldTimeOnly: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (date) {
				//alert('#from_date'+fromdateid);
                var to_date = $('#from_date'+fromdateid).datepicker('getDate');
				//alert(to_date.getDate());
                if(to_date != null)
				{
					to_date.setDate(to_date.getDate());
					$('#to_date'+fromdateid).datepicker('setDate', to_date);
					$('#to_date'+fromdateid).datepicker('option', 'minDate', to_date);
					$('#to_date'+fromdateid).focus();
				}
            }
        });
        $('#to_date'+fromdateid).datepicker({
            showTimePicker: false,
            dateFormat: "mm/dd/yy",
            onClose: function () {
                var dt1 = $('#from_date'+fromdateid).datepicker('getDate');
                var dt2 = $('#to_date'+fromdateid).datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = $('#to_date'+fromdateid).datepicker('option', 'minDate');
                    $('#to_date'+fromdateid).datepicker('setDate', minDate);
                }
            }
        });

});
		
		catsubcatcount++;
		return false;
        }); 
		     
		$('body').on('click', '.remScnt', function(){ 
			$(this).closest('div.'+this.value).remove();
        });
    }); 

</script> 
<script type="text/javascript">
	
	var catsubcatcount_rules = 1;
    $(function() {
		$('body').on('click', '#addScnt_status1', function(){
		
		innerhtml = '';
		
		innerhtml += '<div class="padding-top-2 clear catsubcatdiv_'+catsubcatcount_rules+'">';
			innerhtml += '<div class="col-md-2">';
			innerhtml += '<select class="form-control parsley-validated" name="nth_day[]" id="nth_day">';
            innerhtml += '<option value="">Please Select</option>';
            innerhtml += '<option value="1">First</option>';
			innerhtml += '<option value="2">Second</option>';
            innerhtml += '<option value="3">Third</option>';
            innerhtml += '<option value="4">Fourth</option>';
            innerhtml += '<option value="5">Last</option>';
			innerhtml += '</select>';
			innerhtml += '</div>';
			   
			innerhtml += '<div class="col-md-2">';
			innerhtml += '<select class="form-control parsley-validated" name="nth_date[]" id="nth_date">';
            innerhtml += '<option value="">Please Select</option>';
            innerhtml += '<option value="1">Day</option>';
			innerhtml += '<option value="2">Weekday</option>';
            innerhtml += '<option value="3">Weekend</option>';
            innerhtml += '<option value="4">Monday</option>';
            innerhtml += '<option value="5">Tuesday</option>';
            innerhtml += '<option value="6">Wednesday</option>';
			innerhtml += '<option value="7">Thursday</option>';
            innerhtml += '<option value="8">Friday</option>';
            innerhtml += '<option value="9">Saturday</option>';
            innerhtml += '<option value="10">Sunday</option>';
			innerhtml += '</select>';
			innerhtml += '</div>';
			innerhtml += '<div class="col-md-3">';			
			innerhtml += '<div class="col-md-6">';
			innerhtml += 'Of every month</div>';
			
			innerhtml += '<div class="col-md-6">';
			innerhtml += '<input type="radio" name="rule_type'+catsubcatcount_rules+'" checked id="rule_type" value="1" />Off</div>';			
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-2">';
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-2">';
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-1">';
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-2 clear">';
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-2">';
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-3">';
			innerhtml += '<div class="col-md-6">';
			innerhtml += '</div>';
			innerhtml += '<div class="col-md-6">';
			innerhtml += '<input type="radio" name="rule_type'+catsubcatcount_rules+'"  id="rule_type" value="2" />Has Special Time';
			innerhtml += '</div>';
			innerhtml += '</div>';
			
			innerhtml += '<div class="col-md-2">';
			innerhtml += '<input type="text" class="form-control parsley-validated" data-required="required" name="start_time[]" id="start_time" placeholder="Start Time" /></div>';
			
			innerhtml += '<div class="col-md-2">';
			innerhtml += '<input type="text" class="form-control parsley-validated" data-required="required" name="end_time[]" id="end_time" placeholder="End Time" /></div>';
			innerhtml += '<div class="col-md-1"> <button value="catsubcatdiv_'+catsubcatcount_rules+'" class="btn btn-xs btn-primary remScnt"><i class="fa fa-times"></i></button></div>';
		innerhtml += '</div>';
		
		$(innerhtml).appendTo('.append_data1');
		
		$('#mon_start_time,#mon_end_time,#tue_start_time,#tue_end_time,#wed_start_time,#wed_end_time,#thu_start_time,#thu_end_time,#fri_start_time,#fri_end_time,#sat_start_time,#sat_end_time,#sun_start_time,#sun_end_time,#start_time,#end_time').timepicker({
					showNowButton: true,
					showDeselectButton: true,
				    showPeriod: true,
    				showLeadingZero: true,
					defaultTime: '',  // removes the highlighted time for when the input is empty.
					showCloseButton: true
			});
		
		
		catsubcatcount_rules++;
		return false;
        }); 
		     
		$('body').on('click', '.remScnt', function(){ 
			$(this).closest('div.'+this.value).remove();
        });
    }); 

</script> 
<script type="text/javascript">
function copyall(){
			var a = document.getElementById("mon_start_time").value;
			var b = document.getElementById("mon_end_time").value;
			document.getElementById("tue_start_time").value=a
			document.getElementById("tue_end_time").value=b
			document.getElementById("wed_start_time").value=a
			document.getElementById("wed_end_time").value=b
			document.getElementById("thu_start_time").value=a
			document.getElementById("thu_end_time").value=b
			document.getElementById("fri_start_time").value=a
			document.getElementById("fri_end_time").value=b
			document.getElementById("sat_start_time").value=a
			document.getElementById("sat_end_time").value=b
			document.getElementById("sun_start_time").value=a
			document.getElementById("sun_end_time").value=b
}
</script> 
<script type="text/javascript">
	
	//Time picker
	$('#mon_start_time,#mon_end_time,#tue_start_time,#tue_end_time,#wed_start_time,#wed_end_time,#thu_start_time,#thu_end_time,#fri_start_time,#fri_end_time,#sat_start_time,#sat_end_time,#sun_start_time,#sun_end_time,#start_time,#end_time').timepicker({
					showNowButton: true,
					showDeselectButton: true,
				    showPeriod: true,
    				showLeadingZero: true,
					defaultTime: '',  // removes the highlighted time for when the input is empty.
					showCloseButton: true
			});
/*$(document).ready(function (){			
	jQuery('#mon_start_time,#mon_end_time,#tue_start_time,#tue_end_time,#wed_start_time,#wed_end_time,#thu_start_time,#thu_end_time,#fri_start_time,#fri_end_time,#sat_start_time,#sat_end_time,#sun_start_time,#sun_end_time,#start_time,#end_time').timepicker({
			showDate:false,
            stepHour: 1,
			stepMinute: 1,
			dateFormat: "",
			timeFormat: "HH:mm",
			altFormat: "yy-mm-dd ^",
			altFieldTimeOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
        });
});*/			
	//Time Picker code end.....
	
	//date picker code
	$(function() {
		/*$( "#from_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			datepicker:true,
			dateFormat: "mm/dd/yy",
		});
		$( "#to_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			datepicker:true,
			dateFormat: "mm/dd/yy",
		});*/
		
		$('#from_date').datepicker({
            showTimePicker: true,
            //minDate: 0,
            dateFormat: "mm/dd/yy",
            altFieldTimeOnly: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (date) {
                var to_date = $('#from_date').datepicker('getDate');
				//alert(to_date.getDate());
                if(to_date != null)
				{
					to_date.setDate(to_date.getDate());
					$('#to_date').datepicker('setDate', to_date);
					$('#to_date').datepicker('option', 'minDate', to_date);
					$('#to_date').focus();
				}
            }
        });
        $('#to_date').datepicker({
            showTimePicker: false,
            dateFormat: "mm/dd/yy",
            onClose: function () {
                var dt1 = $('#from_date').datepicker('getDate');
                var dt2 = $('#to_date').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = $('#to_date').datepicker('option', 'minDate');
                    $('#to_date').datepicker('setDate', minDate);
                }
            }
        });

});
    
<!-- ==== LEAVE UPDATE AJAX ===== -->
    
	function get_submit_leave(id)
    {
	var fromdate = $("#e_from_date"+id).val();
	var todate = $("#e_to_date"+id).val();
	if(fromdate=='' && todate=='' && id=='')
	{
            alert("Enter text..");
            $("#fromdate").focus();
	}
	else
	{
            $("#flash_sub_cat").show();
            $("#flash_sub_cat").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>user/<?=$viewname;?>/update_leave',
                data: { from_date:fromdate,to_date:todate,leave_id:id },
                cache: true,
                success: function(html)
                {
                        $("#flash_sub_cat").after(html);
                        $("#from_date").focus();
                }  
				
            });
	}
	return false;
    }

<!-- ==== RULES UPDATE AJAX ===== -->

    function get_submit_rules(id)
    {
	var nth_day = $("#e_nth_day"+id).val();
	var nth_date=$("#e_nth_date"+id).val();
	var rule_type = $('input:radio[name=e_rule_type'+id+']:checked').val();
	var start_time = $("#e_start_time"+id).val();
	var end_time = $("#e_end_time"+id).val();
	if(nth_day=='' && nth_date=='' && rule_type=='' && start_time=='' && end_time=='' && id=='')
	{
            alert("Enter text..");
            $("#e_fromdate").focus();
	}
	else
	{
            $("#flash_sub_cat1").show();
            $("#flash_sub_cat1").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>user/<?=$viewname;?>/update_rules',
                data: { nth_day:nth_day,nth_date:nth_date,rule_type:rule_type,start_time:start_time,end_time:end_time,rule_id:id },
                cache: true,
                success: function(html)
                {
                        $("#flash_sub_cat1").after(html);
                        $("#e_nth_day").focus();
                }  
				
            });
	}
	return false;
    }

</script> 

<!-- ================== END Ajax Script ================== --> 
<script type="text/javascript">

<?php
if(!empty($user_leave))
{
for($i=0;$i<count($user_leave);$i++){ ?>
$(function() {
	/*$( "#e_from_date<?php echo $user_leave[$i]['id'];?>" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		datepicker:true,
		dateFormat: "mm/dd/yy",
	});
	$( "#e_to_date<?php echo $user_leave[$i]['id'];?>" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		datepicker:true,
		dateFormat: "mm/dd/yy",
	});*/
	$('#e_from_date<?php echo $user_leave[$i]['id'];?>').datepicker({
            showTimePicker: true,
            //minDate: 0,
            dateFormat: "mm/dd/yy",
            altFieldTimeOnly: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (date) {
                var to_date = $('#e_from_date<?php echo $user_leave[$i]['id'];?>').datepicker('getDate');
                if(to_date != null)
				{
					to_date.setDate(to_date.getDate());
					$('#e_to_date<?php echo $user_leave[$i]['id'];?>').datepicker('setDate', to_date);
					$('#e_to_date<?php echo $user_leave[$i]['id'];?>').datepicker('option', 'minDate', to_date);
					$('#e_to_date<?php echo $user_leave[$i]['id'];?>').focus();
				}
            }
        });
        $('#e_to_date<?php echo $user_leave[$i]['id'];?>').datepicker({
            showTimePicker: false,
            dateFormat: "mm/dd/yy",
            onClose: function () {
                var dt1 = $('#e_from_date<?php echo $user_leave[$i]['id'];?>').datepicker('getDate');
                var dt2 = $('#e_to_date<?php echo $user_leave[$i]['id'];?>').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = $('#e_to_date<?php echo $user_leave[$i]['id'];?>').datepicker('option', 'minDate');
                    $('#e_to_date<?php echo $user_leave[$i]['id'];?>').datepicker('setDate', minDate);
                }
            }
        });
});
<?php } }?>

</script> 
<script type="text/javascript">
<?php
if(!empty($special_rules))
{
for($i=0;$i<count($special_rules);$i++){ ?>
$(function() {
$('#e_start_time<?php echo $special_rules[$i]['id'];?>').timepicker({
					showNowButton: true,
					showDeselectButton: true,
				    showPeriod: true,
    				showLeadingZero: true,
					defaultTime: '',  // removes the highlighted time for when the input is empty.
					showCloseButton: true
				});
				$('#e_end_time<?php echo $special_rules[$i]['id'];?>').timepicker({
					showNowButton: true,
					showDeselectButton: true,
				    showPeriod: true,
    				showLeadingZero: true,
					defaultTime: '',  // removes the highlighted time for when the input is empty.
					showCloseButton: true
				});
				$('#e_end_time<?php echo $special_rules[$i]['id'];?>').attr("readonly","readonly");
				$('#e_start_time<?php echo $special_rules[$i]['id'];?>').attr("readonly","readonly");
});
<?php } }?>
$(document).ready(function(){
$('#mon_start_time,#mon_end_time,#tue_start_time,#tue_end_time,#wed_start_time,#wed_end_time,#thu_start_time,#thu_end_time,#fri_start_time,#fri_end_time,#sat_start_time,#sat_end_time,#sun_start_time,#sun_end_time,#start_time,#end_time').attr("readonly","readonly");
});
</script> 
