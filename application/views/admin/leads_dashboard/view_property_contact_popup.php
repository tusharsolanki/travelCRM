<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr role="row">
            <th width="15%"><?=$this->lang->line('contact_name')?></th>
            <th width="15%"><?=$this->lang->line('contact_joomla_val_searched_address')?></th>
            <th width="20%"><?=$this->lang->line('contact_joomla_val_searched_domain')?></th>
            <th width="10%"><?=$this->lang->line('common_label_name')?></th>
            <th width="10%"><?=$this->lang->line('common_label_email')?></th>
            <th width="10%"><?=$this->lang->line('common_label_phone')?></th>
            <th width="20%"><?=$this->lang->line('contact_joomla_comment')?></th>
        </tr>
    </thead>
    <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php
        if(!empty($property_contact_list)){
            $i=0;
            foreach($property_contact_list as $row){ if(!empty($row['id'])){ ?>
                <tr class="<?php if($i%2==0){echo 'odd';}else{echo 'even';}$i++;?>">
                    <td class="sorting_1"><?=!empty($row['contact_name'])?ucwords($row['contact_name']):'';?></td>
                    <td class=""><?=!empty($row['property_name'])?$row['property_name']:'';?></td>
                    <td class=""><?=!empty($row['domain'])?$row['domain']:'';?></td>
                    <td class=""><?=!empty($row['name'])?$row['name']:'';?></td>
                    <td class=""><?=!empty($row['email'])?$row['email']:'';?></td>
                    <td class=""><?=!empty($row['phone'])? preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $row['phone']):'';?></td>
                    <td class=""><?=!empty($row['comments'])? stripslashes(nl2br($row['comments'])):'';?></td>
              </tr>
    <?php }} ?>
    <?php }else{ ?>
            <tr>
                <td colspan="7">No Records Found.</td>
            </tr>
    <?php } ?>

    </tbody>
</table>