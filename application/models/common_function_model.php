<?php

/*
    @Description: common function Model
    @Author: Jayesh Rojasara	
    @Input: 
    @Output: 
    @Date: 06-05-14*/

class Common_function_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = '';
    }
	
	/*
    @Description: generate string 
    @Author: Jayesh Rojasara
    @Input: length of string
    @Output: generate string in uppercase
    @Date: 06-05-14*/

	public function randr($j = 8)
    {
        $string = "";
        for($i=0;$i < $j;$i++)
        {
            srand((double)microtime()*1234567);
            $x = mt_rand(0,2);
            switch($x)
            {
                case 0:$string.= chr(mt_rand(97,122));break;
                case 1:$string.= chr(mt_rand(65,90));break;
                case 2:$string.= chr(mt_rand(48,57));break;
            }
        }
        return strtoupper($string);
    }
	
	/*
    @Description: common function Model for encrypt Script
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 06-05-14*/
	
	function encrypt_script($string)
	{
		$key = $this->config->item('encryption_key');
		
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
		
		return $encrypted;
	}
	
	/*
	@Description: common function Model for decrypt Script
	@Author: Jayesh Rojasara
	@Input: 
	@Output: 
	@Date: 06-05-14
	*/
	
	function decrypt_script($string)
	{
		$key = $this->config->item('encryption_key');
		
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		
		return $decrypted;
	}
	
	/*
	@Description: function to send email
	@Author: Jayesh Rojasara
	@Input: 
	@Output: 
	@Date: 22-01-2014
	*/
	
	function send_email($to='',$subject='',$message='',$from='',$cc='')
	{
		
		
		unset($config);
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['protocol'] = 'smtp';
		$config['smtp_port'] = '25';
		$config['smtp_host'] = 'mail.topsdemo.in';
		$config['smtp_user'] = 'survey@topsdemo.in';  
		$config['smtp_pass'] = 'demo123456';  
		$config['mailtype']='html';
		$config['newline']="\r\n";
		$this->load->library('email', $config);
		$this->email->initialize($config);
		$this->email->from($from,$this->config->item('sitename')." Administrator");
		//$this->email->reply_to($from,$this->config->item('sitename')." Administrator");	
		$this->email->to($to);                
		$this->email->subject($subject);
		$this->email->message($message);
		
		return $this->email->send();

		/*unset($config);
			   
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['protocol'] = 'smtp';
		$config['smtp_port'] = '26';
		$config['smtp_host'] = 'mail.tops-tech.com';
		$config['smtp_user'] = 'test@tops-tech.com';  
		$config['smtp_pass'] = 'tops123';  
		$config['mailtype']='html';
		$config['newline']="\r\n";
		$this->load->library('email', $config);

		$this->email->initialize($config);
		$this->email->from($from,$this->config->item('sitename')." Administrator");	
		
		$this->email->to($to);                
		$this->email->subject($subject);
		$this->email->message($message);
		return $this->email->send();*/
	}
}