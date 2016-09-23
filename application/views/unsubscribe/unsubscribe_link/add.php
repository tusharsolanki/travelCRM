<?php 
$viewname = $this->router->uri->segments[2];
$fullwidget_id = $this->router->uri->segments[3];
//$fullwidget_id = $this->input->post('id');
if(!empty($fullwidget_id))
{
	$db_name = explode("--",$fullwidget_id);
	if(!empty($db_name[0]))
		$database_name = base64_decode(urldecode($db_name[0]));
	if(!empty($db_name[1]))
		$email_id = base64_decode(urldecode($db_name[1]));
}
//pr($db_name);exit;
?>

<div class="">
<div class="tab-content" id="myTab1Content">
 
 <div class="tab-pane fade in active" id="home">
  
  <!--<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="" accept-charset="utf-8" action="<?php echo base_url();?><?php echo $viewname?>" >
-->  
  <div id="common_div" class="unsubscribe_message com_div">
			
  </div>
   <div class="row"> 
   <div class="col-sm-8">
   <div class="row">
	 <div class="col-sm-12 form-group">
	  <label for="text-input">Email ID: </label>
	  <input id="email_to" name="email_to" data-type="email" class="form-control parsley-validated" type="text" value="<?=!empty($email_id)?$email_id:'';?>" placeholder="Email id"  data-required="true">
      <input id="database_name" name="database_name" type="hidden" value="<?=!empty($database_name)?$database_name:'';?>" placeholder="Email id"  data-required="true">
      
	 </div>
	</div>
  </div>
	</div>
	<div class="row">
  
  <div class="col-sm-8 pull-left text-center margin-top-10">
	<!--<input type="submit" class="btn btn-secondary" value="Subscribe" onclick="" name="submitbtn" />-->
	<button onclick="return unsubscribe_email()" type="button" class="btn btn-secondary" >Save</button>
	<!--<button class="btn btn-secondary" value="Unsubscribe" onclick="return unsubscribe()" name="submitbtn" /></button>-->
	</div>
 </div>
  
 </div>
</div>
</div>
</div>

<script type="text/javascript">

function unsubscribe_email()
{
	//alert("<?=base_url().'unsubscribe/'.$viewname?>/unsubscribe");
	if($("#email_to").val().trim() != '')
	{
		$.ajax({
			type: "POST",
			url: "<?=base_url().'unsubscribe/'.$viewname?>/unsubscribe",
			data: {
			result_type:'ajax',email_id:$("#email_to").val(),user_name:$("#database_name").val()
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