<?php

function realhomes_social_login_buttons() {
	?>
	<a rel="nofollow" data-provider="facebook" class="rsl-provider rsl-provider-facebook">
		<span><i class="fa fa-facebook"></i> <?php esc_html_e( 'Facebook', 'realhomes-social-login' ); ?></span>
	</a>
	<?php
}

add_action( 'realhomes_social_login', 'realhomes_social_login_buttons' );
