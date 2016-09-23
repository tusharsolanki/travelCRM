<?php
class lead_users_model extends CI_Model
{
    function __construct()
    {
        parent::__construct(); 
        $this->table_name = 'lead_users';
	$this->table_name_child_website_domain_master = 'child_website_domain_master';
    }

    /*
        @Description: Function for get table record
        @Author     : Jayesh Rojasara
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : listing
        @Date       : 05-03-2015
    */
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
			$table = $db_name.".".$this->table_name;
		else
			$table = $this->table_name;
        $sql .= ' FROM '.$table;
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
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$table.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        return $query->result_array();
    }
	
    /*
        @Description: Function for get listing
        @Author     : Sanjay Moghariya
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Listing
        @Date       : 05-03-2015
    */
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='')
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

        if($group_by != null)
            $this->db->group_by($group_by);

        if($orderby != null && $sort != null)
            $this->db->order_by($orderby,$sort);

        elseif($orderby != null )
        $this->db->order_by($orderby);

        if($match_values != null &&  $compare_type != null )
            $this->db->like($match_values, $compare_type);

        if($num != null )
            $this->db->limit($num);

        if($offset != null && $num != null)
            $this->db->limit($num,$offset);

        $query_FC = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query_FC->result_array();
    }

    /*
        @Description: Function for insert user
        @Author     : Sanjay Moghariya
        @Input      : user data
        @Output     : Insert record
        @Date       : 05-03-2015
    */
    function insert_record($data,$db_name='')
    {
        if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name,$data);	
        else
            $result =  $this->db->insert($this->table_name,$data);
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for update user
        @Author     : Sanjay Moghariya
        @Input      : user data
        @Output     : Update record
        @Date       : 05-03-2015
    */
	
    public function update_record($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
		if(!empty($db_name))
            $query =  $this->db->update($db_name.".".$this->table_name,$data);	
        else
            $query = $this->db->update($this->table_name,$data); 
        
    }
	
	public function update_status_record($data,$db_name='')
    {
        $this->db->where('email',$data['email']);
		$this->db->where('domain',$data['domain']);
        $this->db->where('website_status',1);
		if(!empty($db_name))
            $query =  $this->db->update($db_name.".".$this->table_name_child_website_domain_master,$data);	
        else
            $query = $this->db->update($this->table_name_child_website_domain_master,$data); 
        
    }
	
	/*
        @Description: Function for Delete
        @Author     : Mohit Trivedi
        @Input      : Superadmin id
        @Output     : Delete record from db
        @Date       : 30-08-14
    */
	
    public function delete_record($id,$db_name='')
    {
		if(!empty($db_name))
		{
			$this->db->where('domain',$id);
			$this->db->delete($db_name.".".$this->table_name);
		}
		else
		{
			$this->db->where('id',$id);
        	$this->db->delete($this->table_name);
		}
    }
	
	function domain_insert_record($data='',$db_name='')
    {
		 if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_child_website_domain_master,$data);	
        else
            $result =  $this->db->insert($this->table_name_child_website_domain_master,$data);
        $lastId = mysql_insert_id();
        return $lastId;
    }
	
	public function domain_delete_record($domain,$db_name='')
    {
		$this->db->where('domain_name',$domain);
		$this->db->delete($db_name.".".$this->table_name_child_website_domain_master);
    }
	
    public function select_child_website_domain_master($domain,$db_name='')
    {
        $this->db->select('*');
        $this->db->where('domain_name',$domain);
        $this->db->where('website_status',1);
        $result = $this->db->get($db_name.'.'.$this->table_name_child_website_domain_master);
        return $result->result_array();
    }
	
    public function domain_update_record($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
	if(!empty($db_name))
            $query =  $this->db->update($db_name.".".$this->table_name_child_website_domain_master,$data);	
        else
            $query = $this->db->update($this->table_name_child_website_domain_master,$data); 
        
    }
    
    /*
        @Description: Common Function for Delete
        @Author: Sanjay Chabhadiya
        @Input: - Where clause,table name and db name
        @Output: - 
        @Date: 04-06-2015
    */
    
    public function delete_child_website_data($table_name='',$match='',$db_name='')
    {
        $this->db->where($match);
        if(!empty($db_name))
            $this->db->delete($db_name.".".$table_name);
        else
            $this->db->delete($table_name);
    }
    
    /*
        @Description: Common Function for Select
        @Author: Sanjay Chabhadiya
        @Input: - Where clause ,table name and db name
        @Output: - 
        @Date: 04-06-2015
    */
    public function select_child_website_data($table_name='',$fields='',$match='',$db_name='')
    {
        if(!empty($fields))
        {
            foreach($fields as $coll => $value)
                $this->db->select($value,false);
        }
        else
            $this->db->select('*');
        $this->db->where($match);
        if(!empty($db_name))
           $query_FC = $this->db->get($db_name.".".$table_name);
        else
           $query_FC = $this->db->get($table_name);
        return $query_FC->result_array();
    }
    
    public function delete_carousels_trans($domain_id='',$db_name='')
    {
        if(!empty($domain_id) && !empty($db_name))
            $this->db->query('DELETE FROM '.$db_name.'.child_website_carousels_property_type_trans WHERE carousels_id IN(select id from '.$db_name.'.child_website_carousels_master where domain_id = '.$domain_id.')');
        elseif(!empty($domain_id))
            $this->db->query('DELETE FROM child_website_carousels_property_type_trans WHERE carousels_id IN(select id from child_website_carousels_master where child_admin_id = '.$domain_id.')');
    }
}