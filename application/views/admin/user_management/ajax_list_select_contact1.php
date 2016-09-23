<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];?>	 
<h3><?php echo $this->lang->line('user_select_contact_msg')?></h3>	 
<?php if(isset($sortby1) && $sortby1 == 'asc'){ $sorttypepass1 = 'desc';}else{$sorttypepass1 = 'asc';}
?>
<div id="common_div1"> 
<div class="col-sm-12">
				<div class="col-sm-6">
				<label class="pull-left margin-top-5px"><?=$this->lang->line('user_assign_msg');?></label>
				<select class="form-control pull-left parsley-validated margin-left-5px width20-per" name="slt_user_type[]" id="slt_user_type">
				   	<option value="">Users</option>
				   	<?php if(!empty($user_list)){
							foreach($user_list as $row){?>
								<option value="<?=$row['id']?>"><?=$row['first_name']." ".$row['last_name']?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
				  
			<button class="btn btn-success howler margin-left-5px" data-type="danger" onclick="check_assign_contact();">Assign</button>		
			</div>
			<div class="col-sm-6">
				<div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
             <input type="text" name="searchtext1" id="searchtext1" aria-controls="DataTables_Table_0" placeholder="Search...">
			 <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search1();">Search</button>
            </label>
           </div>
		   </div>
				</div>
<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
            
			<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
			
            <th width="15%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield1) && $sortfield1 == 'first_name'){if($sortby1 == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact1('first_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_name')?></a></th>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'company_name'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact1('company_name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('contact_list_company')?></a></th>
			
            <th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'phone_no'){if($sortby1== 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('phone_no','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_phone')?></a></th>
			
			<th width="15%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'email_address'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('email_address','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_email')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'contact_status'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('csm.name','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_contact_status')?></a></th>
			
			<th width="20%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'full_address'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="20%"><a href="javascript:void(0);" onclick="applysortfilte_contact('full_address','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_address')?></a></th>
			
			<th width="10%" class="hidden-xs hidden-sm <?php if(isset($sortfield1) && $sortfield1 == 'contact_type'){if($sortby1 == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?>" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><a href="javascript:void(0);" onclick="applysortfilte_contact1('contact_type','<?php echo $sorttypepass1;?>')"><?=$this->lang->line('common_label_contact_type')?></a></th>
           
		    <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="7%"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          	<tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php if(!empty($select_data_list) && count($select_data_list)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($select_data_list as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
							<td class="hidden-xs hidden-sm ">
											<?=!empty($row['contact_name'])?ucfirst($row['contact_name']):'';?>
										</td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['phone_no'])?$row['phone_no']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_status'])?$row['contact_status']:'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['full_address'])?ucfirst(strtolower($row['full_address'])):'';?></td>
							<td class="hidden-xs hidden-sm "><?=!empty($row['contact_type'])?ucfirst(strtolower($row['contact_type'])):'';?></td>
							<td class="hidden-xs hidden-sm text-center">
										
										<button class="btn btn-xs btn-primary" onclick="deletepopup_assign_contact('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($row['contact_name']))) ?>');"><i class="fa fa-times"></i></button>
										
										<input type="hidden" id="sortfield1" name="sortfield1" value="<?php if(isset($sortfield1)) echo $sortfield1;?>" />
										<input type="hidden" id="sortby1" name="sortby1" value="<?php if(isset($sortby1)) echo $sortby1;?>" />
										</td>
                          </tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>
         <div class="row dt-rb" id="common_tb1">
          <div class="col-sm-6">
           <div class="dataTables_paginate paging_bootstrap float-right">
           
			<div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages1();" id="perpage">
             <option value="">Rows</option>
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

</div>
		
		<script>
	function contact_search1()
	{
		
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/user_management/edit_record/<?php echo $this->router->uri->segments[4]; ?>/",
			data: {
			result_type:'ajax',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
		},
		beforeSend: function() {
					$('#common_div1').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#common_div1").html(html);
				$('#common_div1').unblock(); 
			}
		});
		return false;
	}
	
	
	 $(document).ready(function(){
		  $('#searchtext1').keyup(function(event) 
		  {
			  if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}
				
				if (event.keyCode == 13) {
						contact_search1();
				}
			return false;
		  });
	});
	
	function clearfilter_contact1()
	{
		$("#searchtext1").val("");
		contact_search1();
	}
	
	function changepages1()
	{
		contact_search1();	
	}
	
  	function applysortfilte_contact1(sortfilter1,sorttype1)
	{
		
		$("#sortfield1").val(sortfilter1);
		$("#sortby1").val(sorttype1);
		contact_search1();
	}
	
	function check_assign_contact()
	{      
			var boxes = $('input[name="check[]"]:checked');
			var user_id = $('#slt_user_type').val();
			if(user_id == '')
			{
				alert('Please select user')
				$('#slt_user_type').focus();
				return false;
			}
			else if(boxes.length == '0')
			{
				alert('Please select contacts')
				$('#selecctall').focus();
				return false;
			}
			else
			{
				assign_contact();
			}
						
	} 
	function assign_contact()
	{      
		var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  
				  i++;
			});
		var user_id = $('#slt_user_type').val();
			
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').'user_management/assign_contact';?>",
			url: "<?php echo base_url();?>admin/user_management/edit_record/<?php echo $this->router->uri->segments[4]; ?>/",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'user_id':user_id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/user_management/edit_record/<?php echo $this->router->uri->segments[4]; ?>/",
					data: {
					result_type:'ajax',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val()
				},
				beforeSend: function() {
							$('#common_div1').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div1").html(html);
						$('#common_div1').unblock(); 
					}
				});
				return false;
			}
		});
			
			
	}
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
	
	$('body').on('click','#common_tb_u a.paginclass_A',function(e){
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
		 


