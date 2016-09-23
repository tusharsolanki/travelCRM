<?php
/*
	@Description: Superadmin Map Joomla
	@Author: Ami Bhatti
	@Date: 08-10-14
*/

class child_admin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct(); 
        $this->table_name = 'child_admin_website';
		$this->table_name_child_website_carousels_master = 'child_website_carousels_master';
    }
    /*
        @Description: Check Login is valid or not
        @Author     : Mohit Trivedi
        @Input      : Superadmin Email id and Password
        @Output     : If validate then go to home page else login error
        @Date       : 30-08-14
    */  
    
    public function check_email($email, $id)
    {
			$param_selfold = array('email_id'=>$email);
            $this->db->select();
            $this->db->from($this->table_name);
            $this->db->where($param_selfold);
			$this->db->where('id !=',$id);
            $query= $this->db->get();
		    return $query->result_array();
	}
    /*
        @Description: Function for get Superadmin List (Customer)
        @Author     : Mohit Trivedi
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Superadmin details
        @Date       : 30-08-14
    */
   
    public function get_user($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='',$not_where='')
    {
		//pr($where);
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
          
            $where = 'WHERE (';
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
			
			$where .= ')';
			
			if($where_cond)
        	{
				foreach($where_cond as $key=>$value)
				{   
					$where .= ' AND ('.$key.' ';
					$where .= ' = "'.$value.'")';
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
	
	
	
	public function get_admin($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='')
    {
		//pr($where);
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
          
            $where = 'WHERE (';
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
			
			$where .= ')';
			
			if($where_cond)
        	{
				foreach($where_cond as $key=>$value)
				{   
					$where .= ' AND ('.$key.' ';
					$where .= ' = "'.$value.'")';
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
        @Description: Function is for Insert Superadmin details
        @Author     : Mohit Trivedi
        @Input      : Superadmin details
        @Output     : Insert record into DB
        @Date       : 30-08-14
    */
    function insert_record($data)
    {
        $this->db->insert($this->table_name,$data);
        return $this->db->insert_id();
    }

    /*
        @Description: Function is for update Superadmin details by Superadmin
        @Author     : Mohit Trivedi
        @Input      : Superadmin details
        @Output     : Update record into db
        @Date		: 30-08-14
    */
	
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();exit;
    }
	
    /*
        @Description: Function for Delete Customer Profile By Superadmin
        @Author     : Mohit Trivedi
        @Input      : Superadmin id
        @Output     : Delete record from db
        @Date       : 30-08-14
    */
	
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);            
    }
	
	/*
        @Description: Function is for Insert carousels data
        @Author     : Sanjay Chabhadiya
        @Input      : carousels Property data
        @Output     : 
        @Date       : 16-04-2015
    */
	
    function insert_record_carousels_master($data,$db_name='')
    {
		if(!empty($db_name))
			$this->db->insert($db_name.'.'.$this->table_name_child_website_carousels_master,$data);	
		else
        	$this->db->insert($this->table_name_child_website_carousels_master,$data);	
        return mysql_insert_id();
    }
	
	/*
        @Description: Function for insert Carousels Property
        @Author     : Sanjay Chabhadiya
        @Input      : 
        @Output     : 
        @Date       : 16-04-2015
    */
	
	public function insert_carousels_trans($carouse_id='',$old_carouse_id='',$common_data='')
	{
		if(!empty($old_carouse_id) && !empty($carouse_id))
		{
			$sql = 'INSERT INTO '.$common_data['new_db_name'].'.child_website_carousels_trans (property_id,carousels_id,created_by,created_date,status) SELECT property_id,'.$carouse_id.','.$common_data['lead_user_id'].',created_date,status FROM '.$common_data['old_db_name'].'.child_website_carousels_trans WHERE carousels_id = '.$old_carouse_id;
			$this->db->query($sql);
		}
	}
	
	/*
        @Description: Function for insert Carousels Property
        @Author     : Sanjay Chabhadiya
        @Input      : 
        @Output     : 
        @Date       : 16-04-2015
    */
	
	public function insert_banner_master($common_data)
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.child_website_banner_master (banner_image,domain_id,created_by,created_date,status) SELECT banner_image,'.$common_data['domain_id'].','.$common_data['lead_user_id'].',created_date,status FROM '.$common_data['old_db_name'].'.child_website_banner_master WHERE created_by = '.$common_data['old_lead_user'];
		$this->db->query($sql);
	}
	
	/*
		@Description: Insert users all last login into new db
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 16-04-2015
	*/
	
	public function insert_last_login($common_data='',$user_id='',$contact_id='',$old_user_id='')
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.joomla_rpl_log (uid,log_date,ip,domain,domain_id,lw_admin_id,created_date,status) SELECT '.$user_id.',log_date,ip,domain,'.$common_data['domain_id'].','.$contact_id.',created_date,status FROM '.$common_data['old_db_name'].'.joomla_rpl_log WHERE uid = '.$old_user_id;
		$this->db->query($sql);
	}
	
	/*
		@Description: Insert view property into new admin DB
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 16-04-2015
	*/
	
	public function insert_property_view($common_data,$user_id='',$contact_id='',$old_user_id='')
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.joomla_rpl_track (uid,mlsid,propery_name,log_date,views,domain,domain_id,lw_admin_id,created_date,status) SELECT '.$user_id.',mlsid,propery_name,log_date,views,domain,'.$common_data['domain_id'].','.$contact_id.',created_date,status FROM '.$common_data['old_db_name'].'.joomla_rpl_track WHERE uid = '.$old_user_id;
		$this->db->query($sql);
	}
	
	/*
		@Description: Insert Blog category into new admin DB
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 16-04-2015
	*/
	
	public function insert_blog_category_master($common_data)
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.blog_category_master (domain_id,category_name,created_date,status,copy_id,created_by) SELECT '.$common_data['domain_id'].',category_name,created_date,status,id,'.$common_data['lead_user_id'].' FROM '.$common_data['old_db_name'].'.blog_category_master WHERE created_by = '.$common_data['old_lead_user'];
		$this->db->query($sql);
	}
	 
	 
	/*
		@Description: Insert Blog category into new admin DB
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 16-04-2015
	*/
	
	public function insert_blog_post($common_data)
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.blog_post (domain_id,post_image,post_title,post_name,post_content,meta_title,meta_keyword,meta_description,post_date,comment_count,copy_id,created_date,post_status,created_by) SELECT '.$common_data['domain_id'].',post_image,post_title,post_name,post_content,meta_title,meta_keyword,meta_description,post_date,comment_count,id,created_date,post_status,'.$common_data['lead_user_id'].' FROM '.$common_data['old_db_name'].'.blog_post WHERE created_by = '.$common_data['old_lead_user'];
		$this->db->query($sql);
	}
	
	
	/*
		@Description: Insert Blog Post Category Transaction into new admin DB
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 16-04-2015
	*/
	
	public function insert_blog_post_category_trans($common_data)
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.blog_post_category_trans (post_id,category_id,created_date,created_by) SELECT blog.id,bcm.id as ID,bct.created_date,'.$common_data['lead_user_id'].' FROM '.$common_data['old_db_name'].'.blog_post_category_trans bct LEFT JOIN '.$common_data['new_db_name'].'.blog_post blog ON bct.post_id = blog.copy_id LEFT JOIN '.$common_data['new_db_name'].'.blog_category_master bcm ON bcm.copy_id = bct.category_id WHERE bct.created_by = '.$common_data['old_lead_user'];
		$this->db->query($sql);
	}  
	
	/*
		@Description: Insert Blog Post Comment into new admin DB
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 16-04-2015
	*/
	
	public function insert_blog_post_comment($common_data)
	{
		$sql = 'INSERT INTO '.$common_data['new_db_name'].'.blog_post_comments (post_id,comment_author,comment_author_email,comment_author_url,comment_date,comment_content,comment_parent,created_date,comment_status) SELECT blog.id,comment_author,comment_author_email,comment_author_url,comment_date,comment_content,comment_parent,bct.created_date,comment_status FROM '.$common_data['old_db_name'].'.blog_post_comments bct JOIN '.$common_data['new_db_name'].'.blog_post blog ON bct.post_id = blog.copy_id';
		$this->db->query($sql);
	}
	
	/*
                @Description: Insert Blog Post Comment into new admin DB
                @Author: Sanjay Chabhadiya
                @Input: 
                @Output: 
                @Date: 16-04-2015
	*/
	
	public function insert_user_save_property($common_data,$user_id='',$contact_id='',$old_user_id='')
	{
            $sql = 'INSERT INTO '.$common_data['new_db_name'].'.joomla_rpl_bookmarks (uid,pid,mlsid,propery_name,date,domain,created_date,status,lw_admin_id) SELECT '.$user_id.',pid,mlsid,propery_name,date,domain,created_date,status,'.$contact_id.' FROM '.$common_data['old_db_name'].'.joomla_rpl_bookmarks WHERE uid = '.$old_user_id;
            $this->db->query($sql);
	}
        
        /*
                @Description: Insert users request showing into new DB
                @Author: Sanjay Chabhadiya
                @Input: 
                @Output: 
                @Date: 24-04-2015
	*/
	
	public function insert_showing_request($common_data,$user_id='',$contact_id='',$old_user_id='')
	{
            $sql = 'INSERT INTO '.$common_data['new_db_name'].'.joomla_rpl_property_contact (uid,pid,mlsid,property_name,domain,domain_id,name,email,phone,comments,preferred_time,form_type,mailgun_id,created_date,status,lw_admin_id) SELECT '.$user_id.',pid,mlsid,property_name,domain,'.$common_data['domain_id'].',name,email,phone,comments,preferred_time,form_type,mailgun_id,created_date,status,'.$contact_id.' FROM '.$common_data['old_db_name'].'.joomla_rpl_property_contact WHERE uid = '.$old_user_id;
            $this->db->query($sql);
	}
        
        /*
                @Description: Insert users property valuation searches into new DB
                @Author: Sanjay Chabhadiya
                @Input: 
                @Output: 
                @Date: 25-04-2015
	*/
	
	public function insert_property_valuation_searches($common_data,$user_id='',$contact_id='',$old_user_id='')
	{
            $sql = 'INSERT INTO '.$common_data['new_db_name'].'.joomla_rpl_property_valuation_searches (joomla_uid,lw_admin_id,search_address,city,state,zip_code,date,domain,domain_id,send_report,report_timeline,created_date,status) SELECT '.$user_id.','.$contact_id.',search_address,city,state,zip_code,date,domain,'.$common_data['domain_id'].',send_report,report_timeline,created_date,status FROM '.$common_data['old_db_name'].'.joomla_rpl_property_valuation_searches WHERE joomla_uid = '.$old_user_id;
            $this->db->query($sql);
	}
        
        /*
                @Description: Insert users valuation contact into new DB
                @Author: Sanjay Chabhadiya
                @Input: 
                @Output: 
                @Date: 25-04-2015
	*/
	
	public function insert_valuation_contact($common_data,$user_id='',$contact_id='',$old_user_id='')
	{
            $sql = 'INSERT INTO '.$common_data['new_db_name'].'.joomla_rpl_valuation_contact (uid,lw_admin_id,property_name,domain,domain_id,name,email,phone,comments,preferred_time,created_date,status) SELECT '.$user_id.','.$contact_id.',property_name,domain,'.$common_data['domain_id'].',name,email,phone,comments,preferred_time,created_date,status FROM '.$common_data['old_db_name'].'.joomla_rpl_valuation_contact WHERE uid = '.$old_user_id;
            $this->db->query($sql);
	}
}