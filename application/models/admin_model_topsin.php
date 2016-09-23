<?php
	/*
    @Description: Admin Model
    @Author: Jayesh Rojasara
    @Date: 07-05-14
	*/

class Admin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'login_master';
    }
    /*
        @Description: Check Login is valid or not
        @Author     : Jayesh Rojasara
        @Input      : User Email id and Password
        @Output     : If validate then go to home page else login error
        @Date       : 06-05-14
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
        @Description: Function for get User List (Customer)
        @Author     : Jayesh Rojasara
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : User details
        @Date       : 07-05-14
    */
   
    public function get_user($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='')
    {
		//pr($getfields);exit;
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
        @Description: Function is for Insert user details
        @Author     : Jayesh Rojasara
        @Input      : user details
        @Output     : Insert record into DB
        @Date       :07-05-14
    */
    function insert_user($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
	}
    /*
        @Description: Function is for update user details by Admin
        @Author     : Jayesh Rojasara
        @Input      : user details
        @Output     : Update record into db
        @Date: 	07-05-14
    */
    public function update_user($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
    /*
        @Description: Function for Delete Customer Profile By Admin
        @Author     : Jayesh Rojasara
        @Input      : user id
        @Output     : Delete record from db
        @Date       : 07-05-14
    */
    public function delete_user($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);            
    }    
	
	/*
        @Description: Function to fetch new DB Name
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 08-09-14
    */
	public function getnewdbname()
	{
		$this->db->select('max(id) as max_id');
		$this->db->from('login_master');
		$query= $this->db->get();
	    $result = $query->result_array();
		
		if(count($result) > 0)
			return $result[0]['max_id'];
		else
			return 1;
	}
	
	/*
        @Description: Function to create new db
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 08-09-14
    */
	public function createnewdb($databasename='')
	{
		if($databasename != '')
		{
			$this->load->dbforge();
			if ($this->dbforge->create_database($databasename))
				return 1;
			else
				return 0;
		}
		else
			return 0;
	}
	
	public function createnewdbuser($username='')
	{
		
		// For password 41 char hash is required.
		
		$querypswd = "SELECT PASSWORD('".$username."') as u_pswd";
		$querypswd = $this->db->query($querypswd);
		$newpswd = $querypswd->result_array();
		
		//pr($newpswd);exit;
		
		$dbuserpswd = !empty($newpswd[0]['u_pswd'])?$newpswd[0]['u_pswd']:'';
		
		$query = "CREATE USER '".$username."'@'localhost' IDENTIFIED BY PASSWORD '".$dbuserpswd."'";
		$query = $this->db->query($query);
		
		$query1 = "GRANT ALL PRIVILEGES ON * . * TO  '".$username."'@'localhost' IDENTIFIED BY PASSWORD '".$dbuserpswd."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
		$query1 = $this->db->query($query1);
		
		//return $query."-".$query1;
		
	}
	
	/*
        @Description: Function to copy one db to another
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 08-09-14
    */
	public function copyonedbtoother($parent_db='',$child_db='',$lastId='',$databaseusername='')
	{
		if($parent_db != '' && $child_db != '')
		{
			////////////////////////////////////
			
			$config['hostname'] = "localhost";
			$config['username'] = "root";
			$config['password'] = "ToPs@tops$$";	//For topsdemo.in
			//$config['password'] = "";				//Local
			$config['database'] = $parent_db;
			$config['dbdriver'] = "mysql";
			$config['dbprefix'] = "";
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			
			// or as gorelative notes, to access multiple databases:
			$DB_another = $this->load->database($config, TRUE);
			// and so on
			
			// connect to the database
			
			//echo "here1";
			
			//$DB_another = $this->load->database('anotherdb', TRUE); 
			
			$tables = $DB_another->list_tables();
			
			//echo "here2";pr($tables);exit;
			
			if(!empty($tables) && count($tables) > 0)
			{
				/*if($databaseusername != '')
				{
					$query_g = "GRANT ALL PRIVILEGES ON * . * TO  '".$databaseusername."'@'localhost' IDENTIFIED BY PASSWORD '".$dbuserpswd."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
					$query_g = $this->db->query($query_g);
				}*/
				
				foreach($tables as $row)
				{
					if($row != 'login_master')
					{
						$sql = "CREATE TABLE ".$child_db.".".$row." LIKE ".$parent_db.".".$row; 
						$query = $this->db->query($sql);
						
						$sql1 = "INSERT INTO ".$child_db.".".$row." SELECT * FROM ".$parent_db.".".$row;
						$query1 = $this->db->query($sql1);
					}
					else
					{
						
						$sql = "CREATE TABLE ".$child_db.".".$row." LIKE ".$parent_db.".".$row; 
						$query = $this->db->query($sql);
						
						//echo $lastId;
						
						if($lastId != '')
						{
							$sql_ins = "INSERT INTO ".$child_db.".login_master (user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status) SELECT user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status FROM ".$parent_db.".login_master where id  = ".$lastId.";";
							$query = $this->db->query($sql_ins);
						}
						
						//exit;
						
					}
					
				}
			}
			
			//echo 1;exit;
			
			///////////////////////////////////
		
			//$sql = "SHOW TABLES FROM $parent_db";
			//$result = mysql_query($sql,$conn1);
			
			/*while($row = mysql_fetch_row($result))
			{
				$parent_db_tables[] = $row[0];
			}*/
			
			//echo "<pre>"; print_r($parent_db_tables); exit;
			
			// Copy one db tables to another db
			
			/*for($i=0; $i<count($parent_db_tables); $i++){
				$create_table = "CREATE TABLE ".$child_db.".".$parent_db_tables[$i]." LIKE ".$parent_db.".".$parent_db_tables[$i]; 
				$result1 = mysql_query($create_table,$conn2);
				
				$insert_data = "INSERT INTO ".$child_db.".".$parent_db_tables[$i]." SELECT * FROM ".$parent_db.".".$parent_db_tables[$i];
				$result2 = mysql_query($insert_data,$conn2);
			}
		
			mysql_free_result($result);*/
		}
	}

	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function getadminpagingid($admin_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('user_type','2');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $admin_id)
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
    @Description: Function for get Module Lists Multiple tables
    @Author: Kaushik Valiya
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 17-09-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='')
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
		{
			if($orderby == 'special_case')
				$this->db->order_by('is_done asc,task_date asc');
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
		
		//pr($query_FC->result_array());
		//echo $this->db->last_query();exit;
		
  		return $query_FC->result_array();
  
	}
	
}