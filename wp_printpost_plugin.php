<?php
/*
 * Plugin Name: Print version post
 * Description: Печатная версия статьи
 * Author:      SVteam
 * Version:     1.2
 */

add_action('init', 'getPrintVersion');

function getPrintVersion()
{
  $serverUrl = $_SERVER['REQUEST_URI'];
  $extension = '.pdf';

  if(substr_compare( $serverUrl, $extension, -strlen( $extension ) ) === 0) {
    preg_match('#/(\d+)-#', $serverUrl, $match);

    if(isset($match[1]) && is_numeric($match[1]) && $post = get_post($match[1])) {
      $content = prepareContent($post->post_content);
      createPdf($content);
    }   
  }
}

function createPdf(string $content)
{
  require_once __DIR__ . '/vendor/autoload.php';

  $mpdf = new \Mpdf\Mpdf();
  $mpdf->SetHeader(get_bloginfo());
  $mpdf->WriteHTML($content);
  $mpdf->Output();
}

function prepareContent(string $content)
{
  $content = str_replace('Видео', '', $content);
  return $content;
}