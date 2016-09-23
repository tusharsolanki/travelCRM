<?php
	/*
    @Description: MOdule master Model
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 27-01-2015
	*/

class module_master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'module_master';
		$this->table_name1 = 'user_right_transaction';
	}

    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 27-01-2015
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$or_clause='',$db_name='',$where_in='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        if(!empty($db_name))
			$table = $db_name.".".$this->table_name;
		else
			$table = $this->table_name;
		
        $sql .= $fields ? $fields : '*';
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
		
		if(!empty($or_clause))
			$where .= " AND (".$or_clause.")";
		
        if(empty($match_values) && empty($or_clause) && !empty($where_in))
            $where .= 'where '.$where_in;

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
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 27-01-2015
    */
   
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$or_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        if(!empty($db_name))
			$table = $db_name.".".$this->table_name1;
		else
			$table = $this->table_name1;
		
        $sql .= $fields ? $fields : '*';
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
		
		if(!empty($or_clause))
			$where .= " AND (".$or_clause.")";
		
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
    @Description: Function for get Module Lists Multiple tables
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 27-01-2015
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$totalrow='')
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
				$this->db->join($coll, $value,$join_type);
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
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
			return $query_FC->result_array();
  
	}
    /*
    @Description: Function for get Module Lists Multiple tables
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 27-01-2015
    */
	
	function getmultiple_tables_records1($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='')
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
				$this->db->join($coll, $value,$join_type);
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
  
	}

    /*
    @Description: Function is for Insert Module Master details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Insert into DB
    @Output: - Insert record into DB
    @Date: 27-01-2015
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
    @Description: Function is for Insert Module Master details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Insert into DB
    @Output: - Insert record into DB
    @Date: 27-01-2015
    */
	
    function insert_record1($data,$parent_db='')
    {
		if(!empty($parent_db))
	        $result =  $this->db->insert($parent_db.".".$this->table_name1,$data);	
		else
			$result =  $this->db->insert($this->table_name1,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert Module Master details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Insert into DB
    @Output: - Insert record into DB
    @Date: 27-01-2015
    */
	
    function insert_child_record($data,$db='')
    {
        $result =  $this->db->insert($db.'.'.$this->table_name1,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert Module Master details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Insert into DB
    @Output: - Insert record into DB
    @Date: 27-01-2015
    */
	function truncate_table($table='',$db='')
	{
		if(!empty($db))
		{$this->db->from($db.'.'.$table);}
		else
		{$this->db->from($table);}
		$this->db->truncate();	
	}
	/*
    @Description: Function is for update customer details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 27-01-2015
    */

    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	/*
    @Description: Function is for update customer details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 27-01-2015
    */

    public function update_record1($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name1,$data); 
		
    }
	
	/*
    @Description: Function for Delete Module Master Profile By Admin
    @Author: Niral Patel
    @Input: - Module Master id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 27-01-2015
    */

    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	/*
    @Description: Function for Delete Module Master Profile By Admin
    @Author: Niral Patel
    @Input: - Module Master id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 27-01-2015
    */

    public function delete_record1($id)
    {
        $this->db->where('module_id',$id);
        $this->db->delete($this->table_name1);
    }
	/*
    @Description: Function for Delete Module Master Profile By Admin
    @Author: Niral Patel
    @Input: - Module Master id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 27-01-2015
    */
	public function delete_module_array($id,$arr)
    {
        $this->db->where_in('module_id',$arr);
		$this->db->where('user_id',$id);
        $this->db->delete($this->table_name1);
    }
	/*
    @Description: Function for Delete Module Master Profile By Admin
    @Author: Niral Patel
    @Input: - Module Master id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 27-01-2015
    */
	public function delete_module_subchild_array($id,$arr,$child_db='')
    {
        $this->db->where_in('module_id',$arr);
		$this->db->where('user_id',$id);
        if(!empty($child_db))
        {
            $this->db->delete($child_db.'.'.$this->table_name1);    
        }
        else
        {
            $this->db->delete($this->table_name1);
        }
        
    }
	/*
        @Description: Function For pagination
        @Author     : Niral Patel
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function getemailpagingid($email_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $email_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		
		return $op;
	}
	/*
        @Description: Function For insert superadmin template
        @Author     : 
        @Input      : 
        @Output     : Insert superadmin template to admin
        @Date       : 12-01-15
    */
	public function module_list_by_admin($id='',$parent_db='')
	{
		$this->db->select('module_master.id , module_master.module_unique_name',FALSE);
        $this->db->from($parent_db.'.user_right_transaction as ut');
        $this->db->join($parent_db.'.module_master', 'module_master.id = ut.module_id', '');
		
		$this->db->where('ut.user_id IN('.$id.')');
        //$this->db->group_by('esr.customerReply,esr.sendBlastId');
		//$this->db->order_by("sb.sendBlastDate","desc"); 	
		$query_FC = $this->db->get();
		$result = $query_FC->result_array();
		$module[]='';
		foreach ($result as $res)
		{
			$module[] =  $res['module_unique_name'];
		}
		$module_lists = array_values($module);
		return $module_lists;
		/*echo $this->db->last_query();
		pr($module_lists);
		exit;*/
		
		//echo $this->db->last_query();exit;
		//return $result_array_FC;
	}
	
	public function delete_assistant_rights($dbname,$data)
	{
		$this->db->where($data);
		$this->db->delete($dbname.".".$this->table_name1);
	}
}