<link rel="stylesheet" href="<?=$this->config->item('css_path')?>pdfcrm.css" type="text/css">
<?php
$pdf_data = '';

$pdf_data .= '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Neighborhood Data</title>
    </head>
    <body>
        <table width="100%" cellpadding="0" cellspacing="0" border="1">';
            if(!empty($zillow_data))
            {
                foreach($zillow_data->response->pages as $pages)
                {
                    $pdf_data .="<tr><td colspan='3' class='tdheight'>Affordability</td></tr>";
                    foreach($pages->page as $page)
                    {
                        if($page->name == 'Affordability')
                        {
                            //pr($page->tables->table);
                            foreach($page->tables->table as $aff_data)
                            {
                                foreach($aff_data->data as $attr)
                                {
                                    foreach($attr->children() as $attrib)
                                    {
                                        //pr($attrib);
                                        $pdf_data .= "<tr><td colspan='2' class='tdheight2'>".$attrib->name."</td>";
                                        $type = $attrib->values->neighborhood->value['type'].' ';

                                        if(!empty($attrib->values->neighborhood->value[0]))
                                        {
                                            if(trim($type) == 'percent')
                                                $attr_val = $attrib->values->neighborhood->value[0].'%';
                                            else
                                                $attr_val = $attrib->values->neighborhood->value[0].' '.$type;
                                            
                                            $pdf_data .= "<td>".$attr_val."</td></tr>";
                                        }
                                        else
                                            $pdf_data .= "<td>-</td></tr>";
                                    }
                                }
                            }
                        }
                        else if($page->name == 'Homes & Real Estate')
                        {
                            $pdf_data .="<tr><td colspan='3' class='tdheight'>Homes & Real Estate</td></tr>";
                            foreach($page->tables->table as $hnre)
                            {
                                $name = trim($hnre->name);
                                $pdf_data .="<tr><td colspan='3' class='tdheight'>".$name."</td></tr>";
                                foreach($hnre->data->attribute as $attrib)
                                {
                                    if($name == 'Homes & Real Estate Data')
                                    {
                                        $pdf_data .="<tr><td colspan='2' class='tdheight2'>".$attrib->name."</td>";
                                        $type = $attrib->values->neighborhood->value['type'].' ';
                                        if(!empty($attrib->values->neighborhood->value[0]))
                                        {
                                            if(trim($type) == 'percent')
                                                $attr_val = $attrib->values->neighborhood->value[0].'%';
                                            else
                                                $attr_val = $attrib->values->neighborhood->value[0].' '.$type;
                                            $pdf_data .="<td>".$attr_val."</td></tr>";
                                        }
                                        else
                                            $pdf_data .="<td>-</td></tr>";
                                    }
                                    else
                                    {
                                        $pdf_data .="<tr><td colspan='2' class='tdheight2'>".$attrib->name."</td>";
                                        $type = $attrib->value['type'].' ';

                                        if(!empty($attrib->value[0]))
                                        {
                                            if(trim($type) == 'percent')
                                                $attr_val = $attrib->value[0].'%';
                                            else
                                                $attr_val = $attrib->value[0].' '.$type;

                                            $pdf_data .="<td>".$attr_val."</td></tr>";
                                        }
                                        else
                                            $pdf_data .="<td>-</td></tr>";
                                    }
                                    /*else if($name == 'Census Summary-HomeSize')
                                    {

                                    }
                                    else if($name == 'Census Summary-HomeType')
                                    {

                                    }
                                    else if($name == 'Census Summary-Occupancy')
                                    {

                                    }*/
                                }
                            }
                        }
                        else if($page->name == 'People')
                        {
                            $pdf_data .="<tr><td colspan='3' class='tdheight'>People</td></tr>";
                            foreach($page->tables->table as $people)
                            {
                                $name = trim($people->name);
                                $pdf_data .="<tr><td colspan='3' class='tdheight'>".$name."</td></tr>";
                                foreach($people->data->attribute as $attrib)
                                {
                                    if($name == 'People Data')
                                    {
                                        $pdf_data .="<tr><td colspan='2' class='tdheight2'>".$attrib->name."</td>";
                                        $type = $attrib->values->neighborhood->value['type'].' ';
                                        if(!empty($attrib->values->neighborhood->value[0]))
                                        {
                                            if(trim($type) == 'percent')
                                                $attr_val = $attrib->values->neighborhood->value[0].'%';
                                            else
                                                $attr_val = $attrib->values->neighborhood->value[0].' '.$type;

                                            $pdf_data .="<td>".$attr_val."</td></tr>";
                                        }
                                        else
                                            $pdf_data .="<td>-</td></tr>";
                                        
                                    }
                                    else
                                    {
                                        $pdf_data .="<tr><td colspan='2' class='tdheight2'>".$attrib->name."</td>";
                                        $type = $attrib->value['type'].' ';

                                        if(trim($type) == 'percent')
                                            $attr_val = $attrib->value[0].'%';
                                        else
                                            $attr_val = $attrib->value[0].' '.$type;
                                        $pdf_data .="<td>".$attr_val."</td></tr>";
                                    }
                                }
                            }
                            $pdf_data .="<tr><td colspan='3' class='tdheight'>Segmentation</td></tr>";
                            foreach($page->segmentation->liveshere as $shere)
                            {
                                $pdf_data .="<tr><td>".$shere->title."</td>";
                                $pdf_data .="<td>".$shere->name."</td>";
                                $pdf_data .="<td>".$shere->description."</td></tr>";
                            }
                            $pdf_data .="<tr><td colspan='3' class='tdheight'>Uniqueness</td></tr>";
                            foreach($page->uniqueness->category as $cat)
                            {
                                $pdf_data .="<tr><td colspan='2' class='tdheight2'>".$cat['type']."</td><td>";
                                foreach($cat->characteristic as $char)
                                {
                                    $pdf_data .= $char."<br />";
                                }
                                $pdf_data .= "</td></tr>";
                            }
                        }
                    }
                }
            }
        $pdf_data .= '</table>
    </body>
</html>';
echo $pdf_data;
?>
    