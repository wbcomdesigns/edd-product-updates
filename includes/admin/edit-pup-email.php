<?php
/**
 * Edit Product Update Email Page
 *
 * @since 0.9.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$email_id  = absint( $_GET['id'] );
$email     = get_post( $email_id );
$emailmeta = get_post_custom( $email_id );
$filters = unserialize( $emailmeta['_edd_pup_filters'][0] );
$updated_products = get_post_meta( $email_id, '_edd_pup_updated_products', TRUE );
$recipients = edd_pup_customer_count( $email_id, $updated_products );
$products = edd_pup_get_all_downloads();
$tags = edd_get_email_tags();
$status = get_post_status( $email_id );
$fromname = !empty( $emailmeta['_edd_pup_from_name'][0] ) ? $emailmeta['_edd_pup_from_name'][0] : '';
$fromemail = !empty( $emailmeta['_edd_pup_from_email'][0] ) ? $emailmeta['_edd_pup_from_email'][0] : '';

// Redirect to view page if edit page is accessed directly
if ( $status != 'draft' ) {
	?>
	<script type="text/javascript">
		window.location.href = document.URL.replace('edit_pup_email', 'view_pup_email');
	</script>
	<?php
}

?>

<form id="edd-pup-email-edit" action="" method="POST">
<div id="edd-pup-single-email" class="wrap">
<?php do_action( 'edd_add_receipt_form_top' ); ?>
<h2><?php _e( 'Edit Product Update Email', 'edd-pup' ); ?></h2>
<br>
<a href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-prod-updates' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'edd-pup' ); ?></a>
	<div id="poststuff">
		<div id="edd-dashboard-widgets-wrap">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<!-- actions -->
				<div id="side-sortables-actions" class="meta-box-sortables ui-sortable">
					<div id="submitdiv" class="postbox">
						<h3 class="hndle"><span><?php _e( 'Email Actions', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<div class="submitbox" id="submitpost">
								<div id="minor-publishing-actions">
									<div id="save-action">
										<?php submit_button( __( 'Save Changes', 'edd-pup' ), 'secondary', 'edd-pup-save-email-changes', false);?>
									</div>
									<div id="preview-action">
										<a href="javascript:void(0);" id="edd-pup-open-preview" class="button-secondary" title="<?php _e( 'Product Update Email Preview', 'edd' ); ?> "><?php _e( 'Preview Email', 'edd-pup' ); ?></a>
									<?php wp_nonce_field( 'edd-pup-preview-email', 'edd-pup-prev-nonce', false ); ?>
									</div>
									<div class="clear"></div>
								</div>
								<div id="test-action">
									<p><strong><?php _e( 'Send Test Email To' , 'edd-pup' );?>:</strong></p>
									<input type="text" class="test-email" name="test-email" id="test-email" placeholder="name@email.com" size="10" />
									<p class="description"><?php _e( 'Use a comma between multiple emails.' , 'edd-pup' ); ?></p>
									<a href="javascript:void(0);" id="edd-pup-send-test" class="button-secondary" title="<?php _e( 'Product Update Email Preview', 'edd' ); ?> "><?php _e( 'Send Test Email', 'edd-pup' ); ?></a>
									<?php wp_nonce_field( 'edd-pup-send-test-email', 'edd-pup-test-nonce', false ); ?>
								</div>
								<div id="major-publishing-actions">
									<div id="delete-action">
										<a class="submitdelete deletion" href="<?php echo wp_nonce_url( add_query_arg( 'edd_action' , 'pup_delete_email' ), 'edd-pup-delete-nonce' ); ?>" onclick="var result=confirm(<?php _e( "'Are you sure you want to permanently delete this email?'", 'edd-pup' ); ?>);return result;"><span class="delete"><?php _e( 'Delete Email' , 'edd-pup'); ?></span></a>
									</div>
									<div id="publishing-action">
										<?php submit_button( __( 'Send Update Email', 'edd-pup' ), 'primary', 'send-prod-updates', false);?><span class="edd-pup-spin spinner"></span>
										<?php wp_nonce_field( 'edd-pup-confirm-send', 'edd-pup-send-nonce', false ); ?>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- recipients -->
				<div id="side-sortables-recipients" class="meta-box-sortables ui-sortable">
					<div id="submitdiv" class="postbox">
						<h3 class="hndle"><span><?php _e( 'Estimated Recipients', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<div class="recipients-wrap">
								<p><?php printf( _n( '<span class="recipient-count">1</span> customer will receive this email', '<span class="recipient-count">%s</span> customers will receive this email', $recipients, 'edd-pup' ), number_format( $recipients ) ); ?></p>
								<input type="hidden" name="recipients" class="recipient-input" value="<?php echo absint($recipients); ?>" />
							</div>
						</div>
					</div>
				</div>											
				<!-- tags -->
				<div id="side-sortables-tags" class="meta-box-sortables ui-sortable">
					<div id="submitdiv" class="postbox">
						<h3 class="hndle"><span><?php _e( 'Template Tags', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<div class="tag-list">
							<?php foreach ( $tags as $tag): ?>
									<p class="template-tag"><strong>{<?php echo $tag['tag'];?>}</strong></p>
									<p class="tag-description"><?php echo $tag['description'];?></p>
							<?php endforeach;?>
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Email Setup', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<strong><?php _e( 'Email Name', 'edd-pup' ); ?>:</strong>
							<input type="text" class="regular-text" name="title" id="title" placeholder="<?php _e( 'Name your product update email', 'edd-pup'); ?>" value="<?php echo $email->post_title;?>" size="30" />
							<p class="description"><?php _e( 'For internal use only to help organize product updates – e.g. "2nd Edition eBook Update." Customers will not see this.' , 'edd-pup' ); ?></p>
							
							<!-- products -->
							<strong><?php _e( 'Choose products being updated', 'edd-pup' ) ; ?>:</strong>
							<?php echo EDD()->html->product_dropdown( array( 'multiple' => true, 'chosen' => true, 'name' => 'products[]', 'id' => 'products-select', 'placeholder' => sprintf( __( 'Select one or more %s', 'edd-pup' ), edd_get_label_plural() ), 'selected' => is_array( $updated_products ) ? array_keys( $updated_products ) : $updated_products ) ); ?>
							<p class="description"><?php _e( 'Select which products and its customers you wish to update with this email', 'edd-pup' ); ?></p>
							
							<!-- advanced settings -->
							<div class="bundle-filters-wrap">								
								<!-- bundle option 1-->
								<strong><?php _e( 'Bundled product link output:', 'edd-pup' );?></strong>
									<select name="bundle_1" class="bundle-input" value="">
										<option value="updated" <?php if ( $filters['bundle_1'] == 'updated' ) { echo 'selected="selected"'; }?>><?php _e( 'Show links for updated products only', 'edd-pup' );?></option>
										<option value="all" <?php if ( $filters['bundle_1'] == 'all' ) { echo 'selected="selected"'; }?>><?php _e( 'Show links for all products', 'edd-pup' );?></option>
									</select>
								<p class="description"><?php _e ( 'Choose whether to show links for all products in a bundle or only the products within the bundle that have been updated (and selected above) when using the {updated_products_links} tag.', 'edd-pup' );?></p>
								
								<!-- bundle option 2-->
								<strong>Send only to bundle customers:</strong>
								<input type="checkbox" name="bundle_2" id="bundle_2" value="<?php echo $filters['bundle_2'];?>" <?php checked( $filters['bundle_2'], 1 ); ?>/>
								<p class="description">Only send this email to customers who have purchased a bundle selected above.</p>
							</div>
						</div>
					</div>
					
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Product Update Email Message', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<!-- from name  -->
							<strong><?php _e( 'From Name', 'edd-pup' ); ?>:</strong>
							<input type="text" class="regular-text" name="from_name" id="from_name" placeholder="<?php _e( 'Your Name', 'edd-pup'); ?>" value="<?php echo $fromname; ?>" />
							<p class="description"><?php _e( 'The name customers will see the product update coming from.' , 'edd-pup' ); ?></p>
							<!-- from email -->
							<strong><?php _e( 'From Email', 'edd-pup' ); ?>:</strong>
							<input type="text" class="regular-text" name="from_email" id="from_email" placeholder="<?php _e( 'your_email@website.com', 'edd-pup'); ?>" value="<?php echo $fromemail; ?>" />
							<p class="description"><?php _e( 'The email address customers will receive the product update from.' , 'edd-pup' ); ?></p>
							<!-- subject    -->
							<strong><?php _e( 'Subject', 'edd-pup' ); ?>:</strong>
							<input type="text" class="widefat" name="subject" id="subject" placeholder="<?php _e( 'Your email subject line', 'edd-pup'); ?>" value="<?php echo $email->post_excerpt;?>" size="30" />
							<p class="description"><?php _e( 'Enter the email subject line for this product update. Template tags can be used (see sidebar).' , 'edd-pup' ); ?></p>
							
							<!-- message    -->
							<?php wp_editor( $email->post_content, 'message' ); ?>
						</div>
					</div>				
					
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php do_action( 'edd_add_receipt_form_bottom' ); ?>
	<div class="submit hide-button">
		<input type="hidden" name="edd-action" value="edit_pup_email" />
		<input type="hidden" name="email-id" value="<?php echo absint( $_GET['id'] ); ?>" />
		<input type="hidden" name="edd_pup_nonce" value="<?php echo wp_create_nonce( 'edd_pup_nonce' ); ?>" />
		<input type="hidden" name="edd_pup_tinymce_status" id="edd_pup_tinymce_status" value="<?php echo get_user_option( 'rich_editing' );?>">
		<input type="submit" value="<?php _e( 'Save Email Changes', 'edd-pup' ); ?>" class="button-primary" />
	</div>
	<div class="edit-buttons hide-button">
		<a href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-prod-updates' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'edd-pup' ); ?></a>
	</div>
	</div>
</form>