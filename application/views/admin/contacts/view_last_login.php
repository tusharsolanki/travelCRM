<?php if(isset($sortby4) && $sortby4 == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
 <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                              <thead>
                               <tr role="row">                              
                                <th width="20%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield4) && $sortfield4 == 'log_date'){if($sortby4 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact4('log_date','<?php echo $sorttypepass;?>')"> Login Date </a></th>
                                
                                <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield4) && $sortfield4 == 'ip'){if($sortby4 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact4('ip','<?php echo $sorttypepass;?>')"> IP Address </a></th>
                                
                                <th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield4) && $sortfield4 == 'domain'){if($sortby4 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact4('domain','<?php echo $sorttypepass;?>')">Domain</a></th>

                                <th width="20%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
                                <input type="hidden" id="sortfield4" name="sortfield4" value="<?php if(isset($sortfield4)) echo $sortfield4;?>" />
                                <input type="hidden" id="sortby4" name="sortby4" value="<?php if(isset($sortby4)) echo $sortby4;?>" />
                               </tr>
                               </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                               <?php if(!empty($result_last_login) && count($result_last_login)>0){
                                        $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                          foreach($result_last_login as $rll){?>
                                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                               
                                                <td class="hidden-xs hidden-sm "><?=!empty($rll['log_date'])?date($this->config->item('common_datetime_format'),strtotime($rll['log_date'])):'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rll['ip'])?$rll['ip']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($rll['domain'])?$rll['domain']:'';?></td>
                                                <td class="hidden-xs hidden-sm text-center">
                                                    <a title="View Last Login" data-toggle="modal" class="btn btn-xs btn-success last_login_popup_btn" href="#last_login_popup" data-id="<?=!empty($rll['id'])?$rll['id']:'';?>"><i class="fa fa-search"></i></a>
                                                </td>
                                              </tr>
                              <?php } } else {?>
                              <tr>
                                <td colspan="4" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
                              </tr>
                              
                              <?php } ?>
                              </tbody>
                             </table>
                             
                             <div class="row dt-rb" id="common_tb4">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select class="form-control width100 col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per perpage" onchange="contact_search4('',this.value);" id="perpage4">
             <option <?php if(empty($perpage4)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_last_login_per_page')?></option>
              <option <?php if(!empty($perpage4) && $perpage4 == 10){ echo 'selected="selected"';}?> value="10">10</option>
              <option <?php if(!empty($perpage4) && $perpage4 == 25){ echo 'selected="selected"';}?> value="25">25</option>
              <option <?php if(!empty($perpage4) && $perpage4 == 50){ echo 'selected="selected"';}?> value="50">50</option>
              <option <?php if(!empty($perpage4) && $perpage4 == 100){ echo 'selected="selected"';}?> value="100">100</option>
             </select>
            </label>
            
           </div>
            </div>
         </div>
           <div class="col-sm-6">
             <?php 
			 
			if(isset($pagination4))
			{
				echo $pagination4;
			}
		  	?>
            </div>
           </div>
           
<script>
</script>