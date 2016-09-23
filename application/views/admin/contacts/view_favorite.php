<?php if(isset($sortby2) && $sortby2 == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
 <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                              <thead>
                               <tr role="row">                              
                                <th width="10%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield2) && $sortfield2 == 'mlsid'){if($sortby2 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact2('mlsid','<?php echo $sorttypepass;?>')"> MLSID </a></th>
                                
                                <th width="30%" class="hidden-xs hidden-sm <?php if(isset($sortfield2) && $sortfield2 == 'propery_name'){if($sortby2 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact2('propery_name','<?php echo $sorttypepass;?>')">Property Name </a></th>
                                
                                <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield2) && $sortfield2 == 'domain'){if($sortby2 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact2('domain','<?php echo $sorttypepass;?>')">Domain</a></th>
                                
                                <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield2) && $sortfield2 == 'date'){if($sortby2 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact2('date','<?php echo $sorttypepass;?>')">Date</a></th>
                               
                                <th width="10%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
                                <input type="hidden" id="sortfield2" name="sortfield2" value="<?php if(isset($sortfield2)) echo $sortfield2;?>" />
                                <input type="hidden" id="sortby2" name="sortby2" value="<?php if(isset($sortby2)) echo $sortby2;?>" />
                               </tr>
                               </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                               <?php if(!empty($result_favorite) && count($result_favorite)>0){
                                        $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                          foreach($result_favorite as $rf){?>
                                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                               
                                                <td class="hidden-xs hidden-sm "><?=!empty($rf['mlsid'])?$rf['mlsid']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rf['propery_name'])?$rf['propery_name']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rf['domain'])?$rf['domain']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rf['date'])?date($this->config->item('common_datetime_format'),strtotime($rf['date'])):'';?></td>
                                                <td class="hidden-xs hidden-sm text-center">
                                                    <a title="View Favorite" data-toggle="modal" class="btn btn-xs btn-success favorite_popup_btn" href="#favorite_popup" data-id="<?=!empty($rf['id'])?$rf['id']:'';?>"><i class="fa fa-search"></i></a>
                                                </td>
                                              </tr>
                              <?php } } else {?>
                              <tr>
                                <td colspan="5" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                              </tr>
                              
                              <?php } ?>
                              </tbody>
                             </table>
                             
                             <div class="row dt-rb" id="common_tb2">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="changepages2();" id="perpage2">
             <option <?php if(empty($perpage2)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_favorites_per_page')?></option>
              <option <?php if(!empty($perpage2) && $perpage2 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage2) && $perpage2 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage2) && $perpage2 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage2) && $perpage2 == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination2))
			{
				echo $pagination2;
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
</script> 