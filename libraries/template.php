<?php
/**
 * @name Codeigniter-Template
 * @author Chris Duell
 * @author_url http://www.subooa.com.au
 * @version 2.1
 * @license GPL
 *
 */
class Template
{
	var $CI;

	var $css_raw = '';
	var $css_load = '';
	var $js_raw = '';
	var $js_load = '';
	var $ico_load = '';
	var $http_headers = array();
	var $messages = array(
		'warning' => array(), 
		'error' => array(), 
		'success' => array(), 
		'info' => array()
	);
	var $modal = array(
		'title' => '', 
		'body' => '', 
		'button' => array(
			'type'	=> 'button',
			'class'	=> 'btn btn-default',
			'data'	=> 'data-dismiss = "modal"',
			'value'	=> 'Close'
			)
	);
	
	
	public function __construct($config = array())
	{
			
		$this->CI =& get_instance();
			
		if (count($config) > 0) {
			$this->initialize($config);
		} else {
			$this->_load_config_file();
		}
		
		// add in anything you want all pages to have access to to the data array
		// $this->data['user'] = $this->CI->quickauth->user();		
		
		// if you need to use things like money_format where the locale is needed
		// setlocale(LC_ALL, 'en_US.utf-8');
			
	}


	/**
	 * Initialize the template base preferences
	 *
	 * Accepts an associative array as input, containing display preferences
	 *
	 * @access	public
	 * @param	array	config preferences
	 * @return	void
	 */
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			$this->$key = $val;
		}
	}
	
	
	/**
	 * Load template specific config items 
	 * from config/subooatmpl.php
	 *
	 * including loading up default css, js and head tags
	 */
	private function _load_config_file()
	{
		if ( ! @include(APPPATH.'config/template'.EXT))
		{
			return FALSE;
		}

		foreach($template_conf as $citem => $cval)
		{
			$this->data[$citem] = $cval;
		}
		unset($template_conf);
		
		
		// display the profiler if in dev mode
		if($this->data['devmode']){
			$this->CI->output->enable_profiler(TRUE);
		}


		foreach($template_css as $css)
		{
			$this->add_css($css);
		}
		unset($template_css);


		foreach($template_js as $js)
		{
			$this->add_js($js);
		}
		unset($template_js);


		foreach($template_head as $head)
		{
			$this->add_head($head);
		}
		unset($template_head);

		return true;
	}
		
		
	
	/**
	 * Load the content for the main area of the page, and store
	 * in the data array to be later sent to the template
	 */
	function set_content($view, $data = array()){

		// Menyiapkan pesan dan modal agar dapat di akses di view content
		$this->prepare_messages();
		$this->prepare_modal();
		
		// Menggabungkan data yang di panggil dari controller ke view content
		$data	= array_merge($data, $this->data);
		
		$this->data['content'] = $this->CI->load->view($view, $data, true);
        
	}

	
	
	/**
	 * Custom: load konten ke main area yang berada di dalam template tertentu
	 * Fungsi ini sama halnya dengan set_contet(), hanya saja peletakkan file berada dalam 1 folder template
	 */
	function set_content_template($view, $data = array(), $template = ''){
		
		// Mengarahkan file content berada dalam template tertentu
		$view = 'templates/' . ($template == '' ? $this->data['template'] : $template) . '/' . $view;
		
		// Memanggil kembali fungsi set_content()
		$this->set_content($view, $data);
        
	}
	
	
	/**
	 * Clears all CSS. Raw and scripts
	 */
	function clear_css(){
		
		$this->css_raw = '';
		$this->css_scripts = '';
		
	}
	
	
	/**
	 * Add CSS
	 * 
	 * By default, the CSS will be loaded using the normal <link> method
	 * Optionally, you can choose to have the contents of the file dumped 
	 * straight to screen to reduce the number of resources the browser
	 * needs to load at run time
	 */
	function add_css($css, $load = true){
		
		if($load){
			
			$this->css_load .= '<link href="'.$this->CI->config->item('base_url') . $this->data['assets_dir'] . 'css/' . $css . '.css?'
				.filemtime($this->data['assets_dir'] . 'css/' . $css . '.css')
				.'" media="screen" rel="stylesheet" type="text/css" />';
		
		} else {

			$css_contents = @implode(file($this->CI->config->item('base_url') . $this->data['assets_dir'] . 'css/' . $css . '.css', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
		
			$this->css_raw .= $css_contents;

		}
		
	}
	
	
	/**
	 * Clears all JS. Raw and scripts
	 */
	function clear_js(){
		
		$this->data['js'] = '';
		
	}
	
	
	/**
	 * Add JS
	 * 
	 * By default, the JS will be loaded using the normal <script> method
	 * Optionally, you can choose to have the contents of the file dumped 
	 * straight to screen to reduce the number of resources the browser
	 * needs to load at run time
	 */
	function add_js($js, $load = true){
		
		if($load){
		
			$this->js_load .= '<script src="'.$this->CI->config->item('base_url') . $this->data['assets_dir'] . 'js/' . $js . '.js?'
				.filemtime($this->data['assets_dir'] . 'js/' . $js . '.js')
				.'" type="text/javascript"></script>';

		} else {
		
			$js_contents = @implode(file($this->CI->config->item('base_url') . $this->data['assets_dir'] . 'js/' . $js . '.js', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

			$this->js_raw = $js_contents;
		
		}
		
	}
	
	
	/**
	 * Clears all ICO.
	 */
	function clear_ico(){
		
		$this->data['ico'] = '';
		
	}
	
	
	/**
	 * Add ICO
	 * Menambahkan favicon untuk browser;
	 * 
	 */
	function add_ico($ico){
		
		$this->ico_load .= '<link rel="shortcut icon" href="'.$this->CI->config->item('base_url') . $this->data['assets_dir'] . 'ico/' . $ico . '?'
			.filemtime($this->data['assets_dir'] . 'ico/' . $ico)
			.'">';
		
	}
	
	
	/**
	 * Clear all data in the head
	 */
	function clear_head(){
		
		$this->data['head'] = '';
		
	}
	
	
	/**
	 * Add tag to head
	 */
	function add_head($head){
		
		$this->data['head'] .= $head;
		
	}
	
	
	/**
	 * Clear all data in the foot
	 */
	function clear_foot(){
		
		$this->data['foot'] = '';
		
	}
	
	
	/**
	 * Add tag to foot
	 */
	function add_foot($foot){
		
		$this->data['foot'] .= $foot;
		
	}


	/**
	 * Add http header to output stack
	 *
	 */
	function add_http_header($header){

		$this->http_headers[] = $header;

	}

	/**
	 *  Sequentially load http headers from output stack
	 */
	function build_http_header() {

		// Add some default headers, no point in adding to config!
		$this->http_headers[] = "Pragma: no-cache";
		$this->http_headers[] = "Expires: Sat, 01 Jan 2000 00:00:00 GMT";
		$this->http_headers[] = "Cache-Control: private, no-cache, no-store, must-revalidate";

		foreach($this->http_headers as $h) {
			$this->CI->output->set_header($h);
		}

	}

	
	
	/**
	 * Adds a message to the current page stack
	 * Available types are warning, error, success and info
	 */
	function add_message($type, $message){
	
		$this->messages[$type][] = $message;
	
	}
	
	
	/**
	 * Menambah sebuah pesan modal
	 * dengan jquery dan bootstrap
	 */
	function add_modal($body, $title = 'Pesan Dialog', $button = array(array())){
	
		$this->modal['title'] = $title;
		$this->modal['body'] = $body;		
		$this->modal['button'] = $button;
	
	}
	
	
	/**
	 * Serves purely as a wrapper for the CI flashdata
	 * Just to keep syntax organised
	 */
	function set_flashdata($type, $message){
	
		$this->CI->session->set_flashdata($type, $message);
		
	}
	
	
	/**
	 * Formats the messages added to the stack, 
	 * and any warning, error, success and info messages 
	 * that were added via session->flashdata
	 */
	function prepare_messages(){
		
		foreach($this->messages as $type => $messages){
			
			// add flash data for this type to the stack
			$flash = $this->CI->session->flashdata($type);
			if($flash != ''){
				$messages[] = $flash;
			}
			
			// if there's messages of this type, prepare for printing
			if(sizeof($messages)){
				foreach($messages as $message){
					$this->data['messages'] .= '<div class="alert alert-'.$type.'">';
					$this->data['messages'] .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
					$this->data['messages'] .= $message;
					$this->data['messages'] .= '</div>';
				}
			}
			
		}
	
	}
	
	
	/**
	 * Format data dari modal (bootstrap),
	 * menampilkan pesan dalam bentuk modal
	 * membutuhkan jquery dan bootstrap
	 */
	function prepare_modal(){
		
		$modal	= $this->modal;

		// if there's modal of this type, prepare for printing
		if(count($modal)){
			// Modal
			$this->data['messages'] .= '<div class="modal fade" id="rooModal" tabindex="-1" role="dialog" aria-labelledby="rooModalLabel" aria-hidden="true">';
			$this->data['messages'] .= '<div class="modal-dialog">';
			$this->data['messages'] .= '<div class="modal-content">';

			// Header
			$this->data['messages'] .= '<div class="modal-header">';
			$this->data['messages'] .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			$this->data['messages'] .= '<h4 class="modal-title">'.$modal['title'].'</h4>';
			$this->data['messages'] .= '</div>';

			// Body
			$this->data['messages'] .= '<div class="modal-body">'.$modal['body'].'</div>';

			// Footer
			if(count($modal['button'])){
				$this->data['messages'] .= '<div class="modal-footer">';
				foreach ($modal['button'] as $button) {
					$this->data['messages'] .= '<button type="'.$button['type'].'" ';
					$this->data['messages'] .= 'class="'.$button['class'].'" ';
					$this->data['messages'] .= $button['data'];
					$this->data['messages'] .= '>'.$button['value'].'</button>';
				}
				$this->data['messages'] .= '</div>';
			}

			// Close tag
			$this->data['messages'] .= '</div>'; //.modal-content
			$this->data['messages'] .= '</div>'; //.modal-dialog
			$this->data['messages'] .= '</div>'; //.modal

			$this->add_foot('<script type="text/javascript">$("#rooModal").modal()</script>');
		}
	}
	
	
	
	/**
	 * Combine and organise the raw and loaded
	 * javascript and css files
	 */
	function prepare_jcss(){

		// combine the raw and loaded css
		if(strlen($this->css_raw)){
			$this->data['css'] .= '<style type="text/css">' . $this->css_raw . '</style>';
		}
		if(strlen($this->css_load)){
			$this->data['css'] .= $this->css_load;
		}
	
		// combine the raw and loaded css
		if(strlen($this->js_raw)){
			$this->data['js'] .= '<script lang="text/javascript">' . $this->js_raw . '</script>';
		}
		if(strlen($this->js_load)){
			$this->data['js'] .= $this->js_load;
		}
			
	}
	
	
	
	/**
	 * Menambahkan tag ico
	 * ico file
	 */
	function prepare_ico(){

		$this->data['ico']	.= $this->ico_load;
			
	}
		
	
	/**
	 * Custom: Mengambil value dari item konfigurasi
	 */
	function config($item){
		return $this->data[$item];
	}
	
	
	
	/**
	 * Custom: Mengganti konfigurasi
	 */
	function set_config($template_conf = array()){
		foreach($template_conf as $citem => $cval)
		{
			$this->data[$citem] = $cval;
		}
		unset($template_conf);
	}
	
	
	
	/**
	 * Custom: Mengganti template
	 */
	function set_template($template = 'default'){	
		$this->set_config(array('template' => $template));		
	}
	
	
	/**
	 * Custom: Mengambil pesan
	 */
	function messages(){
		$this->prepare_messages();
		return $this->config('messages');
	}
	
	
	/**
	 * Send the data compiled data to the screen
	 */
	function build(){
	
		$this->prepare_jcss();
		$this->prepare_ico();
		$this->prepare_messages();
		$this->prepare_modal();
		$this->build_http_header();
		
		$this->CI->load->view('templates/'.$this->data['template'].'/index.php', $this->data);
		
	}
}
