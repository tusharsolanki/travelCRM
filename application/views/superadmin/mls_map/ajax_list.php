<?php 
    /*
        @Description: Admin Tempalte list
        @Author: Mohit Trivedi
        @Date: 30-08-14
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
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'mls_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('mls_name')?></a></th>
			
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'mapping_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('mapping_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('mapping_name')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'mls_hostname'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_hostname','<?php echo $sorttypepass;?>')"><?=$this->lang->line('db_host_name')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'mls_db_username'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('mls_db_username','<?php echo $sorttypepass;?>')"><?=$this->lang->line('db_user_name')?></a></th>

			
             <th width="40%" align="center" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" style="text-align:center;"><?php echo $this->lang->line('common_label_action')?></th> 
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
              foreach($datalist as $row){
						  //pr($row);exit;
						?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                <div class="text-center">
                  <?php if(empty($row['total_mapping'])) { ?>
                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
                  <?php } ?>
                </div>
              </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['mls_name'])?ucfirst(strtolower($row['mls_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['mapping_name'])?ucfirst(strtolower($row['mapping_name'])):'';?></td>				
              <td class="hidden-xs hidden-sm "><?=!empty($row['mls_hostname'])?$row['mls_hostname']:'';?></td>       
              <td class="hidden-xs hidden-sm "><?=!empty($row['mls_db_username'])?$row['mls_db_username']:'';?></td>       
              <td class="hidden-xs hidden-sm">
							
							<?php /* <? 
							
								if(!empty($row['status']) && $row['status']==1){ ?>
								<a title="Unpublish Admin" class="btn btn-xs btn-success" onclick="return status_change('0',<?= $row['id'] ?>)" href="#"><i class="fa fa-check-circle"></i></a>	&nbsp;					

                                                  <? }else{ ?>
							<a title="Publish Admin" class="btn btn-xs btn-primary" onclick="return status_change('1',<?= $row['id'] ?>)" href="#"><i class="fa fa-times-circle"></i></a>	&nbsp;			
	
							<? 	}	?> <?php */ ?>
             	
              <a title="Edit MLS" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
              <?php if(!empty($row['mls_hostname'])) { ?>
              <?php if(empty($row['total_mapping'])) { ?>
              <a title="Assign Table" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/add_table_record/<?= $row['mls_id'] ?>">Assign Tables</a> &nbsp; 
              <?php }
               $match = array('mls_id'=>$row['id']);
               $mls_tables_data = $this->obj->select_records_common('mls_type_of_mls_mapping_trans','',$match,'','=');
               //pr($mls_tables_data );
                if(empty($row['mls_dump'])){
              ?>

              <a title="Start Dump" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url'); ?>mls_map/create_cron_url/<?= $row['mls_id'] ?>">Start Dump</a> &nbsp; 
              <? } else {
                ?>
              <a title="Mapping" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/add_mls/<?= $row['mls_id'] ?>">Mapping</a> &nbsp; 
              
							<?php  } } if(empty($row['total_mapping'])) {?>

              <button class="btn btn-xs btn-primary" title="Delete MLS" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['mls_name']))) ?>');"><i class="fa fa-times"></i></button>
              <? }else {
                if(!empty($mls_tables_data))
               {
                ?>
                <a title="Set Cron" class="btn btn-xs btn-success" href="<?= $this->config->item('superadmin_base_url').$viewname; ?>/mls_cron_link/<?= $row['mls_id'] ?>">Set Cron</a> &nbsp;        
                <?
              }}  ?> 

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
           <div class="dataTables_paginate paging_bootstrap float-right" >
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_mls_per_page');?></option>
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
         