<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $page_title . ' | ' . $site_name ?></title>

	<?php
	// Menampilkan head meta dll.
	echo $head;
	?>

	<?php
	// Menampilkan script CSS
	echo $css;
	?>

</head>
<body>
	<div class="container">

		<?php
		// Menampilkan pesan dan modal
		// Anda dapat menaruh variabel ini untuk menampilkan di setiap konten
		// Sebaiknya menampilkan hanya di 1 tempat (file index.php atau konten dinamis)
		// Karena jika di sini dan di konten di cetak, maka akan menampilkan 2 kali
		echo $messages;
		?>

		<?php
		// Menampilkan konten web dinamis
		// Konten dinamis akan di proses melalui controller
		echo $content;
		?>

		<p class="text-muted">Page rendered in {elapsed_time} seconds</p>

	</div>

	<?php
	// Menampilkan script JS
	echo $js;
	?>

	<?php
	// Menampilkan footer
	echo $foot;
	?>

</body>
</html>

<?php
/* End of file index.php */
/* Location: ./application/views/templates/default/index.php */
