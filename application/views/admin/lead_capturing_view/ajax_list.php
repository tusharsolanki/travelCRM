<?php 
    /*
        @Description: Admin Leads list
        @Author: Mohit Trivedi
        @Date: 18-09-14
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
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name_data'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name_data','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_data'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_data','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_add_phone_no')?></a></th>
			
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_data'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_data','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
            
            <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'address_data'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('address_data','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_address')?></a></th>
            
             <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                        	<td class="">
                              <div class="text-center">
                              <?php
                              	if(isset($row['status']) && $row['status'] == 0) { ?>
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
                              <?php } ?>
							  </div>
                            </td> 
                            <?php
							$data3='';
							if(!empty($row['name'])){			
							for($i=0;$i<count($row['name']);$i++)
							{
							$data=explode("{^}",($row['first_name_data']));
							$data1=explode("{^}",($row['last_name_data']));
							}}
							?>
 							<td class="hidden-xs hidden-sm ">
							<a data-toggle="modal" href="#basicModal1"  onclick="contact_details('<?=$row['id']?>')" >
							<?php 
							for($j=0;$j<count($data);$j++)
							{
								 $data3['name'] .= $data[$j].' '.$data1[$j].',';
								 $data4['name']= $data3['name'];
							}
							echo ucwords(rtrim($data4['name'],','));
							?>
                            </a>
							</td>
 							<td class="hidden-xs hidden-sm "><?=!empty($row['phone_data'])?str_replace('{^}',',',($row['phone_data'])):'';?></td>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['email_data'])?str_replace('{^}',',',($row['email_data'])):'';?></td>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['address_data'])?str_replace('{^}',',',($row['address_data'])):'';?></td>
							<td class="hidden-xs hidden-sm text-center">
                            		<?php if(!empty($row['status']) && $row['status']==1){ echo 'Assigned'; ?>
                  <? }else{ ?>
                  <a class="btn btn-xs btn-success view_form_btn" data-toggle="modal" href="#basicModal2" title="Preview Record" data-id="<?=!empty($row['id'])?$row['id']:'';?>">Assign</a>
                  <? } ?>
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
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value="">Rows</option>
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
