<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
 <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                              <thead>
                               <tr role="row">                              
                                <th width="30%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')">Contact Name </a></th>
                                
                                <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('email_address','<?php echo $sorttypepass;?>')">Email ID </a></th>
                                
                                <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'joomla_domain_name'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('joomla_domain_name','<?php echo $sorttypepass;?>')">Domain</a></th>
                                
                                <th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'created_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('created_date','<?php echo $sorttypepass;?>')">Date</a></th>
                               
                                <th width="15%" class="hidden-xs hidden-sm sorting_disabled text-center" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
                                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
                               </tr>
                               </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                               <?php if(!empty($editRecord) && count($editRecord)>0){
                                        $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                          foreach($editRecord as $er){?>
                                            <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                                                <td class="hidden-xs hidden-sm "><?php if(!empty($er['contact_name'])){ echo $er['contact_name']; }else{ echo "-"; } //if(!empty($er['first_name'])){ echo $er['first_name']; }else{ echo "-"; }?><?php //if(!empty($er['middle_name'])){ echo $er['middle_name']; }else{ echo "-"; }?> <?php //if(!empty($er['last_name'])){ echo $er['last_name']; }else{ echo "-"; }?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($er['email_address'])?$er['email_address']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($er['joomla_domain_name'])?$er['joomla_domain_name']:'';?></td>
                                                <td class="hidden-xs hidden-sm "><?=!empty($er['created_date'])?date($this->config->item('common_datetime_format'),strtotime($er['created_date'])):'';?></td>
                                                <td class="hidden-xs hidden-sm text-center">
                                                    <a title="View Contact" data-toggle="modal" class="btn btn-xs btn-success contact_register_popup_btn" href="#contact_register_popup" data-id="<?=!empty($er['id'])?$er['id']:'';?>"><i class="fa fa-search"></i></a>
                                                </td>
                                              </tr>
                              <?php } } else {?>
                              <tr>
                                <td colspan="5" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
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
             <option <?php if(empty($perpage)){ echo 'selected="selected"';}?> value="0"><?=$this->lang->line('label_contacts_per_page')?></option>
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