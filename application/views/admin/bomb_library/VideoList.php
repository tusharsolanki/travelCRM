<?php 
    /*
        @Description: Video List
        @Author: Sanjay Chabhadiya
        @Date: 04-03-2015
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
<div class="videomain">
<?php if(!empty($videolist) && count($videolist)>0){
		$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
			foreach($videolist as $row){?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                	<label>
                    	<div class="radiobox">
                         <input type="checkbox" class="mycheckbox" name="select_video[]" value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['video_id']) && $editRecord[0]['video_id'] == $row['id']) echo 'checked="checked"'; ?> data-id="<?=$row['image']?>" data-group="<?=$row['name']?>">
                        </div>
                        <div class="video_details_<?=$row['id']?>">
                        <div class="videopic">
                         <img src="<?=!empty($row['image'])?$row['image']:''?>" height="150" width="150" />
                        </div>
                        <span class="videopicname"><?=$row['name']?></span>
                        </div>
                   </label>
               </div>
                        <?php } ?>
<?php } else {  ?>

				<div class="text-center">
                	No video Found.
                </div>
<?php }  ?>
</div>

<?php if(!empty($videolist) && count($videolist) > 0) { ?>
<div class="videomain"><div class="text-center">
                            <input type="button" name="video" id="video" value="Save" class="btn btn-secondary" onClick="selectVideo();">
                            <a class="btn btn-primary" title="Cancel" onclick="bombbomb_close_popup()" id="elp_cancel">Cancel</a>
                        </div></div>
<?php } ?>
<!--
<div class="videomain">
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<label>
<div class="radiobox"><input name="" type="radio" value="" /></div>
<div class="videopic"><img src="<?=!empty($row['image'])?$row['image']:''?>" height="200" width="200" /></div>
<span class="videopicname">Video1</span>
</label>

</div>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<label>
<div class="radiobox"><input name="" type="radio" value="" /></div>
<div class="videopic"><img src="<?=!empty($row['image'])?$row['image']:''?>"/></div>
<span class="videopicname">Video1</span>
</label>
</div>	
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<label>
<div class="radiobox"><input name="" type="radio" value="" /></div>
<div class="videopic"><img src="<?=!empty($row['image'])?$row['image']:''?>"/></div>
<span class="videopicname">Video1</span>
</label>

</div>	
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<label>
<div class="radiobox"><input name="" type="radio" value="" /></div>
<div class="videopic"><img src="<?=!empty($row['image'])?$row['image']:''?>"/></div>
<span class="videopicname">Video1</span>

</label>
</div>		
</div>
 <div class="videomain"><div class="text-center">
                    <input type="button" name="video" id="video" value="Save" class="btn btn-secondary" onClick="selectVideo();">
                    <a class="btn btn-primary" title="Cancel" onclick="close_popup()" id="elp_cancel">Cancel</a>
				</div></div>    
     -->
     