<style>
	table tr td,table tr th{ padding:3px;}
		table{
	page-break-before:auto !important;
	}
	
</style>

<table border="1" cellpadding="0" cellspacing="0">
  <thead>
   <tr>
    <th><?=$this->lang->line('task_label_name')?></th>
    <th><?=$this->lang->line('taskdate_label_name')?></th>
   </tr>
   </thead>
    <tbody>
   <?php if(!empty($datalist) && count($datalist)>0){
            $i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
              foreach($datalist as $row){?>
                <tr> 
                	<td> 
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>&nbsp;
                                    
                                </td>
                                <td>
                                <?=!empty($row['task_name'])?ucfirst(strtolower($row['task_name'])):'';?>
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
                               <?php if($row['task_date']=='0000-00-00'){ echo '';}else
                    {?><?=!empty($row['task_date'])?date($this->config->item('common_date_format'),strtotime($row['task_date'])):'';}?>
                                </td>
                            </tr>
                        </table>
                    </td>
                  
                  </tr>
  <?php } } else {?>
  <tr>
    <td colspan="3" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
  </tr>
  <?php } ?>
  </tbody>
</table>

         