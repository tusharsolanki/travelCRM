<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="19%">MLSID</th>
            <th width="27%">Property Name</th>
            <th width="27%">Domain</th>
            <th width="27%">Date</th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php 
        if(!empty($favorite_list)){
            $i=0;
            foreach($favorite_list as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1"><?=!empty($row['mlsid'])?$row['mlsid']:'';?></td>
                    <td class="sorting_2"><?=!empty($row['propery_name'])?ucfirst(strtolower($row['propery_name'])):'';?></td>
                    <td class=""><?=!empty($row['domain'])?$row['domain']:'';?></td>
                    <td class=""><?=!empty($row['date']) && $row['date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($row['date'])):'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="3">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>