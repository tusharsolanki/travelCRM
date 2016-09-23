<?php
/*
    @Description: Template add/edit page
    @Author: Kaushik Valiya
    @Date: 17-09-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
?>
<div id="content" class="content_nomar">
  <div id="content-header">
   <h1><?=$this->lang->line('reset_password');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet portlet1">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?=$this->lang->line('reset_password');?> </h3>
       
	  </div>
    
      <div class="portlet-content">
<div class="">
<div class="tab-content" id="myTab1Content">
 
 <div class="tab-pane fade in active" id="home">
  
  <!--<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="" accept-charset="utf-8" action="<?php echo base_url();?><?php echo $viewname?>" >
-->  
<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('base_url').'reset_password/reset_password_link/reset_password_link_control/reset_password/';?>" novalidate >
   <div class="row"> 
   		<div class="col-sm-8">
        <div class="row">
             <div class="col-sm-12 form-group pull-center">
              <label for="validateSelect">Password <span class="mandatory_field margin-left-5px">*</span></label>
               <input id="txt_npassword" name="txt_npassword" class="form-control parsley-validated" type="password" data-required="true">
             </div>
             <div class="col-sm-12 form-group">
             <label for="validateSelect">Confirm Password <span class="mandatory_field margin-left-5px">*</span></label>
               <input id="txt_cpassword" name="txt_cpassword" class="form-control parsley-validated" type="password" data-equalto="#txt_npassword" data-required="true">
        	 </div>
		</div>
  		</div>
	</div>
	<div class="row">
        <div class="col-sm-8 pull-left text-center margin-top-10">
        <input id="hiddan" name="id" type="hidden" value="<?php echo $this->uri->segment(4); ?>" >
    		<button  type="submit" class="btn btn-secondary" >Save</button>
      </div>
     </div>
  
</form>
</div>
</div>
</div>
 </div>
    </div>
   </div>
  </div>
  
 </div>
<script type="text/javascript">

function reset_password()
{
	//alert("<?=base_url().'unsubscribe/'.$viewname?>/unsubscribe");
	var id = '<?php echo $this->uri->segment(4); ?>';
	var pass = $("#txt_npassword").val();
	//alert(pass);
	if($("#txt_npassword").val().trim() != '')
	{
		$.ajax({
			type: "POST",
			url: "<?=base_url().'reset_password/'.$viewname?>/reset_password",
			data: {
			result_type:'ajax',email_id:$("#email_to").val()
		},
		beforeSend: function() {
					$('#home').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$(".com_div").html(html);
				$('#home').unblock();
			}
		});
	}
	else
	{
		$('.parsley-form').submit();
	}
	//return false;
}
</script>