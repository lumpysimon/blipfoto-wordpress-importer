<?php



defined( 'ABSPATH' ) or die();



global $blipfoto_importer_settings;
$blipfoto_importer_settings = new blipfoto_importer_settings;



class blipfoto_importer_settings {



	var $notice = array();



	function __construct() {

		add_action( 'admin_init',   array( $this, 'init' ) );
		add_action( 'admin_menu',   array( $this, 'add_page' ) );

	}



	function option() {

		return 'blipfoto-importer';

	}



	function init() {

		register_setting(
			$this->option(),
			$this->option(),
			array( $this, 'validate' )
			);

	}



	function validate( $inputs ) {

		$new = array();

		if ( $inputs ) {
			foreach ( $inputs as $k => $v ) {
				$new[$k] = wp_kses( $v, array() );
				if ( ! $new[$k] ) {
					unset( $new[$k] );
				}
			}
		}

		return $new;

	}



	function get() {

		if ( ! $opts = get_option( $this->option() ) ) {
			$opts = $this->defaults();
			$this->update( $opts );
		}

		return $opts;

	}



	function update( $opts ) {

		update_option( $this->option(), $opts );

	}



	function defaults() {

		return array(
			'username'      => '',
			'client-id'     => '',
			'client-secret' => '',
			'access-token'  => '',
			'post-type'     => 'post',
			'post-status'   => 'draft',
			'auto-insert'   => 0,
			'num-entries'   => 25
			);

	}



	function add_page() {

		add_options_page(
			'Blipfoto Importer',
			'Blipfoto Importer',
			'manage_options',
			'blipfoto-importer-settings',
			array( $this, 'render_page' )
			);

	}



	function render_page() {

		$opts = $this->get();

		?>

		<div class="wrap">

			<h2>Blipfoto Importer Settings</h2>

			<h3>How to grant access to your Blipfoto account</h3>
			<ol>
				<li>Go to your <a href="https://polaroidblipfoto.com/settings/profile" target="_blank">Blipfoto profile page</a> to find out your username, and enter it in the <em>username</em> field below.</li>
				<li>Go to the <a href="https://www.polaroidblipfoto.com/developer/apps" target="_blank">Blipfoto apps page</a> and click the <em>Create a new app</em> button.</li>
				<li>Give it any name you wish (e.g. <em>My website</em>).</li>
				<li>Make sure <em>Type</em> is set to <em>Web application</em>.</li>
				<li>Enter the URL of your website (most likely <em><?php echo home_url(); ?></em>) in the <em>Website</em> field.</li>
				<li>The <em>Redirect URI</em> field should be left blank.</li>
				<li>Click <em>Create new app</em>.</li>
				<li>You will now be shown your <em>client ID</em>, <em>client secret</em> and <em>access token</em>. Copy and paste these into the fields below, set the other settings as required and click <em>Save settings</em>.</li>
				<li>When you're done, you can <a href="<?php echo admin_url( 'tools.php?page=blipfoto-import' ); ?>">run the importer</a>.</li>
			</ol>

			<form method="post" action="options.php">

				<?php settings_fields( $this->option() ); ?>

				<table class="form-table">

					<tbody>

						<tr valign="top">
							<th scope="row">Username</th>
							<td>
								<input name="<?php echo $this->option(); ?>[username]" type="text" value="<?php echo $opts['username']; ?>">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Client ID</th>
							<td>
								<input name="<?php echo $this->option(); ?>[client-id]" class="regular-text" type="text" value="<?php echo $opts['client-id']; ?>">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Client secret</th>
							<td>
								<input name="<?php echo $this->option(); ?>[client-secret]" class="regular-text" type="text" value="<?php echo $opts['client-secret']; ?>">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Access token</th>
							<td>
								<input name="<?php echo $this->option(); ?>[access-token]" class="regular-text" type="text" value="<?php echo $opts['access-token']; ?>">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Post type</th>
							<td>
								<input name="<?php echo $this->option(); ?>[post-type]" class="regular-text" type="text" value="<?php echo $opts['post-type']; ?>">
								<p class="description">The post type you wish to use for your entries.</p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Post status</th>
							<td>
								<?php

								$statuses = array(
									'draft'   => 'Draft',
									'publish' => 'Published',
									'pending' => 'Pending review',
									'private' => 'Private'
									);

								echo '<select name="' . $this->option() . '[post-status]">';
								foreach ( $statuses as $k => $v ) {
									echo '<option value="' . $k . '" ' . selected( $opts['post-status'], $k, false ) . '>' . $v . '</option>';
								}
								echo '</select>';

								?>
								<p class="description">The status for imported entries.</p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Auto-insert</th>
							<td>
								<input name="<?php echo $this->option(); ?>[auto-insert]" type="checkbox" value="1" <?php checked( $opts['auto-insert'] ); ?>">
								<p class="description">Each image is automatically set to be the post's 'featured image'. If you tick this option, the image will also be inserted at the beginning of the post content. You probably don't want to tick this if your theme automatically displays featured images.</p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">Number of entries</th>
							<td>
								<input name="<?php echo $this->option(); ?>[num-entries]" class="regular-text" type="text" value="<?php echo $opts['num-entries']; ?>">
								<p class="description">The number of entries to create on each import. Setting this too high may cause timeout or API rate-limit problems.</p>
							</td>
						</tr>

					</tbody>

				</table>

				<p class="submit">
					<input class="button-primary" name="blipfoto-importer-submit" type="submit" value="Save Settings">
				</p>

			</form>

		</div>

		<?php

	}



} // class
