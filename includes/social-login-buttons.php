<?php

function realhomes_social_login_buttons() {
	?>
	<div class="realhomes-social-login">
		<div class="realhomes-social-login-widget">
			<div class="rsl-connect-with"><?php esc_html_e( 'Connect with:', 'realhomes-social-login' ); ?></div>
			<div class="rsl-provider-list">
				<a rel="nofollow" data-provider="facebook" class="rsl-provider rsl-provider-facebook">
					<span><i class="fa fa-facebook"></i> <?php esc_html_e( 'Facebook', 'realhomes-social-login' ); ?></span>
				</a>
				<a rel="nofollow" data-provider="google" class="rsl-provider rsl-provider-google">
					<span><i class="fa fa-google"></i> <?php esc_html_e( 'Google', 'realhomes-social-login' ); ?></span>
				</a>
				<a rel="nofollow" data-provider="twitter" class="rsl-provider rsl-provider-twitter">
					<span><i class="fa fa-twitter"></i> <?php esc_html_e( 'Twitter', 'realhomes-social-login' ); ?></span>
				</a>
				<!-- <a rel="nofollow" data-provider="linkedin" class="rsl-provider rsl-provider-linkedin">
					<span><i class="fa fa-linkedin"></i> <?php esc_html_e( 'LinkedIn', 'realhomes-social-login' ); ?></span>
				</a> -->
			</div>
		</div>
	</div>
	<?php
}

add_action( 'realhomes_social_login', 'realhomes_social_login_buttons' );
