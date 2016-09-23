<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
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
            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" role="columnheader" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <th><?=$this->lang->line('common_label_name')?></th>
            <th><?=$this->lang->line('common_label_email')?></th>
			<th><?=$this->lang->line('common_label_phone')?></th>
			<th></th>
			<th width="7%"><?php echo $this->lang->line('common_label_action')?></th>
           </tr>
           </thead>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
           <?php 
		   		if(!empty($counter_data)){
		   			for($j=0;$j<count($counter_data);$j++)
					{
						$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
						if(!empty($datalist[$j]))
						{
							for($k=0;$k<count($datalist[$j]);$k++)
							{ ?>
								<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> id="tr_<?php echo $datalist[$j][$k]['id'];?>" > 
										<td class="text-center">
                                         <div class="" >
                                          <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $datalist[$j][$k]['id'] ?>">
                                         </div>
                                        </td>
										<td class="hidden-xs hidden-sm ">
											<?=!empty($datalist[$j][$k]['contact_name'])?ucfirst(strtolower($datalist[$j][$k]['contact_name'])):'';?>
										</td>
										<td class="hidden-xs hidden-sm "><?=!empty($datalist[$j][$k]['email_address'])?$datalist[$j][$k]['email_address']:'';?></td>
										<td class="hidden-xs hidden-sm "><?=!empty($datalist[$j][$k]['phone_no'])?$datalist[$j][$k]['phone_no']:'';?></td>
										<td class="text-center">
											<div class="">
											  <input type="checkbox" class="merge_checkbox" id="contact_data" name="contact_data[]" value="<?=!empty($datalist[$j][$k]['id'])?$datalist[$j][$k]['id']:'';?>" data-group="group<?=$j?>" data-id="<?=!empty($datalist[$j][$k]['id'])?$datalist[$j][$k]['id']:'';?>" />
											</div>
										</td>
										<?php if($k==0){ ?>
										<td class="hidden-xs hidden-sm text-center valign-middle" rowspan="<?php if(!empty($counter_data[$j]['rowspan']))echo $counter_data[$j]['rowspan'];?>">
											<a data-group="group<?=$j?>" class="btn btn-success merge_btn" data-toggle="modal" data-target=".bs-example-modal-lg">Merge</a>
											
										</td>
										<?php } ?>
                            </tr>
					<?php   }
						}
					}
				}
				else
				{?>
					<tr>
		  	<td colspan="10" align="center"><?=$this->lang->line('user_general_noreocrds')?></td>
		  </tr>
				<?php }?>
          </tbody>
         </table>
	 
<script type="text/javascript">
	
	$('body').on('click','.merge_btn',function(e){
	
		btngroup = $(this).attr('data-group');
		
		var contactlist = Array();
		var arraydatacount = 0;
		var grouperror = 0;
		var counterror = 0;
		
		$('.merge_checkbox:checked').each(function() {
		
			if($(this).attr('data-group') != btngroup)
				grouperror = 1;
			else
				contactlist[arraydatacount++] = $(this).attr('data-id');
			
		});
		
		if(contactlist.length < 2)
			counterror = 1;
		
		if(grouperror == 1)
		{
			alert('Please select contacts from the same group.');
			e.preventDefault();
			return false;
		}
		else if(counterror == 1)
		{
			alert('Please select at least two contacts from the same group.');
			e.preventDefault();
			return false;
		}
		else
		{
			//alert(JSON.stringify(contactlist));
			$(".merge_popup_main_div .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/get_merge_contact_data_ajax';?>",
			//dataType: 'json',
			//async: false,
			data: {'contacts':contactlist},
			success: function(html){
			
					$(".merge_popup_main_div .modal-body").html(html);
					
				},
			error: function(jqXHR, textStatus, errorThrown) {
			  	//console.log(textStatus, errorThrown);
			  	$(".merge_popup_main_div .modal-body").html('Something went wrong.');
			}
			});
			
		}
	});
	
	
	 $('#selecctall').click(function(event) {  //on click
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
	
	function delete_all()
		{
			//var checkall = $(".mycheckbox").val();
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  //alert(this.value);
				  myarray[i]=this.value;
				  i++;
			});
			//alert(myarray);
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray},
			success: function(data){
			//alert('hi');
				
				window.location.reload();
				
				$(boxes).each(function(){
  				  //alert(this.value);
					 $("#tr_"+this.value).remove();
				});
			
			}
		});
		}
		function deletepopup1()
		{      
			var boxes = $('input[name="check[]"]:checked');
   			if(boxes.length == '0')
			{return false;}
			
			$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to delete record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			delete_all();
			}},'No'	: {'class'	: 'special'}}});
} 


	
</script>