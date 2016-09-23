<?php if(isset($sortby3) && $sortby3 == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
 <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                              <thead>
                               <tr role="row">                              
                                <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield3) && $sortfield3 == 'mlsid'){if($sortby3 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact3('mlsid','<?php echo $sorttypepass;?>')"> MLSID </a></th>
                                
                                <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield3) && $sortfield3 == 'propery_name'){if($sortby3 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact3('propery_name','<?php echo $sorttypepass;?>')">Property Name </a></th>
                                
                                <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield3) && $sortfield3 == 'views'){if($sortby3 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact3('views','<?php echo $sorttypepass;?>')">View </a></th>
                                
                                <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield3) && $sortfield3 == 'domain'){if($sortby3 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact3('domain','<?php echo $sorttypepass;?>')">Domain</a></th>
                                
                                <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield3) && $sortfield3 == 'log_date'){if($sortby3 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact3('log_date','<?php echo $sorttypepass;?>')">Date</a></th>
                               
                                <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
                                <input type="hidden" id="sortfield3" name="sortfield3" value="<?php if(isset($sortfield3)) echo $sortfield3;?>" />
                                <input type="hidden" id="sortby3" name="sortby3" value="<?php if(isset($sortby3)) echo $sortby3;?>" />
                               </tr>
                               </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                               <?php if(!empty($result_properties_viewed) && count($result_properties_viewed)>0){
                                        $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                          foreach($result_properties_viewed as $rpv){?>
                                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                               
                                                <td class="hidden-xs hidden-sm "><?=!empty($rpv['mlsid'])?$rpv['mlsid']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rpv['propery_name'])?$rpv['propery_name']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rpv['views'])?$rpv['views']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rpv['domain'])?$rpv['domain']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rpv['log_date'])?date($this->config->item('common_datetime_format'),strtotime($rpv['log_date'])):'';?></td>
                                                <td class="hidden-xs hidden-sm text-center">
                                                    <a title="View Properties Viewed" data-toggle="modal" class="btn btn-xs btn-success properties_viewed_popup_btn" href="#properties_viewed_popup" data-id="<?=!empty($rpv['id'])?$rpv['id']:'';?>"><i class="fa fa-search"></i></a>
                                                </td>
                                              </tr>
                              <?php } } else {?>
                              <tr>
                                <td colspan="6" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
                              </tr>
                              
                              <?php } ?>
                              </tbody>
                             </table>
                             
                             <div class="row dt-rb" id="common_tb3">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages3();" id="perpage3">
             <option <?php if(empty($perpage3)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_properties_viewed_per_page')?></option>
              <option <?php if(!empty($perpage3) && $perpage3 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage3) && $perpage3 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage3) && $perpage3 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage3) && $perpage3 == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination3))
			{
				echo $pagination3;
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
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/map_joomla/",
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
				},
				beforeSend: function() {
							$('#common_div_pv').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div_pv").html(html);
						$('#common_div_pv').unblock(); 
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