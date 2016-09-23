<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
 <?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            <th class="checkbox-column sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
             <div class="icheckbox_minimal-blue icheck-input">
              <input type="checkbox" class="icheck-input icheck_input_new">
             </div>
            </th>
            <th data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'first_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('first_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_first_name')?></a></th>
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'email'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('email','<?php echo $sorttypepass;?>')"><?=$this->lang->line('staff_add_labelname')?></a></th>
            <th class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'phone_no'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact('phone_no','<?php echo $sorttypepass;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
            <th class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
           
           <?php
								if(!empty($datalist) && count($datalist)>0){
								
	                             $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                                        foreach($datalist as $row){?>
										
										<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
										<td class="checkbox-column ">
                                         <div class="icheckbox_minimal-blue icheck-input" style="position: relative;">
                                          <input type="checkbox" class="icheck-input icheck_input_new">
                                         </div>
                                        </td>
										<td class="hidden-xs hidden-sm "><?php echo  ucfirst(strtolower($row['first_name']." ".$row['last_name'])) ?></td>
										<td class="hidden-xs hidden-sm "><?php echo  $row['email'] ?></td>
										<td class="hidden-xs hidden-sm "><?php echo  $row['phone_no'] ?></td>
										<td class="hidden-xs hidden-sm text-center"><a class="btn btn-xs btn-primary" href="<?= $this->config->item('admin_base_url').$viewname; ?>/edit_record/<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a> &nbsp; <button class="btn btn-xs btn-secondary" onclick="delete_record(<?= $row['id'] ?>);">
<i class="fa fa-times"></i>
</button>
			<input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
</td>
                            </tr>
                            <?php } }?>
          </tbody>
         </table>
         <div class="row dt-rb">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap" id="common_tb">
            <!--<ul class="pagination">
             <li class="prev disabled"><a href="#">← Previous</a></li>
             <li class="active"><a href="#">1</a></li>
             <li><a href="#">2</a></li>
             <li class="next"><a href="#">Next → </a></li>
            </ul>-->
             <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
           </div>
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
				url: "<?php echo base_url();?>admin/contact/",
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
		function clearfilter_contact()
		{
			$("#searchtext").val("");
			contact_search();
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
				url: "<?php echo base_url();?>admin/contact/",
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
		 $(document).ready(function(){
		  $('#searchtext').keyup(function(event) 
		  {
			  if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}
				
				/*if (event.keyCode == 13) {
				if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}
			}*/
			//return false;
			});
			
			});
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