<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
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
            
            <th width="3%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.created_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.created_type','<?php echo $sorttypepass;?>')">Type</a></th>
            
            <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('company_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_list_company')?></a></th>
			
            <th width="8%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
			<th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
			<th width="5%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_status'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_status')?></a></th>
			
			<!--<th width="18%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'full_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="20%"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_address')?></a></th>-->
			
			<!--<th width="8%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_type'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_type','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_type')?></a></th>-->
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'created_by'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_by','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_inserted_by')?></a></th>
           
		    <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
			<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <td class="hidden-xs hidden-sm text-center">
                            	<?php if($row['created_type'] == '1'){ ?>
                                <i class="fa fa-plus-square" title="Livewire"></i>
                                <?php }elseif($row['created_type'] == '2'){ ?>
                                <i class="fa fa-level-down" title="CSV"></i>
                                <?php }elseif($row['created_type'] == '3'){ ?>
                                <i class="fa fa-facebook" title="Facebook"></i>
                                <?php }elseif($row['created_type'] == '4'){ ?>
                                <i class="fa fa-linkedin" title="Linkedin"></i>
                                <?php }elseif($row['created_type'] == '5'){ ?>
                                <!--<label title="Lead">L</label>-->
								 <i class="fa fa-file-text-o" title="Lead"></i>
                                <?php }elseif($row['created_type'] == '6'){ ?>
                               <!-- <label title="Joomla">J</label>-->
							    <i class="fa fa-home" title="Joomla"></i>
                                <?php }elseif($row['created_type'] == '7'){ ?>
                                <i class="fa fa-google-plus" title="Google"></i>
                                <?php } ?>
                            </td>
							<td class="hidden-xs hidden-sm ">
								<?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?>
							</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['company_name'])?ucfirst($row['company_name']):'';?></td>
							<td class="hidden-xs hidden-sm "><?php /*?><?=!empty($row['phone_no'])?$row['phone_no']:'';?><?php */?>
                            
                            <?php if(!empty($row['phone_no'])){ 
                                	
                                    echo substr($row['phone_no'],0,4)."<br>".substr($row['phone_no'],4);
                                    
                                 } ?>
                            
                            </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_status'])?ucfirst(strtolower($row['contact_status'])):'';?></td>
							<!--<td class="hidden-xs hidden-sm ">
							<?php if(!empty($row['full_address'])){
							
									$address=str_replace(', ',',',$row['full_address']);
									$letters = array(',,,,,',',,,,',',,,',',,');
									$fruit   = array(',',',',',',',');
									$text    = $address;
									$output  = str_replace($letters, $fruit, $text);
									$output = ltrim($output,",");
									$output = rtrim($output,",");
									echo ucfirst(strtolower($output));
									}	
							
										?></td>-->
							<!--<td class="hidden-xs hidden-sm "><?=!empty($row['contact_type'])?$row['contact_type']:'';?></td>-->
							<td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?ucfirst(strtolower($row['admin_name'])):ucfirst(strtolower($row['user_name']));?></td>
							<td class="hidden-xs hidden-sm text-center">
										<a class="btn btn-xs btn-success" title="Un-Archive" href="javascript:void(0);" onclick="archivecontactdata('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');">Un-Archive</a> &nbsp; 
										<button class="btn btn-xs btn-primary" title="Delete Contact" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');"><i class="fa fa-times"></i></button>
										
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
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage" class="width100">
             <option value=""><?=$this->lang->line('label_contacts_per_page')?></option>
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