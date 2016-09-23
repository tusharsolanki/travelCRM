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
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'template_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('template_label_name')?></a></th>
			 		
            <th width="45%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'size_w'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('size_w','<?php echo $sorttypepass;?>')"><?=$this->lang->line('size_label_name')?></a></th>
			

			<th width="30%" rowspan="1" colspan="1"><?=$this->lang->line('mail_out_label_name')?></th>		
			
             <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
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
						'Contact Company Name'=>'xxxxxxx'
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
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<?php //pr($row);exit;?>
							<td class="hidden-xs hidden-sm "><?=!empty($row['template_name'])?ucfirst(strtolower($row['template_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['size_w'])?$row['size_w'].'"':'';?> <strong>X</strong> <?=!empty($row['size_h'])?$row['size_h'].'"':'';?></td>
							<td class="hidden-xs hidden-sm "><a class="btn btn-xs btn-success" href="#"><i class="fa fa-pencil"></i></a></td>
							<td class="hidden-xs hidden-sm text-center">
							<a class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>"><i class="fa fa-copy copyicon5"></i></a>
                                        
										<a class="btn btn-xs btn-success" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a>
										<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['template_name']))) ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb">
          <div class="col-sm-12">
           <div class="dataTables_paginate paging_bootstrap float-right" id="common_tb">
           
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
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
           </div>
          </div>
         </div>
         