<style type="text/css">
p{font-family:Verdana, Geneva, sans-serif;}
.adminbtn { background: none repeat scroll 0 0 #00B050; color: #FFFFFF; cursor: pointer; font-size: 12px; line-height: 18px; padding: 5px 12px; border:0; margin:0px !important; }
.title { text-align:left; font-size:15px; font-weight:bold; border-bottom:#00B050 solid 3px; font-family:Arial, Helvetica, sans-serif; padding:7px 0; margin:0px; text-transform:capitalize; color:#000; }
.textfield { width:100%; border:#CCC solid 1px;padding:5px;}
</style>
<div class="accountupgrade bgcontent" style="padding:0 2%; width:96%;">
  <div class="accountform">
    <h2 class="title">Login with google</h2>
    <?php if(!empty($message)){ ?>
        <p>
        	<?=$message?>
        </p>
    <?	} 	else{?>
    <form class="form parsley-form" action="<?=base_url('admin/calendar/google_connection');?>" name="commonForm" id="commonForm" method="post" >
      <p class="form-group">
        <span>Enter your email id</span><input id="email_id" name="email_id" class="textfield form-control parsley-validated" type="text" value="" data-required="true">
      </p>
      <p class="form-group">
       <span>Enter your password</span><input id="password" name="password" class="textfield form-control parsley-validated" type="password" value="" data-required="true">
      </p>
      <div class="alignnone"> </div>
      <div class="survey_btn">
        <input type="submit" class="button adminbtn backto_btn" name="submit" id="submit" value="Submit" style="margin-left:65px;" />
      </div>
    </form>
    <? } ?>
  </div>
</div>
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>crm.css" type="text/css">
<script src="<?=$this->config->item('js_path')?>App.js"></script> 
<script src="<?=$this->config->item('js_path')?>parsley.js"></script> 