<?php 
    /*
        @Description: user Tempalte list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <?php if(!empty($this->modules_unique_name) && in_array('text_blast_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
            <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'template_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('template_label_name')?></a></th>
			
            <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'sms_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('sms_message','<?php echo $sorttypepass;?>')">Message</a></th>
            
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" >Sent</th>
			
			<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" >All Resend</th>
			

           <? if(in_array('text_blast_add',$this->modules_unique_name) || in_array('text_blast_edit',$this->modules_unique_name) || in_array('text_blast_delete',$this->modules_unique_name)){ ?>
             <th width="9%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
             <? } ?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
            
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr class="<? if($i%2==1){ echo 'bgtitle'; }?> <?php if($row['is_sent_to_all'] == '0') {  echo 'new_red_class'; } ?>" > 
							<?php 
										$datetime = $row['sms_send_date']." ".$row['sms_send_time'];
							?>
							<?php if(!empty($this->modules_unique_name) && in_array('text_blast_delete',$this->modules_unique_name)){?>
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <? } ?>
							<td class="hidden-xs hidden-sm "><?=!empty($row['template_name'])?ucwords($row['template_name']):'';?></td>
                            <td class="hidden-xs hidden-sm">
                            <a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?=$row['id']?>">
                            <?php
							if(!empty($row['sms_message']) && strlen($row['sms_message']) > 57)
							{
								$sms = substr($row['sms_message'],0,60);
								echo ucwords($sms)."...";
							}
							else
								echo ucfirst(strtolower($row['sms_message']));
							?>
                            </a>
                            <div id="message_<?=$row['id']?>" style="display:none;">
								<?=!empty($row['sms_message'])?ucfirst(strtolower($row['sms_message'])):'';?>
							</div>
                            </td>
                            
                            <td class="hidden-xs hidden-sm ">
								<?php if($row['is_draft'] == '0' && strtotime(date('Y-m-d')) > strtotime($datetime)) { ?>
									<a class="btn btn-xs btn-success" href="<?= $this->config->item('user_base_url').$viewname; ?>/sent_sms/<?= $row['id'] ?>" title="View" > <i class="fa fa-envelope"></i></a>
								<?php } ?>
								<?php if($row['is_draft'] == '0' && strtotime(date('Y-m-d')) <= strtotime($datetime)) { 
									$datetime = date($this->config->item('common_datetime_format'),strtotime($datetime));
									echo $datetime;
									}
								?>
							</td>
							
							<td class="hidden-xs hidden-sm text-center">
								<?php if($row['is_sent_to_all'] == '0') { ?>
								<a class="btn btn-xs btn-success" href="<?php echo $this->config->item('user_base_url').$viewname.'/resend_sms/'.$row['id']; ?>" title="Resend" > Resend </a>
								<?php } ?>
							</td>
                            
							  <? if(in_array('text_blast_add',$this->modules_unique_name) || in_array('text_blast_edit',$this->modules_unique_name) || in_array('text_blast_delete',$this->modules_unique_name)){ ?>
							<td class="hidden-xs hidden-sm ">
                            <?php if(!empty($this->modules_unique_name) && in_array('text_blast_add',$this->modules_unique_name)){?>
							<a class="btn btn-xs btn-success" href="<?= $this->config->item('user_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>" title="Copy"><i class="fa fa-copy copyicon5"></i></a> &nbsp; 
                            <? } ?>
                             <?php if(!empty($this->modules_unique_name) && in_array('text_blast_edit',$this->modules_unique_name)){?>
                                        <?php if(($row['is_draft'] == 1) || ($row['is_draft'] == 0 && strtotime($datetime) >= strtotime(date('Y-m-d')))) { ?>
										<a class="btn btn-xs btn-success" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>" title="Edit SMS Campaign"><i class="fa fa-pencil"></i></a> &nbsp; 
                                       <?php } ?>
                                          <?php } ?>
                                        <?php if(!empty($this->modules_unique_name) && in_array('text_blast_delete',$this->modules_unique_name)){?>
										<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['template_name']))." (".ucfirst(strtolower($row['sms_message'])).")"); ?>');" title="Delete SMS Campaign"><i class="fa fa-times"></i></button>
                                        <? } ?>
										
										
										</td>
                                         <? } ?>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
          <?php
				$total = !empty($total_sms[0]['sms_counter'])?$total_sms[0]['sms_counter']:'0';
				$remain = !empty($udata[0]['remain_sms'])?$udata[0]['remain_sms']:'0';
				$remains = $total - $remain;
				?>
           		Total No. of Sent SMS : <?=$remains?>/<?=$total
		?>
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_sms_campaign_per_page');?></option>
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
		  	?></div>
          
         </div>
         