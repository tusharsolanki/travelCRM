<?php 
    /*
        @Description: Admin Lead capturing list
        @Author: Mohit Trivedi
        @Date: 13-09-14
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
            <?php if(!empty($this->modules_unique_name) && in_array('listing_manager_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
			<th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'address'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('address','<?php echo $sorttypepass;?>')">Property Address</a></th>
			
            <!--<th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'address'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('address','<?php echo $sorttypepass;?>')">Address</a></th>-->
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'city'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('city','<?php echo $sorttypepass;?>')">City</a></th>
            
            <th width="3%" data-direction="desc" data-sortable="true" data-filterable="true" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending">Contacts</th>
            
			<th width="11%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'mls_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_no','<?php echo $sorttypepass;?>')">MLS Number</a></th>
			
			<th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'property_type_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('property_type_name','<?php echo $sorttypepass;?>')">Property Type</a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'price'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('price','<?php echo $sorttypepass;?>')">Price</a></th>
			
			<th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'agent_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('agent_name','<?php echo $sorttypepass;?>')">Listing Agent</a></th>
			
             <? if(in_array('listing_manager_edit',$this->modules_unique_name) || in_array('listing_manager_delete',$this->modules_unique_name)){ ?>
             <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
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
                            <?php } ?>
							<?php if(!empty($this->modules_unique_name) && in_array('listing_manager_delete',$this->modules_unique_name)){?>
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <? } ?>
							<!--<td class="hidden-xs hidden-sm "><?=!empty($row['property_title'])?ucwords($row['property_title']):'';?></td>-->
							<td class="hidden-xs hidden-sm "><a class="" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['address'])?ucfirst(strtolower($row['address'])):'';?></a></td>
							<td class="hidden-xs hidden-sm "><a class="" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['city'])?ucfirst(strtolower($row['city'])):'';?></a></td>
                            <td class="text-center">
                            <a title="Contacts" data-toggle="modal" class="view_contacts_btn" href="#basicModal" data-id="<?=!empty($row['id'])?$row['id']:'';?>">
								<button class="btn btn-xs1 btn-success"> <b><?=!empty($row['contact_counter'])?$row['contact_counter']:'';?></b> <i class="fa fa-user conicon "></i></button>
							</a>
                            </td>
                            <td class="hidden-xs hidden-sm "><a class="" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['mls_no'])?$row['mls_no']:'';?></a></td>
                            <td class="hidden-xs hidden-sm "><a class="" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><?=!empty($row['property_type_name'])?ucwords($row['property_type_name']):'';?></a></td>
                            <td class="hidden-xs hidden-sm "><a class="" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['my_price'])?$row['my_price']:'';?></a></td>
                            <td class="hidden-xs hidden-sm "><a class="" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>">
							<?=!empty($row['agent_name'])?$row['agent_name']:'';?></a></td>
                             <? if(in_array('listing_manager_edit',$this->modules_unique_name) || in_array('listing_manager_delete',$this->modules_unique_name)){ ?>
							<td class="hidden-xs hidden-sm text-center">
                             <?php if(!empty($this->modules_unique_name) && in_array('listing_manager_edit',$this->modules_unique_name)){?>                     
								<a class="btn btn-xs btn-success" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                                <? } ?>
                                 <?php if(!empty($this->modules_unique_name) && in_array('listing_manager_delete',$this->modules_unique_name)){?>
								<button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['address']))) ?>');"><i class="fa fa-times"></i></button>
								
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
         <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
		<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
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
         