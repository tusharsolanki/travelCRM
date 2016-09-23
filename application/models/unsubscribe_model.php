<?php
	/*
    @Description: Admin Model
    @Author: Jayesh Rojasara
    @Date: 07-05-14
	*/
class unsubscribe_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'contact_emails_trans';
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
			$param_selfold = array('email'=>$email);
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
   
    public function get_user($getfields='', $match_values = '',$condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$database_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$database_name.".".$this->table_name;
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
        @Description: Function is for Insert user details
        @Author     : Jayesh Rojasara
        @Input      : user details
        @Output     : Insert record into DB
        @Date       :07-05-14
    */
    function insert_user($data)
    {
        $this->db->insert($this->table_name,$data);	
	}
    /*
        @Description: Function is for update user details by Admin
        @Author     : Jayesh Rojasara
        @Input      : user details
        @Output     : Update record into db
        @Date: 	07-05-14
    */
    public function update_user($database,$data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($database.'.contact_master',$data); 
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
}