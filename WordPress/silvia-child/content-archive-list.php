<?php echo get_the_date(); ?>
<?php the_title( sprintf( '<h2 class="entry-title news-list" ' . hybrid_get_attr( 'entry-title' ) . '><a href="%s" rel="bookmark" itemprop="url">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
<hr class="newsdivider" />