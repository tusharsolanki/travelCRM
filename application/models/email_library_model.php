<?php
	/*
    @Description: Email Library Model
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 12-08-2014
	*/

class email_library_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'email_template_master';
		$this->table_name_transcation='task_user_transcation';
		$this->table_name_event='email_event_master';
		$this->table_name1 = 'custom_field_master';
	}

    /*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 12-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$or_clause='',$db_name='')
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
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 12-08-2014
    */
   
    public function select_records3($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name1;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        return $query->result_array();
    }

 
 
 
 
 
 /*
    @Description: Function for get Module event
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 12-08-2014
    */
   
    public function select_records2($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_event;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_event.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        return $query->result_array();
    }

	
	
	/*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 12-08-2014
    */
   
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_transcation;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_transcation.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
	    return $query->result_array();
    }
	
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 12-08-2014
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
    @Description: Function is for Insert Email Library details by Admin
    @Author: Mohit Trivedi
    @Input: Email Library details for Insert into DB
    @Output: - Insert record into DB
    @Date: 12-08-2014
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	  /*
    @Description: Function is for Insert Email Library details by Admin
    @Author: Mohit Trivedi
    @Input: Email Library details for Insert into DB
    @Output: - Insert record into DB
    @Date: 12-08-2014
    */
	
    function insert_record1($data)
    {
		$result =  $this->db->insert($this->table_name_transcation,$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }

	/*
    @Description: Function is for update customer details by Admin
    @Author: Mohit Trivedi
    @Input: Email Library details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 12-08-2014
    */

    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	
	/*
    @Description: Function for Delete Email Library Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Email Library id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 12-08-2014
    */

    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	 /*
    @Description: Function for Delete Email Library Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Email Library id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 12-08-2014
    */
    public function delete_record1($data)
    {
        $this->db->where('task_id',$data['task_id']);
		$this->db->where('user_id',$data['user_id']);
        $this->db->delete($this->table_name_transcation);
    }
	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
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
        @Author     : Niral Patel
        @Input      : 
        @Output     : Insert superadmin template to admin
        @Date       : 12-01-15
    */
	function get_new_template($parent_db='',$new_ar='')
	{
		if(!empty($new_ar))
		{
			$new_ar=" AND em.id NOT IN (".$new_ar.")";
		}
		$now=date('Y-m-d H:i:s');
		$sql_ins = "INSERT INTO ".$this->table_name." (template_name, template_category, template_subcategory, template_subject, email_message, email_send_type,email_event , is_unsubscribe,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT em.template_name, ccat.id, em.template_subcategory, em.template_subject, em.email_message,em.email_send_type , em.email_event, em.is_unsubscribe,em.id, '".$now."' as admin_publish_date, em.superadmin_publish_date,em.is_default,'1' as edit_flag, em.created_date,'1' as created_by,em.status FROM ".$parent_db.".".$this->table_name." as em LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON em.template_category  = pcat.id LEFT JOIN marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where is_default  = '1' and em.publish_flag = '1' AND em.email_send_type  = '2' ".$new_ar.";";
		$query = $this->db->query($sql_ins);
		// return $query->result_array();
	}
	function get_new_template_auto($parent_db='',$new_ar='')
	{
		if(!empty($new_ar))
		{
			$new_ar=" AND em.id NOT IN (".$new_ar.")";
		}
		$now=date('Y-m-d H:i:s');
		$sql_ins = "INSERT INTO ".$this->table_name." (template_name, template_category, template_subcategory, template_subject, email_message, email_send_type,email_event , is_unsubscribe,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT em.template_name, ccat.id, em.template_subcategory, em.template_subject, em.email_message,em.email_send_type , em.email_event, em.is_unsubscribe,em.id, '".$now."' as admin_publish_date, em.superadmin_publish_date,em.is_default,'1' as edit_flag, em.created_date,'1' as created_by,em.status FROM ".$parent_db.".".$this->table_name." as em LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON em.template_category  = pcat.id LEFT JOIN marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where is_default  = '1' and em.publish_flag = '1' AND em.email_send_type  = '1' ".$new_ar.";";
        $query = $this->db->query($sql_ins);
		// return $query->result_array();
	}
	/*
        @Description: Function For update superadmin new template
        @Author     : Niral Patel
        @Input      : 
        @Output     : Insert superadmin template to admin
        @Date       : 12-01-15
    */
	function get_update_template($parent_db='',$parent_id='',$next_temp,$db_name='')
	{
		$now=date('Y-m-d H:i:s');
		if(!empty($db_name))
			$table_name = $db_name.'.'.$this->table_name;
		else
			$table_name = $this->table_name;
		$sql_ins = "INSERT INTO ".$table_name." (template_name, template_category, template_subcategory, template_subject, email_message, email_send_type,email_event , is_unsubscribe,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT '".$next_temp."' as template_name,ccat.id, em.template_subcategory, em.template_subject, em.email_message,em.email_send_type , em.email_event, em.is_unsubscribe,em.id, '".$now."' as admin_publish_date, em.superadmin_publish_date,em.is_default,'1' as edit_flag, em.created_date,'1' as created_by,em.status FROM ".$parent_db.".".$this->table_name." as em LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON em.template_category  = pcat.id LEFT JOIN ";
		if(!empty($db_name))
		  	$sql_ins .= $db_name.".marketing_master_lib__category_master ccat ";
		else
			$sql_ins .= "marketing_master_lib__category_master ccat ";
		$sql_ins .= "ON ccat.superadmin_cat_id  = pcat.id where em.id = ".$parent_id.";";
        $query = $this->db->query($sql_ins);
		$lastId = mysql_insert_id();
		return $lastId;
		// return $query->result_array();
	}
	/*
        @Description: Function For check superadmin new template
        @Author     : Niral Patel
        @Input      : 
        @Output     : Insert superadmin template to admin
        @Date       : 12-01-15
    */
	function check_superadmin_template($new_ar='',$send_type='',$parent_db='')
	{
		if(!empty($new_ar))
		{
			$new_ar=" AND id NOT IN (".$new_ar.")";
		}
		$sql_ins = "SELECT  publish_flag,
        SUM(CASE WHEN publish_flag = '1' THEN 1 ELSE 0 END) `publish`,
        SUM(CASE WHEN publish_flag = '0' THEN 1 ELSE 0 END) unpublish
		FROM ".$parent_db.".".$this->table_name." where  email_send_type  = ".$send_type." ".$new_ar."
		 GROUP BY is_default";
		$query = $this->db->query($sql_ins);
		//echo $this->db->last_query();
		return $query->result_array();
	}
	
}