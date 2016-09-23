<?php
class child_nearby_area_model extends CI_Model
{
    function __construct()
    {
        parent::__construct(); 
        $this->table_name = 'child_website_nearby_area';
    }
    
    /*
        @Description: Function for fetch data
        @Author     : Sanjay Moghariya
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Fetch data
        @Date       : 05-05-2015
    */
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='')
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
        if(!empty($where_in)){
            foreach($where_in as $key => $value){
                $this->db->where_in($key,$value);
            }
        }
			
        if($group_by != null)
            $this->db->group_by($group_by);

        if($orderby != null && $sort != null)
            $this->db->order_by($orderby,$sort);
        elseif($orderby != null )
        {
            if($orderby == 'special_case')
                $this->db->order_by('is_done asc,task_date asc');
            elseif($orderby == 'special_case_task')
                $this->db->order_by('log_type asc,is_completed_task desc');
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
        @Description: Function for insert data
        @Author     : Sanjay Moghariya
        @Input      : location data
        @Output     : Insert record into DB
        @Date       : 05-05-2015
    */
    function insert_record($data,$dbname='')
    {
        if(!empty($dbname))
            $this->db->insert($dbname.'.'.$this->table_name,$data);
        else
            $this->db->insert($this->table_name,$data);
        return $this->db->insert_id();
    }

    /*
        @Description: Function is for update data
        @Author     : Sanjay Moghariya
        @Input      : update details
        @Output     : Update record
        @Date       : 05-05-2015
    */
	
    public function update_record($data,$dbname='')
    {
        $this->db->where('id',$data['id']);
        if(!empty($dbname))
            $query = $this->db->update($dbname.'.'.$this->table_name,$data); 
        else
            $query = $this->db->update($this->table_name,$data); 
        //echo $this->db->last_query();exit;
    }
	
    /*
        @Description: Function for Delete data
        @Author     : Sanjay Moghariya
        @Input      : id
        @Output     : Delete record
        @Date       : 05-05-2015
    */
	
    public function delete_record($id,$dbname='')
    {
        $this->db->where('id',$id);
        if(!empty($dbname))
            $this->db->delete($dbname.'.'.$this->table_name);
        else
            $this->db->delete($this->table_name);
    }
}