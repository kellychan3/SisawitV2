<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once dirname(__FILE__) . '/dompdf/autoload.inc.php';

class Pdf
{
    public function createPDF($html, $filename = '', $download = true, $paper = 'A4', $orientation = 'portrait')
    {
	$dompdf = new Dompdf\Dompdf();
	$options = new Dompdf\Options();
	$options->set('isRemoteEnabled',false);
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);
	$dompdf->setOptions($options);
        $dompdf->render();
        if ($download) {
            $dompdf->stream($filename . '.pdf', array('Attachment' => TRUE));
        } else {
            $dompdf->stream($filename . '.pdf', array('Attachment' => FALSE));
        }
    }
}
