<?php 
$viewname = $this->router->uri->segments[2];
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>

<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('cms_add_head');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('cms_add_head');}
                        else if(!empty($insert_data)){ echo $this->lang->line('cms_add_head'); } 
                        else{ echo $this->lang->line('cms_edit_head'); }?> </h3>
                        <span class="float-right margin-top--15"><a href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>" class="btn btn-secondary" title="Back">Back</a> </span>
                    </div>
    
                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="home">
             
                                    <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?= $this->config->item('admin_base_url')?><?php echo $path?>" >
                                        
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_menu_title')?><span style="color:#F00">*</span></label>
                                                    <input id="menu_title" name="menu_title" class="form-control parsley-validated" type="text" value="<?= !empty($editRecord[0]['menu_title'])?htmlentities($editRecord[0]['menu_title']):'';?>" data-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_page_title')?><span style="color:#F00">*</span></label>
                                                    <input id="title" name="title" class="form-control parsley-validated" type="text" value="<?= !empty($editRecord[0]['title'])?htmlentities($editRecord[0]['title']):'';?>" data-required="true">
                                                </div>
                                            </div>
                                            <?php /*
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_page_type')?><span style="color:#F00">*</span></label>
                                                    <select class="form-control parsley-validated" name="page_type" id="page_type" data-required="true">
                                                        <option value="">Select Page Type</option>
                                                        <option <? if((!empty($editRecord[0]['page_type'])) && ($editRecord[0]['page_type']=='1')){echo "Selected"; }?> value="1">CMS Page</option>
                                                        <option <? if((!empty($editRecord[0]['page_type'])) && ($editRecord[0]['page_type']=='2')){echo "Selected"; }?> value="2">Artical Page</option>
                                                    </select>
                                                </div>
                                            </div>
                                             */ ?>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_slug')?><span style="color:#F00">*</span> </label>
                                                    <input id="page_url" name="page_url" class="form-control parsley-validated"  data-required="true" value="<?= !empty($editRecord[0]['page_url'])?htmlentities($editRecord[0]['page_url']):'';?>" onblur="check_slug();" >
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_domain')?><span style="color:#F00">*</span></label>
                                                    <select class="form-control parsley-validated" name="domain_name" id="domain_name" data-required="true" onchange="check_slug();" >
                                                        <option value="">Select Domain</option>
                                                        <?php
                                                        if(!empty($assigned_domain_list))
                                                        {
                                                            foreach($assigned_domain_list as $domainlist)
                                                            { ?>
                                                              <option <? if((!empty($editRecord[0]['domain_name'])) && ($editRecord[0]['domain_name']==$domainlist['domain'])){echo "Selected"; }?> value="<?=$domainlist['domain']?>"><?=$domainlist['domain']?></option>  
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_description');?></label>
                                                    <textarea name="description" id="description" class="description" ><?=!empty($editRecord[0]['description'])?$editRecord[0]['description']:''; ?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('description',
                                                         {
                                                            fullPage : false,

                                                            //toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],

                                                            baseHref : '<?=$this->config->item('ck_editor_path')?>',
                                                            filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
                                                            filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
                                                        }, {width: 200});														
                                                    </script> 
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_meta_title')?></label>
                                                    <input id="meta_title" name="meta_title" class="form-control parsley-validated" type="text" value="<?= !empty($editRecord[0]['meta_title'])?$editRecord[0]['meta_title']:'';?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_meta_keyword')?></label>
                                                    <textarea class="form-control parsley-validated" rows="5" cols="10" name="meta_keyword"><?= !empty($editRecord[0]['meta_keyword'])?$editRecord[0]['meta_keyword']:'';?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="select-multi-input"><?=$this->lang->line('cms_meta_desc')?></label>
                                                    <textarea class="form-control parsley-validated" rows="5" cols="10" name="meta_description"><?= !empty($editRecord[0]['meta_description'])?$editRecord[0]['meta_description']:'';?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    
                                                </div>
                                            </div>
                                            <div class="col-sm-12 pull-left text-center margin-top-10">
                                                <input type="hidden" name="id" value="<?= !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" />
                                                <input type="submit" id="submit" class="btn btn-secondary-green" value="Save" title="Save" onclick="showloading();" name="submitbtn" />
                                                <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
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
</div>

 <script type="text/javascript">
$(document).ready(function(){
});
 
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function showloading()
{
   if ($('#<?php echo $viewname?>').parsley().isValid()) {
       $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
   }
}
function check_slug(page_url)
{
    var domain_name = $('#domain_name').val();
    var page_url = $('#page_url').val();
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/check_slug';?>",
        dataType: 'json',
        async: false,
        data: {'page_url':page_url,'id':"<?=!empty($editRecord[0]['id'])?$editRecord[0]['id']:''?>",'domain_name':domain_name},
        beforeSend: function() {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'})
        },
        success: function(data){
            if(data == '1')
            {
                $('#page_url').focus();
                $('#submit').attr('disabled','disabled');

                $.confirm({'title': 'Alert','message': " <strong>Page URL already exists. Try with different name. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
                    $('#page_url').focus();
                    $('#submit').removeAttr('disabled');
                    $.unblockUI();
                }}}});

            }
            else
                $.unblockUI();
        }
    });
    return false;
}
 
</script>