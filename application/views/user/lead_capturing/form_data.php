<?php
		 /*if(!empty($viewdata))
		 {
			echo $viewdata[0]['lead_form'];
		 }*/
?>

<style type="text/css">
#common_div_form table{ width:75%;}
#common_div_form table tr td{ vertical-align:top;}
#common_div_form table tr td label{ float:left!important;}
#common_div_form table tr td textarea{ width:96%!important;}
#common_div_form table tr td .txt_date{ width:66%!important; float:left;background:#eee !important;}
#common_div_form .ui-datepicker-trigger{ border:none; background:none; padding:0;}
#common_div_form table tr td:nth-child(3) {  
  display:none!important;
  width:0px!important;
}
.val {color:#f00;}
</style>
<?php 
$viewname = $this->router->uri->segments[1];
$formAction = !empty($viewdata)?'insert_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
//pr($form_data);exit;
?>
<div id="common_div_form" align="center" class="col-sm-12">
<form class="form parsley-form" data-validate="parsley" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('base_url')?><?php echo $path?>" novalidate style="background-color:<?=!empty($viewdata[0]['bg_color'])?$viewdata[0]['bg_color']:'#FFFFFF';?>; height:<?=!empty($viewdata[0]['form_height'])?$viewdata[0]['form_height']."px":'700'."px";?>; width:<?=!empty($viewdata[0]['form_width'])?$viewdata[0]['form_width']."px":'700'."px";?>;">

<input id="id" name="id" type="hidden" value="<?php if(!empty($viewdata[0]['form_widget_id'])){ echo $viewdata[0]['form_widget_id']; }?>">     
<div>
<h2>
<?php
	if(isset($viewdata[0]['show_title']) && $viewdata[0]['show_title'] == 1)
		echo !empty($viewdata[0]['form_title'])?ucfirst(strtolower($viewdata[0]['form_title'])):'';
?>
</h2>
</div>
<div>
<?php
	if(isset($viewdata[0]['show_desc']) && $viewdata[0]['show_desc'] == 1)
		echo !empty($viewdata[0]['form_desc'])?ucfirst(strtolower($viewdata[0]['form_desc'])):'';
?>
</div>

<?php
if(!empty($viewdata))
{
	echo $viewdata[0]['lead_form'];
}?>
</form>
</div>
<script type="text/javascript">
$( ".txt_date" ).datepicker({
        showOn: "button",
        changeMonth: true,
        changeYear: true,
        //yearRange: "-100:+2",
        //minDate: "0",
        buttonImage: "<?=base_url('images');?>/calendar.png",
        dateFormat:'mm/dd/yy',
        buttonImageOnly: false
    });
$('body').on('keypress', '#common_div_form input,#common_div_form textarea', function(e){
	e.preventDefault();
});

$('body').on('click', '.file_type', function(e){
	e.preventDefault();
});
</script>