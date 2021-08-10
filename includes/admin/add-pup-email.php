<?php
/**
 * Add Product Update Email Page
 *
 * @since 0.9.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$products = edd_pup_get_all_downloads();
$tags = edd_get_email_tags();
$recipients = 0;

// Defaults
$defaultname     = edd_get_option( 'edd_pup_default_from_name', get_bloginfo('name') );
$defaultemail    = edd_get_option( 'edd_pup_default_from_email', get_bloginfo('admin_email') );
$defaultsubject  = edd_get_option( 'edd_pup_default_subject', __( 'New product update available', 'edd-pup' ) );
$defaultmessage  = '<p>'.__( 'Hello {name},', 'edd-pup').'</p>';
$defaultmessage .= '<p>'.__( 'There are updates available for the following products:', 'edd-pup' ).'</p>';
$defaultmessage .= '{updated_products}';
$defaultmessage .= '<p>'.__( 'You can download these updates from the following links:', 'edd-pup' ).'</p>';
$defaultmessage .= '{updated_products_links}';
$defaultmessage .= '<p>'.__( 'Thank you for being a customer of {sitename}!', 'edd-pup' ).'</p>';
$defaultmessage .= '<p><small>'.__( 'To no longer receive product update emails, please click here: {unsubscribe_link}', 'edd-pup' ).'</small></p>';
$defaultmessage  = edd_get_option( 'edd_pup_default_message', $defaultmessage );

?>
<form id="edd-pup-email-edit" action="" method="POST">
<div id="edd-pup-single-email" class="wrap">
<?php do_action( 'edd_add_receipt_form_top' ); ?>
<h2><?php _e( 'Add Product Update Email', 'edd-pup' ); ?></h2>
<br>
<a href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-prod-updates' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'edd-pup' ); ?></a>
	<div id="poststuff">
		<div id="edd-dashboard-widgets-wrap">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<!-- actions -->
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div id="submitdiv" class="postbox">
						<h3 class="hndle"><span><?php _e( 'Email Actions', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<div class="submitbox" id="submitpost">
								<div id="minor-publishing-actions">
									<div id="save-action">
										<?php submit_button('Save Changes', 'secondary', 'edd-pup-save-email-changes', false);?>
									</div>
									<div id="preview-action">
										<a href="javascript:void(0);" id="edd-pup-open-preview" class="button-secondary" title="<?php _e( 'Product Update Email Preview', 'edd' ); ?> "><?php _e( 'Preview Email', 'edd' ); ?></a>
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
									<div id="publishing-action">
										<?php submit_button('Send Update Email', 'primary', 'send-prod-updates', false);?><span class="edd-pup-spin spinner"></span>
										<?php wp_nonce_field( 'edd-pup-confirm-send', 'edd-pup-send-nonce', false ); ?>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- tags -->
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
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
							<input type="text" class="regular-text" name="title" id="title" value="" placeholder="<?php _e( 'Name your product update email', 'edd-pup'); ?>" size="30" />
							<p class="description"><?php _e( 'For internal use only to help organize product updates – e.g. "2nd Edition eBook Update." Customers will not see this.' , 'edd-pup' ); ?></p>
							
							<!-- products -->
							<strong><?php _e( 'Choose products being updated', 'edd-pup' ) ; ?>:</strong>
							<?php echo EDD()->html->product_dropdown( array( 'multiple' => true, 'chosen' => true, 'name' => 'products[]', 'id' => 'products-select', 'selected' => array(), 'placeholder' => sprintf( __( 'Select one or more %s', 'edd-pup' ), edd_get_label_plural() ) ) ); ?>
							<p class="description"><?php _e( 'Select which products and its customers you wish to update with this email', 'edd-pup' ); ?></p>
							<!-- advanced settings -->
							<a href="#" id="bundle_filters" data-state="hidden" style="display:none;"><?php _e( 'Show Bundle Filters', 'edd-pup' );?></a>
							<div class="bundle-filters-wrap">						
								<!-- bundle option 1-->
								<strong><?php _e( 'Bundled product link output:', 'edd-pup' );?></strong>
									<select name="bundle_1" class="bundle-input" value="">
										<option value="all" selected="selected"><?php _e( 'Show links for all products', 'edd-pup' );?></option>
										<option value="updated"><?php _e( 'Show links for updated products only', 'edd-pup' );?></option>
									</select>
								<p class="description"><?php _e ( 'Choose whether to show links for all products in a bundle or only the products within the bundle that have been updated (and selected above) when using the {updated_products_links} tag.', 'edd-pup' );?></p>
								
								<!-- bundle option 2-->
								<strong>Send only to bundle customers:</strong>
								<input type="checkbox" name="bundle_2" id="bundle_2" value="0"/>
								<p class="description">Only send this email to customers who have purchased a bundle selected above.</p>
							</div>
							<!-- recipients
								<p><strong><?php printf( _n( '1 customer will receive this email', '%s customers will receive this email', $recipients, 'edd-pup' ), $recipients ); ?></p> -->
								<input type="hidden" name="recipients" value="<?php echo $recipients; ?>" />
						</div>
					</div>
					
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Product Update Email Message', 'edd-pup' ); ?></span></h3>
						<div class="inside">
							<!-- from name  -->
							<strong><?php _e( 'From Name', 'edd-pup' ); ?>:</strong>
							<input type="text" class="regular-text" name="from_name" id="from_name" value="<?php echo $defaultname; ?>" placeholder="<?php _e( 'Your Name', 'edd-pup'); ?>"/>
							<p class="description"><?php _e( 'The name customers will see the product update coming from.' , 'edd-pup' ); ?></p>
							<!-- from email -->
							<strong><?php _e( 'From Email', 'edd-pup' ); ?>:</strong>
							<input type="text" class="regular-text" name="from_email" id="from_email" value="<?php echo $defaultemail; ?>" placeholder="<?php _e( 'your_email@website.com', 'edd-pup'); ?>"/>
							<p class="description"><?php _e( 'The email address customers will receive the product update from.' , 'edd-pup' ); ?></p>
							<!-- subject    -->
							<strong><?php _e( 'Subject', 'edd-pup' ); ?>:</strong>
							<input type="text" class="widefat" name="subject" id="subject" value="<?php echo $defaultsubject; ?>" placeholder="<?php _e( 'Your email subject line', 'edd-pup'); ?>" size="30" />
							<p class="description"><?php _e( 'Enter the email subject line for this product update. Template tags can be used (see sidebar).' , 'edd-pup' ); ?></p>
							
							<!-- message    -->
							<?php wp_editor( $defaultmessage, 'message' ); ?>
						</div>
					</div>				
					
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php do_action( 'edd_add_receipt_form_bottom' ); ?>
	<div class="submit hide-button">
		<input type="hidden" name="edd-action" value="add_pup_email" />
		<input type="hidden" name="edd_pup_nonce" value="<?php echo wp_create_nonce( 'edd_pup_nonce' ); ?>" />
		<input type="submit" value="<?php _e( 'Save Email', 'edd-pup' ); ?>" class="button-primary" />
	</div>
	<div class="edit-buttons hide-button">
		<a href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-prod-updates' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'edd-pup' ); ?></a>
	</div>
	</div>
</form>