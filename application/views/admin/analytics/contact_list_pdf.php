<style>
/*	table tr th{ padding:3px;}
	table tr td{ padding:3px;}*/
	table{
	page-break-before:auto !important;
	}
	
</style>
      <table border="1" cellpadding="0" cellspacing="0">
          <thead>
           <tr>
            <th ><?=$this->lang->line('common_label_name')?></th>
            <th><?=$this->lang->line('contact_list_company')?></th>
            <th ><?=$this->lang->line('common_label_phone')?></th>
			<th ><?=$this->lang->line('common_label_email')?></th>
			<th ><?=$this->lang->line('common_label_contact_status')?></th>
			<th ><?=$this->lang->line('common_label_address')?></th>
			<th ><?=$this->lang->line('common_label_contact_type')?></th>
           </tr>
           </thead>
          	<tbody >
           <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
						<tr <? if($i%2==1){ ?>class="bgtitle" <? }?> > 
                            <td> 
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>&nbsp;
                                            
                                        </td>
                                        <td>
                                            <?=!empty($row['contact_name'])?ucfirst(strtolower($row['contact_name'])):'';?> 
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
                                            <?=!empty($row['company_name'])?ucfirst(strtolower($row['company_name'])):'';?> 
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
                                            <?=!empty($row['phone_no'])?$row['phone_no']:'';?>
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
                                           <?=!empty($row['email_address'])?$row['email_address']:'';?>
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
                                            <?=!empty($row['contact_status'])?$row['contact_status']:'';?> 
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
                                            <?php if(!empty($row['full_address'])){
							
									$address=str_replace(', ',',',$row['full_address']);
									$letters = array(',,,,,',',,,,',',,,',',,');
									$fruit   = array(',',',',',',',');
									$text    = $address;
									$output  = str_replace($letters, $fruit, $text);
									$output = ltrim($output,",");
									$output = rtrim($output,",");
									echo $output;
									}	
										?>
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
                                            <?=!empty($row['contact_type'])?$row['contact_type']:'';?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            
							</tr>
          <?php } } else {?>
		  <tr>
		  	<td colspan="10"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
          </tbody>
         </table>