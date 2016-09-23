<table width="100%" class="table1 table-striped1 table-striped1 table-bordered1 table-hover1 table-highlight table table-striped table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                                <thead>
                                                  <tr role="row">
                                                    <th width="7%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Activity</th>
                                                    <th width="7%" aria-label="CSS grade" colspan="1" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled text-center">Action</th>
                                                  </tr>
                                                </thead>
                                                <?php  if(!empty($conversations)){
                                                    for($i=0;$i<count($conversations);$i++) { 
                                                    if($conversations[$i]['status'] != '0' || $conversations[$i]['is_completed_task'] != '0')
                                                    {
                                                if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='1'){?>
                                                <tr class="load_conversations manual_conversations">
                                                 <td colspan="2" width="100%">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="53%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
                                                        <td width="47%"><?php if(!empty($conversations[$i]['interaction_type_name'])){ echo $conversations[$i]['interaction_type_name'];}?> </td>
                                                      </tr>
                                                      <tr>
                                                        <td><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td>
                                                        <td><?php if(!empty($conversations[$i]['disposition_name'])){ echo $conversations[$i]['disposition_name'];}else{echo "Not Available";}?></td>
                                                      </tr>
                                                      
                                                      <tr>
                                                        <td colspan="3"><?php if(!empty($conversations[$i]['description'])){ echo $conversations[$i]['description'];}?></td>
                                                      </tr>
                                                    </table>
                                                </td>
                                                </tr>
                                                <?php }elseif(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='12'){?>
                                                <tr class="load_conversations manual_conversations">
                                                 <td colspan="2" width="100%">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="53%"><b>Notes</b></td>
                                                        <td width="47%"><?php if(!empty($conversations[$i]['created_date'])){ echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));}?></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="2"><?php if(!empty($conversations[$i]['description'])){ echo $conversations[$i]['description'];}?></td>
                                                      </tr>
                                                    </table>
                                                </td>
                                                </tr>
                                                <?php }elseif(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type'] =='11'){ ?>
                                                
                                                <tr class="load_conversations">
                                                 <td colspan="2" width="100%">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="30%"><b>Task</b></td>
                                                        <td ><?php if(!empty($conversations[$i]['task_name'])){ echo $conversations[$i]['task_name'];}?>
                                                      </tr>
                                                    
                                                      <tr>
                                                        <td width="30%"><b>Task Date</b></td>
                                                        <td ><?php if(!empty($conversations[$i]['task_date'])){ echo date($this->config->item('common_date_format'),strtotime($conversations[$i]['task_date']));}?>
                                                      </tr>
                                                      <tr>
                                                        <td><?php if(!empty($conversations[$i]['user_name'])){  echo $conversations[$i]['user_name'];}?></td>
                                                        
                                                        <td ><?php if(!empty($conversations[$i]['desc'])){ echo $conversations[$i]['desc'];}?></td>
                                                    </table>
                                                </td>
                                                </tr>
                                                <?php } else {?>
                                                <tr class="load_conversations">
                                                 <td width="100%" colspan="2">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="30%"><b><?php if(!empty($conversations[$i]['contact_name'])){echo $conversations[$i]['contact_name'];}; ?></b></td>
                                                        <td width="77%">
                                                        
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='2'){ echo "Assign Communication";}?>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='3'){ echo "Assign to";}?>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='4'){ echo "Re-assign to";}?>
                                                        <b><?php if(!empty($conversations[$i]['user_name1'])){echo ":".$conversations[$i]['user_name1'];}; ?></b>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='5')
                                                            {	
                                                                 echo "Email Sent From";
                                                                if(!empty($conversations[$i]['interaction_id']))
                                                                    {
                                                                        if(!empty($conversations[$i]['inte_to_plan_name']))
                                                                        {
                                                                        echo " >> ".$conversations[$i]['inte_to_plan_name'];
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        if(!empty($conversations[$i]['email_template_name']))
                                                                        {
                                                                            echo $conversations[$i]['email_template_name'];
                                                                        }
                                                                        if(!empty($conversations[$i]['email_campaing_template_name']))
                                                                        {
                                                                            echo $conversations[$i]['email_campaing_template_name'];
                                                                        }
                                                                    }
                                                                     
                                                            }?>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='6'){ echo "Email Sent From  Campaign ";}?>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='7')
                                                                    { 	echo "SMS Sent From";
                                                                        if(!empty($conversations[$i]['inte_to_plan_name']))
                                                                        {echo " >> ".$conversations[$i]['inte_to_plan_name'];}
                                                                    }?>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='8'){ echo "SMS Sent From Campaign";}?>
                                                        
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='9'){ if(!empty($conversations[$i]['mail_out_type'])){echo $conversations[$i]['mail_out_type'];}}?>
                                                        <?php if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='10'){ echo "Remove From Communication"; /*if(!empty($conversations[$i]['plan_name'])){echo " >> ".$conversations[$i]['plan_name'];}*/}?>
                                                        
                                                         </td>
                                                      </tr>
                                                      <tr>
                                                        <td>
                                                        <?php if(empty($conversations[$i]['plan_name']))
                                                                {
                                                                    if(!empty($conversations[$i]['created_date']))
                                                                    { 
                                                                    echo date($this->config->item('common_datetime_format'),strtotime($conversations[$i]['created_date']));
                                                                    }
                                                                }else{
                                                                    if(!empty($conversations[$i]['created_date']))
                                                                    { 
                                                                    echo date($this->config->item('common_date_format'),strtotime($conversations[$i]['created_date']));
                                                                    }
                                                                }?>
                                                        </td>
                                                        <td><?php if(!empty($conversations[$i]['plan_name']))
                                                                    { 
                                                                        echo $conversations[$i]['plan_name'];
                                                                    }
                                                                    else if(!empty($conversations[$i]['inte_to_plan_name']))
                                                                    {
                                                                        echo " >> ".$conversations[$i]['inte_to_plan_name'];
                                                                    }
                                                                    if(!empty($conversations[$i]['interaction_name']))
                                                                    { 
                                                                        echo " >> ".$conversations[$i]['interaction_name'];
                                                                    }?>
                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td>
                                                                <?php if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Letter')
                                                                {
                                                                    if(!empty($conversations[$i]['letter_template_name']))
                                                                    {	
                                                                     echo $conversations[$i]['letter_template_name'];
                                                                    }
                                                                }
                                                                else
                                                                if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Envelope')
                                                                {
                                                                    if(!empty($conversations[$i]['envelope_template_name']))
                                                                    {	
                                                                        echo $conversations[$i]['envelope_template_name'];
                                                                    }
                                                                }
                                                                else if(!empty($conversations[$i]['mail_out_type']) && $conversations[$i]['mail_out_type']=='Label')
                                                                {
                                                                    if(!empty($conversations[$i]['label_template_name']))
                                                                    {	
                                                                        echo $conversations[$i]['label_template_name'];
                                                                    }
                                                                }
                                                                if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='8')
                                                                {
                                                                     if(!empty($conversations[$i]['sms_campaing_template_name']))
                                                                    {	
                                                                        echo $conversations[$i]['sms_campaing_template_name'];
                                                                    }
                                                                }
                                                                if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='7')
                                                                    { 
                                                                        if(!empty($conversations[$i]['sms_template_name']))
                                                                        {
                                
                                                                            echo $conversations[$i]['sms_template_name'];
                                
                                                                        }
                                                                        if(!empty($conversations[$i]['sms_campaing_template_name']))
                                                                        {
                                                                            echo $conversations[$i]['sms_campaing_template_name'];
                                                                        }	
                                                                         
                                                                    }
                                                                if(!empty($conversations[$i]['log_type']) && $conversations[$i]['log_type']=='6')
                                                                    { 
                                                                        if(!empty($conversations[$i]['email_campaing_template_name']))
                                                                        {
                                                                            echo $conversations[$i]['email_campaing_template_name'];
                                                                        }
                                                                        
                                                                    }?>
                                                                
                                                        </td>
                                                      </tr>
                                                      
                                                    </table>
                                                </td>
                                               
                                                </tr>
                                                <?php } ?>
                                                <?php }}} else {?>
                                                
                                                <tr>
                                                    <th width="10%" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled"> No Record Found!</th>
                                                </tr>
                                                
                                            <?php 	}?>	
                                              </table>