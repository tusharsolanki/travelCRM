<?php
/*
    @Description: email Signature add
    @Author: Ruchi Shahu
    @Date: 02-08-2014

*/?>
<?php 
$head_title = "Email Signature";
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>

<div id="content">
  <div id="content-header">
    <h1><?=$this->lang->line('email_signature_header');?></h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
	  
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('email_signature_add_table_head');}else{ echo $this->lang->line('email_signature_edit_table_head'); }?> </h3>
			<span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
          </div>
          <!-- /.portlet-header -->
          <div class="portlet-content">
            <div class="col-sm-8">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?= $this->config->item('user_base_url')?><?php echo $path?>" >
                <div class="form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('signature_name');?>
                  </label>
                  <input id="signature_name" name="signature_name" class="form-control parsley-validated" type="text" data-required="required" value="<?= !empty($editRecord[0]['signature_name'])?htmlentities($editRecord[0]['signature_name']):'';?>">
                </div>
                <div class="form-group">
                  <label for="select-multi-input">
                  <?=$this->lang->line('full_signature');?>
                  </label>
                  <!--	<input id="full_signature" name="full_signature" class="form-control parsley-validated" type="text" data-required="required" value="<?= !empty($editRecord[0]['full_signature'])?$editRecord[0]['full_signature']:'';?>">	-->
                  <textarea name="full_signature" id="full_signature" ><?=!empty($editRecord[0]['full_signature'])?$editRecord[0]['full_signature']:'';?>
</textarea>
                  <script type="text/javascript">
												CKEDITOR.replace('full_signature',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});														
											</script>
                </div>
                <div class="form-group text-center">
                  <input type="hidden" name="id" value="<?= !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" />
                  <input type="submit" class="btn btn-secondary-green" value="Save Email Signature" name="submitbtn" onclick="return showloading();" />
                  <a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a> </div>
              </form>
            </div>
          </div>
          <!-- /.portlet-content -->
        </div>
      </div>
    </div>
  </div>
  <!-- #content-header -->
  <!-- /#content-container -->
</div>
<!-- #content -->
<script type="text/javascript">
function showloading()
 {
	if ($('#<?php echo $viewname?>').parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
 }
 </script>