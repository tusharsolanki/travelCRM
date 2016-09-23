<?php 
    /*
        @Description: Admin Tempalte list
        @Author: Mohit Trivedi
        @Date: 12-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>
 <?php if(isset($default_sortby) && $default_sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($default_sortfield) && $default_sortfield == 'template_name'){if($default_sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="default_applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('template_label_name')?></a></th>
            
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($default_sortfield) && $default_sortfield == 'category'){if($default_sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="default_applysortfilte_contact('category','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_category');?></a></th>
            
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'email_event_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_event_name','<?php echo $sorttypepass;?>')">Event</a></th>
			
            <th width="35%" class="hidden-xs hidden-sm <?php if(isset($default_sortfield) && $default_sortfield == 'template_subject'){if($default_sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="default_applysortfilte_contact('template_subject','<?php echo $sorttypepass;?>')"><?=$this->lang->line('tasksubject_label_name')?></a></th>
            <th width="0%">&nbsp;</th>
			
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($default_datalist) && count($default_datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($default_datalist as $row){?>
                      
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
						$content1 = $row['email_message'];
						$title = $row['template_subject']; 
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
							$temptitleOutput = strtr($title, $map);
							$output1 = strtr($content1, $map);
							
							
							//$cdata['template_subject'] = $finaltitle;
							 $finlaOutput = $output1;
							
							//$cdata['email_message'] = $finlaOutput;
							//$mail_data = $output;
						}
										
					?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="hidden-xs hidden-sm " id="temp_name_<?php echo  $row['id'] ?>"><a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?php echo  $row['id'] ?>"><?=!empty($row['template_name'])?ucfirst(strtolower($row['template_name'])):'';?></a>	</td>
                            <td class="hidden-xs hidden-sm "  id="temp_category_<?php echo  $row['id'] ?>"><?=!empty($row['category'])?ucfirst(strtolower($row['category'])):'';?></td>
                            <td class="hidden-xs hidden-sm "  id="temp_event_<?php echo  $row['id'] ?>"><?=!empty($row['email_event_name'])?ucfirst(strtolower($row['email_event_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "  id="temp_subject_<?php echo  $row['id'] ?>"><?=!empty($temptitleOutput)?ucfirst(strtolower($temptitleOutput)):'';?></td>
                            <td>
							 <input type="hidden" id="default_sortfield" name="default_sortfield" value="<?php if(isset($default_sortfield)) echo $default_sortfield;?>" />
							<input type="hidden" id="default_sortby" name="default_sortby" value="<?php if(isset($default_sortby)) echo $default_sortby;?>" />
							<div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;">
									<?=!empty($finlaOutput)?$finlaOutput:'';?>
								</div>
                               </td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="default_common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="default_changepages();" id="default_perpage">
             <option value=""><?=$this->lang->line('label_templates_per_page');?></option>
              <option <?php if(!empty($default_perpage) && $default_perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($default_perpage) && $default_perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($default_perpage) && $default_perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($default_perpage) && $default_perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
		    </div>
          </div>
 			<div class="col-sm-6">
				 <?php 
                if(isset($pagination1))
                {
                    echo $pagination1;
                }
                ?>
            </div>
          
         </div>
         