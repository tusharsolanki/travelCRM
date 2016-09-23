<?php 
    /*
        @Description: Admin Lead capturing list
        @Author: Mohit Trivedi
        @Date: 13-09-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
 <?php if(isset($sortby6) && $sortby6 == 'asc'){ $sorttypepass6 = 'desc';}else{$sorttypepass6 = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <!--<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>-->
            <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield6) && $sortfield6 == 'address'){if($sortby6 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact6('address','<?php echo $sorttypepass6;?>')">Property Address</a></th>
            
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield6) && $sortfield6 == 'city'){if($sortby6 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact6('city','<?php echo $sorttypepass6;?>')">City</a></th>
            <th width="11%" class="hidden-xs hidden-sm <?php if(isset($sortfield6) && $sortfield6 == 'mls_no'){if($sortby6 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact6('mls_no','<?php echo $sorttypepass6;?>')">MLS Number</a></th>
			
			<th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield6) && $sortfield6 == 'property_type_name'){if($sortby6 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact6('property_type_name','<?php echo $sorttypepass6;?>')">Property Type</a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield6) && $sortfield6 == 'my_price'){if($sortby6 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact6('my_price','<?php echo $sorttypepass6;?>')">Price</a></th>
			
            
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<!--<td class="hidden-xs hidden-sm "><?=!empty($row['property_title'])?ucwords($row['property_title']):'';?></td>-->
							<td class="hidden-xs hidden-sm "><a target="_blank" class="" title="Edit Record" href="<?= $this->config->item('admin_base_url').'listing_manager'; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['address'])?ucfirst(strtolower(($row['address']))):'';?></a></td>
							<td class="hidden-xs hidden-sm "><a target="_blank" class="" title="Edit Record" href="<?= $this->config->item('admin_base_url').'listing_manager'; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['city'])?ucfirst(strtolower(($row['city']))):'';?></a></td>
                           
                            
                            <td class="hidden-xs hidden-sm "><a target="_blank" class="" title="Edit Record" href="<?= $this->config->item('admin_base_url').'listing_manager'; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['mls_no'])?$row['mls_no']:'';?></a></td>
                            <td class="hidden-xs hidden-sm "><a target="_blank" class="" title="Edit Record" href="<?= $this->config->item('admin_base_url').'listing_manager'; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['property_type_name'])?ucfirst(strtolower(($row['property_type_name']))):'';?></a></td>
                            <td class="hidden-xs hidden-sm "><a target="_blank" class="" title="Edit Record" href="<?= $this->config->item('admin_base_url').'listing_manager'; ?>/edit_record/<?= $row['id'] ?>"><?=!empty($row['my_price'])?$row['my_price']:'';?></a>
                            <input type="hidden" id="sortfield6" name="sortfield6" value="<?php if(isset($sortfield6)) echo $sortfield6;?>" />
								<input type="hidden" id="sortby6" name="sortby6" value="<?php if(isset($sortby6)) echo $sortby6;?>" />
                            </td>
                            
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <!--<div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value="">Listing manager per page</option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
           </div>
		   </div>
          </div>-->
            <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination6))
			{
				echo $pagination6;
			}
		  	?>
            </div>
           
         </div>
         