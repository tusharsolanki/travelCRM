<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>parsley.js"></script> 
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.maskedinput.js"></script> 
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.price_format.min.js"></script>
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>crm.css" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>bootstrapcrm.css" type="text/css">
<style type="text/css">
#common_div table{ width:75%;}
#common_div table tr td{ vertical-align:top;}
#common_div table tr td label{ float:left!important;}
#common_div table tr td textarea{ width:96%!important;}
#common_div table tr td .txt_date{ width:66%!important; float:left;}
#common_div .ui-datepicker-trigger{ border:none; background:none; padding:0;}
#common_div table tr td:nth-child(3) {  
  display:none!important;
  width:0px!important;
}
.val {color:#f00;}
span{ font:14px/1.7em "Open Sans","trebuchet ms",arial,sans-serif!important; }

</style>

<?php $this->load->view('google_analytics'); ?>

<?php 
$viewname = $this->router->uri->segments[1];
$formAction = !empty($form_data)?'insert_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
//pr($form_data);exit;
?>
<div id="common_div" align="center" class="col-sm-12">
<form enctype="multipart/form-data" class="form parsley-form" data-validate="parsley" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('base_url')?><?php echo $path?>" novalidate style="background-color:<?=!empty($form_data[0]['bg_color'])?$form_data[0]['bg_color']:'#FFFFFF';?>; height:<?=!empty($form_data[0]['form_height'])?$form_data[0]['form_height']:'700';?>; width:<?=!empty($form_data[0]['form_width'])?$form_data[0]['form_width']:'700';?>;">

<input id="id" name="id" type="hidden" value="<?php if(!empty($form_data[0]['form_widget_id'])){ echo $form_data[0]['form_widget_id']; }?>">     
<div>
<h2>
<?php 
	if(isset($form_data[0]['show_title']) && $form_data[0]['show_title'] == 1)
		echo !empty($form_data[0]['form_title'])?$form_data[0]['form_title']:'';
?>
</h2>
</div>
<div>
<?php
	if(isset($form_data[0]['show_desc']) && $form_data[0]['show_desc'] == 1)
		echo !empty($form_data[0]['form_desc'])?$form_data[0]['form_desc']:'';
?>
</div>

<?php
if(!empty($form_data))
{
	echo $form_data[0]['lead_form'];
}?>
<input type="submit" id="submit" name="submit" value="Submit" class="btn howler">
</form>
</div>

<script type="text/javascript">
$('#submit').click(function(){
	var cnt=0;
$( "input[type='file']" ).each(function( index ) {
	
	var val=$(this).val();
	var format=$(this).attr('file-format');
	var filed=format.split(',');
	if(val != '')
	{
		
		var ext = $(this).val().split('.').pop().toLowerCase();
		if($.inArray(ext, filed) == -1) 
		{
			var divchk=$(this).next().html();
			if(divchk == undefined || divchk == '')
			{
			$(this).after('<ul id="" class="parsley-error-list ullist" style="display: block;"><li class="required" style="display: list-item;">Upload only '+format+'.</li></ul>');
			  cnt++;
			}
			if(divchk == '<li class="required" style="display: list-item;">Upload only '+format+'.</li>')
			{
				cnt++;
			}
		}
		else
		{
				var divchk=$(this).next().html();
				if(divchk == '<li class="required" style="display: list-item;">Upload only '+format+'.</li>')
				{
				var ff=$(this).next().html();
			    $(this).next().remove('.ullist');
				//cnt--;
				}
				
		}
  //console.log( index + ": " + $( this ).text() );
	}
});
	if(cnt > 0)
	{
		var cnt=0;
		return false;
	}
	else
	{
		return true;	
	}
	});
    $(document).ready(function(){
         $('.mask_apply_class').mask('999-999-9999');
		/*  $('#<?=$viewname?>').parsley({
			  validators: {
					filemaxsize: function() {
						return {
							validate: function (val, max_megabytes, parsleyField) {
								if (!Modernizr.fileapi) { return true; }
		
								var $file_input = $(parsleyField.element);
								if ($file_input.is(':not(input[type="file"])')) {
									console.log("Validation on max file size only works on file input types");
									return true;
								}
		
								max_bytes = max_megabytes * BYTES_PER_MEGABYTE, files = $file_input.get(0).files;
								if (files.length == 0) {
									// No file, so valid. (Required check should ensure file is selected)
									return true;
								}
								return files.length == 1  && files[0].size <= max_bytes;
							},
							priority: 1
						};
					}
				},
				messages: {
					filemaxsize: "The file cannot be more than %s megabytes."
				}
				, excluded: 'input[type=hidden], :disabled'
			});*/
		
			/**
			 * Extension to Modernizer for File API support
			 */
			//window.Modernizr.addTest('fileapi', function() { return window.File && window.FileReader; });
		
			//window.BYTES_PER_MEGABYTE = 1048576;
		
			/*$('#<?=$viewname?>').on("submit", function(e) {
				e.preventDefault();
				$(this).parsley("validate");
			});*/
    });
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
    //$('.dynamic_dml').remove();
    $('.btn-xs').remove();
function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if(charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}

$('.txt_price_range_from').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});

$('.txt_price_range_to').priceFormat({
	prefix: '$',
	clearPrefix: true,
	centsLimit: 0
});
/*$(document).ready(function(){
	$( "#lead_capturing_form div span:first-child" ).each(function() {
	  var data=$(this).text();
	  alert(data);
	});
});*/
</script>