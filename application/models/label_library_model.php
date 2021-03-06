<?php
	/*
    @Description: Label Model
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 12-08-2014
	*/

class label_library_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'label_template_master';
		$this->table_name_transcation='task_user_transcation';
	}

    /*
    @Description: Function for get Module Lists
    @Author: Mit Makwana
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 12-08-2014
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
	
	
	/*
    @Description: Function for get Module Lists
    @Author: Mit Makwana
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
    @Author: Mit Makwana
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
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
			return $query_FC->result_array();
  
	}
    

    /*
    @Description: Function is for Insert Label Templets details by Admin
    @Author: Mit Makwana
    @Input: Label details for Insert into DB
    @Output: - Insert record into DB
    @Date: 12-08-2014
    */
	
    function insert_record($data)
    {
		//pr($data);exit;
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	  /*
    @Description: Function is for Insert Label details by Admin
    @Author: Mit Makwana
    @Input: Label details for Insert into DB
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
    @Author: Mit Makwana
    @Input: Label details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 12-08-2014
    */

    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	
	/*
    @Description: Function for Delete Label Profile By Admin
    @Author: Mit Makwana
    @Input: - Label id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 12-08-2014
    */

    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	 /*
    @Description: Function for Delete Label Profile By Admin
    @Author: Mit Makwana
    @Input: - Label id which is delete by admin
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
		@Description: Function for Edit
		@Author: Mit Makwana
		@Input: - Get Record ID
		@Output: - Edit Record and display as per pagination
		@Date: 10-09-2014
    */
	public function getemailpagingid($label_id='')
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
				if($row['id'] == $label_id)
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
			$new_ar=" AND la.id NOT IN (".$new_ar.")";
		}
		$now=date('Y-m-d H:i:s');
		$sql_ins = "INSERT INTO ".$this->table_name." (template_name, template_category, template_subcategory,template_type,size_type, size_w,size_h , label_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT la.template_name, ccat.id, la.template_subcategory,la.template_type, la.size_type, la.size_w,la.size_h , la.label_content,la.id, '".$now."' as admin_publish_date, la.superadmin_publish_date,la.is_default,'1' as edit_flag, la.created_date,'1' as created_by,la.status FROM ".$parent_db.".".$this->table_name." as la LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON la.template_category  = pcat.id LEFT JOIN marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where la.is_default  = '1' and la.publish_flag = '1' ".$new_ar.";";
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
		$sql_ins = "INSERT INTO ".$table_name." (template_name, template_category, template_subcategory,template_type, size_type, size_w,size_h , label_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT '".$next_temp."' as template_name, ccat.id, la.template_subcategory,la.template_type, la.size_type, la.size_w,la.size_h , la.label_content,la.id, '".$now."' as admin_publish_date, la.superadmin_publish_date,la.is_default,'1' as edit_flag, la.created_date,'1' as created_by,la.status FROM ".$parent_db.".".$this->table_name." as la LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON la.template_category  = pcat.id LEFT JOIN "; 				
		if(!empty($db_name))
		  	$sql_ins .= $db_name.".marketing_master_lib__category_master ccat ";
		else
			$sql_ins .= "marketing_master_lib__category_master ccat ";
		$sql_ins .= "ON ccat.superadmin_cat_id  = pcat.id where la.id = ".$parent_id.";";
		
		$query = $this->db->query($sql_ins);
		//echo $this->db->last_query(); exit;
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
	function check_superadmin_template($new_ar='',$parent_db='')
	{
		if(!empty($new_ar))
		{
			$new_ar="where id NOT IN (".$new_ar.")";
		}
		$sql_ins = "SELECT  publish_flag,
        SUM(CASE WHEN publish_flag = '1' THEN 1 ELSE 0 END) `publish`,
        SUM(CASE WHEN publish_flag = '0' THEN 1 ELSE 0 END) unpublish
		FROM ".$parent_db.".".$this->table_name." ".$new_ar."
		 GROUP BY is_default";
		$query = $this->db->query($sql_ins);
		return $query->result_array();
	}
}