<!--/*
        @Description: View for add Contact
        @Author: Mit Makwana
        @Input: -
        @Output: -
        @Date: 07-05-14
        */
-->
<?php
	
	$viewname = $this->router->uri->segments[2]; 
  	isset($editRecord) ? $loadcontroller='update_user' : $loadcontroller='insert_user';
  		$path_user = $viewname."/".$loadcontroller;
		?>

<div id="content" class="contact-masters">
  <div id="content-header">
   <h1>General Configuration Masters</h1>
  </div>
  <div id="content-container">
  	
	<div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
      
       <h3> <i class="fa fa-tasks"></i>General Configuration</h3>  
       	 <span class="float-right margin-top--15"><a class="btn btn-secondary" title="Back" href="<?=base_url('admin/livewire_configuration');?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content" style="max-height:none;">
		
		<div class="">
		  <div class="chart_bg1 tbl_border">
			<!-- EMAIL TYPE-->
			<form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_user?>" class="form parsley-form" >
			  <div class="col-md-6">
			  <div class="mrg-bottom-40">
			  <div class="portlet-header">
					<h3>
						 <?php echo $this->lang->line('common_label_user_type')?>
					</h3>
			  </div>
			  <div class="portlet-content">
			  <table width="100%" class="iconment_title_in" >
				<tr >
				  <th><?php echo $this->lang->line('common_label_title')?></th>
				  <th><?php echo $this->lang->line('common_label_action')?></th>
				</tr>
				<?php
					if(!empty($user_type) && count($user_type)>0){
					foreach($user_type as $row)
					{   
					?>
				<tr>
				  <td colspan="2">
				  	<div class="space"></div>
					<div id="flash"></div>
					<div id="show"></div>
				  </td>
				</tr>
				<tr>
				  <td class="text_capitalize" width="70%">
					<input type="text" class="form-control parsley-validated" class="form-control parsley-validated" name="user" id="user_<?=$row['id']?>" value="<?php echo  $row['name'] ?>" /> 
				  </td>
				  <td>
					<a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
					<a href="javascript:void(0);" class="btn btn-xs btn-primary"onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_user_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
					
				  </td>
				</tr>
				<?php }}?>
			  <input type="hidden" id="user_id" name="user_id" class="user_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
				<tr>
				  <td colspan="2"><div id="p_scents" class="form-group">
				  <?php if(empty($user_type) && count($user_type) == 0){ ?>
					  <p>
						<label for="p_scnts">
						<input type="text" class="form-control parsley-validated" data-required="required" name="user_type[0]" id="user_type[0]" value="<?=isset($editRecord) ? $editRecord[0]['name'] : ''?>" />
						</label>
						</p>
					  <?php  } ?>
					</div></td>
				</tr>
				<tr>
					<td><a href="javascript:void(0)" id="addScnt" title="Add User Type" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add User Type</a></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td><input type="submit" style="width:auto;" class="btn btn-primary margin_tops" value="Save" name="type"></td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			  </div>
			  </div>
			  </div>
			</form>
			
			
			<!-- DISPOSITION TYPE-->
			
			
			
			
		  </div>
		</div>
		
	  </div>
	</div>
		
	
  </div>
	
 </div>
<script type="text/javascript">
    $(document).ready(function (){
            //$('#<?php echo $viewname;?>').bValidator();
    });
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function getemail()
    {
        var userid = $("#user").val();
        $.ajax({
            type: "post",
            data: {'user':userid,
            },
            url: '<?php echo $this->config->item('admin_base_url')?>/user/getemail', 
            success: function(msg1) 
            {
                if(msg1 != '')
                {
                    $("#emailexist").val(msg1);
                    $("#user").focus();
                }
                else
                    $("#emailexist").val(msg1);
            }
        });	
    return false;
    }
</script>

<!-- ================== START Multipal Input Box Script ================== -->
<!-- ==== Email INPUT ADD ===== -->
<script type="text/javascript">

$(function() {

        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size();
		$('body').on('click', '#addScnt', function(){
        $('<p><label for="p_scnts"><input type="text" class="form-control parsley-validated" data-required="required" name="user_type[' + i +']" id="user_type[' + i +']"/></label><a href="javascript:void(0)" id="remScnt" class="btn btn-xs btn-primary margin_tops1"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });
        
		$('body').on('click', '#remScnt', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});
</script>

<!-- ==== Email UPDATE AJAX ===== -->
<script type="text/javascript">
function getsubmit(id)
{
	var user = $("#user_"+id).val();
	if(user=='' && id=='')
	{
		alert("Enter text..");
		$("#user").focus();
	}
	else
	{
		//alert(id);
		$("#flash").show();
		$("#flash").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
		$.ajax({
		type: "POST",
		url: '<?=base_url()?>admin/<?=$viewname;?>/update_user',
		data: { user_type:user,user_id:id },
		cache: true,
		success: function(html)
		{
			
			$("#show").after(html);
			//$("#flash").hide();
			$("#user").focus();
		}  
		});
	}
	return false;
}
</script>
