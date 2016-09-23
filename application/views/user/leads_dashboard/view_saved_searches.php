<?php
$viewname = $this->router->uri->segments[2];
$sel_contact_id = !empty($selected_contact_id)?$selected_contact_id:'';
                                
?> 
<?php if(isset($sortby1) && $sortby1 == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
  <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                              <thead>
                               <tr role="row">                              
                                   <th width="3%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'created_type'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_type','<?php echo $sorttypepass;?>')">Type</a></th>
                                <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('name','<?php echo $sorttypepass;?>')">Search Name </a></th>
                                
                                <?php /* <th width="13%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'url'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact1('url','<?php echo $sorttypepass;?>')">URL </a></th> */ ?>
                                
                                <th width="13%" class="hidden-xs hidden-sm <?php //if(isset($sortfield1) && $sortfield1 == 'search_criteria'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade">Search Criteria</th>
                                
                                <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'domain'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('domain','<?php echo $sorttypepass;?>')">Domain</a></th>
                                
                                <th width="5%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'created_date'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('created_date','<?php echo $sorttypepass;?>')">Date</a></th>
                               
                                <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
                                <input type="hidden" id="sortfield1" name="sortfield1" value="<?php if(isset($sortfield1)) echo $sortfield1;?>" />
                                <input type="hidden" id="sortby1" name="sortby1" value="<?php if(isset($sortby1)) echo $sortby1;?>" />
                               </tr>
                               </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                               <?php if(!empty($result_saved_searches) && count($result_saved_searches)>0){
                                        $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                          foreach($result_saved_searches as $rss){?>
                                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                                <td class="hidden-xs hidden-sm text-center">
                                                    <?php if($rss['created_type'] == '1'){ ?>
                                                        <i class="fa fa-plus-square" title="Livewire"></i>
                                                    <?php }elseif($rss['created_type'] == '2'){ ?>
                                                        <label title="Leads">L</label>
                                                    <?php } ?>
                                                </td>
                                                <td class="hidden-xs hidden-sm ">
                                                    <a target="_blank" href="<?= $rss['domain'].'/'.$rss['url'] ?>" class="textdecoration">
							<?=!empty($rss['name'])?$rss['name']:'';?></a>
                                                </td>
                                                <td class="hidden-xs hidden-sm ">
                                                    <?= !empty($rss['search_criteria'])? 'Search Text: '.strip_slashes($rss['search_criteria']).'<br />':'';?>
                                                    <?= !empty($rss['min_price']) ? 'Minimum Price: '.$rss['min_price'].'<br />':'' ?>
                                                    <?= !empty($rss['max_price']) ? 'Maximum Price: '.$rss['max_price'].'<br />':'' ?>
                                                    <?= !empty($rss['bedroom'])? 'Bedroom: '.$rss['bedroom'].'+<br />':'' ?>
                                                    <?= !empty($rss['bathroom'])?'Bathroom: '.$rss['bathroom'].'+<br />':'' ?>
                                                    <?= !empty($property_type[$rss['property_type']])? 'Property Type: '.$property_type[$rss['property_type']].'<br />':'' ?>
                                                    <?= !empty($rss['min_year_built'])? 'Year Built: '.$rss['min_year_built'].'<br />':'' ?>
                                                    <?= !empty($rss['fireplaces_total'])? 'Fireplaces Total: '.$rss['fireplaces_total'].'+<br />':'' ?>
                                                    <?= !empty($rss['min_lotsize'])? 'Lot Size: '.$rss['min_lotsize'].'+<br />':'' ?>
                                                    <?= !empty($rss['garage_spaces'])? 'Garage Spaces: '.$rss['garage_spaces'].'+<br />':'' ?>
                                                    <?= !empty($rss['architecture'])? 'Architecture: <span class="search_details_ARC_'.$rss['id'].'">'.$rss['architecture'].'</span><br />':'' ?>
                                                    <?= !empty($school_data[$rss['id']][0])? 'School District: '.$school_data[$rss['id']][0]['school_district_description'].'<br />':'' ?>
                                                    <?= !empty($rss['waterfront'])? 'Waterfront: <span class="search_details_WFT_'.$rss['id'].'">'.str_replace('{^}', ', ', $rss['waterfront']).'</span><br />':'' ?>
                                                    <?= !empty($rss['s_view'])? 'View: <span class="search_details_VEW_'.$rss['id'].'">'.str_replace('{^}', ', ', $rss['s_view']).'</span><br />':'' ?>
                                                    <?= !empty($rss['parking_type'])? 'Parking Type: <span class="search_details_GR_'.$rss['id'].'">'.$rss['parking_type'].'</span><br />':'' ?>
                                                    <?= !empty($rss['property_status'])? 'Property Status: '.$rss['property_status'].'<br />':'' ?>
                                                    <?= !empty($rss['new_construction'])? 'New Construction: <span class="search_details_NC_'.$rss['id'].'">'.$rss['new_construction'].'</span><br />':'' ?>
                                                    <?= !empty($rss['short_sale'])? 'Short Sale: <span class="search_details_PARQ_'.$rss['id'].'">'.$rss['short_sale'].'</span><br />':'' ?>
                                                    <?= !empty($rss['bank_owned'])? 'Bank Owned: '.$rss['bank_owned'].'<br />':'' ?>
                                                    <?= !empty($rss['CDOM'])? 'New in the last : '.$rss['CDOM'] .' Days <br />':'' ?>
                                                    <?= !empty($rss['mls_id'])? '#MLS: '.$rss['mls_id'].'<br />':'' ?>
                                                    <?= !empty($rss['city'])? 'City: '.str_replace('{^}', ', ', $rss['city']):'' ?>
                                                </td>
                                                <?php /*<td class="hidden-xs hidden-sm "><?=!empty($rss['url'])?$rss['url']:'';?></td> */ ?>
                                                  <?php /*
												  if(!empty($result_saved_searches[0]['where_query']))
												  { 
													$explode_data = explode(',',$result_saved_searches[0]['where_query']);
													//pr( $explode_data); 
													?>
                                                <td class="hidden-xs hidden-sm "><?php echo $explode_data[0]."<br>";
													echo $explode_data[1];?></td><?php } else {
                                                                                                        ?>
                                                <td class="hidden-xs hidden-sm ">-</td>
                                                                                                        <?php } */?>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rss['domain'])?$rss['domain']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rss['created_date'])?date($this->config->item('common_datetime_format'),strtotime($rss['created_date'])):'';?></td>
                                                <td class="hidden-xs hidden-sm text-center">
                                                    <a title="View Saved Searches" data-toggle="modal" class="btn btn-xs btn-success saved_searches_popup_btn" href="#saved_searches_popup" data-id="<?=!empty($rss['id'])?$rss['id']:'';?>"><i class="fa fa-search"></i></a> &nbsp; 
                                                    <a class="btn btn-xs btn-success" title="Edit Saved Searches" href="<?= $this->config->item('user_base_url').$viewname; ?>/edit_saved_searches/<?= $rss['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; 
                                                </td>
                                              </tr>
                              <?php } } else {?>
                              <tr>
                                <td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
                              </tr>
                              
                              <?php } ?>
                              </tbody>
                             </table>
                             
<div class="row dt-rb" id="common_tb1">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages1();" id="perpage1">
             <option <?php if(empty($perpage1)){ echo 'selected="selected"';}?> value="0">Saved Searches per Page</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage1) && $perpage1 == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination1))
			{
				echo $pagination1;
			}
		  	?>
            </div>
           </div>
                             
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
         <?php if(!empty($result_saved_searches) && count($result_saved_searches)>0){
                foreach($result_saved_searches as $rss){
                    if(!empty($ame_data[$rss['id']]))
                    {
                        foreach($ame_data[$rss['id']] as $sin_row)
                        { ?>
                            $('.search_details_<?=$sin_row["code"]?>_<?=$rss["id"]?>').html('<?=$sin_row["value_description"]?>');
                        <?php }
                    }
        } }?>
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