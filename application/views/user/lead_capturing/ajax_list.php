<?php 
    /*
        @Description: user Lead capturing list
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
           <?php if(!empty($this->modules_unique_name) && in_array('form_builder_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
            <th width="31%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'form_title'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('form_title','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			<?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'plan_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('plan_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_add_interaction_plan')?></a></th>
			<? } ?>
            <th width="20%" rowspan="1" colspan="1"><?=$this->lang->line('embed_form')?></th>
            
             <th width="18%" class="hidden-xs hidden-sm sorting_disabled actionbtn6" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<?php if(!empty($this->modules_unique_name) && in_array('form_builder_delete',$this->modules_unique_name)){?>
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <? } ?>
							<td class="hidden-xs hidden-sm ">
                            <?php /*?><a href="<?= $this->config->item('user_base_url'); ?>lead_capturing_view/<?= $row['id'] ?>" class="textdecoration"><?=!empty($row['form_title'])?ucfirst(strtolower($row['form_title'])):'';?></a><?php */?>
                            <?=!empty($row['form_title'])?ucfirst(strtolower($row['form_title'])):'';?>
                            </td>
							<?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['plan_name'])?ucfirst(strtolower($row['plan_name'])):'';?></td>
                            <? } ?>
                            <td class="hidden-xs hidden-sm ">
								<a class="btn btn-xs btn-success view_form_btn" data-toggle="modal" title="Embed form"  href="#basicModal" data-id="<?=!empty($row['form_widget_id'])?$row['form_widget_id']:'';?>"><i class="fa fa-file"></i></a>
							</td>
							<td class="hidden-xs hidden-sm">
							<a class="btn btn-xs btn-success view_form_btndata" data-toggle="modal" href="#basicModal1" title="Preview Record" data-id="<?=!empty($row['id'])?$row['id']:'';?>"><i class="fa fa-search"></i></a> &nbsp; 
                             <?php if(!empty($this->modules_unique_name) && in_array('form_builder_add',$this->modules_unique_name)){?>
                            <a class="btn btn-xs btn-success copyicon4" href="<?= $this->config->item('user_base_url').$viewname; ?>/copy_record/<?=$row['id']?>" title="Copy"><i class="fa fa-copy"></i></a> &nbsp;     
                            <? } ?>
                              <?php if(!empty($this->modules_unique_name) && in_array('form_builder_edit',$this->modules_unique_name)){?>       
							<a class="btn btn-xs btn-success" title="Edit Record" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                            <? } ?>
                            <?php if(!empty($this->modules_unique_name) && in_array('form_builder_delete',$this->modules_unique_name)){?>
							<button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['form_title']))) ?>');"><i class="fa fa-times"></i></button>
									 <? } ?>	
							
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
							<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right" >
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_lead_captu_per_page')?></option>
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
         