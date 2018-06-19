<?php
	global $current_version;
?>
		<?php if(is_user_logged_in()) : ?>
		<div class="row feedsync-errors">
			<div class="col-md-12">
					<?php sitewide_notices(); ?>
			</div>
		</div>
		<div class="footer">
			<div class="feedsync-footer">
				<p><a href="https://easypropertylistings.com.au/extensions/feedsync/">FeedSync</a> v<?php echo $current_version;?> | Developed by <a title="Real Estate Connected" href="http://www.realestateconnected.com.au/">Real Estate Connected</a> &copy; 2017</p>
			</div>
		</div>
		<?php endif; ?>
	</div>
  </body>
</html>
