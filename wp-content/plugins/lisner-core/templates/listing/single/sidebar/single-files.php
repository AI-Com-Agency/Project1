<?php
/**
 * Template Name: Listing Single Files
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $files_args
 * @var $files
 */
global $post;
?>
<?php if ( $files_args['has_title'] ) : ?>
	<?php $title = $files_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<div class="files d-flex mb-0">
	<div class="widget-label d-flex">
		<i class="material-icons mf"><?php echo esc_attr( 'attachment' ); ?></i>
		<span><?php esc_html_e( 'Documents', 'lisner-core' ) ?></span>
	</div>
	<div class="listing-files">
		<?php foreach ( $files as $file ) : ?>
			<?php if ( strlen( $file['name'] ) >= 30 ) : ?>
				<?php $extension = mb_substr( $file['name'], - 4 ); ?>
				<?php $title = str_replace( $extension, '', $file['name'] ); ?>
				<?php $start = mb_substr( $title, 0, 5 ); ?>
				<?php $end = mb_substr( $title, 8, 11 ) . $extension; ?>
				<?php $title = $start . esc_html( '...' ) . $end; ?>
			<?php else: ?>
				<?php $title = $file['name']; ?>
			<?php endif; ?>
			<a href="<?php echo esc_url( $file['url'] ); ?>"
			   class="file-download" title="<?php echo esc_attr( $file['name'] ); ?>"
			   download><span><?php echo esc_html( $title ); ?></span><i
						class="material-icons mf"><?php echo esc_html( 'vertical_align_bottom' ); ?></i></a>
		<?php endforeach; ?>
	</div>
</div>
