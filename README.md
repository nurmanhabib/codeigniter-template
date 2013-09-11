#CodeIgniter Template LIbrary

* [By Chris Duell](http://www.chrisduell.com/)
* Version: 2.1

## Description

A template library for CodeIgniter 2.0, which allows you to quickly build and organise your CI site using a template system.

The base template view is formatted to make use of the [Twitter Bootstrap CSS](http://twitter.github.com/bootstrap/) to get you up and running with a neat looking app, fast.

Resolves an issue with IE caching page elements. Thanks to Steve from [Innexio](http://www.innexio.com.au) for the fix

As a side note, it also works perfectly with [HMVC by wiredesignz](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc)

##Development by

* Chris Duell - Lead Developer ([http://duellsy.com](http://duellsy.com))
* With thanks to Matt Trimarchi - ([http://studio372.com.au](http://studio372.com), worked on V1.0 together.

###Contributors

* [robbiek](https://github.com/robbiek)
* Steve from [Innexio](http://www.innexio.com.au)

##Requirements

Code Igniter 2.0

##Usage

Download and place the files in your CI installation, following the same folder structure. Add the assets folder to your root, this is where you'll put your images / css / js files.

Add 'template' to your autoload file in the libraries config item. 

Make sure that you are also autoloading the session library, and that you have set an encryption key in your app/config/config.php file.

Update the config/template.php file to set your base template settings such as the sites name, title, and other items like which css and js files are always loaded.

A quick example to get you going is to update the default welcome controller in CI to use this library:

	class Welcome extends CI_Controller {
	
		function __construct(){
			parent::__construct();
		}
	
		function index() {
			$data = array('some_variable' => 'some_data');
		
			$this->template->add_message('success', 'You are using duellsys template library');
			$this->template->add_message('info', 'Awesome!');
		
			$this->template->set_content('example', $data);
			$this->template->build();
		}
	}

##Available methods

####function set_template($template_folder)
Memilih template folder yang ada di views/template/<templatefolder>

####function set_content($view, $data = array())
Load the content for the main area of the page, and store
in the data array to be later sent to the template

####function set_content_template($view, $data = array())
Menambahkan content dengan file berada di folder template
	
####function clear_css()
Clears all CSS. Raw and scripts
	
####function add_css($css, $load = true)
By default, the CSS will be loaded using the normal <link> method
Optionally, you can choose to have the contents of the file dumped 
straight to screen to reduce the number of resources the browser
needs to load at run time
	
####function clear_js()
Clears all JS. Raw and scripts
	
####function add_js($js, $load = true)
By default, the CSS will be loaded using the normal <link> method
Optionally, you can choose to have the contents of the file dumped 
straight to screen to reduce the number of resources the browser
needs to load at run time
			
####function clear_ico()
Clear all data in favicon

####function add_ico($head)
Menambahkan favicon
			
####function clear_head()
Clear all data in the head

####function add_head($head)
Add tag to head

####function add_message($type, $message)
Adds a message to the current page stack
Available types are warning, error, success and info

####function add_modal($title, $body, $button)
Menambahkan modal di view (bootstrap)

####function set_flashdata($type, $message)
Serves purely as a wrapper for the CI flashdata
Just to keep syntax organised

####function prepare_messages()
Formats the messages added to the stack, 
and any warning, error, success and info messages 
that were added via session->flashdata

####function build()
Send the data compiled data to the screen
