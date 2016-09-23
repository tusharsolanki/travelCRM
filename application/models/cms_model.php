<?php
class cms_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'cms_master';
		$this->table_cms_menu_trans = 'child_website_cms_menu_trans';
    }

    /*
        @Description: Function for listing
        @Author     : Sanjay Moghariya
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Get listing
        @Date       : 26-02-2015
    */
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$not_where='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
			$table_name = $db_name.'.'.$this->table_name; 
		else
			$table_name = $this->table_name; 
        $sql .= ' FROM '.$table_name;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE ';
            switch ($compare_type)
            {
                case 'like':
                    $where .= $keys[0].' '.$compare_type .'"%'.$match_values[$keys[0]].'%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0].' '.$compare_type .'"'.$match_values[$keys[0]].'" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);
            
            foreach($match_values as $key=>$value)
            {                
                $where .= $and_or.' '.$key.' ';
                switch ($compare_type)
                {
                    case 'like':
                        $where .= $compare_type .'"%'.$value.'%"';
                        break;
                    
                    case '=':
                    default:
                        $where .= $compare_type .'"'.$value.'"';
                        break;
                }
            }
        }
        
        if(!empty($not_where))
        {
            foreach($not_where as $key=>$value)
            {   
                $where .= ' AND ('.$key.' ';
                $where .= ' != '.$value.')';
            }
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();exit;
        return $query->result_array();
    }
	
    /*
        @Description: Function for fetch records from table
        @Author     : Sanjay Moghariya
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Fetch records
        @Date       : 10-11-2014
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
        @Description: Function is for Insert cms data
        @Author     : Kaushik Valiya
        @Input      : cms data
        @Output     : Insert record into DB
        @Date       : 26-02-2015
    */
    function insert_record($data,$db_name='')
    {
		if(!empty($db_name))
			$result =  $this->db->insert($db_name.'.'.$this->table_name,$data);
		else
			$result =  $this->db->insert($this->table_name,$data);
        return mysql_insert_id();
    }
	
    /*
        @Description: Function is for update cms data
        @Author     : Sanjay Moghariya
        @Input      : updated cms data
        @Output     : Update records
        @Date       : 26-02-2015
    */
	
    public function update_record($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
		if(!empty($db_name))
			$query = $this->db->update($db_name.'.'.$this->table_name,$data); 
		else
        	$query = $this->db->update($this->table_name,$data); 
    }
	
    /*
        @Description: Function for Delete cms data
        @Author     : Sanjay Moghariya
        @Input      : cms id
        @Output     : Delete record(s)
        @Date       : 26-02-2015
    */
	
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	/*
        @Description: Function is for Insert cms menu trans data
        @Author     : Sanjay Chabhadiya
        @Input      : cms trans data
        @Output     : Insert record into DB
        @Date       : 16-04-2015
    */
	
    function insert_cms_menu_trans($data,$db_name='')
    {
		if(!empty($db_name))
			$result =  $this->db->insert($db_name.'.'.$this->table_cms_menu_trans,$data);	
		else
        	$result =  $this->db->insert($this->table_cms_menu_trans,$data);	
        return mysql_insert_id();
    }
}