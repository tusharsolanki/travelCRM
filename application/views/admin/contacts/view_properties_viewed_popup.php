<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="20%">MLSID</th>
            <th width="20%">Property Name</th>
            <th width="20%">View</th>
            <th width="20%">Domain</th>
            <th width="20%">Date</th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php 
        if(!empty($properties_viewed_list)){
            $i=0;
            foreach($properties_viewed_list as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1"><?=!empty($row['mlsid'])?$row['mlsid']:'';?></td>
                    <td class="sorting_2"><?=!empty($row['propery_name'])?$row['propery_name']:'';?></td>
                    <td class=""><?=!empty($row['views'])?$row['views']:'';?></td>
                    <td class=""><?=!empty($row['domain'])?$row['domain']:'';?></td>
                    <td class=""><?=!empty($row['log_date']) && $row['log_date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($row['log_date'])):'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="3">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>