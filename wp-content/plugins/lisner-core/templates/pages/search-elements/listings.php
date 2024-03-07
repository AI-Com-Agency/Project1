<?php
/**
 * Element Name: Listings
 *
 * @author pebas
 * @version 1.0.0
 * @package pages/search-elements
 */
?>
<div class="lisner_listings">
	<div class="lisner_listings-load">
		<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
			<div class="listing-load-item">
				<div class="listing-load-item__figure "></div>
				<div class="listing-load-item-content">
					<div class="listing-load-item__top-meta">
						<span class="listing-load-item__top-meta__item"></span>
						<span class="listing-load-item__top-meta__item"></span>
					</div>
					<div class="listing-load-item__title"></div>
					<div class="listing-load-item__text">
						<span class="listing-load-item__text__item"></span>
						<span class="listing-load-item__text__item"></span>
						<span class="listing-load-item__text__item"></span>
						<span class="listing-load-item__text__item"></span>
					</div>
					<div class="listing-load-item__bottom-meta">
						<span class="listing-load-item__bottom-meta__item"></span>
						<span class="listing-load-item__bottom-meta__item"></span>
						<span class="listing-load-item__bottom-meta__item"></span>
					</div>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>

