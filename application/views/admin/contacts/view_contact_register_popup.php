<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="25%">Name</th>
            <th width="25%">Email</th>
            <th width="25%">Domain</th>
            <th width="25%">Date</th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php 
        if(!empty($contact_register_list)){
            $i=0;
            foreach($contact_register_list as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1"><?=!empty($row['contact_name'])?$row['contact_name']:'';?></td>
                    <td class="sorting_2"><?=!empty($row['email_address'])?$row['email_address']:'';?></td>
                    <td class=""><?=!empty($row['joomla_domain_name'])?$row['joomla_domain_name']:'';?></td>
                    <td class=""><?=!empty($row['created_date']) && $row['created_date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($row['created_date'])):'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="3">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>