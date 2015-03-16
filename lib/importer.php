<?php



defined( 'ABSPATH' ) or die();



global $blipfoto_importer_main;
$blipfoto_importer_main = new blipfoto_importer_main;



class blipfoto_importer_main {



	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_page' ) );

	}



	function add_page() {

		add_management_page(
			'Blipfoto Importer',
			'Blipfoto Importer',
			'import',
			'blipfoto-import',
			array( $this, 'render_page' )
			);

	}



	function render_page() {

		echo '<h2>Blipfoto Importer</h2>';

		if ( ! blipfoto_importer::options_saved() ) {
			echo '<p>Please <a href="' . admin_url( 'options-general.php?page=blipfoto-importer-settings' ) . '">go to the settings page</a> to create the app and set the options</p>';
		} else {

			$client = new Blipfoto\Api\Client( blipfoto_importer::option( 'client-id' ), blipfoto_importer::option( 'client-secret' ), blipfoto_importer::option( 'access-token' ) );

			$response = $client->get(
				'user/profile',
				array(
					'return_details' => 1
					)
				);

			if ( $response->error ) {
				echo '<p>Could not connect to Blipfoto with the settings you have entered. Please try again or check your app settings.</p>';
			} else {

				$total_entries = $response->data( 'details.entry_total' );
				$page_size     = blipfoto_importer::option( 'num-entries' );

				if ( $total_entries > 0 ) {

					echo '<p>You have ' . $total_entries . ' entries.</p>';

					if ( isset( $_POST[ 'blipfoto-import-go' ] ) and wp_verify_nonce( $_POST[ 'blipfoto_importer_nonce' ], 'blipfoto-importer-nonce' ) ) {

						$fetch        = min( $page_size, $total_entries );
						$num_to_fetch = 0;
						$to_fetch     = array();
						$page         = 0;

						while ( $num_to_fetch < $fetch ) {

							$journal = $client->get(
								'entries/journal',
								array(
									'page_size'  => $page_size,
									'page_index' => $page
									)
								);

							$entries = $journal->data( 'entries' );

							foreach ( $entries as $entry ) {

								$entry_id = $entry[ 'entry_id_str' ];
								$args = array(
									'post_type'      => blipfoto_importer::option( 'post-type' ),
									'post_status'    => 'any',
									'posts_per_page' => 1,
									'meta_query'     => array(
										array(
											'key'   => 'blipfoto-entry-id',
											'value' => $entry_id
											)
										)
									);
								$post = get_posts( $args );
								if ( ! $post ) {
									$to_fetch[] = $entry_id;
									$num_to_fetch++;
								}

							}

							$page++;

						}

						if ( $num_to_fetch ) {

							foreach ( $to_fetch as $item ) {

								$entry = $client->get(
									'entry',
									array(
										'entry_id'          => $item,
										'return_details'    => 1,
										'return_image_urls' => 1
										)
									);

								if ( ! $title = $entry->data( 'entry.title' ) ) {
									$title = 'Untitled';
								}

								$content = $entry->data( 'details.description' );
								$img_url = $entry->data( 'image_urls.stdres' );

								$post_data = array(
									'post_type'    => blipfoto_importer::option( 'post-type' ),
									'post_status'  => blipfoto_importer::option( 'post-status' ),
									'post_date'    => $entry->data( 'entry.date' ) . ' 00:00:00',
									'post_title'   => $title,
									'post_content' => $content
									);

								if ( $id = wp_insert_post( $post_data ) ) {

									add_action( 'add_attachment', array( $this, 'set_featured_image' ) );
									 media_sideload_image( $img_url, $id );
									 remove_action( 'add_attachment', array( $this, 'set_featured_image' ) );

									if ( blipfoto_importer::option( 'auto-insert' ) ) {

										$img_id = get_post_thumbnail_id( $id );
										$content = '<img class="aligncenter size-full wp-image-' . $img_id . '" src="' . $img_url . '" alt="' . esc_attr( $title ) . '" />' . $content;
										$update_data = array(
											'ID'           => $id,
											'post_content' => $content
											);
										wp_update_post( $update_data );

									}

									add_post_meta( $id, 'blipfoto-entry-id', $item );

									echo '<p>Created <a href="' . get_permalink( $id ) . '" target="_blank">' . get_the_title( $id ) . '</a> (' . $id . ') for entry ' . $item . '</p>';

								}

							}

						} else {
							echo '<p>No more entries to fetch.</p>';
						}

					}

					?>

					<p>Click to import <?php echo $page_size; ?> entries...</p>
					<form method="post" action="<?php echo admin_url( 'tools.php?page=blipfoto-import' ); ?>">
						<?php wp_nonce_field( 'blipfoto-importer-nonce', 'blipfoto_importer_nonce' ); ?>
						<input class="button-primary" type="submit" name="blipfoto-import-go" value="Go!">
					</form>
					<?php

				} else {
					'<p>You have no entries.</p>';
				}

			}

		}

	}



	function set_featured_image( $id ) {

		$p = get_post( $id );
		update_post_meta( $p->post_parent, '_thumbnail_id', $id );

	}



} // class
