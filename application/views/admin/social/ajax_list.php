<?php 
    /*
        @Description: Admin Tempalte list
        @Author: Mohit Trivedi
        @Date: 06-08-14
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
           <? if(in_array('facebook_post_delete',$this->modules_unique_name) || in_array('twitter_delete',$this->modules_unique_name) || in_array('linkedin_delete',$this->modules_unique_name)){ ?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
           
			
            <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'social_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('social_message','<?php echo $sorttypepass;?>')">Message</a></th>
             <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'platform'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('platform','<?php echo $sorttypepass;?>')"><?=$this->lang->line('media_label_name')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade">Sent</th>
			
			 <? if(in_array('facebook_post_add',$this->modules_unique_name) || in_array('facebook_post_edit',$this->modules_unique_name) || in_array('facebook_post_delete',$this->modules_unique_name) || in_array('twitter_add',$this->modules_unique_name) || in_array('twitter_edit',$this->modules_unique_name) || in_array('twitter_delete',$this->modules_unique_name) || in_array('linkedin_add',$this->modules_unique_name) || in_array('linkedin_edit',$this->modules_unique_name) || in_array('linkedin_delete',$this->modules_unique_name) || in_array('all_channels_edit',$this->modules_unique_name) || in_array('all_channels_delete',$this->modules_unique_name)){  ?>
             <th width="13%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
             <? } ?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
            
           <?php 
		  
		   if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
                      <?
                      $chk='';
						   if($row['platform'] == '1' && in_array('facebook_post',$this->modules_unique_name)) {$chk='1';}
							if($row['platform'] == '2' && in_array('twitter',$this->modules_unique_name)){$chk='1';}
							if($row['platform'] == '3' && in_array('linkedin',$this->modules_unique_name)){$chk='1';}
						if(!empty($chk))
						{
					  ?>
						<tr class="<? if($i%2==1){ echo 'bgtitle'; }?> <?php if($row['is_sent_to_all'] == '0') {  echo 'new_red_class'; } ?>" > 
							<?php 
										$datetime = $row['social_send_date']." ".$row['social_send_time'];
							?>
                            <? if(in_array('facebook_post_delete',$this->modules_unique_name) || in_array('twitter_delete',$this->modules_unique_name) || in_array('linkedin_delete',$this->modules_unique_name)){ ?>
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <? } ?>
							<td class="hidden-xs hidden-sm ">
							<?php
							if($row['is_draft'] == '0' && strtotime(date('Y-m-d H:i:s')) > strtotime($datetime)) { ?>
									<a class="" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_data/<?= $row['id'] ?>" title="View" >
                                    <?
                              if(!empty($row['social_message']) && strlen($row['social_message']) > 57)
								{
									$Social = substr($row['social_message'],0,60);
									echo ucfirst(strtolower($Social))."...";
								}
								else
									echo ucfirst(strtolower($row['social_message']));  
								?>    
                              </a>
								<?php } else { 
								  if(!empty($row['social_message']) && strlen($row['social_message']) > 57)
									{
										$Social = substr($row['social_message'],0,60);
										echo ucfirst(strtolower($Social))."...";
									}
									else
										echo ucfirst(strtolower($row['social_message']));  
								 }?>
							
							</td>
							
                            <td class="hidden-xs hidden-sm ">
							<?php
							if(!empty($row['platform']))
							{
							if($row['platform'] == '1'){$platform='<i class="fa fa-facebook scl_btn btn-facebook mrg12"></i>';}	
							if($row['platform'] == '2'){$platform='<i class="fa fa-twitter scl_btn btn-twitter"></i>';}	
							if($row['platform'] == '3'){$platform='<i class="fa fa-linkedin scl_btn btn-linkedin"></i>';}	
							}
							
							?>
							<?=!empty($platform)?$platform:'';?></td>
                            
                            <td class="hidden-xs hidden-sm ">
								<?php 
								if($row['is_draft'] == '0' && strtotime(date('Y-m-d H:i:s')) > strtotime($datetime)) { ?>
									<a class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_data/<?= $row['id'] ?>" title="View" > <i class="fa fa-envelope"></i></a>
								<?php } ?>
								<?php if($row['is_draft'] == '0' && strtotime(date('Y-m-d H:i:s')) <= strtotime($datetime)) { 
									$datetime = date($this->config->item('common_datetime_format'),strtotime($datetime));
									echo $datetime;
									}
								?>
                                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
							</td>
							
							
                             <? if(in_array('facebook_post_add',$this->modules_unique_name) || in_array('facebook_post_edit',$this->modules_unique_name) || in_array('facebook_post_delete',$this->modules_unique_name) || in_array('twitter_add',$this->modules_unique_name) || in_array('twitter_edit',$this->modules_unique_name) || in_array('twitter_delete',$this->modules_unique_name) || in_array('linkedin_add',$this->modules_unique_name) || in_array('linkedin_edit',$this->modules_unique_name) || in_array('linkedin_delete',$this->modules_unique_name) || in_array('all_channels_edit',$this->modules_unique_name) || in_array('all_channels_delete',$this->modules_unique_name)){ 
							
							 $chk1='';
						   if($row['platform'] == '1' && in_array('facebook_post_edit',$this->modules_unique_name)) {$chk1='1';}
							if($row['platform'] == '2' && in_array('twitter_edit',$this->modules_unique_name)){$chk1='1';}
							if($row['platform'] == '3' && in_array('linkedin_edit',$this->modules_unique_name)){$chk1='1';}
							
							 $chk2='';
						   if($row['platform'] == '1' && in_array('facebook_post_delete',$this->modules_unique_name)) {$chk2='1';}
							if($row['platform'] == '2' && in_array('twitter_delete',$this->modules_unique_name)){$chk2='1';}
							if($row['platform'] == '3' && in_array('linkedin_delete',$this->modules_unique_name)){$chk2='1';}
							
							 $chk3='';
						   if($row['platform'] == '1' && in_array('facebook_post_add',$this->modules_unique_name)) {$chk3='1';}
							if($row['platform'] == '2' && in_array('twitter_add',$this->modules_unique_name)){$chk3='1';}
							if($row['platform'] == '3' && in_array('linkedin_add',$this->modules_unique_name)){$chk3='1';}
							
							?>
							<td class="hidden-xs hidden-sm">
                            <? if(!empty($chk3))
							{?>
                             <?php if($row['platform'] != 1) { ?>
							<a class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>" title="Copy"><i class="fa fa-copy copyicon4"></i></a> &nbsp; <? }?>
                             <? } if(!empty($chk1))
							{?>
                                        <?php if(($row['is_draft'] == 1) || ($row['is_draft'] == 0 && strtotime($datetime) >= strtotime(date('Y-m-d H:i:s')))) { ?>
										<a class="btn btn-xs btn-success copyicon5" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>" title="Edit Social Campaign"><i class="fa fa-pencil"></i></a> &nbsp; 
                                        <?php } }?>
                                        <? if(!empty($chk2))
							{?>
										<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['social_message'])))?>');" title="Delete Social Campaign"><i class="fa fa-times"></i></button>
                                        
										<? } ?>
										
										</td>
                                        <? } ?>
                          </tr>
          <?php } } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
          <?php /*?><?php
		   		$total = !empty($total_sms[0]['sms_counter'])?$total_sms[0]['sms_counter']:'0';
				$remain = !empty($udata[0]['remain_sms'])?$udata[0]['remain_sms']:'0';
				$remains = $total - $remain;
				?>
           		Total No. of Sent SMS : <?=$remains?>/<?=$total?>
          <?php */?>      
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""> <?=$this->lang->line('label_social_social_per_page');?></option>
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
         