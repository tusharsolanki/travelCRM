<?php 
    /*
        @Description: Admin Tempalte list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>

<div class="col-md-12">
 <div class="col-sm-12">
  <div class="table_large-responsive">
   <div class="table-responsive">
        <!--<table class="table table-bordered">
         <thead>
           <tr>
             <th data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'template_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('template_name','<?php echo $sorttypepass;?>')"></a>Name</th>
             <th>Company Name</th>
             <th>Phone No.</th>
             <th>Email</th>
             <th>Contact Status</th>
             <th>Address</th>
             <th>Contact Type</th>
           </tr>
         </thead>
         <tbody>
          
         </tbody>
      </table>-->
      <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
           
           <th width="3%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.created_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.created_type','<?php echo $sorttypepass;?>')">Type</a></th>
           
            <th data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('company_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_list_company')?></a></th>
			
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
			<th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
			<th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_status'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_status')?></a></th>
			
			<!--<th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'full_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_address')?></a></th>-->
			
			<!--<th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'contact_type'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('contact_type','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_contact_type')?></a></th>-->
           
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
			<input type="hidden" id="new_contact_count" name="new_contact_count" value="<?=!empty($new_contacts)?$new_contacts:"0"?>" />
            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<!--<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>-->
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
                                <!--<label class="mandatory_field" title="Joomla">J</label>-->
                                <i class="fa fa-home" title="Joomla"></i>
                                <?php }elseif($row['created_type'] == '7'){ ?>
                                <i class="fa fa-google-plus" title="Google"></i>
                                <?php } ?>
                            </td>
                            
							<td class="hidden-xs hidden-sm ">
										<a title="Edit Contact" href="<?= $this->config->item('admin_base_url'); ?>contacts/view_record/<?= $row['id'] ?>" class="textdecoration"><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?></a>										<?php /*?><?=!empty($row['contact_name'])?ucwords($row['contact_name']):'';?><?php */?>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "> <?=!empty($row['phone_no'])?$row['phone_no']:'';?></td>
							<td class="hidden-xs hidden-sm "> <?=!empty($row['email_address'])?$row['email_address']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_status'])?$row['contact_status']:'';?></td>
							<!--<td class="hidden-xs hidden-sm ">
							<?php if(!empty($row['full_address'])){
							
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
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_type'])?$row['contact_type']:'';?></td>-->
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-lg-6 col-sm-6 pull-left">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages();" id="perpage">
             <option <?php if(empty($perpage)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_contacts_per_page')?></option>
              <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-lg-6 col-sm-12">
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
            </div>
           </div>
   </div>
  </div>
 </div>
</div>
