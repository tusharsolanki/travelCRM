<?php 
    /*
        @Description: user email Signature list
        @Author: Ruchi Shahu
   		@Date: 02-08-2014
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
            <?php if(!empty($this->modules_unique_name) && in_array('email_signature_delete',$this->modules_unique_name)){?>
      <th class="checkbox-column sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""> <div class="text-center">
          <input type="checkbox" class="selecctall" id="selecctall">
        </div>
      </th>
      <? } ?>
            <th data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'signature_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('signature_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('signature_name')?></a></th>
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'full_signature'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_signature','<?php echo $sorttypepass;?>')"><?=$this->lang->line('full_signature')?></a></th>
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'is_default'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('is_default','<?php echo $sorttypepass;?>')"><?=$this->lang->line('default')?></a></th>
            <? if(in_array('email_signature_edit',$this->modules_unique_name) || in_array('email_signature_delete',$this->modules_unique_name)){ ?>
      <th class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
      <? } ?>
           </tr>
           </thead>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
           
           <?php
								if(!empty($datalist) && count($datalist)>0){
								
	                             $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                        foreach($datalist as $row){?>
										
										<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
										<?php if(!empty($this->modules_unique_name) && in_array('email_signature_delete',$this->modules_unique_name)){?>
      <td class="checkbox-column "><div class="text-center">
          <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
        </div></td>
      <? } ?>
										<td class="hidden-xs hidden-sm "><?php echo  ucfirst(strtolower($row['signature_name'])) ?></td>
										<td class="hidden-xs hidden-sm "><?php echo  ucfirst(strtolower($row['full_signature'])) ?></td>
										<td class="hidden-xs hidden-sm "><input <?php if($row['is_default'] == 1){?> checked="checked" <?php } ?> type="radio" id="defaulttemplate<?=$i;?>" value="<?php echo $row['id'];?>" name="defaulttemplate" onclick="changedefaulttemplate(this.value);" /></td>
                                         <? if(in_array('email_signature_edit',$this->modules_unique_name) || in_array('email_signature_delete',$this->modules_unique_name)){ ?>
										<td class="hidden-xs hidden-sm text-center">
										<!--<span class="pubunpub_span_<?php echo  $row['id'] ?>">
										 <?php if(!empty($row['status']) && $row['status']==1){ ?>
                                                  <a class="btn btn-xs btn-success" href="javascript:void(0);" onclick="pubunpub_data(0,'<?php echo  $row['id'] ?>');"><i class="fa fa-check-circle"></i></a> &nbsp; 
												  <? }else{ ?>
													<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="pubunpub_data(1,'<?php echo  $row['id'] ?>');"><i class="fa fa-times-circle"></i></a> &nbsp;<? } 
										 ?></span>-->
        &nbsp;
         <?php if(!empty($this->modules_unique_name) && in_array('email_signature_edit',$this->modules_unique_name)){?>
		<a class="btn btn-xs btn-success" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a>
        <? } ?>
		 &nbsp;
         <?php if(!empty($this->modules_unique_name) && in_array('email_signature_delete',$this->modules_unique_name)){?>
        <button class="btn btn-xs btn-primary" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['signature_name']))) ?>');"> <i class="fa fa-times"></i> </button>
        <? } ?></td>
      <? } ?>
    </tr>
    <?php } }     else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>		
          </tbody>
         </table>
         <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
        <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
         <div class="row dt-rb"  id="common_tb" >
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           	<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value=""><?=$this->lang->line('label_email_sign_per_page')?></option>
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
		 //function for search data
		 function delete_record()
		 {
		 	/*$.confirm({
			'title': 'Logout','message': " <strong> Are you sure you want to logout?",'buttons': {'Yes': {'class': 'special',
			'action': function(){
					$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>user/contact/",
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
			}},'No'	: {'class'	: ''}}});*/	 
		 }
		function changepages()
		{
			contact_search();	
		}
	  function applysortfilte_contact(sortfilter,sorttype)
		{
			$("#sortfield").val(sortfilter);
			$("#sortby").val(sorttype);
			contact_search();
		}
		function contact_search()
		{
			$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>user/email_signature/",
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
         $("#common_tb a.paginclass_A").click(function() {
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div").html(html);
					$.unblockUI();
                }
            });
            return false;

        });
         </script>
		 
 <script>
  
function changedefaulttemplate(value)
{
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("user/email_signature/changedefaulttemplate");?>',
			data: {
			selectedvalue:value
		},
			success: function(html){
			}
		});
		return false;
	
}

function changemark(id)
{
	app_id = id;
	$.ajax({
		type: "POST",
		url: "<?php echo base_url();?>user/email_signature/ifselected/",
		data: {
			id : app_id
		},
		success: function(msg){
		}
	});
		
}
</script>