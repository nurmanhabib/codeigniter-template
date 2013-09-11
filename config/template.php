<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$template_conf = array(
	'template' => 'default',
	'site_name' => 'Site Name',
	'site_title' => 'Some slonag here',
	'devmode' => TRUE,
	'content' => '',
	'css' => '',
	'js' => '',
	'ico' => '',
	'head' => '',
	'foot' => '',
	'messages' => '',
	'assets_dir' => 'assets/'
);

$template_css = array('base');

$template_js = array();

$template_head = array(
	'jquery' => '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>',
	'bootstrap_css' => '<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">',
	'bootstrap_js' => '<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>'
);

/* End of file template.php */
/* Location: ./application/config/template.php */
