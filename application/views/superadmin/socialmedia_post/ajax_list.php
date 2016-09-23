<?php 
    /*
        @Description: superadmin Tempalte list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'template_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('template_label_name')?></a></th>
			
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'category'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('category','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_category');?></a></th>
            
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'template_subject'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_subject','<?php echo $sorttypepass;?>')"><?=$this->lang->line('tasksubject_label_name')?></a></th>
			
			<th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'platform'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('platform','<?php echo $sorttypepass;?>')"><?=$this->lang->line('platform_label_name')?></a></th>
			 <th width="8%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'publish_flag'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('publish_flag','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_publish')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm" data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?=$this->lang->line('common_label_update_publish')?></th>
             <th width="15%" class="hidden-xs hidden-sm sorting_disabled actionbtn7" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){
						  //pr($row);exit;
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
						$content1 = $row['post_content'];
						
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
							
							$output1 = strtr($content1, $map);
							
							
							//$cdata['template_subject'] = $finaltitle;
							 $finlaOutput = $output1;
							
							//$cdata['email_message'] = $finlaOutput;
							//$mail_data = $output;
						}
										
					?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm " id="temp_name_<?php echo  $row['id'] ?>"><a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?php echo  $row['id'] ?>"><?=!empty($row['template_name'])?ucfirst(strtolower($row['template_name'])):'';?></a></td>
                             <td class="hidden-xs hidden-sm "  id="temp_category_<?php echo  $row['id'] ?>"><?=!empty($row['category'])?ucfirst(strtolower($row['category'])):'';?></td>
							<td class="hidden-xs hidden-sm "  id="temp_subject_<?php echo  $row['id'] ?>"><?=!empty($row['template_subject'])?ucfirst(strtolower($row['template_subject'])):'';?></td>				
                            <td class="hidden-xs hidden-sm " id="temp_platform_<?php echo  $row['id'] ?>"><?=!empty($row['platform'])?ucfirst(strtolower($row['platform'])):'';?></td>
                            <td class="hidden-xs hidden-sm" align="center" id="temp_publish_<?php echo  $row['id'] ?>">
                                <? 
								if(!empty($row['publish_flag']) && $row['publish_flag'] == 1){ ?>
							<a title="Unpublish Admin" class="btn btn-xs btn-success" onclick="return status_change('0',<?= $row['id'] ?>,'<?=rawurlencode(ucfirst(strtolower($row['template_name'])))?>')" href="#"><i class="fa fa-check-circle"></i></a>	&nbsp;					

                                                  <? }else{ ?>
							<a title="Publish Admin" class="btn btn-xs btn-primary" onclick="return status_change('1',<?= $row['id'] ?>,'<?=rawurlencode(ucfirst(strtolower($row['template_name'])))?>')" href="#"><i class="fa fa-times-circle"></i></a>	&nbsp;				  

<? } ?>
							</td>
                             <td class="hidden-xs hidden-sm" >
                            <button title="Update Record" class="btn btn-xs btn-secondary" onclick="update_data('<?php echo $row['id'] ?>');">Update</button>
                            <br />
                            <?php if(!empty($row['superadmin_publish_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($row['superadmin_publish_date']));}?>
                            </td>
                            
							<td class="hidden-xs hidden-sm">
							<a title="Copy Template" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>"><i class="fa fa-copy copyicon5"></i></a> &nbsp; 
                                        
										<a title="Edit Template" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
										<button class="btn btn-xs btn-primary" title="Delete Template" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['template_name']))) ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
											<div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;">
									<?=!empty($finlaOutput)?$finlaOutput:'';?>
								</div>
								
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('superadmin_general_noreocrds')?></td>
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
             <option value="">Temaplates per page</option>
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
         