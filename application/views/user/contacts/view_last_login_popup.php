<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="33%">Login Date</th>
            <th width="33%">IP Address</th>
            <th width="33%">Domain</th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php 
        if(!empty($last_login_list)){
            $i=0;
            foreach($last_login_list as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1"><?=!empty($row['log_date']) && $row['log_date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($row['log_date'])):'';?></td>
                    <td class="sorting_2"><?=!empty($row['ip'])?$row['ip']:'';?></td>
                    <td class=""><?=!empty($row['domain'])?$row['domain']:'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="3">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>