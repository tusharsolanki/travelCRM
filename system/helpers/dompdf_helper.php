<?php
//echo BASEPATH;
//echo '<pre>';print_r($_SERVER);exit;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Try increasing memory available, mostly for PDF generation
 */
ini_set('memory_limit', '-1');

function pdf_create($html, $filename, $stream=TRUE, $orientation="portrait") {
    require_once(BASEPATH . "helpers/dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();

    // Set Custome PDF SIZE
       // $paper_size = array(0,0,1200,1100);
       // $dompdf->set_paper($paper_size);
    // END
    
    $dompdf->set_paper("a4", $orientation);
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) { //open only
        $dompdf->stream($filename . ".pdf");
    } else { // save to file only, your going to load the file helper for this one
        write_file("pdf/$filename.pdf", $dompdf->output());
    }
}
?>