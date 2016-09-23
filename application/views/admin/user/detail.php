<!--
    @Description: survey veiw
    @Author: Jayesh Rojasara
    @Date: 07-05-14
	-->
	   <link rel="stylesheet" type="text/css" media="screen" href="<?php echo  base_url('css/admin/layout.css') ?>"/>	       <link rel="stylesheet" type="text/css" media="screen" href="<?php echo  base_url('css/admin/style.css') ?>"/>
	   <link rel="stylesheet" type="text/css" media="screen" href="<?php echo  base_url('css/admin/runtime.css') ?>"/>
<style>
table th, table td {
	padding: 8px;
	vertical-align: middle;
	font-size:12px;
	font-family:Arial, Helvetica, sans-serif;
	border-bottom:none;
}
.icoachinput input, select, textarea {
	width:250px;
	border:1px solid #CCCCCC;
}
section#main {background:none !important; }
.module{margin:2px 10px !important;}
</style>
<section id="main">

<div class="clear"></div>
<article class="module width_full" style="padding-bottom:20px;width:126%;">
  <div id="body">
    <?php isset($surveysprofile) ? $loadcontroller='update_data' : $loadcontroller='insert_data';
        $path = "surveys/".$loadcontroller;
    ?>
    <header>
      <h3 class="tabs_involved">
        <?=isset($editRecord)?$this->lang->line('common_view_title')." ".$this->lang->line('common_label_survey'):$this->lang->line('common_view_title')." ".$this->lang->line('common_label_survey')?>
      </h3>
      
    </header>
    <?php
						$viewName = $this->router->uri->segments[2];
						$loadController = !empty($editRecord)?'update_data':'insert_data';
						$formAction = 'admin/'.$viewName.'/'.$loadController;
						

					if(!empty($this->router->uri->segments[4]) && $this->router->uri->segments[4]=='preview'){ ?>
   
    <? }  ?>
    <form method="post" name="surveyForm" id="surveyForm"  action="<?php echo  base_url($formAction) ?>" enctype="multipart/form-data" >
      <table border="0"  cellspacing="0" cellpadding="0" class="icoachinput">
        <tr>
          <td >&nbsp;</td>
          <td colspan="3" align="right"><? 
				$actionView = $this->router->uri->segments[2]; 
				if($actionView=="edit_record"){
					$actionNew = "edit";
				}else{
					$actionNew = "add";	
				}
			?></td>
        </tr>
        <tr>
          <td width="200" ><input type="hidden" name="id" id="id" value="<?php echo !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" />
            <?php echo $this->lang->line('survey_name');?>
           </td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['surveyName'])?$editRecord[0]['surveyName']:''?></td>
        </tr>
        <tr>
          <td colspan="3"><table id="TextBoxesGroup" cellpadding="5">
              <? $n=1; for($j=0; $j<count($questions); $j++)
				{
			?>
              <tr id="TextBoxesGroup">
                <td width="190" style="padding:8px 14px 8px 0;"><?php echo $this->lang->line('srv_your_question').' '.$n++;;?>
                  </td>
                <td > : </td>
                <td><?php echo !empty($questions[$j]['question'])?$questions[$j]['question']:''?>
                  <input type="hidden"  name="questionId[]" id="question" value="<?php echo !empty($questions[$j]['id'])?$questions[$j]['id']:''?>" />
                  <!--<input type='button' value='Add Question' id='addButton'>--></td>
              </tr>
              <? } ?>
            </table></td>
        </tr>
        <tr>
          <td ><?php echo $this->lang->line('srv_subject_email');?></td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['subjectEmail'])?$editRecord[0]['subjectEmail']:''?></td>
        </tr>
        <tr>
          <td >Add Salutation</td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['addSolution'])?$editRecord[0]['addSolution']:''?></td>
        </tr>
        <tr>
          <td >Email Text </td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['emailText'])?$editRecord[0]['emailText']:''?></td>
        </tr>
        <tr>
          <td >Icon Set</td>
          <td> : </td>
          <td> <?php 
		  	 			$match = array("id"=>$editRecord[0]['iconSetId']);
		  				 $icondata = $this->icons_model->selectRecords('',$match,'');
						 $row=$icondata[0];
									
					?>
          <table>
                      <tr>
                        <td><img width="70" height="50"  src="<?php echo  base_url($this->config->item('icon1_small_img_path').$row['image1']) ?>" /></td>
                      
                        <td><img width="70" height="50" src="<?php echo  base_url($this->config->item('icon2_small_img_path').$row['image2']) ?>" /></td>
                        <td><img width="70" height="50" src="<?php echo  base_url($this->config->item('icon3_small_img_path').$row['image3']) ?>" /></td>
                        <td><img width="70" height="50" src="<?php echo  base_url($this->config->item('icon4_small_img_path').$row['image4']) ?>" /></td>
                      </tr>
                    </table>
          
          </td>
        </tr>
        
        <tr>
          <td >Gold Star</td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['goldStar'])?$editRecord[0]['goldStar']:''?></td>
        </tr>
        <tr>
          <td >Green Star</td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['greenStar'])?$editRecord[0]['greenStar']:''?></td>
        </tr>
        <tr>
          <td >Amber Light</td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['ambarLight'])?$editRecord[0]['ambarLight']:''?></td>
        </tr>
        <tr>
          <td >Red Light</td>
          <td> : </td>
          <td><?php echo !empty($editRecord[0]['redLight'])?$editRecord[0]['redLight']:''?></td>
        </tr>
        <tr>
          <td >Company Logo</td>
          <td> : </td>
          <td> <?php
							if(!empty($editRecord[0]['companyLogo']) && file_exists($this->config->item('surveys_small_img_path').$editRecord[0]['companyLogo'])){
								?><img src="<?php echo '../../../'.$this->config->item('surveys_small_img_path').$editRecord[0]['companyLogo']?>" width="100" /> <?
							}
						
						 ?></td>
        </tr>
        </table>
    </form>
  </div>
  
</article>
</section>
<script type="text/javascript">
function delete_image(name,i)
	{
		$.confirm({
'title': 'DELETE IMAGE','message': "Are you sure to want delete image?",'buttons': {'Yes': {'class': '',
'action': function(){
			//loading('Checking');
				 //$('#preloader').html('Deleting...');
				 var id=$('#id').val();
		 $.ajax({
			type: 'post',
			data:{id:id,name:name},
			url:'<?=base_url("admin/surveys/delete_image")?>',
			success:function(msg){
					if(msg == 'done')
					{
					
			      $('#uploaded_preview'+i).html('<span class="noimage"></span>');
				  showSuccess('Image successfully deleted',5000);
				  }
				}//succsess
			});//ajax
			
				  }},'No'	: {'class'	: 'special'}}});}
</script>