<?php
require './assets/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
/*
    @Description: Email Campaign Master Model
    @Author: Sanjay Chabhadiya
    @Date: 02-08-2014
*/

class email_campaign_master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'email_campaign_master';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Sanjay Chabhadiya
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$db_name='')
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
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$table.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
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
		
		if(!empty($where_in)){
			foreach($where_in as $key => $value){
				$this->db->where_in($key,$value);
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
		//echo $this->db->last_query();
		//return $query_FC->result_array();
		if(!empty($totalrow))
            return $query_FC->num_rows();
		else
            return $query_FC->result_array();
  
	}

    /*
    @Description: Function is for Insert email Signature by Admin
    @Author: Sanjay Chabhadiya
    @Input: email signature details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-08-14
    */
	
	function select_email_campaign_recepient_trans($data='',$match='',$db_name='',$totalrow='')
	{
		$this->db->select('*');
		$this->db->where('email_campaign_id',$data);
		if(!empty($match))
			$this->db->where('is_send',$match['is_send']);
		if(!empty($db_name))
			$result = $this->db->get($db_name.'.email_campaign_recepient_trans');
		else
			$result = $this->db->get('email_campaign_recepient_trans');
		if(!empty($totalrow))
            return $result->num_rows();
		else
			return $result->result_array();
	}
	
	function select_email_campaign_attachments($data,$db_name='')
	{
		$this->db->select('*');
		$this->db->where('email_campaign_id',$data);
		if(!empty($db_name))
			$result = $this->db->get($db_name.'.email_campaign_attachments');
		else
			$result = $this->db->get('email_campaign_attachments');
		return $result->result_array();
	}
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

    /*
    @Description: Function is for update email Signature details by Admin
    @Author: Sanjay Chabhadiya
    @Input: email Signature details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 04-08-14
    */
    public function update_record($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
		if(!empty($db_name))
        	$query = $this->db->update($db_name.".".$this->table_name,$data);
		else
			$query = $this->db->update($this->table_name,$data);
		//echo $this->db->last_query();
    }
    /*
    @Description: Function for Delete email Signature By Admin
    @Author: Sanjay Chabhadiya
    @Input: - email Signature id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 04-08-14
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }

	function insert_email_campaign_recepient_trans($data,$db_name='')
    {
        if(!empty($db_name))
			$result =  $this->db->insert($db_name.'.'.'email_campaign_recepient_trans',$data);
		else
        	$result =  $this->db->insert('email_campaign_recepient_trans',$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_email_campaign_attachments($data)
	{
		$result =  $this->db->insert('email_campaign_attachments',$data);	
		$lastId = mysql_insert_id();
		return $lastId;
	}
	
	public function delete_email_campaign_recepient_trans($id)
    {
        $this->db->where('email_campaign_id',$id);
        $this->db->delete('email_campaign_recepient_trans');
    }
	public function delete_email_campaign_attachments($id)
    {
        $this->db->where('email_campaign_id',$id);
        $this->db->delete('email_campaign_attachments');
    }
	
	public function in_query($id)
    {	
		$this->db->where_in('id',$id);
		$result = $this->db->get('contact_master');
      //$result = $this->db->query('SELECT * FROM contact_master WHERE id in('.$id.')');
		return $result->result_array();
    }
	
	public function update_email_campaign_attachments($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update('email_campaign_attachments',$data); 
		//echo $this->db->last_query();
    }
	
	public function contact_email($id)
    {
        $this->db->where('contact_id',$id);
		$this->db->where('is_default','1');
        $result = $this->db->get('contact_emails_trans'); 
		//echo $this->db->last_query();
		return $result->result_array();
    }
	
	function in_query_data($data,$num="",$offset="",$orderby='',$sort='',$src="",$join_tables="",$match,$join_type='')
    {	
		/*$orderby = ($orderby !='')?' order by '.$orderfields.' '.$orderby.'':'order by id desc ';
        $lim = ($num != "")?" limit ".$num:'';*/
		//pr(join_tables);
		$this->db->select('cm.*');
		$this->db->from('contact_master cm');
		
		foreach($join_tables as $coll => $value)
		{
			$this->db->join($coll, $value,$join_type);
		}
		if(!empty($match))
		{
			$this->db->select('ecm.id as ID,ecm.template_subject');
			$this->db->where($match);
		}
		if(!empty($data))
		{
			$this->db->where_in('cm.id',$data);
		}
		
		if(!empty($src))
			$this->db->or_like("CONCAT_WS(' ',cm.first_name,cm.last_name)",$src);
			
		if($orderby != null && $sort != null)
			$this->db->order_by($orderby,$sort);
		elseif($orderby != null )
			$this->db->order_by($orderby);
			
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$result = $this->db->get();
		//echo $this->db->last_query();
		
		/*
			
        if($src=="")
        {
            if($offset == "")
				$query = $this->db->query("SELECT * FROM contact_master WHERE id IN(".$data.") ".$orderby." ".$lim);
			else
				$query = $this->db->query("SELECT * FROM contact_master WHERE id IN(".$data.") ".$orderby." limit ".$offset.",".$num);
			
		}
		else
		{
			 if($offset == "")
                $query = $this->db->query("SELECT * FROM contact_master WHERE id IN(".$data.") AND (CONCAT(first_name,' ',last_name) like '%".$src."%') ".$orderby." ".$lim);
            else
                $query = $this->db->query("SELECT * FROM contact_master WHERE id IN(".$data.") AND (CONCAT(first_name,' ',last_name) like '%".$src."%') ".$orderby." limit ".$offset.",".$num);
		}
		
		/*$this->db->select("*");
		$this->db->where_in('id',$data);
			
		if($condition != null )
		$this->db->where($condition);
		
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
	
		$query_FC = $this->db->get();*/
		//echo $this->db->last_query();
		return $result->result_array();
    }
	
	public function email_in_query($data)
    {
        $this->db->where_in('contact_id',$data);
		$this->db->where('is_default','1');
		$this->db->group_by('contact_id');
        $result = $this->db->get('contact_emails_trans');
		//echo $this->db->last_query();
		return $result->result_array();
    }
	
	public function contact_in_query($data)
    {
        $this->db->where_in('id',$data);
        $result = $this->db->get('contact_master');
		//echo $this->db->last_query();
		return $result->result_array();
    }
	
	public function delete_attachment($data)
	{
		$this->db->where('id',$data);
		$this->db->delete('email_campaign_attachments');
	}
	
	public function total_emails($data,$db_name='')
	{
		$this->db->select_sum('pm.email_counter');
		$this->db->from($db_name.'.user_package_trans up');
		$this->db->join($db_name.'.package_master pm','up.package_id = pm.id','left');
		$this->db->where('login_id',$data);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	public function email_campaign_trans_in_query($data)
	{
		$this->db->select('*');
		$this->db->where_in('id',$data);
        $result = $this->db->get('email_campaign_recepient_trans');
		return $result->result_array();
	}
	
	public function update_email_campaign_trans($data,$db_name='')
	{
		$this->db->where('id',$data['id']);
		if(!empty($db_name))
			$result = $this->db->update($db_name.'.email_campaign_recepient_trans',$data);
		else
        	$result = $this->db->update('email_campaign_recepient_trans',$data);
	}
	
	public function email_campaign_trans_data($data)
	{
		$this->db->where('id',$data);
		$result = $this->db->get('email_campaign_recepient_trans');
		return $result->result_array();
	}
	
	public function email_campaign_trans_data_by_type($contact_id,$contact_type)
	{
		$this->db->where($contact_type,$contact_id);
		$result = $this->db->get('email_campaign_recepient_trans');
		return $result->result_array();
	}
	
	public function email_campaign_trans_delete($data='',$id='')
	{
		if(!empty($data))
		{
			$this->db->where('email_campaign_id',$data);
			$this->db->where('is_send','0');
		}
		elseif(!empty($id))
			$this->db->where('id',$id);
		$this->db->delete('email_campaign_recepient_trans');
	}
	
	public function email_campaign_trans_fetch($data)
	{
		$this->db->where('email_campaign_id',$data);
		$this->db->where('is_send','1');
		$result = $this->db->get('email_campaign_recepient_trans');
		return $result->result_array();
	}
	
	public function delete_interaction_campaign($data)
	{
		$this->db->where($data);
        $this->db->delete('email_campaign_recepient_trans');
	}
	
	
	/*
        @Description: Function For pagination
        @Author     : Sanjay Chabhadiya
        @Input      : 
        @Output     : 
        @Date       : 10-09-14
    */

	public function getpagingid($id='')
	{
		$this->db->select('*');
		$this->db->where('email_type','Campaign');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		//echo $op;exit;
		return $op;
	}
	
	public function getpaging($id='',$campaign_id='')
	{
		$this->db->select('*');
		if(!empty($campaign_id))
			$this->db->where('email_campaign_id',$campaign_id);
		else
			$this->db->where('is_send','1');
		$this->db->from('email_campaign_recepient_trans');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		//echo $op;exit;
		return $op;
	}
	
	function check_mail_send_or_no($data)
	{
		$this->db->select('*');
		$this->db->where($data);
		$result = $this->db->get('email_campaign_recepient_trans');
		return $result->result_array();
		
	}
	
	function email_sent_against_interaction_plan_count($date1='',$date2='')
	{
		$this->db->select('COUNT(*) AS total_sent_email');
		$this->db->from('email_campaign_recepient_trans');
		//$this->db->where_not_in('id','SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans');
		if(!empty($date1) && !empty($date2))
			$this->db->where("email_campaign_id IN (SELECT id FROM email_campaign_master WHERE email_type = 'Intereaction_plan') AND is_send = '1' AND DATE_FORMAT(sent_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'", NULL, FALSE);
		else
			$this->db->where("email_campaign_id IN (SELECT id FROM email_campaign_master WHERE email_type = 'Intereaction_plan') AND is_send = '1' AND sent_date >= (DATE_SUB(CURDATE(),INTERVAL 1 MONTH))", NULL, FALSE);
		
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		if(count($result) > 0)
			return $result[0]['total_sent_email'];
		else
			return 0;
	}
	
	function MailSend($to='',$subject='',$message='',$data='')
	{
		if($this->config->item('email_send_from') == 'smtp')
		{
			//pr($data);exit;
			$this->load->library('email');
			$config = Array(
						'protocol' => 'smtp',
						'smtp_host' => 'ssl://smtp.googlemail.com',
						'smtp_port' => 465,
						'smtp_user' => 'sanjay.chabhadiya@tops-int.com',
						'smtp_timeout'	=>	'30',
						'smtp_pass' => '55551011047b',
						'mailtype'  => 'html',
				);
			//$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");
			$this->email->set_priority(1);
			$this->email->from('<'.$data['from_email'].'>',$data['from_name']);
			$this->email->to($to);
			/*if(!empty($data['campaign_id']))
				$attachment = $this->select_email_campaign_attachments($data['campaign_id']);*/
			
			if(!empty($data['attachment']))
			{
				foreach($data['attachment'] as $row_attachment)
					$this->email->attach("uploads/attachment_file/".$row_attachment['attachment_name']);
			}
			if(!empty($data['from_cc']))
				$this->email->cc($data['from_cc']);
			if(!empty($data['from_bcc']))
				$this->email->bcc($data['from_bcc']);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();
			$this->email->clear(TRUE);
			return 1;
			//exit;
		}
		elseif($this->config->item('email_send_from') == 'mailgun')
		{
			$mgClient = new Mailgun($this->config->item('domain_key'));
			$domain = $this->config->item('domain');
			$attachment = array();
			if(!empty($data['attachment']))
			{
				foreach($data['attachment'] as $row_attachment)
					$attachment[] = $this->config->item('base_path')."/uploads/attachment_file/".$row_attachment['attachment_name'];
			}
			$EmailData = array(
				'from'    => $data['from_name'].'<'.$data['from_email'].'>',
				'to'      => $to,
				'subject' => $subject,
				//'text'    => 'Testing some Mailgun awesomness!',
				'html'    => $message,
				);
			if(!empty($data['from_cc']))
				$EmailData['cc'] = $data['from_cc'];
			if(!empty($data['from_bcc']))
				$EmailData['bcc'] = $data['from_bcc'];
			//pr($EmailData);exit;
			$AttachmentData = array();
			if(!empty($attachment))
				$AttachmentData = array('attachment' => $attachment);
			$result = $mgClient->sendMessage($domain,$EmailData,$AttachmentData);
			return $result;
		}
	}
	
	function BombBombMailSend($to='',$subject='',$message='',$data='')
	{
		//$url = "https://app.bombbomb.com/app/api/api.php?method=VideoQuickSend&email=".$data['username']."&pw=".$data['password']."&email_addresses=".$to."&video_id=".urlencode($data['video_id'])."&subject=".urlencode($subject)."&mobile_message=".urlencode($message);
		/*pr($data);
		echo $to;
		echo $subject;
		echo $message;*/
		//error_reporting(E_ALL);
		//$url = "https://app.bombbomb.com/app/api/api.php?method=SendCustomVideoEmail&email=".urlencode($data['username'])."&pw=".urlencode($data['password'])."&email_addresses=".$to."&subject=".urlencode($subject)."&html_content=".urlencode($message)."&from_name=".urlencode($data['from_name'])."&from_email=".urlencode($data['from_email']);
		
		$url = "https://app.bombbomb.com/app/api/api.php?method=SendCustomVideoEmail";
		//$url="http://app.bombbomb.com/app/api/api.php?method=SendCustomVideoEmail&email=".$data['username']."&pw=".$data['password']."&email_addresses=sanjay.chabhadiya@tops-int.com&subject=sub&html_content=html&from_name=Nishit&from_email=nishit.modi@tops-int.com";
		//$res = @file_get_contents($url);
		//pr($res);//exit;
		//echo $url;
		/*$icdata['email'] = trim($data['username']);
		$icdata['pw'] = trim($data['password']);
		$icdata['email_addresses'] = $to;
		$icdata['subject'] = $subject;
		$icdata['html_content'] = $message;
		$icdata['from_name'] = $data['from_name'];
		$icdata['from_email'] = $data['from_email'];*/
		//pr($icdata);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "email=".urlencode($data['username'])."&pw=".urlencode($data['password'])."&email_addresses=".$to."&subject=".urlencode($subject)."&html_content=".urlencode($message)."&from_name=".urlencode($data['from_name'])."&from_email=".urlencode($data['from_email']));
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		// This is what solved the issue (Accepting gzidp encoding)
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
		$response = curl_exec($ch);
		curl_close($ch); 
		//pr($response);exit;
		return $response;
	}
	
}