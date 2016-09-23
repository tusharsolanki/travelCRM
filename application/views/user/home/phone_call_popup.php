<?php
/*
	@Description: Emails campaign add/edit page
    @Author: Sanjay Chabhadiya
    @Date: 06-08-2014

*/
// "phpuploader/include_phpuploader.php";

//require_once "phpuploader/ajax-attachments-handler.php";

$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
if(!empty($finish) && $finish == 1)
{
?>
<script>
	parent.$('.close_call_popup').trigger('click');
</script>
<?php } ?>

<style>
.ui-multiselect{width:100% !important;}

#sidebar{ display:none;}
#header,#site-logo,.dropdown,#footer,#back{ display:none !important;}
#content{ margin-left:0;}
</style>
<div id="">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?= $this->config->item('user_base_url'); ?>dashboard/phone_call_popup/<?=$this->uri->segment(4)?>/<?=$this->uri->segment(5)?>" novalidate data-validate="parsley">
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
          <?php if(!empty($editRecord[0]['interaction_id']) && $editRecord[0]['is_done'] == '0')
		   		echo $this->lang->line('previous_interaction_not_complete');
		   ?>
           <div class="row">
           <div class="col-sm-12">
		   <div class="row">
             <div class="col-sm-6 form-group">
              <label for="text-input"></span> Telephone : </label>
              <input id="phone_no" name="phone_no" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['phone_no'])?$editRecord[0]['phone_no']:''?>" readonly="readonly" >
             </div>
             <div class="col-sm-4 form-group">
              <label for="text-input float_left">Name : </label>
              <input id="contact_name" name="contact_name" class="form-control parsley-validated" type="text" value="<?=!empty($editRecord[0]['contact_name'])?htmlentities($editRecord[0]['contact_name']):''?>" readonly="readonly" >
             </div>
             <div class="col-sm-2 form-group">
             <?=!empty($uri_segment)?$uri_segment+1:'1';?>/<?=!empty($total_row)?$total_row:'0';?>
             </div>
            </div>
            <?php if(!empty($editRecord[0]['calling_script'])){ ?>
			<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input">Script: </label>
               <textarea name="message" readonly="readonly" id="message" class="form-control parsley-validated" ><?=!empty($editRecord[0]['calling_script'])?$editRecord[0]['calling_script']:'';?></textarea>			  
                  <script type="text/javascript">
					CKEDITOR.replace('message',
					 {
						fullPage : false,
						readOnly:true,
						//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
						
						baseHref : '<?=$this->config->item('ck_editor_path')?>',
						filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
						filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
					}, {width: 200});												
				</script>
             </div>
            </div>
            <?php } ?>
            <div class="row">
             <div class="col-sm-6 form-group">
              <label for="text-input">Notes : </label>
              <textarea name="notes" id="notes" class="form-control parsley-validated" ></textarea>
             </div>
             <div class="col-sm-6 form-group">
             <label for="text-input">Disposition : </label>
             <?php if(!empty($contact_disposition_master) && count($contact_disposition_master) > 0) { 
			 		$i=0; // pr($contact_disposition_master);
			 			foreach($contact_disposition_master as $row) { ?>
             	<input type="radio" <?php if($i==0) { echo 'checked=checked'; } $i++; ?> name="disposition" id="disposition_<?=$row['id']?>" value="<?=$row['id']?>" onclick="addtxt(this.value);" />&nbsp; <?=$row['name']?> &nbsp; <br />
			 <?php 
             			}
					}
			 ?>
             </div>
              <div class="col-sm-6 form-group">
             </div>
             <div class="col-sm-4 form-group disposition" style="display:none;">
              <label for="text-input"></span> Date : </label>
              <input id="selecteddate" name="selecteddate" class="form-control" type="text" value="<?=date('Y-m-d',strtotime(date('Y-m-d')."+1 Days"))?>">
             </div>
            </div>
            </div>
          		<div class="col-sm-12 text-center">		
                <input type="hidden" id="uri_segment" name="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>"   />
                <input type="hidden" id="finish" name="finish" value="0"   />
                <?php if(!empty($uri_segment)){?>
                	<input type="submit" class="btn btn-secondary" id="" value="Back" title="Back" name="backbtn" /> <?php } ?>
               <?php if(empty($editRecord[0]['interaction_id']) ||(!empty($editRecord[0]['interaction_id']) && $editRecord[0]['is_done'] == '1')) { ?>
                <input type="submit" class="btn btn-secondary <?php if(($uri_segment) == ($total_row-1)) echo 'save_and_finish'; ?>" id="Save and Next" value="<?php if(($uri_segment) == ($total_row-1)) echo 'Save and Finish'; else echo 'Save and Next'; ?>" title="Save and Next" name="submitbtn" onclick="return validation();" />
               <?php } ?>
            	<?php if(($uri_segment+1) != $total_row) { ?>    
                <input type="submit" class="btn btn-secondary" id="nextbtn" value="Next" title="Next" name="nextbtn" />
                <?php } ?>
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
</div>
<script>
$('.save_and_finish').click(function (){
	$('#finish').val(1);
	//parent.$('.close_contact_select_popup').trigger('click');
});

function addtxt(value)
{
	if(value == 3)
		$(".disposition").show();
	else
		$(".disposition").hide();
	
	$( "#selecteddate" ).datepicker({
		//showOn: "both",
		<?php if(!empty($dt) && $dt > date('Y-m-d')) { ?>
		minDate:0,
		<?php } else { ?>
		minDate:1,
		<?php } ?>
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+1",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'yy-mm-dd',
		buttonImageOnly: false
	});
}

function validation()
{
	if(($("#disposition_3").is(":checked")) && $("#selecteddate").val().trim() == '')
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select date "+"<strong></strong>",
		'buttons': {'ok'	: {
				'class'	: 'btn_center alert_ok',	
				'action': function(){
					$('#selecteddate').focus();
		}},  }});
		return false;
	}
	else
		return true;
}
</script>