<?php 
if(!empty($size_w) && !empty($size_h)){
$mypdf = new mPDF('', array($size_w,$size_h), '', '', '0', '0', '0', '0', '0', '0');
}
else{
	//redirect(site_url('user/mail_out'));
	$mypdf = new mPDF('','', '', '', '0', '0', '0', '0', '0', '0');
}

//mode,format,default_font_size,default_font,margin_left,margin_right,margin_top,margin_bottom,margin_header,margin_footer,orientation

//$mypdf->showWatermarkImage = true;
//$mypdf->SetWatermarkImage("images/user/logo/logo.png");
$img_path = $this->config->item('image_path');
//$stylesheet = file_get_contents('tablesorter.css');
//$mypdf->Cell(50,10,'Employee File Management Website',0,0,'C');
// $this->fpdf->Ln(5);
$html = '';

/*$mypdf->SetHTMLHeader('
<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">{PAGENO}</div>
', 'O', true);*/

$mypdf->SetHTMLHeader('', 'O', true);

$mypdf->SetHTMLFooter('
', 'O', true);
//$html.=$this->load->view('report/pdf/mail_new');
$html.='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Livewire CRM</title>
</head>
<body>';
/*$html.='<table class="iconment_title_in" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:12px;border:0.5px solid #4A4A4A;">';*/
					
                    if(!empty($templatedata) && count($templatedata) > 0)
                    { 
						
						$html.=/*'<tr>
						  <td valign="middle">'.*/$templatedata/*.'</td>
						</tr>'*/;
					} 
					else {
						 $html.=/*'<tr>
						  <td colspan="9"  valign="middle"><h5>'.*/$this->lang->line('common_list_not_found')/*.'</h5></td>
						</tr>'*/;
					}
          /*$html.=' </table>';*/
	
$html.='</body></html>';

//echo $html;exit;
$mypdf->WriteHTML($html);
$mypdf->Output('myFile.pdf','D');
//$mypdf->Output();