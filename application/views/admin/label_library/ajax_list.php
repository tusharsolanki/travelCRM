<?php 
    /*
        @Description: Admin Tempalte Envelope
        @Author: Mit Makwana
        @Date: 12-08-2014
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
            <?php if(!empty($this->modules_unique_name) && in_array('label_library_delete',$this->modules_unique_name)){?>
          <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%"> <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
            </div>
          </th>
          <? } ?>
            <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'template_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('template_label_name')?></a></th>
			 
             <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'category'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('category','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_category');?></a></th>		
                    
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'size_w'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('size_w','<?php echo $sorttypepass;?>')"><?=$this->lang->line('size_label_name')?></a></th>
			
           <th width="9%" rowspan="1" colspan="1"><?=$this->lang->line('common_label_preview')?></th>		
			 <? if(in_array('label_library_add',$this->modules_unique_name) || in_array('label_library_edit',$this->modules_unique_name) || in_array('label_library_delete',$this->modules_unique_name)){ ?>
             
               <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'created_by'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_by','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_inserted_by')?></a></th>
             
             <th width="8%" class="hidden-xs hidden-sm sorting_disabled actionbtn7" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
             <? } ?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){ //pr($row);?>
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
						$content1 = $row['label_content'];
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
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							 <?php if(!empty($this->modules_unique_name) && in_array('label_library_delete',$this->modules_unique_name)){?>
      <td class=""><div class="text-center">
          <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
        </div></td>
      <? } ?>
							<?php //pr($row);exit;?>
							<td class="hidden-xs hidden-sm" id="temp_name_<?php echo  $row['id'] ?>"><a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?php echo  $row['id'] ?>"><?=!empty($row['template_name'])?ucfirst(strtolower($row['template_name'])):'';?></a></td>
                            <td class="hidden-xs hidden-sm "  id="temp_category_<?php echo  $row['id'] ?>"><?=!empty($row['category'])?ucfirst(strtolower($row['category'])):'';?></td>
							<td class="hidden-xs hidden-sm "  id="temp_size_type_<?php echo  $row['id'] ?>">
                             <? if((!empty($row['size_type'])) && ($row['size_type']=='1')){echo "Avery 5159 ( 4\" x 1.5\" )"; }
							  else if((!empty($row['size_type'])) && ($row['size_type']=='2')){echo "Avery 5160 ( 2.62\" x 1\" )"; }
							  else if((!empty($row['size_type'])) && ($row['size_type']=='3')){echo "Avery 5161 ( 4\" x 1\" )"; }
							  else if((!empty($row['size_type'])) && ($row['size_type']=='4')){echo "Avery 5162 ( 4\" x 1.33\" )"; }
							  else if((!empty($row['size_type'])) && ($row['size_type']=='5')){echo "Avery 5163 ( 4\" x 2\" )"; }
							  else if((!empty($row['size_type'])) && ($row['size_type']=='6')){echo "Avery 5164 ( 4\" x 3.33\" )"; }
							  else
							  { ?>
								  <?=!empty($row['size_w'])?$row['size_w'].'"':'';?> <strong>X</strong> <?=!empty($row['size_h'])?$row['size_h'].'"':'';?>
							  <? } ?>
							
							<?php /*?><?=!empty($row['size_w'])?$row['size_w'].'"':'';?> <strong>X</strong> <?=!empty($row['size_h'])?$row['size_h'].'"':'';?><?php */?>
                            
                            </td>
							<td class="hidden-xs hidden-sm ">
                            <a class="btn btn-secondary view_form_btn" data-toggle="modal" href="#basicModal" data-id="<?php echo  $row['id'] ?>" title="<?=$this->lang->line('common_label_preview')?>"><?=$this->lang->line('common_label_preview')?></a>
								<!--<form class="form parsley-form" enctype="multipart/form-data" name="mail_out" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').'mail_out/mail_out_data'?>" novalidate>
								<input type="hidden" id="template_id" name="template_id" value="<?php if(isset($row['id'])) echo $row['id'];?>" />
								<input type="hidden" id="template_name" name="template_name" value="<?php if(isset($row['template_name'])) echo $row['template_name'];?>" />
								<input type="hidden" id="template_category" name="template_category" value="<?php if(isset($row['template_category'])) echo $row['template_category'];?>" />
								<input type="hidden" id="template_subcategory" name="template_subcategory" value="<?php if(isset($row['template_subcategory'])) echo $row['template_subcategory'];?>" />
								<input type="hidden" id="template_type" name="template_type" value="Label" />
								<input type="submit" class="btn btn-secondary" value="Mail Out" onclick="return setdefaultdata();" name="mail_out" title="Mail Out" />
								</form>-->
                                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										<div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;">
									<?=!empty($finlaOutput)?$finlaOutput:'';?>
								</div>
							</td>
                              <td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?ucfirst(strtolower($row['admin_name'])):ucfirst(strtolower($row['user_name']));?></td>	
                            <? if(in_array('label_library_add',$this->modules_unique_name) || in_array('label_library_edit',$this->modules_unique_name) || in_array('label_library_delete',$this->modules_unique_name)){ ?>
							<td class="hidden-xs hidden-sm ">
                             <?php if(!empty($this->modules_unique_name) && in_array('label_library_add',$this->modules_unique_name)){?>
								<a class="btn btn-xs btn-success" title="Copy Label"  href="<?= $this->config->item('admin_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>"><i class="fa fa-copy copyicon5"></i></a> 
                                 <? } ?>
                                  <?php if(!empty($this->modules_unique_name) && in_array('label_library_edit',$this->modules_unique_name)){?>
								<a class="btn btn-xs btn-success"  title="Edit Label" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a>
                                <? } ?>
        <?php if(!empty($this->modules_unique_name) && in_array('label_library_delete',$this->modules_unique_name)){?>
								<button class="btn btn-xs btn-primary" title="Delete Label" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['template_name']))) ?>');"><i class="fa fa-times"></i></button>
									<? } ?>	
										</td>
                                        <? } ?>	
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
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
             <option value=""><?=$this->lang->line('label_templates_per_page');?></option>
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