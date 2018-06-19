<?php
if (!defined('SITE_URL') )
	die;
/*
 * Home Page template
 *
*/

get_header('home');
$xmls = get_input_xml();
?>

<div class="jumbotron">
	<?php echo feedsync_description_jumbotron() ?>
</div>
<?php if(!empty($xmls)) { ?>
<div class="panel panel-default">
	<div class="panel-heading">Files Ready For Processing</div>
	<table class="table"> 
		
		<?php
			
			foreach ($xmls as $my_glob) {
				$file_details = pathinfo($my_glob); ?>
				<tr>
					<td><a href="<?php echo INPUT_URL.$file_details['basename'] ?>"><?php echo $file_details['basename'] ?></a></td> 
				</tr> <?php
			}
		?>
	</table>
</div>

<?php 
}
echo get_footer(); 
?>
