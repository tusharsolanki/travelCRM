<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user_id = !empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';?>


		 
<h3><?php echo $this->lang->line('user_select_contact_msg')?></h3>	 
<?php if(isset($sortby1) && $sortby1 == 'asc'){ $sorttypepass1 = 'desc';}else{$sorttypepass1 = 'asc';}
?>
 
<div class="col-sm-12">
<div class="row">
		<div class="col-lg-8 col-md-6 col-sm-6 col-sm-12">
		<div class="row">
			<label><?=$this->lang->line('user_assign_msg_agent');?>
			<input type="hidden" name="slt_user_type" id="slt_user_type1" value="<?php echo $user_id; ?>">
			<?php /*?><select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per" name="slt_user_type[]" id="slt_user_type1">
				<option value="">Users</option>
				<?php if(!empty($user_list)){
						foreach($user_list as $row){?>
				<option value="<?=$row['id']?>"><?=$row['first_name']." ".$row['last_name']?></option><?php }?>
			   <?php } ?>
			</select><?php */?>
			<button class="btn btn-success howler" data-type="danger" onclick="check_assign_contact();" title="Assign Contact">Assign</button>	
			</label>	
		</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-6 col-sm-12 col-xs-12">
        <div class="row">
			<div class="dataTables_filter flr" id="DataTables_Table_0_filter">
				<lable>
					<input type="text" name="searchtext1" id="searchtext1" aria-controls="DataTables_Table_0" placeholder="Search...">
					<button class="btn btn-secondary howler" data-type="danger" onclick="contact_search1();" title="Search">Search</button>
					<button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact1();" title="View All">View All</button>
				</lable>
			</div>  
            </div>           
	   </div>
       </div>
</div>
		
<div class="table-responsive1">
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter pull-left" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            
			<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
			
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'first_name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('first_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'company_name'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact1('company_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('contact_list_company')?></a></th>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'phone_no'){if($sortby1== 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('phone_no','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
			<th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'email_address'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('email_address','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'contact_status'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('csm.name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_contact_status')?></a></th>
			
			<th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'full_address'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="20%"><a href="javascript:void(0);" onclick="applysortfilte_contact1('full_address','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_address')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'contact_type'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('contact_type','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_contact_type')?></a></th>
           
		    <!--<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>-->
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($select_data_list) && count($select_data_list)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($select_data_list as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
											<?=!empty($row['contact_name'])?ucwords($row['contact_name']):'';?>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['phone_no'])?$row['phone_no']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_status'])?ucfirst(strtolower($row['contact_status'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?php if(!empty($row['full_address'])){
							
									$address=str_replace(', ',',',$row['full_address']);
									$letters = array(',,,,,',',,,,',',,,',',,');
									$fruit   = array(',',',',',',',');
									$text    = $address;
									$output  = str_replace($letters, $fruit, $text);
									$output = ltrim($output,",");
									$output = rtrim($output,",");
									echo $output;
									}	
									?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_type'])?ucfirst(strtolower($row['contact_type'])):'';?></td>
							<!--<td class="hidden-xs hidden-sm text-center">
										
										<button class="btn btn-xs btn-primary" onclick="deletepopup_assign_contact1('<?php echo  $row['id'] ?>','<?php echo $row['contact_name'] ?>');"><i class="fa fa-times"></i></button> </td>-->
										
										<input type="hidden" id="sortfield1" name="sortfield1" value="<?php if(isset($sortfield1)) echo $sortfield1;?>" />
										<input type="hidden" id="sortby1" name="sortby1" value="<?php if(isset($sortby1)) echo $sortby1;?>" />
										<input type="hidden" id="perpage_no" name="perpage_no" value="<?=$this->uri->segment(6)?>" />
										
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
</div> 		 
         <div class="row dt-rb" id="common_tb1">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages1();" id="perpage1">
             <option value=""><?=$this->lang->line('label_contacts_per_page')?></option>
              <option <?php if(!empty($perpage1) && $perpage1 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 100){ echo 'selected="selected"';}?> value="100">100</option>
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


		
		 
		 


<script type="text/javascript">
	
	$('#searchtext1').keyup(function(event) 
		  {
			  if (event.keyCode == 13) {
						contact_search1();
				}
			//return false;
		  });
	
</script>