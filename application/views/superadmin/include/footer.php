<?php 
$this->session->set_userdata('message_session',array('msg'  => ''));
?>
</div>
<!-- #wrapper -->

<footer id="footer">
 <ul class="nav pull-right">
  <li> Copyright © <?php echo "2014-".date('Y')?></li>
 </ul>
</footer>

<script src="<?=$this->config->item('js_path')?>bootstrap.js"></script> 
<script src="<?=$this->config->item('js_path')?>App.js"></script> 
<script src="<?=$this->config->item('js_path')?>parsley.js"></script> 
<script src="<?=$this->config->item('js_path')?>jquery-ui-1.9.2.custom.min.js"></script> 
<script src="<?=$this->config->item('js_path')?>jquery_002.js"></script>
<script src="<?=$this->config->item('js_path')?>ajaxupload.3.5.js"></script>

<a style="display: none;" href="#top" id="back-to-top"><i class="fa fa-chevron-up"></i></a>
</body>
</html>
<script>
/*$(document).ready(function(){
	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) 
	  {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	}); 

});*/
</script>