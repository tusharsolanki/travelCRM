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
      <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('company_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('tasksubject_label_name')?></a></th>
			
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')">Communication</a></th>
			
			<th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')">Send Date</a></th>
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
			<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            <input type="hidden" id="sent_email_count" name="sent_email_count" value="<?=!empty($email_sent_against_interaction_plan_count)?$email_sent_against_interaction_plan_count:'0'?>" />
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
                      	<tr <? if($i%2==1){ ?> class="bgtitle table-striped1" <? }else{?> class="table-striped1"<?php }?> >
                        	<td colspan="3" class="hidden-xs hidden-sm ">
							
							<a href="<?= $this->config->item('admin_base_url'); ?>interaction/<?= $row['id'] ?>" class="textdecoration">
							<?=!empty($row['plan_name'])?ucfirst($row['plan_name']):'';?></a>
							 </td>
							<td class="hidden-xs hidden-sm "> Total : <?=!empty($row['total_sent_mail'])?$row['total_sent_mail']:'';?></td>
                        </tr>
                        <?php if(!empty($emaildata[$row['id']]) && count($emaildata[$row['id']])>0) 
						{
							$j = 1;
							foreach($emaildata[$row['id']] as $row1){
						?>
                            <tr <? if($j%2==1){ ?>class="bgtitle" <? }?> > 
                                
                                <td class="hidden-xs hidden-sm "><?=!empty($row1['contact_name'])?ucfirst(strtolower($row1['contact_name'])):'';?></td>
                                <td class="hidden-xs hidden-sm "><?=!empty($row1['template_subject'])?ucfirst(strtolower($row1['template_subject'])):'';?></td>
                                <td class="hidden-xs hidden-sm "> <?=ucfirst(strtolower($row1['plan_name']." >> ".$row1['description']));?></td>
                                <td class="hidden-xs hidden-sm "><?=!empty($row1['sent_date'])?ucwords(date($this->config->item('common_datetime_format'),strtotime($row1['sent_date']))):'';?></td>
                           </tr>
                           
                       <?php $j++; }
						}
						$i++; ?>
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
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages();" id="perpage">
             <option <?php if(empty($perpage)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_send_mail_per_page')?></option>
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
   </div>
  </div>
 </div>
</div>