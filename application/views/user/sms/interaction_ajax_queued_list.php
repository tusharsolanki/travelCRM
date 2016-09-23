<?php 
    /*
        @Description: User Queued List
        @Author: Sanjay Chabhadiya
        @Date: 06-08-14
    */
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
//$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
//pr($editRecord);
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
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'cm.first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('cm.first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_name')?></a></th>
			
            <th width="25%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'scr.sms_message'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('scr.sms_message','<?php echo $sorttypepass;?>')">SMS Message</a></th>
			
			<th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'ipm.plan_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('ipm.plan_name','<?php echo $sorttypepass;?>')"> Communication </a></th>
			
			<th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'scr.send_sms_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('scr.send_sms_date','<?php echo $sorttypepass;?>')"> Queued Date </a></th>
			
			 <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == ''){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"> Action </th>
			
             <!--<th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>-->
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
							<?php
								if($row['sms_type'] == 'Intereaction_plan')
									$datetime = $row['send_sms_date'];
								else
									$datetime = $row['sms_send_date']." ".$row['sms_send_time']; ?> 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['ID'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
							<?=!empty($row['first_name'])?ucfirst(strtolower($row['first_name'])):'';?> <?=!empty($row['last_name'])?ucfirst(strtolower($row['last_name'])):'';?>
							</td>
							<td class="hidden-xs hidden-sm ">
							<?=!empty($row['sms_message'])?ucfirst(strtolower($row['sms_message'])):'';?>
							</td>
							
							<td class="hidden-xs hidden-sm ">
								<?=ucfirst(strtolower($row['plan_name']." >> ".$row['description']));?>
							</td>
							<td class="hidden-xs hidden-sm ">
								<?php //echo date($this->config->item('common_date_format'),strtotime($row['email_send_date']));
								$datetime = date($this->config->item('common_date_format'),strtotime($datetime));
								echo $datetime;
								?>
							 </td>	
                             <td class="hidden-xs hidden-sm text-center">
							 <?php 
							 if(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id1']) && $row['is_done'] == '0'){?>
                             <a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-orange fa-exclamation-triangle" title="<?=$this->lang->line('previous_interaction_not_complete')?>"></i></a>
                             <?php }
							 elseif((isset($row['is_default']) && $row['is_default'] == '0') || empty($row['is_default'])){?>
                             	<a href="javascript:void(0)" class="btn"><i class="fa btn-xs btn-blue fa-info-circle" title="No Mobile Phone"></i></a>
                             <?php }
							 elseif(date('Y-m-d') >= $row['send_sms_date']) { ?>
							 	 <!--<button class="btn btn-xs btn-success" onclick="interaction_mailsms(<?=$row['id']?>,<?=$row['interaction_id']?>)" title="Send Mail"> <i class="fa fa-play"></i> </button>-->
                                 <a href="<?php echo $this->config->item('user_base_url')?><?=$viewname?>/view_interaction_data/<?=$row['id']?>/<?=$this->uri->segment(4)?>" class="btn btn-xs btn-success" title="Send Mail"> <i class="fa fa-play"></i> </a>
							<?php } else { ?>
								 <!--<a href="#" class="btn btn-xs btn-success" onclick="interaction_mailsms(<?=$row['id']?>,<?=$row['interaction_id']?>)"> <i class="fa fa-play"></i> </a>-->
								 <i class="fa fa-play" title="Send Mail(Disabled)"></i>
							<?php } ?>
								&nbsp;
                                <?php if(!empty($this->modules_unique_name) && in_array('text_blast_delete',$this->modules_unique_name)){?>
								<button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['first_name'].' '.$row['last_name']))) ?>');" title="Delete"><i class="fa fa-times"></i></button>
                                <?php } ?>
							 </td>				
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
		  <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
										<input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
										 <input type="hidden" id="id" name="id" value="<?=!empty($campaign_id)?$campaign_id:''?>" />
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
			
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_sms_cam_per_page');?></option>
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
<script>
function interaction_mailsms(id,interaction_id)
{
	var pageno = '<?=$this->uri->segment(5)?>';
	$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/interaction_mailsms/';?><?=$this->uri->segment(4)?>",
			async: false,
			data: {single_id:id,interaction_id:interaction_id},
			success: function(result){
			//alert(result);
				$.ajax({
					type: "POST",
					url: "<?php echo $this->config->item('user_base_url').$viewname.'/interaction_plan_queued_list/'.$this->uri->segment(4)?>/"+pageno,
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
}
function deletepopup1(id,name)
{      
		if(name.length > 50)
		{
			name = unescape(name).substr(0, 50)+'...';
			var msg = 'Are you sure want to delete  "'+name+'"';
		}
		else
		{
			var msg = 'Are you sure want to delete  "'+unescape(name)+'"';
		}
			$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
   'action': function(){
						delete_all(id);
					}},'No'	: {'class'	: 'special'}}});
} 
function delete_all(id)
{
	var interaction_plan = '<?=$this->uri->segment(4)?>';
	var pageno = '<?=$this->uri->segment(5)?>';
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('user_base_url').$viewname.'/delete_record_trans';?>",
		dataType: 'json',
		async: false,
		data: {id:id},
		success: function(result){
			$.ajax({
					type: "POST",
					url: "<?php echo $this->config->item('user_base_url').$viewname.'/interaction_plan_queued_list/'?>"+interaction_plan+"/"+pageno,
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
}


</script>         