<?php
class mls_masters_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'mls_property_type';
        $this->table_name_status = 'mls_status_master';
        $this->table_name_area = 'mls_area_master';
        
        $this->table_name_ptype_trans = 'user_mls_property_type_trans';
        $this->table_name_status_trans = 'user_mls_status_trans';
        $this->table_name_area_trans = 'user_mls_area_trans';
        
    }
    
    
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='',$having='',$or_where='')
    {  
        if(!empty($fields))
        {
            foreach($fields as $coll => $value)
            {
                $this->db->select($value,false);
            }
        }

        $this->db->from($table);

        if(!empty($join_tables))
        {
            foreach($join_tables as $coll => $value)
            {
                //$this->db->join($coll, $value,$join_type);
                $colldata = explode('jointype',$coll);
                $coll = trim($colldata[0]);

                if(!empty($colldata[1]))
                {	
                    $join_type1 = trim($colldata[1]);
                    if($join_type1 == 'direct')
                        $join_type1 = '';
                }

                if(isset($join_type1))
                    $this->db->join($coll, $value,$join_type1);
                else
                    $this->db->join($coll, $value,$join_type);

                unset($join_type1);
            }
        }

        if($condition != null )
            $this->db->where($condition);

        if($wherestring != '')
            $this->db->where($wherestring, NULL, FALSE);
        
        if(!empty($where_in)){
            foreach($where_in as $key => $value){
                $this->db->where_in($key,$value);
            }
        }

        if(!empty($or_where)){
            foreach($or_where as $key => $value){
                $this->db->or_where($key,$value);
            }
        }

        if($group_by != null)
            $this->db->group_by($group_by);
        if($having != null)
            $this->db->having($having);
        if($orderby != null && $sort != null)
            $this->db->order_by($orderby,$sort);
        elseif($orderby != null )
        {
            if($orderby == 'special_case')
                $this->db->order_by('is_done asc,task_date asc');
            elseif($orderby == 'special_case_task')
                $this->db->order_by('id desc');
            else
                $this->db->order_by($orderby);
        }

        if($match_values != null &&  $compare_type != null )
            $this->db->or_like($match_values);

        if($offset != null && $num != null)
            $this->db->limit($num,$offset);
        elseif($num != null )
            $this->db->limit($num);

        $query_FC = $this->db->get();
        //echo $this->db->last_query();exit;
        if(!empty($totalrow))
            return $query_FC->num_rows();
        else
            return $query_FC->result_array();
    }
    
    /*
        @Description: Function for get listing
        @Author     : Sanjay Moghariya
        @Input      : Table name, Fieldl list(id,name..),join table name, join type, match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Listing records
        @Date       : 03-03-2015
    */
    /*function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='')
    {  
        if(!empty($fields))
        {
            foreach($fields as $coll => $value)
            {
                $this->db->select($value,false);
            }
        }

        $this->db->from($table);

        if(!empty($join_tables))
        {
            foreach($join_tables as $coll => $value)
            {
                $colldata = explode('jointype',$coll);
                $coll = trim($colldata[0]);

                if(!empty($colldata[1]))
                {	
                    $join_type1 = trim($colldata[1]);
                    if($join_type1 == 'direct')
                        $join_type1 = '';
                }

                if(isset($join_type1))
                    $this->db->join($coll, $value,$join_type1);
                else
                    $this->db->join($coll, $value,$join_type);

                unset($join_type1);
            }
        }

        if($condition != null )
            $this->db->where($condition);

        if($wherestring != '')
            $this->db->where($wherestring, NULL, FALSE);

        if($group_by != null)
            $this->db->group_by($group_by);

        if($orderby != null && $sort != null)
            $this->db->order_by($orderby,$sort);
        elseif($orderby != null )
            $this->db->order_by($orderby);

        if($match_values != null &&  $compare_type != null )
            $this->db->or_like($match_values);

        if($offset != null && $num != null)
            $this->db->limit($num,$offset);
        elseif($num != null )
            $this->db->limit($num);

        $query_FC = $this->db->get();
        return $query_FC->result_array();
    }*/

    /*
        @Description: Function is for Insert property type
        @Author     : Sanjay Moghariya
        @Input      : property type data
        @Output     : insert record
        @Date       : 02-03-2015
    */
    function insert_property_type($data)
    {
        $property_list_type = $data['name'];
        for($i=0;$i<count($property_list_type);$i++)
        {
            $data['name'] = $property_list_type[$i];
            if(trim($data['name']) != "")
                $result =  $this->db->insert($this->table_name,$data);
            $lastId = mysql_insert_id();
        }
        return $lastId;
    }
    
    /*
        @Description: Function is for Insert mls status
        @Author     : Sanjay Moghariya
        @Input      : property status data
        @Output     : insert record
        @Date       : 02-03-2015
    */
    function insert_mls_status($data)
    {
        $property_list_type = $data['name'];
        for($i=0;$i<count($property_list_type);$i++)
        {
            $data['name'] = $property_list_type[$i];
            if(trim($data['name']) != "")
                $result =  $this->db->insert($this->table_name_status,$data);
            $lastId = mysql_insert_id();
        }
        return $lastId;
    }
    
    /*
        @Description: Function is for Insert mls area
        @Author     : Sanjay Moghariya
        @Input      : property area data
        @Output     : insert record
        @Date       : 02-03-2015
    */
    function insert_mls_area($data)
    {
        $property_list_type = $data['name'];
        for($i=0;$i<count($property_list_type);$i++)
        {
            $data['name'] = $property_list_type[$i];
            if(trim($data['name']) != "")
                $result =  $this->db->insert($this->table_name_area,$data);
            $lastId = mysql_insert_id();
        }
        return $lastId;
    }
	
    /*
        @Description: Function is for update property type
        @Author     : Sanjay Moghariya
        @Input      : property type details
        @Output     : Update records
        @Date       : 02-03-2015
    */
    public function update_property_type($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
    
    /*
        @Description: Function is for update status
        @Author     : Sanjay Moghariya
        @Input      : status details
        @Output     : Update records
        @Date       : 02-03-2015
    */
    public function update_mls_status($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_status,$data); 
    }
    
    /*
        @Description: Function is for update area
        @Author     : Sanjay Moghariya
        @Input      : area details
        @Output     : Update records
        @Date       : 02-03-2015
    */
    public function update_mls_area($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_area,$data); 
    }
	
    /*
        @Description: Function for Delete property type
        @Author     : Sanjay Moghariya
        @Input      : property type id
        @Output     : Delete recodrs
        @Date       : 03-03-2015
    */
    public function delete_property_type($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
    
    /*
        @Description: Function for Delete mls status
        @Author     : Sanjay Moghariya
        @Input      : status id
        @Output     : Delete recodrs
        @Date       : 03-03-2015
    */
    public function delete_mls_status($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_status);
    }
    
    /*
        @Description: Function for Delete mls area
        @Author     : Sanjay Moghariya
        @Input      : area id
        @Output     : Delete recodrs
        @Date       : 03-03-2015
    */
    public function delete_mls_area($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_area);
    }
    
    /*
        @Description: Function for insert mls property type trans
        @Author     : Sanjay Moghariya
        @Input      : ptype data
        @Output     : Insert records
        @Date       : 03-03-2015
    */
    function insert_mls_property_type_trans($data,$parent_db='')
    {
        if(!empty($parent_db))
            $result =  $this->db->insert($parent_db.".".$this->table_name_ptype_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_ptype_trans,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Delete property type trans
        @Author     : Sanjay Moghariya
        @Input      : user id, ptype id
        @Output     : Delete recodrs
        @Date       : 03-03-2015
    */
    public function delete_mls_property_type_trans($id,$arr)
    {
        $this->db->where_in('mls_property_type_id',$arr);
        $this->db->where('user_id',$id);
        $this->db->delete($this->table_name_ptype_trans);
    }
    
    /*
        @Description: Function for insert mls status trans
        @Author     : Sanjay Moghariya
        @Input      : status data
        @Output     : Insert records
        @Date       : 03-03-2015
    */
    function insert_mls_status_trans($data,$parent_db='')
    {
        if(!empty($parent_db))
            $result =  $this->db->insert($parent_db.".".$this->table_name_status_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_status_trans,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Delete status trans
        @Author     : Sanjay Moghariya
        @Input      : user id, status id
        @Output     : Delete recodrs
        @Date       : 03-03-2015
    */
    public function delete_mls_status_trans($id,$arr)
    {
        $this->db->where_in('mls_status_master_id',$arr);
        $this->db->where('user_id',$id);
        $this->db->delete($this->table_name_status_trans);
    }
    
    /*
        @Description: Function for insert mls status trans
        @Author     : Sanjay Moghariya
        @Input      : status data
        @Output     : Insert records
        @Date       : 03-03-2015
    */
    function insert_mls_area_trans($data,$parent_db='')
    {
        if(!empty($parent_db))
            $result =  $this->db->insert($parent_db.".".$this->table_name_area_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_area_trans,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Delete status trans
        @Author     : Sanjay Moghariya
        @Input      : user id, status id
        @Output     : Delete recodrs
        @Date       : 03-03-2015
    */
    public function delete_mls_area_trans($id,$arr)
    {
        $this->db->where_in('mls_area_master_id',$arr);
        $this->db->where('user_id',$id);
        $this->db->delete($this->table_name_area_trans);
    }
}