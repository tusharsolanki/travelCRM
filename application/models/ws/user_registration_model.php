<?php

	/*
    @Description: User registration model
    @Author: Ami Bhatti
    @Input: 
    @Output: 
    @Date: 09-10-14
	*/

class user_registration_model extends CI_Model
{
    function __construct()
    {
        parent::__construct(); 
        $this->table_name = 'contact_master';
		$this->table_name_email = 'contact_emails_trans';

    }

    /*
    @Description: Function for get Module Lists
    @Author: Jayesh Rojasara
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-05-14
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
	
    function sendPasswordMail($email,$user_id,$fname,$lname='',$admin_data='',$email_temp='',$admin_email_temp='',$from_email='',$domain='',$contact_data='',$brokerage_pic='',$admin_name='',$admin_all_details='')
    {
        $this->load->library('email');
        //extract($row);
        $data['email'] = $email;
        //$data['pass'] = '123456';
        $data['name'] = ucwords($fname);
        $data['is_admin'] = '0';
        $data['admin_all_details'] = $admin_all_details;
        $data['contact_data'] = $contact_data;
        
        $contact_id = $contact_data['id'];
        $db_name = $admin_all_details['db_name'];
        if(!empty($email_temp['template_subject'])) {
            $sub = $email_temp['template_subject'];
            $sub .= !empty($domain)?' - '.$domain:'';
        }
        else {
            $d = !empty($domain)?' '.$domain:'';
            $sub = "Welcome to ".$d;
        }
        
        if(!empty($from_email))
            $from = $from_email;
        else
            $from = $this->config->item('admin_email');
        
        /*$full_name = "New Lead ";
        $from = $full_name.'<'.$from.'>';
        $headers = "From: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        */
        $edata['from_name'] = "Welcome";
        $edata['from_email'] = $from;
        if(!empty($admin_data)) // Admin Email
        {
            $data['is_admin'] = '1';
            $data['brokerage_pic'] = $admin_data['brokerage_pic'];
            //foreach($admin_data as $row)
            //{
                $to = $admin_data['email_id'];
                if(!empty($admin_email_temp['template_subject'])) {
                    $sub = $admin_email_temp['template_subject'];
                    $sub .= !empty($domain)?' - '.$domain:'';
                }
                else {
                    $d = !empty($domain)?' '.$domain:'';
                    $sub = "New Lead registered on ".$d;
                }
                
                $data['admin_name'] = $admin_data['admin_name'];
                
                if(!empty($admin_email_temp['email_message']))
                {
                    $emaildata = array(
                        'Date'=>date('Y-m-d'),
                        'Day'=>date('l'),
                        'Month'=>date('F'),
                        'Year'=>date('Y'),
                        'Day Of Week'=>date("w",time()),
                        'Agent Name'=>$data['admin_name'],
                        'Contact First Name'=> !empty($contact_data['first_name'])?ucwords($contact_data['first_name']):'',
                        'Contact Spouse/Partner First Name'=>!empty($contact_data['spousefirst_name'])?ucwords($contact_data['spousefirst_name']):'',
                        'Contact Last Name'=> !empty($contact_data['last_name'])?ucwords($contact_data['last_name']):'',
                        'Contact Spouse/Partner Last Name'=> !empty($contact_data['spouselast_name'])?ucwords($contact_data['spouselast_name']):'',
                        'Contact Company Name'=> !empty($contact_data['company_name'])?ucwords($contact_data['company_name']):''
                    );
                    
                    $pattern = "{(%s)}";
                    $map = array();

                    if($emaildata != '' && count($emaildata) > 0)
                    {
                        foreach($emaildata as $var => $value)
                        {
                            $map[sprintf($pattern, $var)] = $value;
                        }
                        $output = strtr($admin_email_temp['email_message'], $map);
                        $data['admin_temp_msg'] = $output;
                    }
                }
                $data['domain'] = $domain;
                $message   = $this->load->view('ws/user_register_email', $data, TRUE);
                
                //// Mailgun email 19-03-2015
                $subject = $sub;
                
                $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                
                $updata['id'] = $contact_id;
                $updata['mailgun_admin_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                $this->contacts_model->update_record($updata,$db_name);
                //mail($to,$sub,$message,$headers);
            //}
        } else { // Lead Email
            $to = $email;            
            if(!empty($email_temp['email_message']))
            {
                $emaildata = array(
                    'Date'=>date('Y-m-d'),
                    'Day'=>date('l'),
                    'Month'=>date('F'),
                    'Year'=>date('Y'),
                    'Day Of Week'=>date("w",time()),
                    'Agent Name'=>$admin_name,
                    'Contact First Name'=> !empty($contact_data['first_name'])?ucwords($contact_data['first_name']):'',
                    'Contact Spouse/Partner First Name'=>!empty($contact_data['spousefirst_name'])?ucwords($contact_data['spousefirst_name']):'',
                    'Contact Last Name'=> !empty($contact_data['last_name'])?ucwords($contact_data['last_name']):'',
                    'Contact Spouse/Partner Last Name'=> !empty($contact_data['spouselast_name'])?ucwords($contact_data['spouselast_name']):'',
                    'Contact Company Name'=> !empty($contact_data['company_name'])?ucwords($contact_data['company_name']):''
                );
                                   
                $pattern = "{(%s)}";
                $map = array();

                if($emaildata != '' && count($emaildata) > 0)
                {
                    foreach($emaildata as $var => $value)
                    {
                        $map[sprintf($pattern, $var)] = $value;
                    }
                    $output = strtr($email_temp['email_message'], $map);
                    $data['temp_msg'] = $output;
                }
            }
            $data['admin_name'] = $admin_name;
            $data['brokerage_pic'] = $brokerage_pic;
            $data['domain'] = $domain;
            $message = $this->load->view('ws/user_register_email', $data, TRUE);
            
            $subject = $sub;
            ///// Mailgun Email 19-03-2015
            $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                
            $updata['id'] = $contact_id;
            $updata['mailgun_contact_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
            $this->contacts_model->update_record($updata,$db_name);
            //mail($to,$sub,$message,$headers);
        }
        
        // Tushar Sir END
		
		//$this->email->from('topsdeveloper@gmail.com','Love Print');
        //$this->email->to($email);
        //$this->email->subject($subject);
        //$this->email->message($message);   
        //$this->email->send();  
        
		//mail($email,$subject,$message,$header,"-f".$from); 
    }
    
/*
        @Description: Function for get User List (Customer)
        @Author     : Ruchi Shahu
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : User details
        @Date       : 17-07-2014
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
    @Description: Function is for Insert tips details by Admin
    @Author: Jayesh Rojasara
    @Input: tips details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
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
	function insert_record_email($data,$db_name='')
    {
        if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_email,$data);	
        else
            $result =  $this->db->insert($this->table_name_email,$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }
    /*
    @Description: Function is for update customer details by Admin
    @Author: Jayesh Rojasara
    @Input: tips details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
	public function update_player_trans($data)
    {
		$this->db->where('player_id',$data['player_id']);
        $query = $this->db->update($this->table_player_trans,$data); 
    }
	
    /*
    @Description: Function for Delete tips Profile By Admin
    @Author: Jayesh Rojasara
    @Input: - tips id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_record($id)
    {
		$this->db->select('*');
		$this->db->from('team_player_trans');
		$this->db->where('player_id',$id);
		$resultdata = $this->db->get()->result();
		
		if(count($resultdata) > 0)
		{
			return 'fail';
		}
		else
		{
			$this->db->where('player_id',$id);
			$this->db->delete('team_player_trans');
			
			$this->db->where('id',$id);
			$this->db->delete($this->table_name);
		}
    }
	
	public function getplayerpagingid($player_id='')
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
				if($row['id'] == $player_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		
		return $op;
		
	}
	
	public function player_trans_records()
	{
		$this->db->select('DISTINCT player_id',false);
		$this->db->from('team_player_trans');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	public function not_select_records($playerlist='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		
		if(!empty($playerlist)){
			$this->db->where_not_in('id',$playerlist);
		}
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		
		//pr($result); exit;
		
		return $result;
	}

}