<?php 
$mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
//$mypdf->showWatermarkImage = true;
//$mypdf->SetWatermarkImage("images/admin/logo/logo.png");
$img_path = $this->config->item('image_path');
//$stylesheet = file_get_contents('tablesorter.css');
//$mypdf->Cell(50,10,'Employee File Management Website',0,0,'C');
// $this->fpdf->Ln(5);
$html = '';

$mypdf->SetHTMLHeader('
<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">{PAGENO}</div>
', 'O', true);
$mypdf->SetHTMLFooter('
<table width="100%" border="0" cellpadding="0" >
  <tr>
             <td colspan="3" style="padding-bottom:10px;"> © Copyright 2014 LivewireCRM</td>
             <td align="right"><span style="float:right;font-size:18px;font-weight:bold;color:#376091;padding-right:20px;padding-bottom:30px;">Compare Report</span></td>
  </tr>
</table>
', 'O', true);
//$html.=$this->load->view('report/pdf/mail_new');
$html.='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Livewire CRM</title>
</head>
<body>';
$html.='<table class="iconment_title_in" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:12px;border:0.5px solid #4A4A4A;">';
                    if(!empty($templatedata) && count($templatedata) > 0)
                    { 
                        $html.='<tr>
						  <td valign="middle">'.$templatedata.'</td>
						</tr>';
					} 
					else {
						 $html.='<tr>
						  <td colspan="9"  valign="middle"><h5>'.$this->lang->line('common_list_not_found').'</h5></td>
						</tr>';
					}
          $html.=' </table>';
$html.='</body></html>';

//echo $html;exit;
$mypdf->WriteHTML($html);
$mypdf->Output('myFile.pdf','D');
//$mypdf->Output();