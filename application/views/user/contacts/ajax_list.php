<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
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
             <? if(in_array('contact_delete',$this->modules_unique_name)){ ?>
           <?php if(in_array('2',$user_right)) {?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
            <? } ?> 
            <th width="3%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.created_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.created_type','<?php echo $sorttypepass;?>')">Type</a></th>

            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>

           <?php /*?> <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
<?php */?>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('company_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_list_company')?></a></th>
			
            <th width="8%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
			<th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
			<th width="11%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_status'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_status')?></a></th>
			
			<!--<th width="18%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'full_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_address')?></a></th>-->
			
			<!--<th width="8%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_type'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_type','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_type')?></a></th>-->
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'created_by'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_by','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_inserted_by')?></a></th>
           
		    <? if(in_array('contact_edit',$this->modules_unique_name) || in_array('contact_delete',$this->modules_unique_name)){ ?>
		    <th width="13%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
            <? } ?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){
                            if(!empty($current_date))
                            {
                                if(date('Y-m-d H:i:s',strtotime($row['created_date'])) > date('Y-m-d H:i:s',strtotime($current_date)))
                                { ?>
                                    <tr class="bgtitle new_bold_class"> 
                                <?php } else {  ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                <?php 
                                }
                            } else { ?>
                                <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                            <?php }
                            ?>
                        <? if(in_array('contact_delete',$this->modules_unique_name)){ ?>
                          <?php if(in_array('2',$user_right)) {?>
							<td class="">
                              <div class="text-center">
							   <?php if(!empty($row['uct_id']) || (!empty($row['createdby_id']) && ($row['createdby_id'] == $user_session['id'] || (is_array($this->user_session['agent_id_array']) && in_array($row['createdby_id'],$this->user_session['agent_id_array']))))){ ?>
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
								  <?php } ?>
							  </div>
                            </td>
                            <? } ?>
                             <? } ?>
                            <td class="hidden-xs hidden-sm text-center">
                            	<?php if($row['created_type'] == '1'){ ?>
                                <i class="fa fa-plus-square" title="Livewire"></i>
                                <?php }elseif($row['created_type'] == '2'){ ?>
                                <i class="fa fa-level-down" title="CSV"></i>
                                <?php }elseif($row['created_type'] == '3'){ ?>
                                <i class="fa fa-facebook" title="Facebook"></i>
                                <?php }elseif($row['created_type'] == '4'){ ?>
                                <i class="fa fa-linkedin" title="Linkedin"></i>
                                <?php }elseif($row['created_type'] == '5'){ ?>
                                <i class="fa fa-file-text-o" title="Lead"></i>
                                <?php }elseif($row['created_type'] == '6'){ ?>
                                <i class="fa fa-home" title="Joomla"></i>
                                <?php }elseif($row['created_type'] == '7'){ ?>
                                <i class="fa fa-google-plus" title="Google"></i>
                                <?php } ?>
                            </td>
							<td class="hidden-xs hidden-sm ">
                             <? //if(!empty($row['uct_id']) || (!empty($row['createdby_id']) && $row['createdby_id'] == $user_session['id'])){?>
											<a href="<?= $this->config->item('user_base_url').$viewname; ?>/view_record/<?= $row['id'] ?>" class="textdecoration"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></a>
                                            <? //} else { ?> 
											<?php /*?><?=!empty($row['contact_name'])?ucfirst($row['contact_name']):'';?><?php */?>
											<? //} ?>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "><a href="#basicModal" class="text_size" id="basicModal" data-toggle="modal" onclick="add_sms_campaign('<?=$row['id']?>','<?=$row['phone_trans_id']?>')"> <?php /*?><?=!empty($row['phone_no'])?$row['phone_no']:'';?><?php */?>
                            
                            <?php if(!empty($row['phone_no'])){ 
                                	$ph = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $row['phone_no']);
                                        echo substr($ph,0,4)."<br>".substr($ph,4);
                                    //echo substr($row['phone_no'],0,4)."<br>".substr($row['phone_no'],4);
                                    
                                 } ?>
                            
                             </a></td>
							<td class="hidden-xs hidden-sm ">
                            <?php if($row['is_subscribe'] == '0' && !empty($this->modules_unique_name) && (in_array('email_blast_add',$this->modules_unique_name) || in_array('bomb_bomb_email_blast_add',$this->modules_unique_name))) { ?><a href="#basicModal" class="text_size" id="basicModal" data-toggle="modal" onclick="add_email_campaign('<?=$row['id']?>','<?=$row['email_trans_id']?>')"><?=!empty($row['email_address'])?$row['email_address']:'';?> </a> <?php } else { echo $row['email_address']; } ?>
                            </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_status'])?$row['contact_status']:'';?></td>
							<!--<td class="hidden-xs hidden-sm ">
							<?php if(!empty($row['full_address'])){
							
									$address=str_replace(', ',',',$row['full_address']);
									$letters = array(',,,,,',',,,,',',,,',',,');
									$fruit   = array(',',',',',',',');
									$text    = $address;
									$output  = str_replace($letters, $fruit, $text);
									$output = ltrim($output,",");
									$output = rtrim($output,",");
									echo $output;
									}	
							?>
							</td>-->
							<!--<td class="hidden-xs hidden-sm "><?=!empty($row['contact_type'])?$row['contact_type']:'';?></td>-->
							<td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?$row['admin_name']:$row['user_name'];?></td>
							
                             <? if(in_array('contact_edit',$this->modules_unique_name) || in_array('contact_delete',$this->modules_unique_name)){ ?>
                            <td class="hidden-xs hidden-sm">
                            			  <?php /*?><a class="btn btn-xs btn-success" title="View Contact" href="<?= $this->config->item('user_base_url').$viewname; ?>/view_record/<?= $row['id'] ?>"><i class="fa fa-search"></i></a> &nbsp; <?php */?>
                                          <?php if(!empty($row['uct_id']) || (!empty($row['createdby_id']) && ($row['createdby_id'] == $user_session['id'] || (is_array($this->user_session['agent_id_array']) && in_array($row['createdby_id'],$this->user_session['agent_id_array']))))){ ?> 
                                          <?php if(!empty($this->modules_unique_name) && in_array('contact_edit',$this->modules_unique_name)){?>
										<a class="btn btn-xs btn-success" title="Edit Contact" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                                        
                                         <? } ?>
                                         <?php if(!empty($this->modules_unique_name) && in_array('contact_delete',$this->modules_unique_name)){?>
                                        <?php if(in_array('2',$user_right)) {?>
										<button class="btn btn-xs btn-primary" title="Delete Contact" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');"><i class="fa fa-times"></i></button> &nbsp; 
										 <a class="btn btn-xs btn-primary" title="Archive Contact" href="javascript:void(0);" onclick="archivecontactdata('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');"><i class="fa fa-archive"></i></a>
										<? } } ?>
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
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage" class="width100">
             <option value=""><?=$this->lang->line('label_contacts_per_page')?></option>
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
<script>
function add_email_campaign(id,email_trans_id){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>emails/add_record/'+id+'/'+email_trans_id;
	$('.popup_heading_h3').html('Email');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="530" width="99.6%"></iframe>');
}
function add_sms_campaign(id,phone_trans_id){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>sms/add_record/'+id+'/'+phone_trans_id;
	$('.popup_heading_h3').html('SMS');
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="530" width="99.6%"></iframe>');
}
</script>