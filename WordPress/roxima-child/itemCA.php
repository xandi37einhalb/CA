<article class="item-entry item-align-left">

	<?php if ( has_post_thumbnail() ): ?>
		<figure class="item-thumb">
		<?php $postlink = get_post_meta($post->ID, 'reiselink', true); ?>
		<?php echo $postlink; ?>
		<?php the_post_thumbnail( 'roxima_hero' ); ?>
		</figure>
	<?php endif; ?>

	<div class="entry-item-content">
		<p class="item-title"><?php the_title(); ?></a></p>
		<time class="item-subtitle el-underline" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php #echo esc_html( get_the_date() ); ?></time>

		<?php the_content(); ?>
	</div>
</article>
