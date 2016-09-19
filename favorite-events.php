<script type="text/javascript">
//Favorite Events
	jQuery(function(){
		jQuery(".favorite").slideToggle(false);
		jQuery(".favbtn").on("click",function(){
			jQuery(".favorite").slideToggle();
		});
	});
	jQuery(function() {
		jQuery(".carousel-fav-list").jCarouselLite({
			btnNext: ".next-fav",
			btnPrev: ".prev-fav",
			visible: 4,
			speed: 100,
			circular: false,
		});
	});
</script>
<div class="favbtn"><?php echo esc_html__('Your Favorite Lists ', 'SagasWhat'); ?>(&nbsp;<?php echo count( wpfp_get_users_favorites() ); ?>&nbsp;<?php echo esc_html__('events', 'SagasWhat'); ?>&nbsp;)</div>
<div class="favorite">
<aside class="mymenu-thumb mymenu-favorite">
<h2><?php echo esc_html(__('Favorite Events', 'SagasWhat')); ?></h2>

<div class="carousel-fav">
<a href="#" class="prev-fav"><i class="fa fa-arrow-left"></i><?php echo esc_html__('Prev', 'SagasWhat'); ?></a>
<a href="#" class="next-fav"><?php echo esc_html__('Next', 'SagasWhat'); ?><i class="fa fa-arrow-right"></i></a>
<div class="carousel-fav-list">
	<?php wpfp_list_favorite_posts(); ?>
</div>
</div>

</aside>
</div>
