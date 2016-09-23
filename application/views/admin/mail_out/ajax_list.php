<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
//pr($this->session->userdata('iplans_sortsearchpage_data'));
?>
<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>

<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <? if(in_array('letter_delete',$this->modules_unique_name) || in_array('envelope_delete',$this->modules_unique_name) || in_array('label_delete',$this->modules_unique_name)){ ?>
      <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%"> <div class="text-center">
          <input type="checkbox" class="selecctall" id="selecctall">
        </div>
      </th>
      <? } ?>
      <th width="29%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'mail_out_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('mail_out_type','<?php echo $sorttypepass;?>')">Mail Out Type</a></th>
      <th width="29%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'template_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('template_label_name')?>
        </a></th>
      <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'save_type'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" ><a href="javascript:void(0);" onclick="applysortfilte_contact('save_type','<?php echo $sorttypepass;?>')">Type</a></th>
      <th class="hidden-xs hidden-sm text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="15%"><?=$this->lang->line('interaction_contacts_assigned')?></th>
      <? if(in_array('letter_delete',$this->modules_unique_name) || in_array('envelope_delete',$this->modules_unique_name) || in_array('label_delete',$this->modules_unique_name)){ ?>
      <th width="18%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" ><?php echo $this->lang->line('common_label_action')?></th>
      <? } ?>
      <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
      <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php 
		 // pr($datalist);exit;
		  if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){
						  
						   $chk='';
						   if($row['mail_out_type'] == '1' && in_array('label',$this->modules_unique_name)) {$chk='1';}
							if($row['mail_out_type'] == '2' && in_array('envelope',$this->modules_unique_name)){$chk='1';}
							if($row['mail_out_type'] == '5' && in_array('letter',$this->modules_unique_name)){$chk='1';}
							if(!empty($chk))
						{
						  ?>
    <?
                                       
						$emaildata = array(
						'Date'=>'xx/xx/xxxx',
						'Day'=>'xxx',
						'Month'=>'xxxx',
						'Year'=>'xxxx',
						'Day Of Week'=>'xxxxxxx',
						'Agent Name'=>'xxxxx',
						'First Name'=>'xxxxx',
						'Contact First Name'=>'xxxxxx',
						'Contact Spouse/Partner First Name'=>'xxx',
						'Contact Last Name'=>'xxxxx',
						'Contact Spouse/Partner Last Name'=>'xxxxx',
						'Contact Company Name'=>'xxxxxxx',
						'Contact Address'=>'xxxxx',
						'Contact City'=>'xxxxx',
						'Contact State'=>'xxxxx',
						'Contact Zip'=>'xxxxxxx',
						'first name'=>'xxxxxxx',
						'last name'=>'xxxxxxx',
						'company name'=>'xxxxxxx',
						'Agent First Name'=>'xxxxxxx',
						
						'Agent First Name'=>'xxxxxxx',
						'Agent Last Name'=>'xxxxxxx',
						'Agent Company'=>'xxxxxxx',
						'Agent Title'=>'xxxxxxx',
						'Agent Address'=>'xxxxxxx',
						'Agent City'=>'xxxxxxx',
						'Agent State'=>'xxxxxxx',
						
						'Agent Zip'=>'xxxxxxx',
						'Contact Address'=>'xxxxxxx',
						'Contact City'=>'xxxxxxx',
						'Contact State'=>'xxxxxxx',
						'Contact Zip'=>'xxxxxxx',
					);
					 /*if(!empty($row['mail_out_type']) && $row['mail_out_type'] == '2')
					 {
						$content1 = $row['envelope_content'];	
					 }
					 if(!empty($row['mail_out_type']) && $row['mail_out_type'] == '1')
					 {
					 	$content1 = $row['label_content'];
					 }
					 if(!empty($row['mail_out_type']) && $row['mail_out_type'] == '5')
					 {
					 	$content1 = $row['letter_content'];
					 }*/
					 $content1 = $row['message'];
					
						
						//$title = $row['template_subject']; 
						//$content = $headers;
						//$cdata['email_message'] = $content1;
						$pattern = "{(%s)}";
						$map = array();
						if($emaildata != '' && count($emaildata) > 0)
						{
							foreach($emaildata as $var => $value)
							{
								$map[sprintf($pattern, $var)] = $value;
							}
							//$temptitleOutput = strtr($title, $map);
							$output1 = strtr($content1, $map);
							
							
							//$cdata['template_subject'] = $finaltitle;
							 $finlaOutput = $output1;
							
							//$cdata['email_message'] = $finlaOutput;
							//$mail_data = $output;
						}
										
					?>
    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> id="view_archive_<?php echo  $row['id'] ?>" >
      <? if(in_array('letter_delete',$this->modules_unique_name) || in_array('envelope_delete',$this->modules_unique_name) || in_array('label_delete',$this->modules_unique_name)){ ?>
      <td class=""><div class="text-center">
          <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
        </div></td>
      <? } ?>
      <td class="hidden-xs hidden-sm "><?=!empty($row['save_type_name'])?ucfirst(strtolower($row['save_type_name'])):'';?></td>
      <td class="hidden-xs hidden-sm "  id="temp_name_<?php echo  $row['id'] ?>"><?
                                 if(!empty($row['template_id']) && !empty($row['mail_out_type']) && $row['mail_out_type'] == '2')
								 {
									 ?>
        <a class="view_form_btn1" data-toggle="modal" title="Template"  href="#basicModal1" data-id="<?php echo  $row['id'] ?>">
        <?=!empty($row['en_template_name'])?ucfirst(strtolower($row['en_template_name'])):'';?>
        </a>
        <?
								 }
								 else if(!empty($row['template_id']) && !empty($row['mail_out_type']) && $row['mail_out_type'] == '1')
								 {
									?>
        <a class="view_form_btn2" data-toggle="modal" title="Template"  href="#basicModal1" data-id="<?php echo  $row['id'] ?>">
        <?=!empty($row['label_template_name'])?ucfirst(strtolower($row['label_template_name'])):'';?>
        </a>
        <?
								 }
								 else if(!empty($row['template_id']) && !empty($row['mail_out_type']) && $row['mail_out_type'] == '5')
								 {
									?>
        <a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal1" data-id="<?php echo  $row['id'] ?>">
        <?=!empty($row['template_name'])?ucfirst(strtolower($row['template_name'])):'';?>
        </a>
        <?	 
								 }
								 else
									{?>
        <a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal1" data-id="<?php echo  $row['id'] ?>">No Template </a>
        <?	 }
								?></td>
      <td class=""><?=!empty($row['save_type'])?ucfirst(strtolower($row['save_type'])):'';?></td>
      <td class="text-center"><a title="Assigned Contacts" data-toggle="modal" class="text_color_red text_size view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
        <button class="btn btn-xs btn-success"> <b>
        <?=!empty($row['total_contacts'])?$row['total_contacts']:'';?>
        </b> <i class="fa fa-user conicon "></i></button>
        </a>
        <?
                                 if(!empty($row['mail_out_type']) && $row['mail_out_type']== '2')
								 {
									 ?>
        <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($finlaOutput)?$finlaOutput:'';?>
        </div>
        <div id="template_type_<?php echo  $row['id'] ?>" style="display:none;">
          <?php if(!empty($row['template_type']) && $row['template_type'] == '1') { echo ucwords('Fix');} else if($row['template_type'] == '2') { echo ucfirst(strtolower('Custom')); } else { echo '-';}?>
        </div>
        <div id="temp_category_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($row['category'])?$row['category']:'';?>
        </div>
        
        <!--<div id="temp_size_<?php echo  $row['id'] ?>" style="display:none;">
									<?php
								if((!empty($row['template_size_id'])) && ($row['template_size_id']=='1'))
								{echo "10"; }
								else
								{ ?>
									<?=!empty($row['size_w'])?$row['size_w'].'"':'';?> <strong>X</strong> <?=!empty($row['size_h'])?$row['size_h'].'"':''; }?>
								</div>-->
        
        <?
								 }
								 if(!empty($row['mail_out_type']) && $row['mail_out_type'] == '1')
								 {
									?>
        <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($finlaOutput)?ucfirst(strtolower($finlaOutput)):'';?>
        </div>
        <div id="temp_category_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($row['category'])?$row['category']:'';?>
        </div>
        
        <!--<div id="temp_size_type_<?php echo  $row['id'] ?>" style="display:none;">
										 <? if((!empty($row['size_type'])) && ($row['size_type']=='1')){echo "Avery 5159"; }?>
										 <? if((!empty($row['size_type'])) && ($row['size_type']=='2')){echo "Avery 5160"; }?>
                                         <? if((!empty($row['size_type'])) && ($row['size_type']=='3')){echo "Avery 5161"; }?>
                                         <? if((!empty($row['size_type'])) && ($row['size_type']=='4')){echo "Avery 5162"; }?>
                                         <? if((!empty($row['size_type'])) && ($row['size_type']=='5')){echo "Avery 5163"; }?>
                                         <? if((!empty($row['size_type'])) && ($row['size_type']=='6')){echo "Avery 5164"; }?>
										</div>-->
        
        <?
								 }
								 if(!empty($row['mail_out_type']) && $row['mail_out_type'] == '5')
								 {
								
									?>
        <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($finlaOutput)?$finlaOutput:'';?>
        </div>
        <div id="temp_category_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($row['category'])?$row['category']:'';?>
        </div>
        <div id="temp_subject_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($row['template_subject'])?$row['template_subject']:'';?>
        </div>
        
        <!--<div id="temp_size_<?php echo  $row['id'] ?>" style="display:none;">
                                       <?=!empty($row['size_w'])?$row['size_w'].'"':'';?> <strong>X</strong> <?=!empty($row['size_h'])?$row['size_h'].'"':'';?>
                                    </div>-->
        
        <div id="temp_subject_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($row['template_subject'])?$row['template_subject']:'';?>
        </div>
        <?	 
								 }
									
								?>
        <div id="temp_size_<?php echo  $row['id'] ?>" style="display:none;">
          <?=!empty($row['size_w'])?$row['size_w'].'"':'';?>
          <strong>X</strong>
          <?=!empty($row['size_h'])?$row['size_h'].'"':'';?>
        </div></td>
      <? if(in_array('letter_delete',$this->modules_unique_name) || in_array('envelope_delete',$this->modules_unique_name) || in_array('label_delete',$this->modules_unique_name)){ ?>
      <td class="hidden-xs hidden-sm text-center"><!--<a title="View Communication" class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url'); ?>interaction/<?= $row['id'] ?>"><i class="fa fa-search"></i></a> &nbsp; -->
        
        <?
									    $chk1='';
                                        if($row['mail_out_type'] == '1' && in_array('label_delete',$this->modules_unique_name)) {$chk1='1';}
										if($row['mail_out_type'] == '2' && in_array('envelope_delete',$this->modules_unique_name)){$chk1='1';}
										if($row['mail_out_type'] == '5' && in_array('letter_delete',$this->modules_unique_name)){$chk1='1';}
										if(!empty($chk1))
										{
									   ?>
        <button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id']; ?>','<?php echo rawurlencode(ucfirst(strtolower($row['save_type_name']))); ?>');"><i class="fa fa-times"></i></button>
        &nbsp;
        <? } ?></td>
      <? } ?>
    </tr>
    <?php } } } else {?>
    <tr>
      <td colspan="7" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
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
            <option <?php if(empty($perpage)){ echo 'selected="selected"';}?> value="0">Mail Blast Per Page</option>
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
    <?php if(isset($pagination)){echo $pagination;}?>
  </div>
</div>
