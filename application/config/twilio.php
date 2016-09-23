<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Name:  Twilio
	*
	* Author: Ben Edmunds
	*		  ben.edmunds@gmail.com
	*         @benedmunds
	*
	* Location:
	*
	* Created:  03.29.2011
	*
	* Description:  Twilio configuration settings.
	*
	*
	*/

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	$config['mode']   = 'sandbox';
	//$config['mode']   = 'prod';

	/**
	 * Account SID
	 **/
        //$config['account_sid']   = 'ACf4a430706f0854003e777e834842725c'; /*Client Test*/ 
		//$config['account_sid']   = 'ACb3160db6dfe1257e08101949aa71b4a9'; /*Client Live*/
		//$config['account_sid']   = 'ACc978f13a8d1fce52968190c0f7b3dc3e'; /*Niral*/
		//$config['account_sid']   = 'AC4ec32109e9a34e30d1ac2d8f906466d2'; /*Niral*/
		
		//$config['account_sid']   = 'AC32c9e2c9127c55f965b34e25b2a7e77b'; /*Live Account Brian*/
		//$config['account_sid']   = 'ACeabd646c72c996cb0216ab7fc5a99d7b'; /*Live Sub-Account Brian*/
        
	/**

	 * Auth Token
	 **/
        //$config['auth_token']    = 'd5e8fb5d20386ce571978518110b88dd'; /*Client Test*/
		//$config['auth_token']    = '880af6bb87d90da06e5e26543d492007'; /*Client Live*/
		//$config['auth_token']    = '02c3f9c395890a732cb6eaee5e4f10e9'; /*Niral*/
		//$config['auth_token']    = 'd6c38b26293265129e8a295cc1a46427'; /*Niral*/
		
		//$config['auth_token']    = '1bdb231ae2529705edc2c49910181b3e'; /*Live Account Brian*/
		//$config['auth_token']    = 'd1dd0abd4a0f5918b9862c7227104f6e'; /*Live Sub-Account Brian*/

        /**
	 * API Version
	 **/

	$config['api_version']   = '2010-04-01';

	/**
	 * Twilio programable Phone Number which provide by twillo 
	 **/

        //$config['number'] = '+1 620-330-9812'; /*Client*/
		//$config['number'] = '+15005550006'; /*Test*/
		//$config['number'] = '+15177988534'; /*Niral*/
		//$config['number'] = '+15084554748'; /*Niral*/
		
		//$config['number'] = '+12064296720'; /*Live Account Brian*/
		//$config['number'] = '+18582630620'; /*Live Sub-Account Brian*/
		
		
	///////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		/* Test account Niral(Old) */
		
		$config['account_sid']   = 'ACc978f13a8d1fce52968190c0f7b3dc3e'; 
		$config['auth_token']    = '02c3f9c395890a732cb6eaee5e4f10e9'; 
		$config['number'] 		 = '+15177988534'; 
		
		/* Test account Niral(New-working) */
		
		/*$config['account_sid']   = 'AC4ec32109e9a34e30d1ac2d8f906466d2'; 
		$config['auth_token']    = 'd6c38b26293265129e8a295cc1a46427';
		$config['number'] 		 = '+15084554748'; */
		
		/* Live Account Brian */
		
		/*$config['account_sid']   = 'AC32c9e2c9127c55f965b34e25b2a7e77b';
		$config['auth_token']    = '1bdb231ae2529705edc2c49910181b3e';
		$config['number'] = '+12064296720';*/
		
		/* Live Sub-Account Brian */
		
		/*$config['account_sid']   = 'ACeabd646c72c996cb0216ab7fc5a99d7b';
		$config['auth_token']    = 'd1dd0abd4a0f5918b9862c7227104f6e';
		$config['number'] = '+18582630620';*/
		
		

/* End of file twilio.php */