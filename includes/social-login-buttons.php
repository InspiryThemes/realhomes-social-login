<?php

if ( ! function_exists( 'realhomes_social_login_buttons' ) ) {
	/**
	 * Display the markup of the social login buttons.
	 */
	function realhomes_social_login_buttons() {
		$rsl_settings = get_option( 'rsl_settings' );
		if ( isset( $rsl_settings['enable_social_login_facebook'] ) || isset( $rsl_settings['enable_social_login_google'] ) || isset( $rsl_settings['enable_social_login_twitter'] ) ) {
			?>
			<div class="realhomes-social-login">
				<div class="realhomes-social-login-widget">
					<div class="rsl-connect-with"><?php esc_html_e( 'Connect with:', 'realhomes-social-login' ); ?></div>
					<div class="rsl-provider-list">
						<?php
						// Login with facebook button.
						if ( isset( $rsl_settings['enable_social_login_facebook'] ) ) {
							?>
							<a rel="nofollow" data-provider="facebook" class="rsl-provider rsl-provider-facebook">
								<span><i class="fa fa-facebook"></i> <?php esc_html_e( 'Facebook', 'realhomes-social-login' ); ?></span>
							</a>
							<?php
						}

						// Login with google button.
						if ( isset( $rsl_settings['enable_social_login_google'] ) ) {
							?>
							<a rel="nofollow" data-provider="google" class="rsl-provider rsl-provider-google">
								<span><i class="fa fa-google"></i> <?php esc_html_e( 'Google', 'realhomes-social-login' ); ?></span>
							</a>
							<?php
						}

						// Login with twitter button.
						if ( isset( $rsl_settings['enable_social_login_twitter'] ) ) {
							?>
							<a rel="nofollow" data-provider="twitter" class="rsl-provider rsl-provider-twitter">
								<span><i class="fa fa-twitter"></i> <?php esc_html_e( 'Twitter', 'realhomes-social-login' ); ?></span>
							</a>
							<?php
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	add_action( 'realhomes_social_login', 'realhomes_social_login_buttons' );
}
