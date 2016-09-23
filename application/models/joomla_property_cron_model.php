<?php

/*
    @Description: Joomla Property cron Model
    @Author     : Sanjay Moghariya
    @Input      : 
    @Output     : 
    @Date       : 18-11-2014
*/

class joomla_property_cron_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'joomla_property_cron_master';
        $this->table_name_transcation='joomla_property_cron_trans';
    }

    /*
        @Description: Function for get joomla property cron list
        @Author     : Sanjay Moghariy
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Joomla property cron list
        @Date       : 18-11-2014
    */
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause = '',$totalrow='')
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
		
        if(!empty($where_clause))
        {
                $where_field = "WHERE ";
                foreach($where_clause as $key=>$value)
                        $where_field .= $key.' = '.$value.' AND ';
                if(!empty($where))
                        $where = $where_field.'('.ltrim($where,'WHERE ').')';	
                else
                        $where = rtrim($where_field,'AND ');		
        }
		
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrow))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	
    /*
        @Description: Function for get joomla property cron trans list
        @Author     : Sanjay Moghariy
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Joomla property cron trans list
        @Date       : 18-11-2014
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
        @Author     : Sanjay Moghariya
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Database records
        @Date       : 18-11-2014
    */
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$match3='',$totalrow='')
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

        if($match3 != null &&  $compare_type != null )
            $this->db->or_like($match3);

        if($offset != null && $num != null)
                $this->db->limit($num,$offset);
        elseif($num != null )
                $this->db->limit($num);

        $query_FC = $this->db->get();

        //echo $this->db->last_query();
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
			return $query_FC->result_array();
    }
    
    /*
    @Description: Function is for Insert Task details by Admin
    @Author: Mohit Trivedi
    @Input: Task details for Insert into DB
    @Output: - Insert record into DB
    @Date: 02-08-2014
    */
	
    function get_admin_users_list()
    {
        //'lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name'
        $this->db->select('lm.id,lm.admin_name,lm.email_id,lm.user_id,lm.status,lm.user_type,um.agent_id,CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.status as user_status',false);
        $this->db->from('login_master as lm');
        $this->db->join('user_master as um','um.id = lm.user_id','left');
        $this->db->where("(lm.user_type = '2' OR lm.user_type = '3') AND lm.status = '1' AND (um.status = '1' OR lm.user_type = '2')");
        $this->db->group_by('lm.id');
        $query_FC = $this->db->get();
		
        //echo $this->db->last_query();

        //pr($query_FC->result_array());

        return $query_FC->result_array();
    }

    /*
        @Description: Function is for Insert Joomla property cron data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron data for Insert into DB
        @Output     : Insert record into DB
        @Date       : 18-11-2014
    */
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
	
    /*
        @Description: Function is for Insert Joomla property cron trans data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron trans data for Insert into DB
        @Output     : Insert record into DB
        @Date       : 18-11-2014
    */
    function insert_joomla_cron_trans($data)
    {
        $result =  $this->db->insert($this->table_name_transcation,$data);
        $lastId = mysql_insert_id();
        return $lastId;
    }

    /*
        @Description: Function is for update Joomla property cron data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron
        @Output     : Update record into DB
        @Date       : 18-11-2014
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
    
    /*
        @Description: Function is for update Joomla property cron trans data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron trans
        @Output     : Update record into DB
        @Date       : 18-11-2014
    */
    public function update_task($data)
    {
        $this->db->where('joomla_property_cron_master_id',$data['joomla_property_cron_master_id']);
        $this->db->where('contact_id',$data['contact_id']);
        $query = $this->db->update($this->table_name_transcation,$data); 
    }

    /*
        @Description: Function is for update Joomla property cron trans data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron trans
        @Output     : Update record into DB
        @Date       : 18-11-2014
    */
    public function update_task1($data)
    {
       	//pr($data);exit;
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_transcation,$data); 
    }

    /*
        @Description: Function is for Delete Joomla property cron data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron id
        @Output     : Delete record from DB
        @Date       : 18-11-2014
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
    /*
        @Description: Function is for Delete Joomla property cron trans data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron trans id
        @Output     : Delete record from DB
        @Date       : 18-11-2014
    */
    public function delete_record1($data)
    {
        $this->db->where('joomla_property_cron_master_id',$data['joomla_property_cron_master_id']);
        $this->db->where('contact_id',$data['contact_id']);
        $this->db->delete($this->table_name_transcation);
    }
    
    /*
        @Description: Function is for Delete Joomla property cron trans data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron trans id
        @Output     : Delete record from DB
        @Date       : 18-11-2014
    */
    public function delete_trans_cron($id,$contact_id='')
    {
        if(!empty($contact_id)) {
            $this->db->where('contact_id',$contact_id);
        }
        $this->db->where('joomla_property_cron_master_id',$id);
        $this->db->delete($this->table_name_transcation);
    }
    
    /*
        @Description: Function is for Delete Joomla property cron trans data
        @Author     : Sanjay Moghariya
        @Input      : Joomla property cron trans id
        @Output     : Delete record from DB
        @Date       : 18-12-2014
    */
    public function delete_from_trans($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_transcation);
    }
    
    public function cron_fetch_event($db_name1='')
    {
            $curdate=date('Y-m-d H:i:s');
            $this->db->select('*');

            if(!empty($db_name1))
                    $this->db->from($db_name1.".".$this->table_name);
            else
                    $this->db->from($this->table_name);

            $this->db->where('is_email','1');
            $this->db->where("CONCAT(start_date, ' ', start_time) >", $curdate);
            $this->db->where('reminder_email_date <=',$curdate);
            $this->db->where('task_id',0);
            $this->db->where('is_mail_sent',0);
            //$this->db->get()->result_array();
            return $this->db->get()->result_array();
            //echo $this->db->last_query();exit;
    }
}