<?php 
/*
    @Description: Admin Dashborad task list
    @Author     : Sanjay Moghariya
    @Date       : 22-10-14
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[3];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
$uri_segment = !empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';
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
            <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')">Recipients</a></th>
            
            <th width="22%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'ipm.plan_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')">Communication </a></th>
            
            <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'ecrt.template_subject'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('ecrt.template_subject','<?php echo $sorttypepass;?>')">Subject</a></th>
            
            <th width="23%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'ecrt.email_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('ecrt.email_message','<?php echo $sorttypepass;?>')">Email message</a></th>
			
		    <th width="15%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($emails_datalist) && count($emails_datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($emails_datalist as $row){?>
						<tr class="<? if($i%2==1) echo 'bgtitle '; if(strtotime($row['task_date']) < strtotime($dt)) echo 'new_red_class'; ?>" > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox_5" name="check_5[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?>							</td>
                            <td class="hidden-xs hidden-sm "><?=!empty($row['communication'])?ucfirst(strtolower($row['communication'])):'';?></td>
                            <td class="hidden-xs hidden-sm ">
                                <?=!empty($row['template_subject'])?ucfirst(strtolower($row['template_subject'])):'';?>
                            </td>
							<td class="hidden-xs hidden-sm">
                            <a class="view_form_btn" data-toggle="modal" title="Template"  href="#template_details" data-id="<?php echo  $row['id'] ?>">
								<?php /*?><?=!empty($row['email_message'])?ucfirst($row['email_message']):'';?><?php */?>
                                <?php
									if(!empty($row['email_message']))
									{
										if(strlen($row['email_message']) > 50)
										{?>
                                        	<div class="">
												<?php echo substr(ucfirst($row['email_message']),0,50)."...";?>
                                               <!-- <a href="javascript:void(0);" class="div_dash_more_data_a">Read more</a>-->
                                            </div>
                                            <!--<div class="div_dash_more_data div_dash_display_none">
                                            	<?php echo ucfirst($row['email_message']);?>
                                                <a href="javascript:void(0);"  class="div_dash_less_data_a">Less</a>
                                            </div>-->
										<?php }
										else
										{
											echo ucfirst(strtolower($row['email_message']));
										}
									}
								?>
                             </a>
                            </td>
							<td class="hidden-xs hidden-sm text-center">
                            <?php
							 if(!empty($row['is_subscribe']) && $row['is_subscribe'] == '1')
							 {
							?>
                            	 <a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-black fa-minus-square" title="<?=$this->lang->line('unsubscribe_msg')?>"></i></a>
                            <?php 
							 }elseif((isset($row['is_default']) && $row['is_default'] == '0') || empty($row['is_default'])){
							?>
                                 <a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-blue fa-info-circle" title="<?=$this->lang->line('email_not_exist')?>"></i></a>
                            <?php 
							}elseif(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id']) && $row['is_done'] == '0'){
							?>
								<a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-orange fa-exclamation-triangle" title="<?=$this->lang->line('previous_interaction_not_complete')?>"></i></a>
                             <?php
							 }
							 else {?>
                            	<a href="javascript:void(0);" class="btn" onclick="resend_mail('<?=$row['id']?>','<?=$row['contact_id']?>')" title="Send"><i class="fa btn-xs btn-success fa-check"></i></a>
                            <?php } ?>&nbsp;
										<button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?=rawurlencode(ucfirst(strtolower($row['contact_name'])))?>');"><i class="fa fa-times"></i></button>
							</td>
                          </tr>
                          <div id="temp_desc_<?php echo  $row['id'] ?>" style="display:none;"><?=!empty($row['email_message'])?ucfirst(strtolower($row['email_message'])):''?></div>
          <?php } ?>
          					<input type="hidden" id="sortfield_5" name="sortfield_5" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
							<input type="hidden" id="sortby_5" name="sortby_5" value="<?php if(isset($sortby)) echo $sortby;?>" />
		  					<input class="" type="hidden" name="uri_segment_5" id="uri_segment_5" value="<?=!empty($uri_segment)?$uri_segment:'0';?>">
		  		
		  <?php  } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb common_tb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage_5">
             <option value=""><?=$this->lang->line('email_tasks_per_page');?></option>
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
			 
			if(isset($emails_pagination))
			{
				echo $emails_pagination;
			}
		  	?>
           </div>
         </div>
<script type="text/javascript">
/*function resend_mail(id,contact_id)
{
	var uri_segment = '<?php //echo $this->uri->segment(4)?>';
	$.ajax({
			type: "POST",
			url: "<?php //echo $this->config->item('admin_base_url').'dashboard/send_mail/';?>",
			async: false,
			data: {'single_id':id,contact_id:contact_id,uri_segment:uri_segment},
			success: function(result){
			//alert(result);
				$.ajax({
					type: "POST",
					url: "<?php //echo $this->config->item('admin_base_url').'dashboard/'.$viewname;?>/"+result,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
				},
				beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div").html(html);
						$('#common_div').unblock(); 
					}
				});
				return false;
			}
		});
}*/

</script>