<?php 
    /*
        @Description: Superadmin Map Joomla
        @Author: Ami Bhatti
        @Date: 08-10-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));

//pr($datalist); 
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
            <th width="12%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'user_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('user_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
            
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'caw.email_id'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('caw.email_id','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'domain'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('domain','<?php echo $sorttypepass;?>')">Domain</a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'slug'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('slug','<?php echo $sorttypepass;?>')">Slug</a></th>
            
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'admin_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('admin_name','<?php echo $sorttypepass;?>')">Admin Name</a></th>
			
             <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'status'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('status','<?php echo $sorttypepass;?>')">status</a></th>
             <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'mls_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('mls_list')?></a></th>
             
			 <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th> 

           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
					
					//$j=0;
					
                      foreach($datalist as $row){
					  
					  
						  //pr($row);exit;
						  ?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
						
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
						
							<a href=""><td class="hidden-xs hidden-sm "><?=!empty($row['user_name'])?ucfirst(strtolower($row['user_name'])):'';?></td></a>
	
    						<td class="hidden-xs hidden-sm "><?=!empty($row['email_id'])?$row['email_id']:'';?></td>						
							<td class="hidden-xs hidden-sm "><?=!empty($row['domain'])?$row['domain']:'';?></td>
              <td class="hidden-xs hidden-sm "><?=!empty($row['slug'])?$row['slug']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['admin_name'])?$row['admin_name']:'';?></td>
                            <td class="hidden-xs hidden-sm ">
                            <?php 
								if(isset($row['status']) && $row['status'] == 0)
									echo "Suspend";
								elseif(isset($row['status']) && $row['status'] == 1)
									echo "Publish";
								elseif(isset($row['status']) && $row['status'] == 2)
									echo "Draft";
							?>
                            </td>
                            <td class="hidden-xs hidden-sm ">
                                <?=!empty($row['mls_name'])?$row['mls_name']:'';?>
                            </td>
							<td class="hidden-xs hidden-sm text-left">
							
							<?php if(isset($row['status'])) {
                  if($row['status']== 1 ) { ?>
                    <a title="Suspend Domain" class="btn btn-xs btn-primary" onclick="return status_change('0',<?= $row['id'] ?>)" href="javascript:void(0);"><i class="fa fa-times-circle"></i></a>&nbsp;
                  <?php } else { ?>
							       <a title="Publish Domain" class="btn btn-xs btn-success" onclick="return status_change('1',<?= $row['id'] ?>)" href="javascript:void(0);"><i class="fa fa-check-circle"></i></a>	&nbsp;
                  <?php } ?>
              <?php } ?>
              <a title="Edit Domain" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
										<button class="btn btn-xs btn-primary" title="Delete Domain" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo ucfirst(strtolower($row['admin_name'])) ?>','<?php echo $row['domain'] ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										</td>		
                      		
                          </tr>
						  
          <?php
		  } }
		  else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right" >
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('contact_joomla_domain_perpage');?></option>
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
         