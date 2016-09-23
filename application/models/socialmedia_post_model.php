<?php
	/*
    @Description: socialmedia post model
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 08-08-2014
	*/

class socialmedia_post_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'social_media_template_master';
		$this->table_name_transcation='social_media_template_platform_trans';
	}

    /*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 08-08-2014
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
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 08-08-2014
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
    @Date: 08-08-2014
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
		//echo $this->db->last_query();exit;
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
			return $query_FC->result_array();
		
  
	}
    

    /*
    @Description: Function is for Insert socialmedia post details by Admin
    @Author: Mohit Trivedi
    @Input: socialmedia post details for Insert into DB
    @Output: - Insert record into DB
    @Date: 08-08-2014
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	  /*
    @Description: Function is for Insert socialmedia post details by Admin
    @Author: Mohit Trivedi
    @Input: socialmedia post details for Insert into DB
    @Output: - Insert record into DB
    @Date: 08-08-2014
    */
	
    function insert_record1($data)
    {
		$result =  $this->db->insert($this->table_name_transcation,$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }

	/*
    @Description: Function is for update details by Admin
    @Author: Mohit Trivedi
    @Input: socialmedia post details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 08-08-2014
    */

    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	
	/*
    @Description: Function is for update details by Admin
    @Author: Mohit Trivedi
    @Input: socialmedia post details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 08-08-2014
    */

    public function update_record1($data)
    {
        $this->db->where('social_template_id',$data['social_template_id']);
        $query = $this->db->update($this->table_name_transcation,$data);
		//echo $this->db->last_query();exit; 
		
    }
	/*
    @Description: Function is for update details in contact by Admin
    @Author: kaushik Valiya
    @Input: socialmedia post details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 04-09-2014
    */
	
	 public function update_record_contact($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	
	/*
    @Description: Function for Delete socialmedia post Profile By Admin
    @Author: Mohit Trivedi
    @Input: - socialmedia post id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 08-08-2014
    */

    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	
	 /*
    @Description: Function for Delete socialmedia post Profile By Admin
    @Author: Mohit Trivedi
    @Input: - socialmedia post id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 08-08-2014
    */
    public function delete_record1($data)
    {
       	$this->db->where('social_template_id',$data['social_template_id']);
		$this->db->where('platform',$data['platform']);
		$this->db->delete($this->table_name_transcation);
    }
	public function delete_record2($data)
    {
       	$this->db->where('social_template_id',$data['id']);
		$this->db->delete($this->table_name_transcation);
    }
	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function getadminpagingid($post_id='')
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
				if($row['id'] == $post_id)
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
			$new_ar="AND sm.id NOT IN (".$new_ar.")";
		}
		$now=date('Y-m-d H:i:s');
		$sql_ins = "INSERT INTO ".$this->table_name." (template_name, template_category, template_subcategory,template_subject, post_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT sm.template_name, ccat.id, sm.template_subcategory, sm.template_subject, sm.post_content,sm.id, '".$now."' as admin_publish_date, sm.superadmin_publish_date, sm.is_default,'1' as edit_flag,sm.created_date,'1' as created_by,sm.status FROM ".$parent_db.".".$this->table_name." as sm LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sm.template_category  = pcat.id LEFT JOIN marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where sm.is_default  = '1' and sm.publish_flag = '1' ".$new_ar.";";
		$query = $this->db->query($sql_ins);
		//echo $lastId = mysql_insert_id();exit;
		
		
		
		$sql_ins1 = "INSERT INTO ".$this->table_name_transcation." (social_template_id, platform) SELECT sol.id, tsol1.platform FROM ".$this->table_name." as sol LEFT JOIN ".$parent_db.".".$this->table_name." sol1 ON sol.superadmin_template_id  = sol1.id LEFT JOIN ".$parent_db.".".$this->table_name_transcation." tsol1 ON tsol1.social_template_id  = sol1.id;";
		$query = $this->db->query($sql_ins1);
		// return $query->result_array();
	}
	/*
        @Description: Function For update superadmin new template
        @Author     : Niral Patel
        @Input      : 
        @Output     : Insert superadmin template to admin
        @Date       : 12-01-15
    */
	function get_update_template($parent_db='',$parent_id='',$next_temp)
	{
		$now=date('Y-m-d H:i:s');
		$sql_ins = "INSERT INTO ".$this->table_name." (template_name, template_category, template_subcategory,template_subject, post_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT '".$next_temp."' as template_name, ccat.id, sm.template_subcategory, sm.template_subject, sm.post_content,sm.id, '".$now."' as admin_publish_date, sm.superadmin_publish_date, sm.is_default,'1' as edit_flag,sm.created_date,'1' as created_by,sm.status FROM ".$parent_db.".".$this->table_name." as sm LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sm.template_category  = pcat.id LEFT JOIN marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id  where sm.id = ".$parent_id.";";
		$query = $this->db->query($sql_ins);
		$lastId = mysql_insert_id();
		
	$sql_ins1 = "INSERT INTO ".$this->table_name_transcation." (social_template_id, platform) SELECT ".$lastId." as social_template_id, tsol1.platform FROM ".$this->table_name." as sol LEFT JOIN ".$parent_db.".".$this->table_name." sol1 ON sol.superadmin_template_id  = sol1.id LEFT JOIN ".$parent_db.".".$this->table_name_transcation." tsol1 ON tsol1.social_template_id  = sol1.id  where sol.id = ".$lastId.";";
		$query = $this->db->query($sql_ins1);
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