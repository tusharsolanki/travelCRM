<?php
/*
    @Description: Joomla Dashboard view
    @Author     : Sanjay Moghairya
    @Date       : 01-12-2014

*/?>
<?php 

$viewname = $this->router->uri->segments[2];
$contact_id = $this->router->uri->segments[4];
/*if(!empty($this->router->uri->segments[5]))
    $tabid = $this->router->uri->segments[5];
else
    $tabid = 1;*/
$formAction = !empty($editRecord)?'update_view':'insert_data'; 
$path = $viewname.'/'.$formAction;
$path_per_tou = 'contacts/insert_personal_touches';
$path_comm = $viewname.'/insert_last_action_communication_plan';
$path_update_view = $viewname.'/update_view';


// Nishit //

$path_per_notes = 'contacts/insert_contact_notes';

////////////

$path_per_1 = 'contacts/insert_conversations';
$path_per_2 = $viewname.'/update_conversations';
//Path for facebook chat history 
$loadcontroller='view_record/'.$contact_id.'?action=login';
$path_view = $viewname."/".$loadcontroller;
$fb_path=$viewname."/fb_conversation";
$loadcontroller1='view_record/'.$contact_id.'/7';
$path_view = $viewname."/".$loadcontroller;
$path_view1 = $viewname."/".$loadcontroller1;

if(!empty($tabid)) {
    $tabid = $tabid;
}
else {
    $tabid = 1;
	}
//echo  $tabid;exit;	
?>
<style>
/*.ui-multiselect{width:55% !important;
margin-left: 14px;}*/
.ui-multiselect{width:265px !important;}
.ui-multiselect-menu {
    width:20.5% !important;
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.price_format.min.js"></script>

<div aria-hidden="true" style="display: none;" id="basicModal_2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Add To Do Task</h3>
      </div>
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_per_tou;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <!--<tr style="display:none;">
              <td>Action Type:</td>
              <td>
                <select id="interaction_type" name="interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
              </td>
            </tr>-->
            <tr>
              <td>Task:</td>
              <td>
                <textarea class="form-control" name="task" id="task"></textarea>
              </td>
            </tr>
            <tr>
              <td>Follow-up Date:</td>
              <td>
			  	 <input id="interaction_type" name="interaction_type" type="hidden"  value="7">
			  	 <input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id; ?>">
                 <input id="followup_date" name="followup_date" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['followup_date']) && $editRecord[0]['followup_date'] != '0000-00-00' && $editRecord[0]['followup_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['followup_date'])); }?>" readonly>
              </td>
            </tr>
          </table>
		 
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
      	<input type="hidden" name="from_joomla_view" value="1" />
        <input type="submit" value="Add To Do Task" class="btn btn-secondary" title="Add To Do Task" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">
        
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>


<div aria-hidden="true" style="display: none;" id="basicModal_notes" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Add Note</h3>
      </div>
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_per_notes;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Notes:</td>
              <td>
                <textarea class="form-control" name="notes_detail" id="notes_detail"></textarea>
              </td>
            </tr>
          </table>
		 
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
      	<input type="hidden" name="from_joomla_view" value="1" />
        <input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id; ?>">
        <input type="submit" value="Add Note" class="btn btn-secondary" title="Add Note" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">
        
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>


<div aria-hidden="true" style="display: none;" id="basicModal_conversation" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Add Call Log</h3>
      </div>
	   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>_call_log" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_per_1;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		 
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="display:none;">
              <td>Action Type:
			  </td>
              <td>
               <select id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>
                <textarea class="form-control" name="description" id="description"></textarea>
              </td>
            </tr>
            <tr>
              <td>Disposition:</td>
              <td>
                 <select id="disposition_type" name="disposition_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($disposition_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
                 <input type="hidden" name="from_joomla_view" value="1" />
				 <input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id; ?>">
              </td>
            </tr>
          </table>
		  
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="submit" id="activitylog" class="btn btn-secondary" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">Add Call Log</button>
        <!--<button type="button" class="btn btn-primary">Cancel</button>-->
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<div aria-hidden="true" style="display: none;" id="basicModal_email_popup" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Send <span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body holds-the-iframe">
        <iframe src="" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('contact_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3><i class="fa fa-tasks"></i><?=$this->lang->line('contact_view_table_head');?></h3>
                        <span class="pull-right"><a title="Back" class="btn btn-secondary  margin-left-5px" href="<?php echo $this->config->item('user_base_url')?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
                        <!--<span class="pull-right"><a title="Edit" class="btn btn-secondary " href="<?php echo $this->config->item('user_base_url')?><?php echo $viewname;?>/edit_record/<?php echo $contact_id;?>"><?php echo $this->lang->line('common_edit_title')?></a> </span>-->
                    </div>
                    <div class="portlet-content joomla-dashboard-page div_height"> 
                    <div class="row">
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        
                        <div class="leads_dashboard_top_left_part">
                       <h4><span><i class="fa fa-user"></i></span><?=ucwords($editRecord[0]['first_name']." ".$editRecord[0]['last_name'])?></h4>
                       <div class="leads_dashboard_details">
                       <i class="fa fa-phone"></i>
                       		<?php if(!empty($phone_trans_data[0]) && count($phone_trans_data) > 0){ ?>
                            <a title="Add Conversations Log" href="#basicModal_conversation"  data-toggle="modal" class="" onclick="addconversation();" >
                            	<?php
                                    //!empty($phone_trans_data[0]['phone_no'])?$phone_trans_data[0]['phone_no']:'';
                                    echo preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $phone_trans_data[0]['phone_no']);
                                ?> 
                            </a>
                            <?php }else{ echo '-';} ?>
                       <br />
                       <i class="fa fa-envelope"></i>
                            <?php
						   if(!empty($email_trans_data[0]) && !empty($editRecord[0])){
							  //pr($email_trans_data[0]); ?>
								<?php 
								if($editRecord[0]['is_subscribe'] == '0') { ?>
									<a href="#basicModal_email_popup" class="" id="basicModal_email_popup" data-toggle="modal" onclick="add_email_campaign('<?=$editRecord[0]['id']?>','<?=$email_trans_data[0]['id']?>')">
										<?=!empty($email_trans_data[0]['email_address'])?$email_trans_data[0]['email_address']:'';?> 
									</a> 
								<?php 
								}else{ ?>
                                	<?=!empty($email_trans_data[0]['email_address'])?$email_trans_data[0]['email_address']:'';?> 
                                <?php } ?>
								
						  <?php
						   }else{ echo '-';} ?>
                       <br />
                       <i class="fa fa-location-arrow"></i>
                       <?php
							/*if(!empty($address_trans_data[0])){ 
								if(!empty($address_trans_data[0]['city']) || !empty($address_trans_data[0]['state']) || !empty($address_trans_data[0]['zip_code'])){ ?>
								
								<?=!empty($address_trans_data[0]['city'])?$address_trans_data[0]['city']:'';?>
                                <?=!empty($address_trans_data[0]['state'])?", ".$address_trans_data[0]['state']:'';?>
                                <?=!empty($address_trans_data[0]['zip_code'])?", ".$address_trans_data[0]['zip_code']:'';?>
                                
                                <?php 
								}else{ echo '-';}
							} */
                       /*if(!empty($address_trans_data[0]['address_line1'])){
                                    echo $address_trans_data[0]['address_line1'];
                                }else{ echo '-';}*/
                                if(!empty($editRecord[0]['joomla_address'])){
                                    echo $editRecord[0]['joomla_address'];
                                }else{ echo '-';}
                       ?>
                       <br />
                       <!--<p><a href="#">dashboard link1</a> |
                       <a href="#">dashboard link1</a></p>-->
                       <div class="bottom-btn-set">
                     <?php if(!empty($phone_trans_data[0]['phone_no']))
					 { ?>  
                       <a title="Add Conversations Log" href="#basicModal_conversation"  data-toggle="modal" class="" onclick="addconversation();" ><button title="Log Call" data-type="danger" class="btn btn-secondary howler smaller_btn"><i class="fa fa-phone"></i>Log Call</button></a>
                       <?php } ?>
                       <?php
					   if(!empty($email_trans_data[0])){
						  //pr($email_trans_data[0]); ?>
                            <?php 
							if(!empty($editRecord[0]) && $editRecord[0]['is_subscribe'] == '0') { ?>
                                <a href="#basicModal_email_popup" class="text_size" id="basicModal_email_popup" data-toggle="modal" onclick="add_email_campaign('<?=$editRecord[0]['id']?>','<?=$email_trans_data[0]['id']?>')">
                                    <?php /*?><?=!empty($email_trans_data[0]['email_address'])?$email_trans_data[0]['email_address']:'';?> <?php */?>
                                    <button title="New Email" data-type="danger" class="btn btn-secondary howler smaller_btn"><i class="fa fa-envelope"></i>New Email</button>
                                </a> 
							<?php 
							} ?>
                            
                      <?php
					   } ?>
                      
                      <a title="Add To Do" href="#basicModal_2"  data-toggle="modal" class="" onclick="addconversation1();"><button title="Set To Do" data-type="danger" class="btn btn-secondary howler smaller_btn"><i class="fa fa-file-text"></i>Set To Do</button></a>
                      
                      <a title="Add Notes" href="#basicModal_notes"  data-toggle="modal" class="">
                      <button title="Add Note" data-type="danger" class="btn btn-secondary howler smaller_btn"><i class="fa fa-paste"></i>Add Note</button></a>
                       </div>
                       </div>
                       </div>
                       </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                       <div class="leads_dashboard_top_right_part">
                       <div class="joomla-data-box"><label>Price Range:</label><?=!empty($editRecord[0]['price_range_from'])?'$'.number_format($editRecord[0]['price_range_from']):''?>-<?= !empty($editRecord[0]['price_range_to'])?'$'.number_format($editRecord[0]['price_range_to']):'';?></div>
                       <div class="joomla-data-box"><label>Registered: </label><?=!empty($registered_date_word)?$registered_date_word:'-'?></div>
                       <div class="joomla-data-box"><label>Source: </label><?=!empty($editRecord[0]['joomla_domain_name'] )?$editRecord[0]['joomla_domain_name']:'-'?></div>
                       <!--<div class="joomla-data-box"><label>Realer:</label>100% <a href="#"> web link1</a></div>
                       <div class="joomla-data-box"><label>Lander:</label>100% <a href="#"> web link1</a></div>-->
                       <div class="joomla-data-box"> <label>Last logged in:</label><?=!empty($last_login_words)?$last_login_words:$registered_date_word?></div>
                       <!--<div class="joomla-data-box"><label>Opp Wht</label><a href="#"> included</a> |<a href="#"> included</a> </div>-->
                       </div>
                       </div>
                         </div>
                        <div class="clear"></div><br />
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs" id="myTab1">
                                <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="Summary" onclick="load_view('1');" data-toggle="tab" href="#home">
                                    <?=$this->lang->line('leads_dashboard_summary_head');?>
                                </a> </li>

                                <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Properties" data-toggle="tab" onclick="load_view('2');" href="#properties">
                                    <?=$this->lang->line('leads_dashboard_properties_head');?>
                                </a> </li>
                                <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Searches" data-toggle="tab" onclick="load_view('3');" href="#searches">
                                  <?=$this->lang->line('leads_dashboard_searches_head');?>
                                </a> </li>
                                 <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_edit',$this->modules_unique_name)){?>
                                <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="Edit Profile" data-toggle="tab" onclick="load_view('4');" href="#edit_profile">
                                <?=$this->lang->line('leads_dashboard_edit_profile_head');?>
                                </a> </li>
								 <?php } ?>
                                  
                            </ul>
                            
                            <div class="tab-content" id="myTab1Content">
                                <div <?php if($tabid == '' || $tabid == 1){ ?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="home" > 
                                
                                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                    <div class="summary-todo-box ">
                                    <div class="summary-todo-box-title portlet-header">
                                  <h4>To-Do's <span>for <?=ucwords($editRecord[0]['first_name']." ".$editRecord[0]['last_name'])?></span></h4>
                                    </div>
                                    <div class="portlet-content div_height">
                                    <div class="col-lg-12">
                                    	<div class="row">
										<div class="table-responsive">
                                    		<div class="table-in-responsive">
                                    <table width="100%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                <thead>
                  <tr role="row">
                    <th width="10%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Status</th>
                    <th width="90%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Task Details</th>
                  </tr>
                </thead>
				<?php 
				if(!empty($personale_touches)){				
				 for($i=0;$i<count($personale_touches);$i++) { ?>
                <tr>
                
                <td width="10%" align="center" valign="middle">
				  <?php if($personale_touches[$i]['is_done'] == '0'){	?>
                    <input type="checkbox" id="selectall1_<?php echo $personale_touches[$i]['id']; ?>" value="<?php if(!empty($personale_touches[$i]['is_done']) && $personale_touches[$i]['is_done'] == '1'){ echo '1';}?>" class="selecctall"<?php if(!empty($personale_touches[$i]['is_done']) && $personale_touches[$i]['is_done'] == '1'){ echo "checked=checked";}?> onClick="is_done_p(this.value,<?php echo $personale_touches[$i]['id']; ?>);">
                <?php } ?><br />
                <span class="reload_class_<?php echo $personale_touches[$i]['id']; ?>"> <?php if(!empty($personale_touches[$i]['is_done'])) { ?><!--<label title="Completed" class="btn_done btn_success_done reload_class">Completed</label>--><?php }else {?><!--<label title="Pending" class="btn_danger_pending btn_done">Pending</label>--><?php } ?></span>
                 
						</td>
                
                  <td width="90%">
                    <table width="100%" class="personaltouches mrg-bottom-0" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="20%"><b><?php if(!empty($personale_touches[$i]['followup_date'])){ echo date($this->config->item('common_date_format'),strtotime($personale_touches[$i]['followup_date']));}?></b></td>
                        <td width="80%"><?php if(!empty($personale_touches[$i]['task'])){ echo $personale_touches[$i]['task'];}?></td>
                      </tr>
                      <tr>
                      <!--   <td class="reload_class_<?php echo $personale_touches[$i]['id']; ?>"></td>-->
                      </tr>
                    </table>
                  </td>
                  
                </tr><?php } 
				}
				else {?>
				<tr>
					<td width="10%" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled"><div class="summary-todo-box-con"> <i class="fa fa-warning"></i>You do NOT have any follow-ups setup for Details <a title="Add To Do" href="#basicModal_2"  data-toggle="modal" class="" onclick="addconversation1();">Set To Do</a></div></td>
				</tr>
				
			<?php 	}?>
              </table>
                    </div></div>
                                    </div>
                                    </div>                
                                   
                                    </div>
                                    </div>
                                    <br />
                                    <div class="summary-todo-box ">
                                    <div class="summary-todo-box-title portlet-header">
                                  <h4>History <span><?=ucwords($editRecord[0]['first_name']." ".$editRecord[0]['last_name'])?></span></h4>
                                    </div>
                                    <div class="portlet-content div_height">
                                    
                                   <div class="summary-history-box-con">
                                   
                                    <label><input value="1" name="history_type[]" checked="checked" type="checkbox" >Call</label>
                                    <label><input value="12" name="history_type[]" checked="checked" type="checkbox" >Notes</label>
                                    <?php /*?><label><input value="" name="history_type[]" checked="checked" type="checkbox" >To-Do's</label><?php */?>
                                    <label><input value="99" name="history_type[]" checked="checked" type="checkbox" >Web Activity</label>
                                    <label><input value="6" name="history_type[]" checked="checked" type="checkbox" >Emails</label>
                                    <!--<label><input type="checkbox">e-Alerts</label>-->
     
        
                                   </div>
                                   
                                   <div class="row">
            
                                         <div class="col-lg-12">
                                        <div class="conversations_table append_conversation_data_ajax">
                                         <?php $this->load->view('user/leads_dashboard/history_details');?>
                                            </div>
                                            </div>
                                           
                                            </div>
                                   
                                   <!--<div class="history-mail-box">
                                   <div class="row">
                                   <div class="col-sm-8">
                                   <div class="history-mail-box-icon"><span><i class="fa fa-envelope-o"></i> </span><b>Derek Peluso</b> replied to: <a href="#">more photos</a>
                                   </div>   </div>
                                   <div class="col-sm-4"><div class="date-time-set">2:13PM / Nov 23 </div></div>
                                   </div>
                                   <div class="col-sm-3"><div class="history-img"><img src="../images/theme_pic.jpg" /></div>
                                   </div><div class="col-sm-9 row">Listing <a href="#">140 | link here</a><br />
showing Date: 10:20:14<br>
showing Time: Anytime<br>
Time Frame Showing Request</div>
                                    
                                    <div class="row">
                                     <div class="col-sm-10">
                                    <button class="btn btn-secondary howler smaller_btn1" data-type="danger" title="Reply">Reply</button>
                                    </div>
                                    
                                    <div class="col-sm-2"><div class="more-link"><button title="Reply" data-type="danger" class="btn btn-secondary howler smaller_btn2">More</button></div></div>
                                    </div>
                                   </div>-->
                                   <!--<div class="history-mail-box">
                                   <div class="row">
                                   <div class="col-sm-8">
                                   <div class="history-mail-box-icon"><span><i class="fa fa-envelope-o"></i> </span><b>Derek Peluso</b> replied to: <a href="#">more photos</a>
                                   </div>   </div>
                                   <div class="col-sm-4"><div class="date-time-set">2:13PM / Nov 23 </div></div>
                                   </div>
                                   <p>Lorem Ipsum is simply dummy text of the printing and typesetting indusy.
                                    Lorem Ipsum has been the industry's standard dummy text  ly dummy text ever since.</p>
                                    
                                    <div class="row">
                                     <div class="col-sm-10">
                                    <button class="btn btn-secondary howler smaller_btn1" data-type="danger" title="Reply">Reply</button>
                                    </div>
                                    
                                    <div class="col-sm-2"><div class="more-link"><button title="Reply" data-type="danger" class="btn btn-secondary howler smaller_btn2">More</button></div></div>
                                    </div>
                                   </div>-->
                                   
                                    </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                        <!--
                                    <div class="summary-todo-box ">
                                    <div class="summary-todo-box-title portlet-header">
                                    <h4>Discription <span>Showing 415 Viewed (Show <a href="3"> Favorited</a>)</span></h4>
                                    </div>
                                    <div class="portlet-content div_height">
                                    
                                   <div class="summary-todo-box-con"> <i class="fa fa-warning"></i>You do NOT have any follow-ups setup for Details <a href="#">Select a To-Do</a></div>
                                    </div>
                                    </div>
                                    <br />
                                    -->
                                    <div class="summary-todo-box ">
                                    <div class="summary-todo-box-title portlet-header">
                                    <h4>Recent Website Visits</h4>
                                    </div>
                                    <div class="portlet-content div_height">
                                    
                                   <div class="summary-todo-box-con">
                                       <?php
                                       if(!empty($last_login_data) && count($last_login_data) > 0)
                                       {
                                           foreach($last_login_data as $log_row)
                                           {
                                           ?>
                                                <div class="list-visit"><?php if(!empty($log_row['log_date']) && $log_row['log_date'] != '0000-00-00 00:00:00') echo date('D M d Y H:i A',strtotime($log_row['log_date']))?></div>   
                                            <?php
                                           }
                                       } else { ?>
                                           <div class="list-visit">No Website visits..!</div>
                                       <?php }
                                       ?>
                                   </div>
                                    </div>
                                    </div>
                                    <br />
                                    <? if(in_array('communications',$this->modules_unique_name)){ ?>
                                    <div class="summary-todo-box">
                                    <div class="summary-todo-box-title portlet-header">
                                    <h4><?=$this->lang->line('label_assigned_iplan');?></h4>
                                    </div>
                                    <div class="portlet-content div_height ">
                                    
                                <ul id="myTab10" class="nav nav-tabs">
                                 <li  class="active"> <a href="#assigned_plans" data-toggle="tab" title="Assigned Plans">Assigned Plans </a> </li>

                                <li> <a href="#add_plans" data-toggle="tab" title="Add Plans">
                                   + Add Plans  <i class="icon-remove-sign"></i></a> </li>
                            	</ul>
							
                              
					
							 <?php 
							 	//pr($communication_trans_data);exit;
								 $plan_list_array = array();
								 if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
										foreach($communication_trans_data as $rowtrans){
											$plan_list_array[] = $rowtrans['interaction_plan_id'];
									}}		
									?>
								<div id="myTab10Content" class="tab-content">
							
                           
							<div class="smart-drip-plan-con-box row tab-pane fade in active"  id="assigned_plans">
                            	<div class="col-sm-8"> <strong>Assigned communication plan By Admin</strong>
                        <ul>
										  <?php
                                foreach($admin_interection_plan as $row)
                                {
                                    ?>
                                          <li>
                                            <?=$row['plan_name']?>
                                          </li>
                                          <?	
                                }
                                ?>
                        </ul>
                        <br />
                         <label for="text-input">
                        <strong>
                          <?="Assigned ".$this->lang->line('contact_add_interaction_plan')." By Agent";?>
                          </strong>
                        </label>
							 <?php 
                              if(!empty($communication_plans)){
							foreach($communication_plans as $row){
							 if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ ?>
                            <div class="smart-drip-plan-con">
                            <div class="col-sm-9"><?=$row['plan_name'];?></div>
                               </div>
							<?php }}}
                                                        if(empty($plan_list_array)) { ?>
                                                            <div class="smart-drip-plan-con">
                                                                <div class="col-sm-9"><?php echo 'Plan not assigned..!!';?></div>
                                                            </div>
                                                            
                                                        <?php }
                                                            ?>
							
                         	</div>
							</div>
							<div class="row tab-pane fade in" id="add_plans">
							 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_update_view;?>" novalidate >
							 
                            <select class="form-control parsley-validated" name="slt_communication_plan[]" id="slt_communication_plan" multiple="multiple">
				   <!--<option value="">Please Select</option>-->
				   <?php if(!empty($communication_plans)){
							foreach($communication_plans as $row){?>
								<option <?php if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ echo "selected";} ?> value="<?=$row['id']?>"><?=$row['plan_name']?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
                       <input type="hidden" id="viewtab" name="viewtab" value="1" />
					   <input type="hidden" id="id" name="id" value="<?=$contact_id?>" />
<input type="submit" title="Save Contact" class="btn btn-secondary" value="Assign Communication Plan" onclick="return setdefaultdata();" name="submitbtn" />
							</form>
                            </div>
							

						
                             </div>
                            
                             
                             
                             
                                
                                    </div>
                                    </div>
                                    <? } ?>
                                    <br />
                                    <!--
                                    <div class="e-alerts-box">
                                    <div class="e-alerts-box-title portlet-header">
                                    <h4>e-Alerts<span> <a href="#"> What is an Alerts</a></h4>
                                    </div>
                                    <div class="portlet-content div_height">
                                    
                                 <div class="leads_dashboard_top_right_part">
                                 <h5>Homes in Adams Twp</h5>
                       <div class="joomla-data-box"><label>Alerts Type:</label>Evyer </div>
                       <div class="joomla-data-box"><label>Created By:</label>System on 03-04-2014 modified by Agect</div>
                       <div class="joomla-data-box"><label>Location:</label>Ahmedabad </div>
                       <div class="joomla-data-box"><label>Price Range:</label>$100k - $600k </div>
                       <div class="joomla-data-box"><label>Property Type:</label>Homes </div>
                       <div class="joomla-data-box"> <label>Details:</label>4+Beds / 1+Baths </div>
                       <div class="joomla-data-box"><label>Last Opened:</label>Apr 18, 2014</div>
                       <div class="joomla-data-box"><label>e-Alerts:</label>Never</div>
                       <div class="joomla-data-box"><label>Status:</label>Active (Last Sent on 04-03-2014)</div>
                       <div class="joomla-data-box"><label>Last Opened:</label>417 days ago (Last Opened on 10-09-2013)</div>
                       <div class="e-alerts-link"><a href="#">Edit This Search </a> <a href="#">Unsubsribe </a> <a href="#">Delete </a></div>
                          <div class="e-alerts-bottom-box"><h5>Create an e-Alert</h5>
                          <button title="Reply" data-type="danger" class="btn btn-secondary howler smaller_btn1">System e-Alert</button>
                          <button title="Reply" data-type="danger" class="btn btn-secondary howler smaller_btn1">Blank e-Alert</button>
                          <button title="Reply" data-type="danger" class="btn btn-secondary howler smaller_btn1">Search History</button>
                          </div>
                       
                       </div>
                                    </div>
                                    </div>
                                    -->
                                    
                                    </div>
                                    
                                </div>
                                <div <?php if($tabid == 2){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="properties">
                                    <div id="common_div_pv">  
                                        <?=$this->load->view('user/'.$viewname.'/property_tab')?>
                                    </div>
                                    <?php /*
                                        if(!empty($editRecord[0]['joomla_domain_name']) && $this->config->item('livewire_db_conditions')) {
                                        ?>
                                    <iframe width="100%" frameborder="0" height="500px" name="myiframe" src="<?=!empty($editRecord[0]['joomla_domain_name'])?$editRecord[0]['joomla_domain_name']:''?>/libraries/api/property_view.php?userid=<?=!empty($editRecord[0]['joomla_user_id'])?$editRecord[0]['joomla_user_id']:''?>" ></iframe>
                                    <?php }
                                    else {  Property not found..!!*/?>
                                    <?php //} ?>
                                    <!--
                                    http://topsdemo.in/~seattlenew/explore.livewiresites.com/libraries/api/property_view.php?userid=520
 <iframe width="100%"  frameborder="0" scrolling="no" marginheight="0" marginwidth="0"src="http://topsdemo.in/~seattlenew/explore.livewiresites.com/libraries/api/property_view.php"></iframe>
                                    -->
                    
 <?php
 /*
 <div class="propery-tab-box">  
                               
<h5>History of Visits and Listings Viewed</h5>

<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="property-section-left">
<h5>Key Action</h5>

<div class="key-action-box"><a href="#">View Favorites (2)</a></div>

<div class="key-action-box"><a href="#">Viwed 3+ Times (103)</a></div>

<div class="key-action-box"><a href="#">Viewed 10+ Times (1)</a></div>

<div class="key-action-box"><a href="#">Emailed the Listing (1)</a></div>

<div class="key-action-box"><a href="#">Calculated a Loan (18)</a></div>

<hr />
<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseOne">
         November 2014 (2)
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
      <div class="panel-body">
     <div class="panel-nav-set">
      <ul>   
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseTwo">
         October 2014
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
      <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
         September 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
         july 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
         june 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
         May 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
         April 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
         March 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
        Fabruary 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" 
          href="#collapseThree">
        January 2014
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
<li><a href="#">Wed Nov 26-2014 / 14:08:21 PM (2)</a></li>
</ul>
</div>
      </div>
    </div>
  </div>
  
  <hr />
  
  <h5>Visits in 2013</h5>
  
        <div class="panel-nav-set">
      <ul>
<li><a href="#">Wed Nov 26-2013 / 14:08:21 PM (2)</a></li>

</ul>
</div>
  
  
  
</div></div>
</div>
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">

<div class="property-section-right">
<div class="property-section-right-title">
<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
Property Details
</div>
<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">Key Action</div>
</div>
<div class="pro-detail-main-box">
<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
<div class="pro-details-img"><img src="" width="120px" height="50px" /></div>
<div class="pro-details-box">
<p>

<a href="#">925 Innis St</a><br />

Cranberry Twp-Ven 16301<br />

<a href="#">MLS# 10366813</a><br />

2 Beds | 1 Baths | 0 Halfbaths<br />
</p>
</div>

</div>

<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
<div class="pro-details-price">$ 86,000</div>
<div class="pro-details-price">on Nov 26, 2014</div>
</div>
</div>

<div class="pro-detail-main-box">
<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
<div class="pro-details-img"><img src="" width="120px" height="50px" /></div>
<div class="pro-details-box">
<p>

<a href="#">925 Innis St</a><br />

Cranberry Twp-Ven 16301<br />

<a href="#">MLS# 10366813</a><br />

2 Beds | 1 Baths | 0 Halfbaths<br />
</p>
</div>

</div>

<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
<div class="pro-details-price">$ 86,000</div>
<div class="pro-details-price">on Nov 26, 2014</div>
</div>
</div>

<div class="pro-detail-main-box">
<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
<div class="pro-details-img"><img src="" width="120px" height="50px" /></div>
<div class="pro-details-box">
<p>

<a href="#">925 Innis St</a><br />

Cranberry Twp-Ven 16301<br />

<a href="#">MLS# 10366813</a><br />

2 Beds | 1 Baths | 0 Halfbaths<br />
</p>
</div>

</div>

<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
<div class="pro-details-price">$ 86,000</div>
<div class="pro-details-price">on Nov 26, 2014</div>
</div>
</div>

</div>

</div>
</div>
*/
 ?>
                                </div>
                                <div  <?php if($tabid == 3){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="searches" >
                                   
 <!--view saved searched-->
<div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <div class="table-in-responsive">
                         <div class="row dt-rt">

<?php if(!empty($msg)){?>
                                                <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>                              <div class="col-lg-12 col-sm-12 col-xs-12">
                                  <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                      <label>
                                          <input class="" type="hidden" name="uri_segment1" id="uri_segment1" value="<?=!empty($uri_segment1)?$uri_segment1:'0'?>">
                                          <input class="" type="text" name="searchtext1" id="searchtext1" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext1)?htmlentities($searchtext1):''?>" />
                                          <button class="btn howler" data-type="danger" onclick="contact_search1('changesearch');" title="Search Contacts">Search</button>
                                          <button class="btn howler" data-type="danger" onclick="clearfilter_contact1();" title="View All Contacts">View All</button>
                                      </label>
                                 </div>
                                 <h3><?php echo $this->lang->line('visitor_saved_search')?></h3>
                              </div>          
                          </div>
                          
                         <div class="row dt-rt">
                            <div class="col-sm-12">
                                <?php
                                $sel_contact_id = !empty($selected_contact_id)?$selected_contact_id:'';
                                ?>
                                <a class="btn btn-secondary pull-right btn-success howler" title="Add Saved Searches" href="<?=base_url('user/'.$viewname.'/add_saved_searches/'.$sel_contact_id);?>">Add Saved Searches</a>
                            </div>
                           </div>
                                              <div id="common_div_ss">                 
                                       <?=$this->load->view('user/'.$viewname.'/view_saved_searches')?>
                          </div>
                    </div>
                  </div>
                </div>
            </div>
            


 <!--view valuation searched-->
<div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="table-in-responsive">
                    <!-- table code start-->
                    
                    <div class="row dt-rt">
                    <?php if(!empty($msg)){?>
                                                <div class="col-sm-12 text-center" id="div_msg1"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                                $newdata = array('msg'  => '');
                                                $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
                       <div class="col-lg-12 col-sm-12 col-xs-12">
                       
                           <div class="dataTables_filter" id="DataTables_Table_0_filter">
                               
                               <label>
                               
                                   <input class="" type="hidden" name="uri_segment5" id="uri_segment5" value="<?=!empty($uri_segment5)?$uri_segment5:'0'?>">
                                   <input class="" type="text" name="searchtext5" id="searchtext5" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext5)?htmlentities($searchtext5):''?>" />
                                   <button class="btn howler" data-type="danger" onclick="contact_search5('changesearch');" title="Search Contacts">Search</button>
                                   <button class="btn howler" data-type="danger" onclick="clearfilter_contact5();" title="View All Contacts">View All</button>
                                   
                               </label>
                           </div>
                           <h3><?php echo $this->lang->line('property_valuation_history')?></h3>	
                       </div>          
                   </div>
                   <div id="common_div_vs">
                    <?=$this->load->view('user/'.$viewname.'/view_valuation_searched')?>
                   </div>
                </div>
              </div>
            </div>
        </div>
        
        
                                </div>
                                 <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_edit',$this->modules_unique_name)){?>
                                <div  <?php if($tabid == 4){?> class="row tab-pane fade in active" <?php }else{ ?> class="row tab-pane fade in" <?php } ?> id="edit_profile" >
                                    <!--Edit profile-->
                                
                                <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" data-validate="parsley" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
          
          <?php if(!empty($editRecord[0]['id'])){ ?>
          <div class="col-sm-12 pull-left text-center margin-bottom-10">
            <input type="submit" title="Save Contact" class="btn btn-secondary" value="Save Profile" onclick="return setdefaultdata();" name="submitbtn" />
           <!-- <input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="return setdefaultdata();" name="submitbtn" />-->
            <a class="btn btn-primary fnt-color" title="cancel" href="javascript:history.go(-1);">Cancel</a>
         </div>
         <?php } ?>
          
           <div class="col-sm-12 col-lg-7 new-mar-bot">
            <div class="row">
             <div class="col-sm-12 col-lg-4">
              <label for="text-input"><?=$this->lang->line('contact_add_prefix');?></label>
			  <select class="form-control parsley-validated" name="slt_prefix" id="slt_prefix">
               <option value="">Please Select</option>
               <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Mr.'){ echo "selected"; }?> value="Mr.">Mr.</option>
			   <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Ms.'){ echo "selected"; }?> value="Ms.">Ms.</option>
			   <option <?php if(!empty($editRecord[0]['prefix']) && $editRecord[0]['prefix'] == 'Mrs.'){ echo "selected"; }?> value="Mrs.">Mrs.</option>
              </select>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12  col-lg-4 form-group">
              <label for="text-input"><?=$this->lang->line('contact_add_fname');?><span class="mandatory_field margin-left-5px">*</span></label>
              <input id="txt_first_name" name="txt_first_name" maxlength="50" class="form-control parsley-validated charval" type="text"  value="<?php if(!empty($editRecord[0]['first_name'])){ echo $editRecord[0]['first_name']; }?>" data-required="true" onkeypress="return isCharacterKey(event)" placeholder="e.g. John">
             </div>
			 <div class="col-sm-12  col-lg-4">
              <label for="text-input"><?=$this->lang->line('contact_add_mname');?></label>
              <input id="txt_middle_name" name="txt_middle_name" maxlength="50" class="form-control parsley-validated" onkeypress="return isCharacterKey(event)" type="text" value="<?php if(!empty($editRecord[0]['middle_name'])){ echo $editRecord[0]['middle_name']; }?>" placeholder="e.g. Jane">
             </div>
             <div class="col-sm-12  col-lg-4">
              <label for="text-input"><?=$this->lang->line('contact_add_lname');?></label>
              <input id="txt_last_name" name="txt_last_name" maxlength="50" onkeypress="return isCharacterKey(event)" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['last_name'])){ echo $editRecord[0]['last_name']; }?>" placeholder="e.g. Laren">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12  col-lg-4 form-group">
              <label for="text-input"><?=$this->lang->line('contact_add_spousefname');?><!--<span class="mandatory_field margin-left-5px">*</span>--></label>
              <input id="txt_spousefirst_name" name="txt_spousefirst_name" maxlength="50" class="form-control parsley-validated" type="text" onkeypress="return isCharacterKey(event)" value="<?php if(!empty($editRecord[0]['spousefirst_name'])){ echo $editRecord[0]['spousefirst_name']; }?>" placeholder="e.g. Ruby" >
             </div>
			 <div class="col-sm-12  col-lg-4">
              <label for="text-input"><?=$this->lang->line('contact_add_spouselname');?></label>
              <input id="txt_spouselast_name" name="txt_spouselast_name" maxlength="50" class="form-control parsley-validated" type="text" onkeypress="return isCharacterKey(event)" value="<?php if(!empty($editRecord[0]['spouselast_name'])){ echo $editRecord[0]['spouselast_name']; }?>" placeholder="e.g. Sen">
             </div>
             </div>
            <div class="row">
             <div class="col-sm-12 col-lg-8">
              <label for="text-input"><?=$this->lang->line('contact_add_company');?></label>
              <input id="txt_company_name" name="txt_company_name" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_name'])){ echo $editRecord[0]['company_name']; }?>" placeholder="e.g. Company Name">
             </div>
            </div>
            <div class="row">
             <div class="col-sm-12 col-lg-8">
              <label for="text-input"><?=$this->lang->line('contact_add_title1');?></label>
              <input id="txt_company_post" name="txt_company_post" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['company_post'])){ echo $editRecord[0]['company_post']; }?>" placeholder="e.g. Title">
             </div>
            </div>
            <div class="row form-group">
             <div class="col-sm-12 checkbox">
              <label class="">
             	Is this Contact a Lead
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="chk_is_lead" name="chk_is_lead" <?php if(!empty($editRecord[0]['is_lead']) && $editRecord[0]['is_lead'] == '1'){ echo 'checked="checked"'; }?>>
              </div>
              </label>
             </div>
            </div>
            <div class="add_emailtype">
            <div class="add_email_address_div">
			
			<div class="col-sm-4">
			  <label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>
			 </div>
			 <div class="col-sm-4">
			  <label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>
			 </div>
			 <div class="col-sm-2 text-center icheck-input-new">
			  <div class="">
			   <label><?=$this->lang->line('common_default');?></label>
			  </div>
			 </div>
			 <div class="col-sm-1 text-center icheck-input-new">
			  <div class="">
			   <label>&nbsp;</label>
			  </div>
			 </div>
			
			<?php if(!empty($email_trans_data) && count($email_trans_data) > 0){
			 		foreach($email_trans_data as $rowtrans){ ?>
			 	
				<div class="delete_email_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
					<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
					  <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
					  <input type="hidden" name="email_type_trans_id[]" id="email_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
					  <select class="form-control parsley-validated contact_module" name="slt_email_typee[]" id="slt_email_typee" >
					   <option value="">Please Select</option>
					   <?php if(!empty($email_type)){
								foreach($email_type as $row){?>
									<option <?php if(!empty($rowtrans['email_type']) && $rowtrans['email_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
								<?php } ?>
					   <?php } ?>
					  </select>
					 </div>
					 <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
					  <!--<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>-->
					  <input id="txt_email_addresse" name="txt_email_addresse[]" class="form-control parsley-validated" type="email"  value="<?php if(!empty($rowtrans['email_address'])){ echo $rowtrans['email_address']; }?>" data-parsley-type="email" placeholder="e.g. abc.gmail.com">
					 </div>
					 <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
					  <div class="form-group">
					   <!--<label><?=$this->lang->line('common_default');?></label>-->
					   <div class="radio">
						<label class="">
						<div class="margin-left-48">
						 <input type="radio" class=""  name="rad_email_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> >
						</div>
						</label>
					   </div>
					  </div>
					 </div>
					 <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
					  <div class="">
					   <!--<label>&nbsp;</label>-->
					    <?php if($rowtrans['is_default'] != '1') { ?>
					   <a class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" title="Delete Email" onclick="return ajaxdeletetransdata('delete_email_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
					   <?php } ?>
					  </div>
					 </div>
				</div>
				<?php } ?>	
			 <?php }else{ ?>
			
				 <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
				  <!--<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>-->
				  <select class="form-control parsley-validated contact_module" name="slt_email_type[]" id="slt_email_type" >
				   <option value="">Please Select</option>
				   <?php if(!empty($email_type)){
							foreach($email_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
				 </div>
				 <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
				  <!--<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>-->
				  <input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email" placeholder="e.g. abc.gmail.com">
				 </div>
				 <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
				  <div class="form-group">
				   <!--<label><?=$this->lang->line('common_default');?></label>-->
				   <div class="radio">
					<label class="">
					<div class="margin-left-48">
					 <input type="radio" class=""  name="rad_email_default" checked="checked" >
					</div>
					</label>
				   </div>
				  </div>
				 </div>
				 <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
				  <div class="">
				   <!--<label>&nbsp;</label>-->
				   <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
				  </div>
				 </div>
			 
			 <?php } ?>
             
             </div>
             
             <div class="clear col-sm-12 topnd_margin"> <a href="javascript:void(0);" title="Add Email" class="text_color_red text_size add_email_address"><i class="fa fa-plus-square"></i> Add Email Address</a> </div>
			
            </div>
            
			<!--<div class="row">
             <div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);" title="Add Email" class="text_color_red text_size add_email_address"><i class="fa fa-plus-square"></i> Add Email Address</a> </div>
            </div>-->
			
			<!--Email Complete-->
			
            <div class="add_emailtype">
            <div class="add_phone_number_div">
			
			 <div class="col-sm-4 col-lg-4 my_col_lg_4">
              <label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>
             </div>
             <div class="col-sm-4 col-lg-4 my_col_lg_4">
              <label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>
             </div>
             <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
              <div class="">
               <label><?=$this->lang->line('common_default');?></label>
              </div>
             </div>
             <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
              <div class="">
               <label>&nbsp;</label>
               <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
              </div>
             </div>
			 
			 <?php if(!empty($phone_trans_data) && count($phone_trans_data) > 0){
			 		foreach($phone_trans_data as $rowtrans){ ?>	
					
					<div class="delete_phone_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
						
						<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
						  <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
						  <input type="hidden" name="phone_type_trans_id[]" id="phone_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						  <select class="form-control parsley-validated" name="slt_phone_typee[]" id="slt_phone_type"  >
						   <option value="">Please Select</option>
						   <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
										<option <?php if(!empty($rowtrans['phone_type']) && $rowtrans['phone_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
									<?php } ?>
						   <?php } ?>
						  </select>
						 </div>
						 <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
						  <!--<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>-->
						    <input id="txt_phone_no" name="txt_phone_noe[]"  maxlength="12"  data-maxlength="12" class="form-control parsley-validated mask_apply_class" type="text" value="<?php if(!empty($rowtrans['phone_no'])){ echo $rowtrans['phone_no']; }?>" placeholder="e.g. 123-456-7890">
						 </div>
						 <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
						  <div class="">
						   <!--<label><?=$this->lang->line('common_default');?></label>-->
						   <div class="radio">
							<label class="">
							<div class="margin-left-48">
							 <input type="radio" class=""   name="rad_phone_default" <?php if(!empty($rowtrans['is_default']) && $rowtrans['is_default'] == '1'){ echo 'checked="checked"'; }?> >
							</div>
							</label>
						   </div>
						  </div>
						 </div>
						 <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
						  <div class="">
						   <!--<label>&nbsp;</label>-->
						   <?php if($rowtrans['is_default'] != '1') { ?>
						   <a class="btn btn-xs btn-primary mar_top_con_my"  title="Delete Phone" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_phone_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
						   <?php } ?>
						  </div>
						 </div>
						
					</div>
					
				<?php } ?>
			<?php }else{ ?>
			
             <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
              <!--<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>-->
              <select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type" >
               <option value="">Please Select</option>
               <?php if(!empty($phone_type)){
			   			foreach($phone_type as $row){?>
               				<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
						<?php } ?>
			   <?php } ?>
              </select>
             </div>
             <div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">
              <!--<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>-->
              <input id="txt_phone_no"  maxlength="12"   name="txt_phone_no[]" class="form-control parsley-validated mask_apply_class" type="text"  placeholder="e.g. 123-456-7890">
             </div>
             <div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">
              <div class="form-group">
               <!--<label><?=$this->lang->line('common_default');?></label>-->
               <div class="radio">
                <label class="">
                <div class="margin-left-48">
				
                 <input type="radio" class=""   name="rad_phone_default" checked="checked" >
                </div>
                </label>
               </div>
              </div>
             </div>
             <div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">
              <div class="">
               <!--<label>&nbsp;</label>-->
               <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
              </div>
             </div>
			 
			 <?php } ?>
			 
            </div>
            
            <div class="clear col-sm-12 topnd_margin"> <a href="javascript:void(0);"  title="Add Email" class="text_color_red text_size add_phone_number"><i class="fa fa-plus-square"></i> Add Phone No.</a> </div>
            
            </div>
			
            <!--<div class="row">
			<div class="col-sm-12 topnd_margin"> <a href="javascript:void(0);"  title="Add Email" class="text_color_red text_size add_phone_number"><i class="fa fa-plus-square"></i> Add Phone No.</a> </div>
            </div>-->
            <div class="row add_address_div">
			
			<?php if(!empty($address_trans_data) && count($address_trans_data) > 0){
			 		foreach($address_trans_data as $rowtrans){ ?>	
					
					<div class="delete_address_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
						<div class="col-sm-3 columns">
						  <input type="hidden" name="address_type_trans_id[]" id="address_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						  <select class="form-control parsley-validated" name="slt_address_typee[]" id="slt_address_type">
						   <option value="">Please Select</option>
						   <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
										<option <?php if(!empty($rowtrans['address_type']) && $rowtrans['address_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
									<?php } ?>
						   <?php } ?>
						  </select>
						 </div>
						 <div class="col-sm-6 columns">
						  <div class="row">
						   <textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1e[]" class="form-control parsley-validated"><?php if(!empty($rowtrans['address_line1'])){ echo $rowtrans['address_line1']; }?></textarea>
						  </div>
						  <div class="row">
						   <input type="text" placeholder="Address Line 2" name="txtarea_address_line2e[]" id="txtarea_address_line2" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['address_line2'])){ echo $rowtrans['address_line2']; }?>">
						  </div>
						  <div class="row">
						   <div class="col-sm-5 nopadding">
							<input type="text" placeholder="City" id="txt_city" name="txt_citye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['city'])){ echo $rowtrans['city']; }?>">
						   </div>
						   <div class="col-sm-3 nopadding">
							<input type="text" placeholder="State" id="txt_state" name="txt_statee[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['state'])){ echo $rowtrans['state']; }?>">
						   </div>
						   <div class="col-sm-4 nopadding form-group">
							<input type="text" placeholder="Zip Code" id="txt_zip_code" name="txt_zip_codee[]" maxlength="5" data-minlength="5" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['zip_code'])){ echo $rowtrans['zip_code']; }?>">
						   </div>
						  </div>
						  <div class="row">
						   <input type="text" placeholder="Country" id="txt_country" name="txt_countrye[]" class="form-control parsley-validated" value="<?php if(!empty($rowtrans['country'])){ echo $rowtrans['country']; }?>">
						  </div>
						 </div>
						 <div class="col-sm-2">
						  <a class="btn nomargin btn-xs btn-primary mar_top_con_my" href="javascript:void(0)" onclick="return ajaxdeletetransdata('delete_address_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
						 </div>
						 <div> </div>
					</div>
					
				<?php } ?>
			<?php }else{ ?>
			
             <div class="col-sm-3 columns">
              <select class="form-control parsley-validated" name="slt_address_type[]" id="slt_address_type">
               <option value="">Please Select</option>
               <?php if(!empty($address_type)){
			   			foreach($address_type as $row){?>
               				<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
						<?php } ?>
			   <?php } ?>
              </select>
             </div>
             <div class="col-sm-6 columns new-mar-bot">
              <div class="row">
               <textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1[]" class="form-control parsley-validated"></textarea>
              </div>
              <div class="row">
               <input type="text" placeholder="Address Line 2" name="txtarea_address_line2[]" id="txtarea_address_line2" class="form-control parsley-validated">
              </div>
              <div class="row">
               <div class="col-sm-5 nopadding">
                <input type="text" placeholder="City" id="txt_city" name="txt_city[]" class="form-control parsley-validated">
               </div>
               <div class="col-sm-3 nopadding">
                <input type="text" placeholder="State" id="txt_state" name="txt_state[]" class="form-control parsley-validated">
               </div>
               <div class="col-sm-4 nopadding">
			    <input type="text" placeholder="Zip Code" id="txt_zip_code" name="txt_zip_code[]" maxlength="5" data-minlength="5" class="form-control parsley-validated">
               </div>
              </div>
              <div class="row">
               <input type="text" placeholder="Country" id="txt_country" name="txt_country[]" class="form-control parsley-validated">
              </div>
             </div>
             <div class="col-sm-2">
              <!--<button class="btn nomargin btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
             </div>
             <div> </div>
			 
			 <?php } ?>
			 
            </div>
            <div class="row">
             <div class="col-sm-12 topnd_margin"> <a class="text_color_red text_size add_new_address"  title="Add Address" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Address</a> </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_notes');?></label>
			  <textarea name="txtarea_notes" placeholder="e.g. Notes" id="txtarea_notes" class="form-control parsley-validated"><?php if(!empty($editRecord[0]['notes'])){ echo $editRecord[0]['notes']; }?></textarea>
             </div>
            </div>
           </div>
           
           <div class="col-sm-12 col-lg-5">
           <div class="">
				
				<div class="add_emailtype autooverflow">
					<div class="col-sm-12">
					
						<label for="text-input"><?=$this->lang->line('contact_add_contact_pic');?></label>
					
						<div class="browse"> <span class="text"> </span>
						  <div class="browse_btn">
							<div class="file_input_div">
							  <input type="button" value="Browse" class="file_input_button"  />
							  <input type="file" alt="1" name="contact_pic" id="contact_pic" onchange="showimagepreview(this)" class="file_input_hidden"/>
							</div>
						  </div>
						  <input class="image_upload" type="hidden"  data-bvalidator="extension[jpg:png:jpeg:bmp:gif]" data-bvalidator-msg="Please upload jpg | jpeg | png | bmp | gif file only" name="hiddenFile" id="hiddenFile" value="" />
						</div>
						<p> <span class="txt">&nbsp;</span>
                        	<?php  if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_big_img_path').$editRecord[0]['contact_pic'])){
							?>
						  <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_small')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>"/> <a class="img_delete" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
						  <? } else{
				if(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['contact_pic'])){
				?>
						  <img  width="100" height="100" id="uploadPreview1" src="<?=$this->config->item('contact_upload_img_big')?>/<?=(!empty($editRecord[0]['contact_pic'])?$editRecord[0]['contact_pic']:'');?>" /> <a class="img_delete" onclick="delete_image('contact_pic','uploadPreview1');" href="javascript:void(0);"> <img class="top" title="Remove image" width="17" height="17" src="<?php echo base_url('images/delete_icon.png'); ?>"> </a>
						  <?
				}else{
				?>
						  <img id="uploadPreview1" class="noimage" src="<?=base_url('images/no_image.jpg')?>"  width="100" />
						 <? } } ?>
						
				
						</p>
                        <label> Allowed File Types: jpg,jpeg,png,bmp,gif </label>
					</div>
				</div>
				
			</div>
            <div class="add_website">
             <div>
              <div class="row add_website_div">
			  
			   
			   <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>
               </div>
               <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('contact_add_website');?></label>
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                </div>
               </div>
			   
			   <?php if(!empty($website_trans_data) && count($website_trans_data) > 0){
			 		foreach($website_trans_data as $rowtrans){ ?>
					
						<div class="delete_website_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
							<div class="col-sm-5">
                            
							<input type="hidden" class="form-control" id="txt_website_typeid" name="txt_website_typeid[]" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
							<select class="form-control parsley-validated" name="txt_website_typee[]" id="txt_website_typee">
							   <option value="">Please Select</option>
							   <?php if(!empty($website_type)){
										foreach($website_type as $row){?>
											<option <?php if(!empty($rowtrans['website_type']) && $rowtrans['website_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
										<?php } ?>
							   <?php } ?>
							</select>
                           </div>
						   <div class="col-sm-5 form-group">
							<input type="url" class="form-control parsley-validated" id="txt_website_namee" name="txt_website_namee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }?>" data-parsley-type="url"  placeholder="e.g. www.xyz.com">
						   </div>
						   <div class="col-sm-1 text-center icheck-input-new">
							<div class="">
							 <a title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_website_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
							</div>
						   </div>
						</div>
					
				<?php } ?>
			   <?php } else { ?>
			  
               <div class="col-sm-5">
  				<select class="form-control parsley-validated" name="txt_website_type[]" id="txt_website_type">
				   <option value="">Please Select</option>
				   <?php if(!empty($website_type)){
							foreach($website_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
				</select>
               </div>
               <div class="col-sm-5 form-group">
                <!--<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>-->
                <input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]" data-parsley-type="url" placeholder="e.g. www.xyz.com">
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                 <!--<label>&nbsp;</label>-->
                 <!--<button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
                </div>
               </div>
			   
			   <?php } ?>
			   
              </div>
              <div class="row">
               <div class="col-sm-12 topnd_margin"> <a title="Add Website" class="text_color_red text_size add_new_website" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Website</a> </div>
              </div>
             </div>
             <div>
              <div class="row add_social_profile_div">
			  
			   <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>
               </div>
               <div class="col-sm-5">
                <label for="text-input"><?=$this->lang->line('contact_add_profile_name');?></label>
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                 <!--<label>&nbsp;</label>-->
                </div>
               </div>
			   
			   <?php if(!empty($profile_trans_data) && count($profile_trans_data) > 0){
			 		foreach($profile_trans_data as $rowtrans){ ?>
					
					  <div class="delete_social_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
						<div class="col-sm-5">
							<input type="hidden" class="form-control" id="slt_profile_typeid" name="slt_profile_typeid[]" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
							<select class="form-control parsley-validated" name="slt_profile_typee[]" id="slt_profile_typee">
							   <option value="">Please Select</option>
							   <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
											<option <?php if(!empty($rowtrans['profile_type']) && $rowtrans['profile_type'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucfirst($row['name']);?></option>
										<?php } ?>
							   <?php } ?>
							</select>
						   </div>
						   <div class="col-sm-5 form-group">
							<input type="text" class="form-control parsley-validated fbid" id="txt_social_profilee" name="txt_social_profilee[]" value="<?php if(!empty($rowtrans['website_name'])){ echo $rowtrans['website_name']; }?>" data-parsley-type="url" placeholder="e.g. https://twitter.com/demo">
						   </div>
						   <div class="col-sm-1 text-center icheck-input-new">
							<div class="">
							 <a title="Delete Social Website" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_social_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
							</div>
						   </div>
						</div>
					
				<?php } ?>
			   <?php } else { ?>
			  
               <div class="col-sm-5">
                <!--<label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>-->
				<select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">
				   <option value="">Please Select</option>
				   <?php if(!empty($profile_type)){
							foreach($profile_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
				</select>
               </div>
               <div class="col-sm-5 form-group">
                <!--<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>-->
                <input type="text" class="form-control parsley-validated fbid" id="txt_social_profile" name="txt_social_profile[]" data-parsley-type="url" placeholder="e.g. https://twitter.com/demo" >
               
               </div>
               <div class="col-sm-1 text-center icheck-input-new">
                <div class="">
                 <!--<label>&nbsp;</label>
                 <button class="btn btn-xs btn-primary mar_top_con_my"> <i class="fa fa-times"></i> </button>-->
                </div>
               </div>
			   
			   <?php } ?>
			   
              </div>
              <div class="row">
               <div class="col-sm-12 topnd_margin"> <a title="Add Social Profile" class="text_color_red text_size add_new_social_profile" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Social Profile</a> </div>
              </div>
             </div>
            </div>
            <div class="row">
             <!--<div class="col-sm-12 topnd_margin">
              <h2 class="text_color_red text_size"><?=$this->lang->line('common_label_contact_type');?></h2>
             </div>-->
             <div class="col-sm-8">
              <label for="validateSelect"><?=$this->lang->line('contact_add_source');?></label>
              <select class="form-control parsley-validated" name="slt_contact_source" id="slt_contact_source">
				   <option value="">Please Select</option>
				   <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option <?php if(!empty($editRecord[0]['contact_source']) && $editRecord[0]['contact_source'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label for="validateSelect"><?=$this->lang->line('contact_add_method');?></label>
              <select class="form-control parsley-validated" name="slt_contact_method" id="slt_contact_method">
				   <option value="">Please Select</option>
				   <?php if(!empty($method_type)){
							foreach($method_type as $row){?>
								<option <?php if(!empty($editRecord[0]['contact_method']) && $editRecord[0]['contact_method'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <div class="form-group">
               <div><label class="nomargin"><?=$this->lang->line('common_label_contact_type');?></label></div>
			   
			   <?php
			   		$selectedcontacttypes = array(); 
			   		if(!empty($contact_trans_data)){
							foreach($contact_trans_data as $row){ 
							
								$selectedcontacttypes[] = $row['contact_type_id'];
							
							} ?>
				<?php } ?>
               
			   <?php if(!empty($contact_type)){
							foreach($contact_type as $row){?>

							   <div class="checkbox nopadding margin-left-20">
								<label class="">
								<div class="">
								 <input <?php if(in_array($row['id'],$selectedcontacttypes)){ echo 'checked="checked"';}?> type="checkbox" class="" name="chk_contact_type_id[]" value="<?=$row['id'];?>">
								</div>
								<?=$row['name'];?>
								</label>
							   </div>

               				<?php } ?>
				<?php } ?>
				
              </div>
             </div>
            </div>
            
			<div class="row">
             <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_status');?></label>
              <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status">
				   <option value="">Please Select</option>
				   <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option <?php if(!empty($editRecord[0]['contact_status']) && $editRecord[0]['contact_status'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
             </div>
            </div>
			
			<div class="row add_tag_div">
			
			 <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>
             </div>
             <div class="col-sm-8">
			 <input type="text" name="txt_tag" id="txt_tag" />
             </div>
             <script type="text/javascript">
			 var common = 0;
			$(document).ready(function() {
			
				$("#txt_tag").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: <?=$row['id']?>, name: "<?=$row['tag']?>"},
					<?php } } ?>
					<?php 
					if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
						{id: <?=$rowtrans['id']?>, name: "<?=$rowtrans['tag']?>"},
					<?php } } ?>
				],
				{prePopulate:[
					<?php 
					if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
						{id: <?=$rowtrans['id']?>, name: "<?=$rowtrans['tag']?>"},
					<?php } } ?>
				],onAdd: function (item) {
					common++;
					//alert(common);
				},
				onResult: function (item) {
					try{
						if($.isEmptyObject(item)){
							  return [{id:'NEWTAG-'+common+'{^}'+$("tester").text(),name: $("tester").text()}]
						}else{
							  return item
						}
					}
					catch(e)
					{
							
					}
			
				},
				preventDuplicates: true,
				hintText: "Enter Tag Name",
                noResultsText: "No Tag Found",
                searchingText: "Searching...",
				theme: "facebook"}
				);
			//$("#email_to").attr("placeholder","Enter Contact Name");
		});
		</script>
			 <?php /*if(!empty($tag_trans_data) && count($tag_trans_data) > 0){
			 		foreach($tag_trans_data as $rowtrans){ ?>
					
				   <div class="delete_tag_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
					<div class="col-sm-8">
						<input type="hidden" name="tag_type_trans_id[]" id="tag_type_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
						<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tage[]" value="<?php if(!empty($rowtrans['tag'])){ echo $rowtrans['tag']; }?>">
					</div>
					<div class="col-sm-1 text-center icheck-input-new">
					 <div class="">
					  <a title="Delete Tag" class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" onclick="return ajaxdeletetransdata('delete_tag_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
					 </div>
				   	</div>
				   </div>
					
				<?php  } ?>
			<?php }else{ ?>
			
             <div class="col-sm-8">
              <!--<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>-->
              <input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]" placeholder="e.g. Tag">
             </div>
			 
			 <?php } */ ?>
			 
            </div>
            <div class="row">
            <!-- <div class="col-sm-12 topnd_margin"> <a class="text_color_red text_size add_new_tag" href="javascript:void(0);" title="Add Tags"><i class="fa fa-plus-square"></i> Add Tags</a> </div>-->
            </div>
            <? if(in_array('communications',$this->modules_unique_name)){ ?>
            <div class="row add_communication_plan_div">
			
			 <div class="col-sm-8">
              <label for="text-input"><?=$this->lang->line('contact_add_interaction_plan');?></label>
             </div>
			 
			 <?php 
			 $plan_list_array = array();
			 if(!empty($communication_trans_data) && count($communication_trans_data) > 0){
			 		foreach($communication_trans_data as $rowtrans){
						$plan_list_array[] = $rowtrans['interaction_plan_id'];
						 ?>
					
						<!--<div class="delete_communication_trans_record<?=$rowtrans['id']?> padding-top-10 clear autooverflow">
							<div class="col-sm-8">
							 <input type="hidden" name="communication_trans_id[]" id="communication_trans_id" value="<?php if(!empty($rowtrans['id'])){ echo $rowtrans['id']; }?>">
							 <select class="form-control parsley-validated" name="slt_communication_plan_ide[]" id="slt_communication_plan_ide">
								   <option value="">Please Select</option>
								   <?php if(!empty($communication_plans)){
											foreach($communication_plans as $row){?>
												<option <?php if(!empty($rowtrans['interaction_plan_id']) && $rowtrans['interaction_plan_id'] == $row['id']){ echo "selected"; }?> value="<?=$row['id']?>"><?=$row['plan_name']?></option>
											<?php } ?>
								   <?php } ?>
							  </select>
							 </div>
							 <div class="col-sm-1 text-center icheck-input-new">
								<div class="">
								 <a class="btn btn-xs btn-primary mar_top_con_my" href="javascript:void(0);" title="Add Communication" onclick="return ajaxdeletetransdata('delete_communication_trans_record','<?=$rowtrans['id']?>');"> <i class="fa fa-times"></i> </a>
								</div>
							 </div>
						</div>-->
					
				<?php } ?>
			<?php } 
			//else { ?>
			
             <div class="col-sm-8">
		
              <select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id" multiple="multiple">
				   <!--<option value="">Please Select</option>-->
				   <?php if(!empty($communication_plans)){
							foreach($communication_plans as $row){?>
								<option <?php if(!empty($plan_list_array) && in_array($row['id'],$plan_list_array)){ echo "selected";} ?> value="<?=$row['id']?>"><?=$row['plan_name']?></option>
							<?php } ?>
				   <?php } ?>
			  </select>
              
             </div>
			 
			 <?php // } ?>
			 
            </div>
            <? } ?>
            <!--<div class="row">
             <div class="col-sm-12 topnd_margin"> <a title="Add Communication" class="text_color_red text_size add_new_communication_plan" href="javascript:void(0);"><i class="fa fa-plus-square"></i> Add Communication</a> </div>
			 
            </div>-->
			
			<div class="row">
				<div class="col-sm-8">
					<label class="pull-left margin-top-5px"><?=$this->lang->line('user_assign_msg_agent');?></label>
					
					<select class="form-control parsley-validated" name="slt_user" id="slt_user">
				   	<option value="">Users</option>
				   	<?php if(!empty($user_list)){
							foreach($user_list as $row){
								$email = !empty($row['email_id'])?"(".$row['email_id'].")":'' ?>
								<option value="<?=$row['id']?>" <?php if(!empty($user_add_list[0]['user_id']) && $user_add_list[0]['user_id'] == $row['id']){ echo "selected"; }?>><?=ucwords($row['first_name']." ".$row['middle_name']." ".$row['last_name'].$email);?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
				</div>
			</div>
           </div>
		   
           
          <div class="col-sm-12 pull-left text-center margin-top-10">
<!--<a class="btn btn-secondary" href="javascript:void(0)">Save Contact</a>-->
<input type="hidden" id="fb_id" name="fbid" value="<?php if(!empty($editRecord[0]['fb_id'])){ echo $editRecord[0]['fb_id']; }?>"/>
<input type="hidden" id="viewtab" name="viewtab" value="4" />
<input type="submit" title="Save Contact" class="btn btn-secondary" value="Save Profile" onclick="return setdefaultdata();" name="submitbtn" />
<!--<input type="submit" title="Save and Continue" class="btn btn-secondary" value="Save and Continue" onclick="return setdefaultdata();" name="submitbtn" />-->
 <a class="btn btn-primary fnt-color" title="cancel" href="javascript:history.go(-1);">Cancel</a>
         </div>
          </form>
                                </div>
								<?php } ?>
                            </div>
                        </div>
                        <!-- /.portlet-content --> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #content-header --> 

<!-- /#content-container -->

<!-- Contact Register Popup -->
<div aria-hidden="true" style="display: none;" id="contact_register_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_contact_register_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Contact Register</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 contact_register_popup">
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

<!-- Saved Searches Popup -->
<div aria-hidden="true" style="display: none;" id="saved_searches_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_saved_searches_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Saved Searches</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 saved_searches_popup">
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

<!-- Favorite Popup -->
<div aria-hidden="true" style="display: none;" id="favorite_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_favorite_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Favorite</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 favorite_popup">
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

<!-- Properties Viewed Popup -->
<div aria-hidden="true" style="display: none;" id="properties_viewed_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_properties_viewed_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Properties Viewed</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 properties_viewed_popup">
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

<div aria-hidden="true" style="display: none;" id="joomla_logcall_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_joomla_logcall_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Properties Viewed</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 joomla_logcall_popup">
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

<div aria-hidden="true" style="display: none;" id="joomla_newemail_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_joomla_newemail_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title">Properties Viewed</h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 joomla_newemail_popup">
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

<div aria-hidden="true" style="display: none;" id="valuation_searched_popup" class="modal fade">
    <div class="modal-dialog modal-dialog_lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_valuation_searched_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3 class="modal-title"><?=$this->lang->line('contact_joomla_tab_val_searched')?></h3>
            </div>
            <div class="modal-body">
                <div class="cf"></div>
                <div class="col-sm-12 valuation_searched_popup">
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

<script type="text/javascript">

	$('.mask_apply_class').mask('999-999-9999');

	$('body').on('click','.add_email_address',function(e){
	
		var inlinehtml = '';
		
		
		inlinehtml += '<div class="remove_email_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_email_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated contact_module" name="slt_email_type[]" id="slt_email_type">';
               inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($email_type)){
										foreach($email_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_email_address');?></label>';
              inlinehtml += '<input id="txt_email_address" name="txt_email_address[]" class="form-control parsley-validated" type="email" data-parsley-type="email" placeholder="e.g. abc.gmail.com">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">';
              inlinehtml += '<div class="form-group">';
               //inlinehtml += '<label><?=$this->lang->line('common_default');?></label>';
               inlinehtml += '<div class="radio">';
                inlinehtml += '<label class="">';
                inlinehtml += '<div class="margin-left-48">';
                 inlinehtml += '<input type="radio" class=""  name="rad_email_default">';
                inlinehtml += '</div>';
                inlinehtml += '</label>';
               inlinehtml += '</div>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_email_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
			
		
		/*$('.add_email_address_div').hide();
		$('.add_email_address_div').append(inlinehtml).fadeIn('slow');*/
		
		$('.add_email_address_div').append(inlinehtml);
	
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
		
		
		/*var liData = '<div class="new-rows" style="display:none;"></div>';
		$(liData).appendTo('.add_email_address_div').fadeIn('slow');
	
		jQuery('.new-rows').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_email_div_button',function(e){
	
		var removediv = $(this).closest('.remove_email_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_phone_number',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_phone_div padding-top-10 clear autooverflow">';
             inlinehtml += '<div class="col-sm-4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('common_label_phone_type');?></label>';
              inlinehtml += '<select class="form-control parsley-validated" name="slt_phone_type[]" id="slt_phone_type">';
               inlinehtml += '<option value="">Please Select</option>';
						   <?php if(!empty($phone_type)){
									foreach($phone_type as $row){?>
										inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
									<?php } ?>
						   <?php } ?>
              inlinehtml += '</select>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-4 col-lg-4 my_col_lg_4 form-group">';
              //inlinehtml += '<label for="validateSelect"><?=$this->lang->line('contact_add_phone_no');?></label>';
              inlinehtml += '<input id="txt_phone_no" maxlength="12" name="txt_phone_no[]" class="form-control parsley-validated mask_apply_class" type="text" placeholder="e.g. 123-456-7890">';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-2 col-lg-2 my_col_lg_2 text-center icheck-input-new">';
              inlinehtml += '<div class="form-group">';
               //inlinehtml += '<label><?=$this->lang->line('common_default');?></label>';
               inlinehtml += '<div class="radio">';
                inlinehtml += '<label class="">';
                inlinehtml += '<div class="margin-left-48">';
                 inlinehtml += '<input type="radio"  class="" name="rad_phone_default">';
                inlinehtml += '</div>';
                inlinehtml += '</label>';
               inlinehtml += '</div>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
             inlinehtml += '<div class="col-sm-1 col-lg-1 my_col_lg_1 text-center icheck-input-new">';
              inlinehtml += '<div class="">';
               //inlinehtml += '<label>&nbsp;</label>';
               inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_phone_div_button"> <i class="fa fa-times"></i> </button>';
              inlinehtml += '</div>';
             inlinehtml += '</div>';
            inlinehtml += '</div>';
		
		$('.add_phone_number_div').append(inlinehtml);
		$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
		$('.mask_apply_class').mask('999-999-9999');
		
		
		/*var liData = '<div class="new-rows1" style="display:none;"></div>';
		$(liData).appendTo('.add_phone_number_div').fadeIn('slow');
	
		jQuery('.new-rows1').html(inlinehtml, 500);*/
	});
	
	$('body').on('click','.delete_phone_div_button',function(e){
		
		var removediv = $(this).closest('.remove_phone_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_website',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_website_div padding-top-10 clear autooverflow">';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('common_label_website_type');?></label>';
			//inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_website_type" name="txt_website_type[]">';
		   	 inlinehtml += '<select class="form-control parsley-validated" name="txt_website_type[]" id="txt_website_type">';
			 inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($website_type)){
										foreach($website_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
			inlinehtml += '</select>';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5 form-group">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>';
			inlinehtml += '<input type="url" class="form-control parsley-validated" id="txt_website_name" name="txt_website_name[]"  data-parsley-type="url" placeholder="e.g. www.xyz.com">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Website" class="btn btn-xs btn-primary mar_top_con_my delete_website_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_website_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows2" style="display:none;"></div>';
		$(liData).appendTo('.add_website_div').fadeIn('slow');
	
		jQuery('.new-rows2').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_website_div_button',function(e){
		
		var removediv = $(this).closest('.remove_website_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_social_profile',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_social_profile_div padding-top-10 clear autooverflow">';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_profile_type');?></label>';
			inlinehtml += '<select class="form-control parsley-validated" name="slt_profile_type[]" id="slt_profile_type">';
			   inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($profile_type)){
										foreach($profile_type as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
										<?php } ?>
							   <?php } ?>
			inlinehtml += '</select>';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-5">';
			//inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_website');?></label>';
			inlinehtml += '<input type="text" class="form-control parsley-validated fbid" id="txt_social_profile" name="txt_social_profile[]" data-parsley-type="url" placeholder="e.g. https://twitter.com">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Social WebSite" class="btn btn-xs btn-primary mar_top_con_my delete_social_profile_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  
		$('.add_social_profile_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows3" style="display:none;"></div>';
		$(liData).appendTo('.add_social_profile_div').fadeIn('slow');
	
		jQuery('.new-rows3').html(inlinehtml, 500);*/
	});
	
	$('body').on('click','.delete_social_profile_div_button',function(e){
		
		var removediv = $(this).closest('.remove_social_profile_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_tag',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_tag_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-8">';
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_tag');?></label>';
		  inlinehtml += '<input type="text" class="form-control parsley-validated" id="txt_tag" name="txt_tag[]" placeholder="e.g. Tag">';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button title="Delete Tag" class="btn btn-xs btn-primary mar_top_con_my delete_tag_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_tag_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows4" style="display:none;"></div>';
		$(liData).appendTo('.add_tag_div').fadeIn('slow');
	
		jQuery('.new-rows4').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_tag_div_button',function(e){
		
		var removediv = $(this).closest('.remove_tag_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_address',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_address_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-3 columns">';
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_address_type[]" id="slt_address_type">';
		   inlinehtml += '<option value="">Please Select</option>';
						   <?php if(!empty($address_type)){
									foreach($address_type as $row){?>
										inlinehtml += '<option value="<?=$row['id']?>"><?=$row['name']?></option>';
									<?php } ?>
						   <?php } ?>
		  inlinehtml += '</select>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-6 columns">';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<textarea placeholder="Address Line 1" id="txtarea_address_line1" name="txtarea_address_line1[]" class="form-control parsley-validated"></textarea>';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<input type="text" placeholder="Address Line 2" name="txtarea_address_line2[]" id="txtarea_address_line2" class="form-control parsley-validated">';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<div class="col-sm-5 nopadding">';
			inlinehtml += '<input type="text" placeholder="City" id="txt_city" name="txt_city[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-3 nopadding">';
			inlinehtml += '<input type="text" placeholder="State" id="txt_state" name="txt_state[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		   inlinehtml += '<div class="col-sm-4 nopadding">';
			inlinehtml += '<input type="text" placeholder="Zip Code" id="txt_zip_code" maxlength="5" data-minlength="5"  name="txt_zip_code[]" class="form-control parsley-validated">';
		   inlinehtml += '</div>';
		  inlinehtml += '</div>';
		  inlinehtml += '<div class="row">';
		   inlinehtml += '<input type="text" placeholder="Country" id="txt_country" name="txt_country[]" class="form-control parsley-validated">';
		  inlinehtml += '</div>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-2">';
		  inlinehtml += '<button class="btn nomargin btn-xs btn-primary mar_top_con_my delete_address_div_button"> <i class="fa fa-times"></i> </button>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div> </div>';
		inlinehtml += '</div>';
		
		$('.add_address_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows5" style="display:none;"></div>';
		$(liData).appendTo('.add_address_div').fadeIn('slow');
	
		jQuery('.new-rows5').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_address_div_button',function(e){
		
		var removediv = $(this).closest('.remove_address_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	$('body').on('click','.add_new_communication_plan',function(e){
	
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_communication_plan_div padding-top-10 clear autooverflow">';
		 inlinehtml += '<div class="col-sm-8">';
		  //inlinehtml += '<label for="text-input"><?=$this->lang->line('contact_add_communication_plan');?></label>';
		  inlinehtml += '<select class="form-control parsley-validated" name="slt_communication_plan_id[]" id="slt_communication_plan_id">';
			   inlinehtml += '<option value="">Please Select</option>';
							   <?php if(!empty($communication_plans)){
										foreach($communication_plans as $row){?>
											inlinehtml += '<option value="<?=$row['id']?>"><?=$row['description']?></option>';
										<?php } ?>
							   <?php } ?>
		  inlinehtml += '</select>';
		 inlinehtml += '</div>';
		 inlinehtml += '<div class="col-sm-1 text-center icheck-input-new">';
			inlinehtml += '<div class="">';
			 //inlinehtml += '<label>&nbsp;</label>';
			 inlinehtml += '<button class="btn btn-xs btn-primary mar_top_con_my delete_communication_plan_div_button"> <i class="fa fa-times"></i> </button>';
			inlinehtml += '</div>';
		   inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.add_communication_plan_div').append(inlinehtml);
		
		/*var liData = '<div class="new-rows6" style="display:none;"></div>';
		$(liData).appendTo('.add_communication_plan_div').fadeIn('slow');
	
		jQuery('.new-rows6').html(inlinehtml, 500);*/
		
	});
	
	$('body').on('click','.delete_communication_plan_div_button',function(e){
		
		var removediv = $(this).closest('.remove_communication_plan_div');
	
		 $.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
										 removediv.remove();			
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
		
	});
	
	function ajaxdeletetransdata(functionname,id)
	{	
		var id1 = $('#id').val();
		$.confirm({
					'title': 'CONFIRM','message': " <strong> Are you sure want to delete <strong>?</strong>",
					'buttons': {
						'Yes': {'class': '',	
								'action': function(){
								
										$.ajax({
											type: "post",
											url: '<?php echo $this->config->item('user_base_url')?><?=$viewname;?>/'+functionname+'/'+id,
											data: {'id1':id1}, 
											success: function(msg1) 
											{
												$('.'+functionname+id).remove();
												
											}
										});	
								
									}},
					 	'No'	: {'class'	: 'special'}
					 }
				});
		
		return false;
	}
	
	function setdefaultdata()
	{
		//alert('hiii');
		var returndata = 0;
		
		// get all the inputs into an array.
		var $inputs = $('.add_email_address_div :input[type=email]');
		var $inputs1 = $('.add_phone_number_div :input[type=text]');
	
		// not sure if you wanted this, but I thought I'd add it.
		// get an associative array of just the values.
		var unique_values = {};
		$inputs.each(function() {
		
			if ( ! unique_values[this.value] ) {
				unique_values[this.value] = true;
			} else {
				// We have duplicate values!
					$.confirm({'title': 'Alert','message': " <strong> Same email id used multiple times. Please insert different email ids. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same email id used multiple times. Please insert different email ids.');
				returndata = 1;
			}
			
		});
		
		var unique_values1 = {};
		$inputs1.each(function() {
		
			if ( ! unique_values1[this.value] ) {
				unique_values1[this.value] = true;
			} else {
				// We have duplicate values!
				$.confirm({'title': 'Alert','message': " <strong> Same phone no used multiple times. Please insert different phone no. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Same phone no used multiple times. Please insert different phone no.');
				returndata = 1;
			}
			
		});
		
		if(returndata == 1)
			return false;
		
		emailchkval = $('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').closest("div.my_col_lg_2").siblings('div.my_col_lg_4').find('input[type=email]').val();
		$('input[name=rad_email_default]:checked', '#<?php echo $viewname;?>').val(emailchkval);
		
		phonechkval = $('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').closest("div.my_col_lg_2").siblings('div.my_col_lg_4').find('input[type=text]').val();
		$('input[name=rad_phone_default]:checked', '#<?php echo $viewname;?>').val(phonechkval);
		
	}
	
</script>
<script type="text/javascript">
    $(document).ready(function(){
		<?php 
		if(!empty($msg1))
		{
			?>
				$.confirm({'title': 'Alert','message': " <strong> <?=$msg1?></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			<?	
			$newdata = array('msg'  => '');
           $this->session->set_userdata('message_session1', $newdata);
		}
		
		?>
		
	 $("#div_msg").fadeOut(4000); 
         load_view(<?=$tabid?>);
    });
    function load_view(id)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('user_base_url').$viewname.'/selectedview_session';?>",
            data: {selected_view:id},
            success: function(html){
                if(id == '3')
                {
                    contact_search1('');
                    //$("#searches").show();
                    $("#div_msg1").fadeOut(4000);
                }    
                else {
                    //$("#home").show();
                    $("#div_msg").fadeOut(4000); 
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    //$(".view_contact_popup").html('Something went wrong.');
            }
        });
        
    }

/*Contact Register*/

$('body').on('click','.contact_register_popup_btn',function(e){
    $(".contact_register_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/contact_register_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".contact_register_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".contact_register_popup").html('Something went wrong.');
        }
    });
});
function contact_search(allflag)
{
    var uri_segment = $("#uri_segment").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>user/contacts/view_record_index/"+id,
            data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext_cr").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,id:id
    },
    beforeSend: function() {
                            $('#common_div').block({ message: 'Loading...' }); 
                      },
            success: function(html){
                    $("#common_div").html(html);
                    //$('#common_div').unblock(); 
            }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext_cr').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search('changesearch');
        }
    });
});

function clearfilter_contact()
{
    $("#searchtext_cr").val("");
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
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
            $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext_cr").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),id:id
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

/*Saved Searches*/

$('body').on('click','.saved_searches_popup_btn',function(e){
    $(".saved_searches_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_record_index_savser';?>",
        data: {'search_id':search_id,'result_type':'ajax'},
        success: function(html){
                $(".saved_searches_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".saved_searches_popup").html('Something went wrong.');
        }
    });
});

function contact_search1(allflag)
{
    var uri_segment = $("#uri_segment1").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/contacts/view_record_index_savser/"+id,
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_ss').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_ss").html(html);
            //$('#common_div_ss').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext1').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search1('changesearch');
        }
    });
});

function clearfilter_contact1()
{
    $("#searchtext1").val("");
    contact_search1('all');
}

function changepages1()
{
    contact_search1('');	
}

function applysortfilte_contact1(sortfilter,sorttype)
{
    $("#sortfield1").val(sortfilter);
    $("#sortby1").val(sorttype);
    contact_search1('changesorting');
}

$('body').on('click','#common_tb1 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_ss").html(html);
            $.unblockUI();
        }
    });
    return false;
});

/* Favorite */

$('body').on('click','.favorite_popup_btn',function(e){
    $(".favorite_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/favorite_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".favorite_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".favorite_popup").html('Something went wrong.');
        }
    });
});

function contact_search2(allflag)
{
    var uri_segment = $("#uri_segment2").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/contacts/view_record_index_fav/"+id,
        data: {
        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage2").val(),searchtext:$("#searchtext2").val(),sortfield:$("#sortfield2").val(),sortby:$("#sortby2").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_fav').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_fav").html(html);
            //$('#common_div_fav').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext2').keyup(function(event)
    {
        if (event.keyCode == 13) {
            contact_search2('changesearch');
        }
    });
});

function clearfilter_contact2()
{
    $("#searchtext2").val("");
    contact_search2('all');
}

function changepages2()
{
    contact_search2('');	
}

function applysortfilte_contact2(sortfilter,sorttype)
{
    $("#sortfield2").val(sortfilter);
    $("#sortby2").val(sorttype);
    contact_search2('changesorting');
}

$('body').on('click','#common_tb2 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage2").val(),searchtext:$("#searchtext2").val(),sortfield:$("#sortfield2").val(),sortby:$("#sortby2").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_fav").html(html);
            $.unblockUI();
        }
    });
    return false;
});

/* Property Viewed */

$('body').on('click','.properties_viewed_popup_btn',function(e){
    $(".properties_viewed_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/properties_viewed_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
                $(".properties_viewed_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                $(".properties_viewed_popup").html('Something went wrong.');
        }
    });
});

function contact_search3(allflag)
{
    var uri_segment = $("#uri_segment3").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/leads_dashboard/view_record_index_prop_view/"+id,
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage3").val(),searchtext:$("#searchtext3").val(),sortfield:$("#sortfield3").val(),sortby:$("#sortby3").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_pv').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_pv").html(html);
            //$('#common_div_pv').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext3').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search3('changesearch');
        }
    });
});

function clearfilter_contact3()
{
    $("#searchtext3").val("");
    contact_search3('all');
}

function changepages3()
{
    contact_search3('');	
}

function applysortfilte_contact3(sortfilter,sorttype)
{
    $("#sortfield3").val(sortfilter);
    $("#sortby3").val(sorttype);
    contact_search3('changesorting');
}

$('body').on('click','#common_tb3 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage3").val(),searchtext:$("#searchtext3").val(),sortfield:$("#sortfield3").val(),sortby:$("#sortby3").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_pv").html(html);
            $.unblockUI();
        }
    });
    return false;
});

/*Last Login*/

$('body').on('click','.last_login_popup_btn',function(e){
    $(".last_login_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/last_login_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
            $(".last_login_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
            //console.log(textStatus, errorThrown);
            $(".last_login_popup").html('Something went wrong.');
        }
    });
});

function contact_search4(allflag,per_page)
{
    var uri_segment = $("#uri_segment4").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/contacts/view_record_index_lastlog/"+id,
        data: {
        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:per_page,searchtext:$("#searchtext4").val(),sortfield:$("#sortfield4").val(),sortby:$("#sortby4").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_ll').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_ll").html(html);
            //$('#common_div_ll').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext4').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search4('changesearch');
        }
    });
});

function clearfilter_contact4()
{
    $("#searchtext4").val("");
    contact_search4('all');
}

function changepages4()
{
    contact_search4('');	
}

function applysortfilte_contact4(sortfilter,sorttype)
{
    $("#sortfield4").val(sortfilter);
    $("#sortby4").val(sorttype);
    contact_search4('changesorting');
}

$('body').on('click','#common_tb4 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage4").val(),searchtext:$("#searchtext4").val(),sortfield:$("#sortfield4").val(),sortby:$("#sortby4").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_ll").html(html);
            $.unblockUI();
        }
    });
    return false;
});
  
</script> 
<script type="text/javascript">
$("select#slt_communication_plan_id").multiselect({
		header: "Select Communication",
		noneSelectedText: "Select Communication",
		selectedList: 1
	}).multiselectfilter();
$("select#slt_communication_plan").multiselect({
		header: "Select Communication",
		noneSelectedText: "Select Communication",
		selectedList: 1
	}).multiselectfilter();	
</script>

<script type="text/javascript">
function showimagepreview(input) 
{
	
	var maximum = input.files[0].size/1024;
	if (input.files && input.files[0] && maximum <= 2048) 
	{
		var arr1 = input.files[0]['name'].split('.');
		var arr= arr1[1].toLowerCase();	
		if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'bmp' || arr == 'gif')
		{
			var filerdr = new FileReader();
			filerdr.onload = function(e) {
			$('#uploadPreview1').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please upload jpg | jpeg | png | bmp | gif file only "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
		}	
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Maximum upload size 2 MB "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			return false;
	}
}
	function delete_image(name,divid)
	{
		$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure want to delete image?",'buttons': {'Yes': {'class': '',
'action': function(){
			//loading('Checking');
				 //$('#preloader').html('Deleting...');
		var id=$('#id').val();
		 $.ajax({
			type: 'post',
			data:{id:id,name:name},
			url: '<?=$this->config->item('user_base_url').$viewname."/delete_image";?>',
			success:function(msg){
					if(msg == 'done')
					{
					$('.img_delete').hide();
			      	$('#'+divid).attr('src','<?=base_url('images/no_image.jpg')?>');
				  }
				}//succsess
			});//ajax
			
			}},'No'	: {'class'	: 'special'}}});
	}
	
</script>
<script type="text/javascript">
function applysortfilte_contact(sortfilter,sorttype)
{
    $("#sortfield").val(sortfilter);
    $("#sortby").val(sorttype);
    contact_search('changesorting');
}

function contact_search1(allflag)
{
	var uri_segment = $("#uri_segment1").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/leads_dashboard/view_record_index_savser/"+id,
        data: {
            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage1").val(),searchtext:$("#searchtext1").val(),sortfield:$("#sortfield1").val(),sortby:$("#sortby1").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_ss').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_ss").html(html);
        }
    });
    return false;
}

</script>

<script type="text/javascript">
$('body').on('click','.valuation_searched_popup_btn',function(e){
    $(".valuation_searched_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
    var search_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/valuation_searched_popup';?>",
        data: {'search_id':search_id},
        success: function(html){
            $(".valuation_searched_popup").html(html);	
        },
        error: function(jqXHR, textStatus, errorThrown) {
            //console.log(textStatus, errorThrown);
            $(".valuation_searched_popup").html('Something went wrong.');
        }
    });
});

function contact_search5(allflag)
{
    var uri_segment = $("#uri_segment5").val();
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>user/leads_dashboard/view_record_index_valuation_searched/"+id,
        data: {
        result_type:'ajax',perpage:$("#perpage5").val(),searchtext:$("#searchtext5").val(),sortfield:$("#sortfield5").val(),sortby:$("#sortby5").val(),allflag:allflag,id:id
        },
        beforeSend: function() {
            $('#common_div_vs').block({ message: 'Loading...' }); 
        },
        success: function(html){
            $("#common_div_vs").html(html);
            //$('#common_div_ll').unblock(); 
        }
    });
    return false;
}

$(document).ready(function(){
    $('#searchtext5').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search5('changesearch');
        }
    });
});

function clearfilter_contact5()
{
    $("#searchtext5").val("");
    contact_search5('all');
}

function changepages5()
{
    contact_search5('');	
}

function applysortfilte_contact5(sortfilter,sorttype)
{
    $("#sortfield5").val(sortfilter);
    $("#sortby5").val(sorttype);
    contact_search5('changesorting');
}

$('body').on('click','#common_tb5 a.paginclass_A',function(e){
    var id = '<?php echo $this->router->uri->segments[4]; ?>';
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax',searchreport:$("#searchreport5").val(),perpage:$("#perpage5").val(),searchtext:$("#searchtext5").val(),sortfield:$("#sortfield5").val(),sortby:$("#sortby5").val(),id:id
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#common_div_vs").html(html);
            $.unblockUI();
        }
    });
    return false;
});

</script>
<script type="text/javascript">
function add_email_campaign(id,email_trans_id){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>emails/add_record/'+id+'/'+email_trans_id;
	$('.popup_heading_h3').html('Email');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".email_sms_send_popup .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
function addconversation()
{				
	document.forms['<?php echo $viewname;?>_call_log'].elements['sl_interaction_type'].value=4;
	document.forms['<?php echo $viewname;?>_call_log'].elements['disposition_type'].value=2;
}
function addconversation1()
{				
	document.forms['<?php echo $viewname;?>_call_log'].elements['sl_interaction_type'].value=7;
}
$(function() {
	$( "#followup_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
function is_done_p(id,is_done_hidd)
{	

	if($('#selectall1_'+is_done_hidd).prop('checked') == true)
		var id = 1;
	else
		var id = 0;
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('user_base_url').'contacts/personal_id_done';?>",
		dataType: 'json',
		async: false,
		data: {'id':id,'is_done_hidd':is_done_hidd},
		success: function(data){
				
				window.location.reload();

		
				}
	});
}
function history_search()
{
	var myarray = new Array;
	var i=0;
	var boxes = $('input[name="history_type[]"]:checked');
	$(boxes).each(function(){
		if(this.value == 99)
		{
			myarray[i]=2;
			i++;
			myarray[i]=3;
			i++;
			myarray[i]=4;
			i++;
			myarray[i]=5;
			i++;
			myarray[i]=7;
			i++;
			myarray[i]=8;
			i++;
			myarray[i]=9;
			i++;
			myarray[i]=10;
			i++;
			myarray[i]=11;
			i++;
		}
		else
		{
		  myarray[i]=this.value;
		  i++;
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo base_url();?>user/contacts/change_conversations",
		data: {
		result_type:'ajax','contact_id':'<?=$contact_id?>','history_type':myarray
	},
	beforeSend: function() {
				$('.append_conversation_data_ajax').block({ message: 'Loading...' }); 
			  },
		success: function(html){
			$(".append_conversation_data_ajax").html(html);
			$('.append_conversation_data_ajax').unblock(); 
		}
	});
	return false;
}
$('.summary-history-box-con').on('click','input[name=history_type[]]',function(e){
	//alert('');
	history_search();
});
</script>