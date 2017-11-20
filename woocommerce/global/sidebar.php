				<div id="wisemanstore" class="sidebar col-md-3" role="complementary">

					<?php 
//class="sidebar m-all t-1of3 d-2of7 last-col cf"
if ( is_active_sidebar( 'wisemanstore' ) ) : ?>

						<?php dynamic_sidebar( 'wisemanstore' ); ?>

					<?php else : ?>

						<?php
							/*
							 * This content shows up if there are no widgets defined in the backend.
							*/
						?>

					<?php endif; ?>

				</div>
