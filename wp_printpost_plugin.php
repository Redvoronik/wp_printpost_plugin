<?php
/*
 * Plugin Name: Print version post
 * Description: Печатная версия статьи
 * Author:      SVteam
 * Version:     1.0
 */
require 'mpdf60/mpdf.php';

add_action('init', 'redirect_to_register');

function redirect_to_register(){

  $serverUrl = $_SERVER['REQUEST_URI'];
  $extension = '.pdf';

  $file = 'download.pdf';

  if(substr_compare( $serverUrl, $extension, -strlen( $extension ) ) === 0) {

    preg_match('#/(\d+)-#', $serverUrl, $match);

    if(isset($match[1]) && is_numeric($match[1])) {
      $post = get_post($match[1]);
      $content = $post->post_content;

      $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4'
      ]);

      $mpdf->WriteHTML($content,2);

      $mpdf->Output($file, 'F');


      $filecontent = file_get_contents($file);

      header('Content-Type: application/pdf');
      header('Content-Length: ' . strlen($filecontent));
      header('Content-Disposition: inline; filename="' . $file . '"');
      header('Cache-Control: private, max-age=0, must-revalidate');
      header('Pragma: public');
      ini_set('zlib.output_compression','0');

      die($filecontent);
    }   
  }
}