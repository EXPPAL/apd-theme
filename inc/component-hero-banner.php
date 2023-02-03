<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<section class="hero-banner">
    <div class="mask"></div>
    <button class="btn-open-video"></button>
    <section class="video-popup">
        <div class="wrapper-video">
            <div class="action-holder">
                <button data-action="open-short-video">Play short video (0:53)</button>
                <button data-action="open-long-video">Play long video (4:06)</button>
            </div>
            <div class="viewport short-video">
                <iframe class="video" src="https://www.youtube.com/embed/TMeLrvmGiVQ" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="viewport long-video">
                <iframe class="video" src="https://www.youtube.com/embed/AJPll1n-o_k" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
	</section><!-- end .video-popup -->

</section><!-- end .hero-banner -->