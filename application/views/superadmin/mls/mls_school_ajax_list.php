<?php 
    /*
        @Description: MLS Area Community Data
        @Author: Sanjay Chabhadiya
        @Date: 23-02-2015
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
            <!--<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <!-- <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'property_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('property_type','<?php echo $sorttypepass;?>')">Property Type</a></th> -->
			       <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'mls_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('mls_name')?></a></th>
             <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'school_district_code'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('school_district_code','<?php echo $sorttypepass;?>')">School District Code</a></th>		
                    
            <th width="35%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'school_district_description'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('school_district_description','<?php echo $sorttypepass;?>')">School District Description</a></th>
			
             <?php /*?><th width="10%" class="hidden-xs hidden-sm sorting_disabled actionbtn7" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th><?php */?>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){ //pr($row);?>
                      
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<?php /*?><td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_5" name="check_5[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td><?php */?>
							<?php //pr($row);exit;?>
							<!-- <td class="hidden-xs hidden-sm"><?=!empty($row['property_type'])?$row['property_type']:'';?></td> -->
              <td class="hidden-xs hidden-sm"><?=!empty($row['mls_name'])?ucfirst(strtolower($row['mls_name'])):'';?></td>
              <td class="hidden-xs hidden-sm"><?=!empty($row['school_district_code'])?$row['school_district_code']:'';?></td>
							<td class="hidden-xs hidden-sm"><?=!empty($row['school_district_description'])?ucfirst(strtolower($row['school_district_description'])):'';?></td>
							<?php /*?><td class="hidden-xs hidden-sm ">
								<!--<a class="btn btn-xs btn-success" title="Copy Label"  href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/copy_record/<?= $row['id'] ?>"><i class="fa fa-copy copyicon5"></i></a> -->
								<!--<a class="btn btn-xs btn-success"  title="Edit Label" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a>-->
								<button class="btn btn-xs btn-primary" title="Delete Label" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['property_type']))) ?>');"><i class="fa fa-times"></i></button>
								</div>
										</td><?php */?>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('superadmin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <input type="hidden" id="sortfield_5" name="sortfield_5" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
		 <input type="hidden" id="sortby_5" name="sortby_5" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_5">
             <option value="">MLS School</option>
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