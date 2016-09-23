<?php
$viewname = $this->router->uri->segments[2];
$sel_contact_id = !empty($selected_contact_id)?$selected_contact_id:'';
                                
?>

<?php if(isset($sortby5) && $sortby5 == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr role="row">                              
            <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield5) && $sortfield5 == 'search_address'){if($sortby5 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact5('search_address','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_address')?></a></th>
            <th width="25%" class="hidden-xs hidden-sm <?php if(isset($sortfield5) && $sortfield5 == 'domain'){if($sortby5 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact5('domain','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_domain')?></a></th>
            <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield5) && $sortfield5 == 'report_timeline'){if($sortby5 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact5('report_timeline','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_timeline')?></a></th>
            <th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield5) && $sortfield5 == 'send_report'){if($sortby5 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact5('send_report','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_send_report')?></a></th>
            <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield5) && $sortfield5 == 'date'){if($sortby5 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact5('date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('contact_joomla_val_searched_date')?></a></th>
            <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
            <input type="hidden" id="sortfield5" name="sortfield5" value="<?php if(isset($sortfield5)) echo $sortfield5;?>" />
            <input type="hidden" id="sortby5" name="sortby5" value="<?php if(isset($sortby5)) echo $sortby5;?>" />
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php if(!empty($result_valuation_searched) && count($result_valuation_searched)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                foreach($result_valuation_searched as $rvs){?>
                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                        <td class="hidden-xs hidden-sm ">
                            <?php 
                            $address = '';
                            if(!empty($rvs['search_address'])) $address = $rvs['search_address'].',';
                            if(!empty($rvs['city'])) $address .= $rvs['city'].' ';
                            if(!empty($rvs['state'])) $address .= $rvs['state'].' ';
                            if(!empty($rvs['zip_code'])) $address .= $rvs['zip_code'];
                            echo trim($address,',');
                            ?>
                        </td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['domain'])?$rvs['domain']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['report_timeline'])?$rvs['report_timeline']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['send_report'])?$rvs['send_report']:'';?></td>
                        <td class="hidden-xs hidden-sm "><?=!empty($rvs['date']) && $rvs['date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($rvs['date'])):'';?></td>
                        <td class="hidden-xs hidden-sm text-center">
                            <a title="View Valuation Searched" data-toggle="modal" class="btn btn-xs btn-success valuation_searched_popup_btn" href="#valuation_searched_popup" data-id="<?=!empty($rvs['id'])?$rvs['id']:'';?>"><i class="fa fa-search"></i></a> &nbsp; 
                            <a class="btn btn-xs btn-success" title="Edit Valuation Searched" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_valuation_searched/<?= $rvs['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                        </td>
                    </tr>
                <?php } 
            } else {?>
                <tr>
                  <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                </tr>

            <?php } ?>
    </tbody>
</table>
                             
<div class="row dt-rb" id="common_tb5">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages5();" id="perpage5">
             <option <?php if(empty($perpage5)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_valuation_searched_per_page')?></option>
              <option <?php if(!empty($perpage5) && $perpage5 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage5) && $perpage5 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage5) && $perpage5 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage5) && $perpage5 == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination5))
			{
				echo $pagination5;
			}
		  	?>
            </div>
           </div>
                             
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });

    $('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  i++;
			});
			if(id != '0')
			{
				var single_remove_id = id;
			}
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>superadmin/map_joomla/",
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext1:$("#searchtext1").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
				},
				beforeSend: function() {
							$('#common_div_ss').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div_ss").html(html);
						$('#common_div_ss').unblock(); 
					}
				});
				return false;
			}
		});
	}
	
	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0' && id== '0')
			{
				
				var msg = 'Please select Record(s) To Delete.';
				alert(msg);
				return false;
				
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to delete Record(s)';
			}
			else
			{
				if(name.length > 50)
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete '+unescape(name)+'';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 


</script>