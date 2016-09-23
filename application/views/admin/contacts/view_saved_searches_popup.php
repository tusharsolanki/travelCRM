<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="25%">Search Name</th>
            <?php /*<th width="20%">URL</th> */ ?>
            <th width="25%">Search Criteria</th>
            <th width="25%">Domain</th>
            <th width="25%">Date</th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php 
        if(!empty($result_saved_searches)){
            $i=0;
            foreach($result_saved_searches as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1"><?=!empty($row['name'])?$row['name']:'';?></td>
                    <?php /*<td class="sorting_2"><?=!empty($row['url'])?$row['url']:'';?></td> */?>
                    <td class="">
                        <?= !empty($row['search_criteria'])? 'Search Text: '.strip_slashes($row['search_criteria']).'<br />':'';?>
                        <?= !empty($row['min_price']) ? 'Minimum Price: '.$row['min_price'].'<br />':'' ?>
                        <?= !empty($row['max_price']) ? 'Maximum Price: '.$row['max_price'].'<br />':'' ?>
                        <?= !empty($row['bedroom'])? 'Bedroom: '.$row['bedroom'].'+<br />':'' ?>
                        <?= !empty($row['bathroom'])?'Bathroom: '.$row['bathroom'].'+<br />':'' ?>
                        <?= !empty($property_type[$row['property_type']])? 'Property Type: '.$property_type[$row['property_type']].'<br />':'' ?>
                        <?= !empty($row['min_year_built'])? 'Year Built: '.$row['min_year_built'].'<br />':'' ?>
                        <?= !empty($row['fireplaces_total'])? 'Fireplaces Total: '.$row['fireplaces_total'].'+<br />':'' ?>
                        <?= !empty($row['min_lotsize'])? 'Lot Size: '.$row['min_lotsize'].'+<br />':'' ?>
                        <?= !empty($row['garage_spaces'])? 'Garage Spaces: '.$row['garage_spaces'].'+<br />':'' ?>
                        <?= !empty($row['architecture'])? 'Architecture: <span class="search_details_ARC_'.$row['id'].'">'.$row['architecture'].'</span><br />':'' ?>
                        <?= !empty($school_data[$row['id']][0])? 'School District: '.$school_data[$row['id']][0]['school_district_description'].'<br />':''?>
                        <?= !empty($row['waterfront'])? 'Waterfront: <span class="search_details_WFT_'.$row['id'].'">'.str_replace('{^}', ', ', $row['waterfront']).'</span><br />':'' ?>
                        <?= !empty($row['s_view'])? 'View: <span class="search_details_VEW_'.$row['id'].'">'.str_replace('{^}', ', ', $row['s_view']).'</span><br />':'' ?>
                        <?= !empty($row['parking_type'])? 'Parking Type: <span class="search_details_GR_'.$row['id'].'">'.$row['parking_type'].'</span><br />':'' ?>
                        <?= !empty($row['property_status'])? 'Property Status: '.$row['property_status'].'<br />':'' ?>
                        <?= !empty($row['new_construction'])? 'New Construction: <span class="search_details_NC_'.$row['id'].'">'.$row['new_construction'].'</span><br />':'' ?>
                        <?= !empty($row['short_sale'])? 'Short Sale: <span class="search_details_PARQ_'.$row['id'].'">'.$row['short_sale'].'</span><br />':'' ?>
                        <?= !empty($row['bank_owned'])? 'Bank Owned: '.$row['bank_owned'].'<br />':'' ?>
                        <?= !empty($row['CDOM'])? 'New in the last : '.$row['CDOM'] .' Days <br />':'' ?>
                        <?= !empty($row['mls_id'])? '#MLS: '.$row['mls_id'].'<br />':'' ?>
                        <?= !empty($row['city'])? 'City: '.str_replace('{^}', ', ', $row['city']):'' ?>
                    </td>
                    <td class=""><?=!empty($row['domain'])?$row['domain']:'';?></td>
                    <td class=""><?=!empty($row['created_date']) && $row['created_date'] != '0000-00-00 00:00:00'?date($this->config->item('common_datetime_format'),strtotime($row['created_date'])):'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="4">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>
<script>
    $(document).ready(function(){
        <?php if(!empty($result_saved_searches) && count($result_saved_searches)>0){
                foreach($result_saved_searches as $row){
                    if(!empty($ame_data[$row['id']]))
                    {
                        foreach($ame_data[$row['id']] as $sin_row)
                        { ?>
                            $('.search_popup_<?=$sin_row["code"]?>_<?=$row["id"]?>').html('<?=$sin_row["value_description"]?>');
                        <?php }
                    }
        } }?>
    });
</script>