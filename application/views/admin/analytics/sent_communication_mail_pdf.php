<style>
	table tr td,table tr th{ padding:3px;}
		table{
	page-break-before:auto !important;
	}
	
</style>

<table border="1" cellpadding="0" cellspacing="0">
  <thead>
   <tr>
    <th><?=$this->lang->line('common_label_name')?></th>
    <th><?=$this->lang->line('tasksubject_label_name')?></th>
    <th>Communication</th>
    <th>Send Date</th>
   </tr>
   </thead>
    <tbody>
   <?php if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
              foreach($datalist as $row){?>
                <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                    <td colspan="3"> <?=!empty($row['plan_name'])?ucwords($row['plan_name']):'';?></td>
                    <td> Total : <?=!empty($row['total_sent_mail'])?$row['total_sent_mail']:'';?></td>
                </tr>
                <?php if(!empty($emaildata[$row['id']]) && count($emaildata[$row['id']])>0) 
                {
                    $j = 1;
                    foreach($emaildata[$row['id']] as $row1){
                ?>
                    <tr <? if($j%2==1){ ?>class="bgtitle" <? }?>>
                        
                        <td> 
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>&nbsp;
                                    
                                </td>
                                <td>
                                  <?=!empty($row1['contact_name'])?ucfirst(strtolower($row1['contact_name'])):'';?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    	<td> 
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>&nbsp;
                                    
                                </td>
                                <td>
                                  <?=!empty($row1['template_subject'])?ucfirst(strtolower($row1['template_subject'])):'';?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    	<td> 
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>&nbsp;
                                    
                                </td>
                                <td>
                                   <?=ucfirst(strtolower($row1['plan_name']." >> ".$row1['description']));?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    	<td> 
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>&nbsp;
                                    
                                </td>
                                <td>
                                  <?=!empty($row1['sent_date'])?date($this->config->item('common_datetime_format'),$row1['sent_date']):'';?>
                                </td>
                            </tr>
                        </table>
                    </td>
                        
                   </tr>
                   
               <?php $j++; }
                }
                $i++; ?>
  <?php } } else {?>
  <tr>
    <td colspan="4" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
  </tr>
  
  <?php } ?>
  </tbody>
</table>