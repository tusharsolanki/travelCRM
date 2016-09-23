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
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}
?>
 <h3><?php echo $this->lang->line('user_assigned_contact_msg')?></h3>
<div class="col-sm-12">
	<div class="row">
    	<div class="col-lg-7 col-md-12 col-sm-12">
			<label class="margin-top-5px col-md-4 col-sm-5  col-xs-12"><?=$this->lang->line('user_assign_msg_agent');?></label>
            <div class="col-sm-5 row">
			<select class="form-control col-sm-5 col-md-3 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per" name="slt_user_type2[]" id="slt_user_type2">
				<option value="">Users</option>
				<?php if(!empty($user_list)){
						foreach($user_list as $row){
							$email = !empty($row['email_id'])?" (".$row['email_id'].")":''?>
				<option value="<?=$row['id']?>"><?=$row['first_name']." ".$row['last_name'].$email?></option><?php }?>
			   <?php } ?>
			</select>
            </div>
			<button class="btn btn-success howler col-sm-2 col-lg-2 col-md-2 col-xs-2 howler margin_left_10px" data-type="danger" onclick="check_assign_contact1();" title="Assign Contact">Assign</button>	
		</div>		
	</div>
</div> 
<div class="table_large-responsive">
       
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall1" id="selecctall1">
             </div>
            </th>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('company_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_list_company')?></a></th>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
			<th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_status'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('csm.name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_status')?></a></th>
			
			<th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'full_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="20%"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_address')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_type'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_type','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_type')?></a></th>
           
		    <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($assign_contact_list) && count($assign_contact_list)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($assign_contact_list as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox1" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
											<?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['phone_no'])?$row['phone_no']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_status'])?$row['contact_status']:'';?></td>
							<td class="hidden-xs hidden-sm "><?php if(!empty($row['full_address'])){
							
									$address=str_replace(', ',',',$row['full_address']);
									$letters = array(',,,,,',',,,,',',,,',',,');
									$fruit   = array(',',',',',',',');
									$text    = $address;
									$output  = str_replace($letters, $fruit, $text);
									$output = ltrim($output,",");
									$output = rtrim($output,",");
									echo ucfirst(strtolower($output));
									}	
									?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_type'])?ucfirst(strtolower($row['contact_type'])):'';?></td>
							<td class="hidden-xs hidden-sm text-center">
										
										<button class="btn btn-xs btn-primary" title="Delete Contact" onclick="deletepopup_assign_contact('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										<input type="hidden" id="perpage_no1" name="perpage_no1" value="<?=$this->uri->segment(6)?>" />
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         </div>
         <div class="row dt-rb" id="common_tb_u">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
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