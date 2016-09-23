<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="25%"><?=$this->lang->line('contact_joomla_val_searched_address')?></th>
            <th width="25%"><?=$this->lang->line('contact_joomla_val_searched_domain')?></th>
            <th width="15%"><?=$this->lang->line('contact_joomla_val_searched_timeline')?></th>
            <th width="15%"><?=$this->lang->line('contact_joomla_val_searched_send_report')?></th>
            <th width="20%"><?=$this->lang->line('contact_joomla_val_searched_date')?></th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php 
        if(!empty($valuation_searched_list)){
            $i=0;
            foreach($valuation_searched_list as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1">
                        <?php
                        $address = '';
                        if(!empty($row['search_address'])) $address = $row['search_address'].',';
                        if(!empty($row['city'])) $address .= $row['city'].' ';
                        if(!empty($row['state'])) $address .= $row['state'].' ';
                        if(!empty($row['zip_code'])) $address .= $row['zip_code'];
                        echo ucfirst(strtolower(trim($address,',')));
                        ?>
                        <?php //!empty($row['search_address'])?$row['search_address']:'';?>
                    </td>
                    <td class=""><?=!empty($row['domain'])?$row['domain']:'';?></td>
                    <td class=""><?=!empty($row['report_timeline'])?$row['report_timeline']:'';?></td>
                    <td class=""><?=!empty($row['send_report'])?$row['send_report']:'';?></td>
                    <td class=""><?=!empty($row['date']) && $row['date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($row['date'])):'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="5">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>