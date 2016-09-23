<?php 
    /*
        @Description: Admin Tempalte list
        @Author: Mohit Trivedi
        @Date: 12-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php

$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
$CI=&get_instance(); 
?>

 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <!--<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'module_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('module_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('module_label_name')?></a></th>
            
            <th width="18%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'category'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('category','<?php echo $sorttypepass;?>')"><?=$this->lang->line('module_unique_label_name');?></a></th>
			
            <th width="18%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'template_subject'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><?=$this->lang->line('module_right_label')?></th>
			
             <?php /*?><th width="16%" class="hidden-xs hidden-sm sorting_disabled actionbtn" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th><?php */?>
           </tr>
           </thead>
           
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php 
		   if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){
						   if(!empty($row['module_right']))
							{
						  ?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<!--<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>-->
							<td class="hidden-xs hidden-sm" id="temp_name_<?php echo  $row['id'] ?>"><a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?php echo  $row['id'] ?>"><?=!empty($row['module_name'])?ucfirst(strtolower($row['module_name'])):'';?></a>	</td>
                            <td class="hidden-xs hidden-sm" ><?=!empty($row['module_unique_name'])?strtolower($row['module_unique_name']):'';?></td>
							<td class="hidden-xs hidden-sm" ><?=!empty($row['module_right'])?strtolower($row['module_right']):'';?>
								
								</td>
							<?php /*?><td class="hidden-xs hidden-sm">
							 
										<a class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>" title="Edit Record"><i class="fa fa-pencil"></i></a> &nbsp; 
										<button title="Delete Record" class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo ucfirst(strtolower($row['template_name'])) ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
													
										</td><?php */?>
                          </tr>
                          
                          <?
						   //Call sub module
						   if(!empty($row['id'])) 
						   {
							  // $table='module_master';
							   //$where = array("module_parent"=>$row['id']);
							   //$fields = array('*,GROUP_CONCAT(case when module_right="" then null else module_right end) module_right');
							  // $group_by='module_id';	
							    $table='module_master as m1';
								$join_tables = array(
												'module_master as m2' 	=> 'm1.id= m2.module_id',
											);
								$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) module_right');
								
								$group_by='m2.module_id';
								//$where = "m1.module_parent = 0";
								$where = array("m1.module_parent"=>$row['id']);
								$subdatalist=$this->module_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.id','asc',$group_by,$where);
							   //$subdatalist=$this->module_master_model->getmultiple_tables_records($table,$fields,'','','','','',$config['per_page'], $uri_segment,'id','desc',$group_by,$where);   
							   //echo $this->db->last_query();exit;
							  // pr($subdatalist);
							  //echo count($subdatalist);exit;
							   if(!empty($subdatalist))
							   {
								   for($j=0;$j<count($subdatalist);$j++)
									{
										 if(!empty($subdatalist[$j]['module_right']))
											{
											 ?>
												  <tr> 
													<!--<td class="">
													  <div class="text-center">
														  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $subdatalist[$j]['id'] ?>">
													  </div>
													</td>-->
													<td style="padding-left:25px;" class="hidden-xs hidden-sm" id="temp_name_<?php echo  $subdatalist[$j]['id'] ?>"><a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?php echo  $subdatalist[$j]['id'] ?>"><?=!empty($subdatalist[$j]['module_name'])?ucfirst(strtolower($subdatalist[$j]['module_name'])):'';?></a>	</td>
													<td class="hidden-xs hidden-sm" ><?=!empty($subdatalist[$j]['module_unique_name'])?strtolower($subdatalist[$j]['module_unique_name']):'';?></td>
													<td class="hidden-xs hidden-sm" ><?=!empty($subdatalist[$j]['module_right'])?strtolower($subdatalist[$j]['module_right']):'';?>
														
														</td>
													<?php /*?><td class="hidden-xs hidden-sm">
													 
																<a class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $subdatalist[$j]['id'] ?>" title="Edit Record"><i class="fa fa-pencil"></i></a> &nbsp; 
																<button title="Delete Record" class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $subdatalist[$j]['id'] ?>','<?php echo ucfirst(strtolower($subdatalist[$j]['module_name'])) ?>');"><i class="fa fa-times"></i></button>
																
																<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
																<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
																			
																</td><?php */?>
												  </tr>
									 		<? 
											}
						 			} 
						 		} 
							}
						?>
          <?php } } } else {?>
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
         