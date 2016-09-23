<?php 
    /*
        @Description: MLS Amenity Data
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
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'mls_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('mls_name')?></a></th>
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'PTYP'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('PTYP','<?php echo $sorttypepass;?>')">Property Type</a></th>
			       <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'LN'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('LN','<?php echo $sorttypepass;?>')">Listing Number</a></th>
             <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'full_address'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass;?>')">Address</a></th>		
                    
          
           <? /*  <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'LP'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('LP','<?php echo $sorttypepass;?>')">Listing Price</a></th>
			

			
            
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'OLP'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('OLP','<?php echo $sorttypepass;?>')">Original Price</a></th> */ ?>
            
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'ST'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('ST','<?php echo $sorttypepass;?>')">Status</a></th>
            
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'display_price'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('display_price','<?php echo $sorttypepass;?>')">Price</a></th>
             <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'UD'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('UD','<?php echo $sorttypepass;?>')">Updated Date</a></th>
			
             <th width="10%" class="hidden-xs hidden-sm sorting_disabled actionbtn7" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
			   
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){ //pr($row);?>
                      
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<?php /*?><td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_7" name="check_7[]" value="<?php echo  $row['ID'] ?>">
							  </div>
                            </td><?php */?>
							<?php //pr($row);exit;?>
              <td class="hidden-xs hidden-sm"><?=!empty($row['mls_name'])?$row['mls_name']:'';?></td>
							<td class="hidden-xs hidden-sm"><a class="" title="Property Image"  href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/property_image_list/<?= $row['LN'] ?>"><?=!empty($row['PTYP'])?strtoupper($row['PTYP']):'';?></a></td>
              <td class="hidden-xs hidden-sm"><?=!empty($row['LN'])?$row['LN']:'';?></td>
              <td class="hidden-xs hidden-sm"><?=!empty($row['full_address'])?$row['full_address']:'';?></td>
              <? /*<td class="hidden-xs hidden-sm"><?=!empty($row['LP'])?$row['LP']:'';?></td>
               <td class="hidden-xs hidden-sm"><?=!empty($row['OLP'])?$row['OLP']:'';?></td> */ ?>
              <td class="hidden-xs hidden-sm"><?=!empty($row['ST'])?$row['ST']:'';?></td>
              <td class="hidden-xs hidden-sm"><?=!empty($row['display_price'])?$row['display_price']:'';?></td>
              <td class="hidden-xs hidden-sm"><?=(!empty($row['UD']) && $row['UD'] != '0000-00-00 00:00:00')?date($this->config->item('common_date_format'),strtotime($row['UD'])):''?></td>
							<td class="hidden-xs hidden-sm ">
								<a class="btn btn-xs btn-success" title="Copy Label"  href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/view_property/<?= $row['ID'] ?>">View</a>
								<!--<a class="btn btn-xs btn-success"  title="Edit Label" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['ID'] ?>"><i class="fa fa-pencil"></i></a>-->
								<? /* <button class="btn btn-xs btn-primary" title="Delete Label" onclick="deletepopup1('<?php echo $row['ID'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['PTYP']))) ?>');"><i class="fa fa-times"></i></button>  */?>
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
         <input type="hidden" id="sortfield_7" name="sortfield_7" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
		 <input type="hidden" id="sortby_7" name="sortby_7" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_7">
             <option value="">MLS Property List</option>
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
