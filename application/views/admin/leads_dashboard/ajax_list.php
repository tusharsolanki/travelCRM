<?php 
    /*
    @Description: Joomla Dashboard
    @Author     : Sanjay Moghariya
    @Date       : 14-11-2014

*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
           <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
            <th width="18%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'joomla_contact_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('joomla_contact_type','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_lead_type')?></a></th>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_name')?><br /><?=$this->lang->line('leads_dashboard_location')?><br /><?=$this->lang->line('leads_dashboard_phone')?></a></th>
            <th width="9%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'joomla_category'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('joomla_category','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_category')?></a></th>
            <th width="7%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'joomla_timeframe'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('joomla_timeframe','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_timeframe')?></a></th>
            <th width="6%" data-direction="desc" data-sortable="true" data-filterable="true"  role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><?=$this->lang->line('common_label_action')?></th>
            <? if(in_array('communications',$this->modules_unique_name)){ ?>
            <th width="6%" data-direction="desc" data-sortable="true" data-filterable="true"  role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><?php echo $this->lang->line('leads_dashboard_comm_plan'); ?></th>
            <? } ?>
            
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" role="columnheader" <?php if(isset($sortfield) && ($sortfield == 'total_calls_made' || $sortfield == 'emails_sent_count')){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending">
                <a href="javascript:void(0);" onclick="applysortfilte_contact('total_calls_made','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_calls_made')?></a>
                <a href="javascript:void(0);" onclick="applysortfilte_contact('emails_sent_count','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_email_sent')?></a>
            </th>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" role="columnheader" <?php if(isset($sortfield) && $sortfield == 'total_contactform'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('total_contactform','<?php echo $sorttypepass;?>')"><?=$this->lang->line('joomla_property_contact_form')?></a></th>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" role="columnheader" <?php if(isset($sortfield) && $sortfield == 'created_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_registration')?></a></th>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" role="columnheader" <?php if(isset($sortfield) && $sortfield == 'log_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('log_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_last_visit')?></a></th>
            <th width="10%" data-direction="desc" data-sortable="true" <?php if(isset($sortfield) && ($sortfield == 'total_favorites' || $sortfield == 'total_properties_viewed' || $sortfield == 'total_saved_searches' /*$sortfield == 'total_properties_viewed'*/)){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> data-filterable="true" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending">
                    <a href="javascript:void(0);" onclick="applysortfilte_contact('total_properties_viewed','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_visits')?></a><br />
                    <a href="javascript:void(0);" onclick="applysortfilte_contact('total_favorites','<?php echo $sorttypepass;?>')">Favs</a><br />
                    <a href="javascript:void(0);" onclick="applysortfilte_contact('total_saved_searches','<?php echo $sorttypepass;?>')">Saved</a>
                    <?php /*<a href="javascript:void(0);" onclick="applysortfilte_contact('total_visits','<?php echo $sorttypepass;?>')">Props</a> */?>
            </th>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'price_range_from'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('price_range_from','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_price_range')?></a></th>
            <?php /*<th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'joomla_domain_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('joomla_domain_name','<?php echo $sorttypepass;?>')" ><?=$this->lang->line('leads_dashboard_registered_source')?></a></th>*/ ?>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'um.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('um.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_agent_name')?></a></th>
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'uml.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('uml.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('leads_dashboard_lender_name')?></a></th>
             <? if(in_array('lead_dashboard_edit',$this->modules_unique_name) || in_array('lead_dashboard_delete',$this->modules_unique_name)){ ?>
             <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
             <? } ?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){ //pr($row);
                            if(!empty($current_date))
                            {
                                if(date('Y-m-d H:i:s',strtotime($row['created_date1'])) > date('Y-m-d H:i:s',strtotime($current_date)))
                                { ?>
                                    <tr class="bgtitle new_bold_class"> 
                                <?php } else {  ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                <?php 
                                }
                            } else { ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                            <?php } ?>
                        
                                     <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_delete',$this->modules_unique_name)){?>
                                    <td class="">
                                        <div class="text-center">
                                              <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
                                        </div>
                                    </td>
                                    <? } ?>
                                        <td class="hidden-xs hidden-sm ">
                                            <div class="radio">
						<label class="">
                                                    <div <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Buyer'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                                        <input type="radio" name="joomla_contact_type_<?=$row['id']?>" id="joomla_contact_type" onclick="change_contact_type('Buyer',<?=$row['id']?>);" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Buyer'){ echo 'checked="checked"'; }?> >Buyer
                                                    </div>
						</label>
					   </div>
                                            <div class="radio">
						<label class="">
                                                    <div <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Seller'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                                        <input type="radio" name="joomla_contact_type_<?=$row['id']?>" id="joomla_contact_type" onclick="change_contact_type('Seller',<?=$row['id']?>);" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Seller'){ echo 'checked="checked"'; }?> >Seller
                                                    </div>
						</label>
					   </div>
                                            <div class="radio">
						<label class="">
                                                    <div <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Buyer/Seller'){ echo 'class="fnt_bold"'; } else { echo 'class="fnt_normal"';}?>>
                                                        <input type="radio" name="joomla_contact_type_<?=$row['id']?>" id="joomla_contact_type" onclick="change_contact_type('Buyer/Seller',<?=$row['id']?>);" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Buyer/Seller'){ echo 'checked="checked"'; }?> >Buyer/Seller
                                                    </div>
						</label>
					   </div>
                                            <?php /*
                                            <select name="joomla_contact_type" id="joomla_contact_type" onchange="change_contact_type(this.value,<?=$row['id']?>);" class="dboard_ctype form-control">
                                                <option value="Buyer" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Buyer') { echo "selected='selected'"; }?>>Buyer</option>
                                                <option value="Seller" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Seller') { echo "selected='selected'"; }?>>Seller</option>
                                                <option value="Buyer/Seller" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_contact_type'] == 'Buyer/Seller') { echo "selected='selected'"; }?>>Buyer/Seller</option>
                                            </select>
                                             */?>
                                            <?php //!empty($row['joomla_contact_type'])?$row['joomla_contact_type']:'';?>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <a title="View Contact" href="<?= $this->config->item('admin_base_url')?>leads_dashboard/view_record/<?= $row['id'] ?>" class="textdecoration1 text_color_red"><?=!empty($row['contact_name'])?ucwords($row['contact_name']):'';?></a><br />

                                            <?= !empty($row['joomla_address'])?ucwords($row['joomla_address']):'-'; //!empty($row['joomla_ip_address'])?ucwords($row['joomla_ip_address']):'-';?><br />                                            
                                            <?php if(!empty($row['phone_no'])){ ?>
                                                <a title="Log Call" href="javascript:void(0);" class="" onclick="show_action('a_conversation_id',<?=$row['id']?>);" >
                                                    <?php
                                                        //!empty($row['phone_no'])?$row['phone_no']:'-';
                                                        echo preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $row['phone_no']);
                                                    ?> 
                                                </a>
                                                <?php }else{ echo '-';} ?>
                                        </td>
                                        <td class="hidden-xs hidden-sm dboard-selection">
                                            <?php
                                            $sel_class = '';
                                            if($row['joomla_category'] == 'New')
                                                $sel_class = 'new-color1';
                                            else if($row['joomla_category'] == 'Qualify')
                                                $sel_class = 'quality-color1';
                                            else if($row['joomla_category'] == 'Nurture')
                                                $sel_class = 'narture-color1';
                                            else if($row['joomla_category'] == 'Watch')
                                                $sel_class = 'watch-color1';
                                            else if($row['joomla_category'] == 'Hot')
                                                $sel_class = 'hot-color1';
                                            else if($row['joomla_category'] == 'Pending Transaction')
                                                $sel_class = 'pending-color1';
                                            else if($row['joomla_category'] == 'Closed Transaction')
                                                $sel_class = 'closed-color1';
                                            else if($row['joomla_category'] == 'Inactive Prospect')
                                                $sel_class = 'archive-color1';
                                            else if($row['joomla_category'] == 'Bogus')
                                                $sel_class = 'trash-color1';
                                            ?>
                                            <select name="joomla_contact_category" id="joomla_contact_category_<?=$row['id']?>" onchange="change_contact_category(this.value,<?=$row['id']?>);" class="<?=$sel_class?> form-control">
                                                <option class='new-color1' value="New" <?php if(!empty($row['joomla_contact_type']) && $row['joomla_category'] == 'New') { echo "selected='selected'"; }?>>New</option>
                                                <option class='quality-color1' value="Qualify" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Qualify') { echo "selected='selected'";?>  <?php }?>>Qualify</option>
                                                <option class='narture-color1' value="Nurture" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Nurture') { echo "selected='selected'"; }?>>Nurture</option>
                                                <option class='watch-color1' value="Watch" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Watch') { echo "selected='selected'"; }?>>Watch</option>
                                                <option class='hot-color1' value="Hot" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Hot') { echo "selected='selected'"; }?>>Hot</option>
                                                <option class='pending-color1' value="Pending Transaction" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Pending Transaction') { echo "selected='selected'"; }?>>Pending</option>
                                                <option class='closed-color1' value="Closed Transaction" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Closed Transaction') { echo "selected='selected'"; }?>>Closed</option>
                                                <option class='archive-color1'  value="Inactive Prospect" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Inactive Prospect') { echo "selected='selected'"; }?>>Archive</option>
                                                <option class='trash-color1' value="Bogus" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Bogus') { echo "selected='selected'"; }?>>Bogus</option>
                                            </select>
                                            <?php //!empty($row['joomla_contact_type'])?$row['joomla_contact_type']:'';?>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <?php echo !empty($row['joomla_timeframe'])?$row['joomla_timeframe']:'';?>
                                        </td>
										
                                                                            <td class="hidden-xs hidden-sm">
										<a href="#basicModal_email_popup1" style="display:none;" class="text_size" id="basicModal_email_popup_<?=$row['id']?>" data-toggle="modal" onclick="add_email_campaign('<?=$row['id']?>','<?=$row['em_id']?>')">email</a>
																				<?php 	if(!empty($row['em_id']))
																				{?>			
                                                                                <a class="btn btn-xs btn-success smaller_btn_new1"  title="New Email" href="javascript:void(0);" onclick="show_action('basicModal_email_popup_<?=$row['id']?>',<?=$row['id']?>);"><i class="fa fa-envelope"></i></a>
                                                                                <?php } ?>
                                                                                
                                                                                <a class="btn btn-xs btn-success smaller_btn_new1"  title="Add Note" href="javascript:void(0);" onclick="show_action('Add_Note',<?=$row['id']?>);"><i class="fa fa-paste"></i></a> <br />
                                                                                <?php if(!empty($row['phone_no']))
																				{?>
                                                                                <a class="btn btn-xs btn-success smaller_btn_new1"  title="Log Call" href="javascript:void(0);" onclick="show_action('a_conversation_id',<?=$row['id']?>);"><i class="fa fa-phone"></i></a>
                                                                                <?php } ?>
                                                                                <a class="btn btn-xs btn-success smaller_btn_new1"  title="Set To Do" href="javascript:void(0);" onclick="show_action('set_to_do',<?=$row['id']?>);"><i class="fa fa-file-text"></i></a>
                                                                               
                                                                                <?php /*
                                                                                <a title="New Email" class="text_size" href="javascript:void(0);" onclick="show_action('basicModal_email_popup_<?=$row['id']?>',<?=$row['id']?>);">
                                                                                    <button title="New Email" class="btn btn-secondary howler smaller_btn_new"><i class="fa fa-envelope"></i></button>
                                                                                </a>
                                                                                <a title="Add Note" href="javascript:void(0);" class="" onclick="show_action('Add_Note',<?=$row['id']?>);">
                                                                                    <button title="Add Note" class="btn btn-secondary howler smaller_btn_new"><i class="fa fa-paste"></i></button>
                                                                                </a>
                                                                                <br />
                                                                                <a title="Log Call" href="javascript:void(0);"  class="" onclick="show_action('a_conversation_id',<?=$row['id']?>);" ><button title="Log Call" class="btn btn-secondary howler smaller_btn_new"><i class="fa fa-phone"></i></button></a>
                                                                                <a title="Set To Do" href="javascript:void(0);" class="" onclick="show_action('set_to_do',<?=$row['id']?>);"><button title="Set To Do" class="btn btn-secondary howler smaller_btn_new"><i class="fa fa-file-text"></i></button></a>
                                                                                */?>
                                                                                <?php /*
                                                                                <select name="joomla_action" id="joomla_action" onchange="show_action(this.value,<?=$row['id']?>);" data-toggle="modal">
                                                                                     <option value="">Select Action</option>
                                                                                    <option value="a_conversation_id">Log call</option>
                                                                                    <option value="basicModal_email_popup">New Email</option>
                                                                                    <option value="set_to_do" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Set to Do') { echo "selected='selected'"; }?>>Set to Do</option>
                                                                                    <option value="Add_Note" <?php if(!empty($row['joomla_category']) && $row['joomla_category'] == 'Add Note') { echo "selected='selected'"; }?>>Add Note</option>
                                                                                </select>
                                                                                 * 
                                                                                 */
                                                                                ?>
                                            <?php //!empty($row['joomla_contact_type'])?$row['joomla_contact_type']:'';?>
                                        </td>
                                        <? if(in_array('communications',$this->modules_unique_name)){ ?>
										  <td class="hidden-xs hidden-sm ">
										<a title="Communication Plan" data-toggle="modal" class="view_contacts_btn" href="#basicModal" data-id="<?=$row['id']?>"><?php echo $this->lang->line('leads_dashboard_comm_plan'); ?></a></td>
                                        <? } ?>
                                        
                                        <td class="hidden-xs hidden-sm ">
                                            <b><?=!empty($row['total_calls_made'])?$row['total_calls_made']:'0';?></b><?php if(!empty($row['phone_no'])){ ?> <a title="Add Conversations Log" href="javascript:void(0);"  class="" onclick="show_action('a_conversation_id',<?=$row['id']?>);" >calls</a><?php } else { echo ' calls'; }?><br />
                                            <?php //!empty($row['last_calls_made_date']) && $row['last_calls_made_date'] != '0000-00-00 00:00:00' ?date($this->config->item('common_datetime_format'),strtotime($row['last_calls_made_date'])):'';?>
                                            <?= !empty($row['last_calls_made_words'])? $row['last_calls_made_words']:'-';?>
                                            <br />
                                            <b><?=!empty($row['emails_sent_count'])?$row['emails_sent_count']:'0';?></b><?php if(!empty($row['em_id'])) { ?> <a title="New Email" href="javascript:void(0);"  class="" onclick="show_action('basicModal_email_popup_<?=$row['id']?>',<?=$row['id']?>);" >emails</a><?php } else { echo " emails"; }?><br />
                                            <?php //!empty($row['last_emails_sent_date']) && $row['last_emails_sent_date'] != '0000-00-00 00:00:00' ?date($this->config->item('common_datetime_format'),strtotime($row['last_emails_sent_date'])):'';?>
                                            <?= !empty($row['last_emails_sent_words'])? $row['last_emails_sent_words']:'-';?>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?><?=$row['id']?>" id="<?php echo $viewname;?><?=$row['id']?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?>leads_dashboard/property_contact_form/<?=$row['id']?>">
                                                <input type="hidden" name="contact_id" value="<?=!empty($row['id'])?$row['id']:''?>">
                                                <a title="New Property Contact Form" href="#"  class="" onclick="return form_submit3('<?=addslashes($viewname);?><?=$row['id']?>');" ><b><?=!empty($row['total_contactform'])?$row['total_contactform']:'0';?></b> forms</a><br />
                                            </form>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <?php if(!empty($row['created_date1']) && $row['created_date1'] != '0000-00-00 00:00:00'){ echo date($this->config->item('common_datetime_format'),strtotime($row['created_date1'])); } else { echo '-'; }?>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <?= !empty($row['last_login_words'])?$row['last_login_words']:$row['created_date'];?><br />
                                            <?php //!empty($row['last_login']) && $row['last_login'] != '0000-00-00 00:00:00' ?date($this->config->item('common_datetime_format'),strtotime($row['last_login'])):'';?>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <a href="javascript:void(0);" onclick="setcontact_session(<?=$row['id']?>,'2');"><b><?=!empty($row['total_properties_viewed'])?$row['total_properties_viewed']:'0';?></b> visits</a><br />
                                            <a href="javascript:void(0);" onclick="setcontact_session(<?=$row['id']?>,'2');"><b><?=!empty($row['total_favorites'])?$row['total_favorites']:'0';?></b> favs</a><br />
                                            <a href="javascript:void(0);" onclick="setcontact_session(<?=$row['id']?>,'3');"><b><?=!empty($row['total_saved_searches'])?$row['total_saved_searches']:'0';?></b> saved</a>
                                            <?php /*<b><?=!empty($row['total_visits'])?$row['total_visits']:'0';?></b> props*/ ?>
                                        </td>
                                        <td class="hidden-xs hidden-sm "><?=!empty($row['price_range_from'])?'$'.number_format($row['price_range_from']):''?>-<br /><?= !empty($row['price_range_to'])?'$'.number_format($row['price_range_to']):'';?></td>
                                        <?php /*<td class="hidden-xs hidden-sm ">
                                            <?php  //echo !empty($row['created_date'])? $row['created_date']:'-'; ?><br />
                                            <?php //if((!empty($row['created_date'])) && ($row['created_date'] != '0000-00-00 00:00:00')) { echo date($this->config->item('common_date_format'),strtotime($row['created_date'])); }?>
                                            <?=!empty($row['joomla_domain_name'])?$row['joomla_domain_name']:'';?>
                                        </td>*/ ?>
                                        <td class="hidden-xs hidden-sm ">
                                            <?=!empty($row['assigned_agent_name'])?ucfirst(strtolower($row['assigned_agent_name'])):'';?>
                                        </td>
                                        <td class="hidden-xs hidden-sm ">
                                            <?=!empty($row['assigned_lender_name'])?ucfirst(strtolower($row['assigned_lender_name'])):'';?>
                                             <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                                            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                                        </td>
                                         <? if(in_array('lead_dashboard_edit',$this->modules_unique_name) || in_array('lead_dashboard_delete',$this->modules_unique_name)){ ?>
                                        <td class="hidden-xs hidden-sm text-center">
                                            <?php /*
                                            <a class="btn btn-xs btn-success" title="Copy Label"  href="<?= $this->config->item('admin_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>"><i class="fa fa-copy"></i></a> &nbsp; 
                                             */ ?>
                                             <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_edit',$this->modules_unique_name)){?>
                                            <a class="btn btn-xs btn-success"  title="Edit Contact" href="<?= $this->config->item('admin_base_url'); ?>contacts/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                                            <? } ?>
                                             <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_delete',$this->modules_unique_name)){?>
                                            <button class="btn btn-xs btn-primary" title="Delete Contact" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');"><i class="fa fa-times"></i></button>
                                            
<? } ?>
                                           
                                        </td>
                                         <? } ?>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="16" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_leads_per_page');?></option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
           </div>
          </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
           </div>
          </div>