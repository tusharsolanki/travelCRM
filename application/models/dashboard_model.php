<?php

/*
    @Description: DashBoard Model
    @Author: Kaushik Valiya
    @Input: 
    @Output: 
    @Date: 29-08-2014
*/

class dashboard_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'task_master';
		$this->table_name_tran = 'task_user_transcation';
        $this->table_name_error= 'error_data_master';
	//	$this->table_contact_per_touches = 'interaction_plan_contact_personal_touches';
		
    }

    /*
    @Description: Function for get Module Lists
    @Author: Kaushik Valiya
    @Input: User ID to Get User.
    @Output: Notification list
    @Date: 29-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        return $query->result_array();
    }
	public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$totalrow='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_error;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_error.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		if(!empty($totalrow))
            return $query->num_rows();
		else
            return $query->result_array();
       // return $query->result_array();
    }
    /*
    @Description: Function is for Insert Module Master details by Admin
    @Author: Niral Patel
    @Input: Module Master details for Insert into DB
    @Output: - Insert record into DB
    @Date: 27-01-2015
    */
    
    function insert_record1($data)
    {
        $result =  $this->db->insert($this->table_name_error,$data);  
        $lastId = mysql_insert_id();
        return $lastId;
    }
	function select_contact_conversation_trans_record($id)
    {
		$this->db->select('*');
		$this->db->from($this->table_contact_per_touches);
		$this->db->where('created_by',$id);
		$result = $this->db->get()->row();
		
		return $result;
    }
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$compare_type1='')
    {  
		//echo $condition;exit;
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
		
		if($match_values != null &&  $compare_type1 != null )
		$this->db->or_where($match_values);
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
		
		//pr($query_FC->result_array());
		//echo $this->db->last_query();exit;
		
  		return $query_FC->result_array();
  
	}
	/*
	@Description: DashBoard Model in Update
    @Author: Kaushik Valiya
    @Input: Id To Update
    @Output: pop-up update
    @Date: 29-08-2014
	*/
	public function update_task($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
	}
	public function update_task_trans($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_tran,$data); 
		//echo $this->db->last_query();exit;
	}
	public function update_task1($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();exit;
	}
        
        /*
            @Description: Function for getting task list
            @Author     : Sanjay Moghariya
            @Input      : Current Date, starting row number, limit per page, seatch text, sort field name, sort by (asc,desc),count
            @Output     : Task list
            @Date       : 22-10-2014
        */
        function dashboard_task_list($curr_date='',$per_page, $offset='',$searchtext='',$sortbyfield='',$sortby='',$cnt='',$where='')
        {
                    //echo $leadtype; exit;
            $this->db->select('tm.id,tut.id as task_trans_id,tm.task_name,tm.task_date,tm.desc as description');
            $this->db->from('task_master as tm');
            $this->db->join('task_user_transcation as tut', 'tut.task_id  = tm.id', 'left');
			if(!empty($where))
            	$this->db->where($where, NULL, FALSE);
			$this->db->where('tm.is_completed','0');
			if(strtotime($curr_date) <= strtotime(date('Y-m-d')))
            	$this->db->where('DATE_FORMAT(tm.task_date,"%Y-%m-%d") <= ',$curr_date);
			else
				$this->db->where('DATE_FORMAT(tm.task_date,"%Y-%m-%d")',$curr_date);
                   
            //if($offset != '')
            //    $this->db->limit($per_page,$offset);
            if($offset != null && $per_page != null)
			$this->db->limit($per_page,$offset);
            elseif($per_page != null )
                    $this->db->limit($per_page);

            if($searchtext != '')
            {
                $this->db->where("(tm.task_name LIKE '%$searchtext%' OR tm.desc LIKE '%$searchtext%' OR tm.task_date LIKE '%$searchtext%')");
            }

            $this->db->order_by('tm.is_completed','desc');
            if($sortbyfield != '')
            {
                $this->db->order_by($sortbyfield,$sortby);
            }

            $this->db->group_by('tm.id');

            $query_result = $this->db->get();
            $numrows = $query_result->num_rows();
            if ($numrows > 0) {
                    $result_array = $query_result->result_array();
            }else{
                    $result_array = '';
            }

            //echo $this->db->last_query();
            if($cnt != '')
                return $numrows;
            else
                return $result_array;
        }
    public function update_error($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_error,$data); 
    }
}