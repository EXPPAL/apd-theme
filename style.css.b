/*
Theme Name: Audio Plugin Deals
Theme URI: https://wordpress.org/themes/apd/
Author: the Audio Plugin Deals team
Author URI: https://wordpress.org/
Description: The 2012 theme for WordPress is a fully responsive theme that looks great on any device. Features include a front page template with its own widgets, an optional display font, styling for post formats on both index and single views, and an optional no-sidebar page template. Make it yours with a custom menu, header image, and background.
Version: 2.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, one-column, two-columns, right-sidebar, custom-background, custom-header, custom-menu, editor-style, featured-images, flexible-header, footer-widgets, full-width-template, microformats, post-formats, rtl-language-support, sticky-post, theme-options, translation-ready
Text Domain: apd

This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned with others.
*/

/* =Notes
--------------------------------------------------------------
This stylesheet uses rem values with a pixel fallback. The rem
values (and line heights) are calculated using two variables:

$rembase:     14;
$line-height: 24;

---------- Examples

* Use a pixel value with a rem fallback for font-size, padding, margins, etc.
	padding: 5px 0;
	padding: 0.357142857rem 0; (5 / $rembase)

* Set a font-size and then set a line-height based on the font-size
	font-size: 16px
	font-size: 1.142857143rem; (16 / $rembase)
	line-height: 1.5; ($line-height / 16)

---------- Vertical spacing

Vertical spacing between most elements should use 24px or 48px
to maintain vertical rhythm:

.my-new-div {
	margin: 24px 0;
	margin: 1.714285714rem 0; ( 24 / $rembase )
}

---------- Further reading

http://snook.ca/archives/html_and_css/font-size-with-rem
http://blog.typekit.com/2011/11/09/type-study-sizing-the-legible-letter/


/* =Reset
-------------------------------------------------------------- */


.morecontent span {
    display: none;
}

.morelink {
    display: block;
}

.more {
}

.BuyNow_Button button {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
    float: none !important;
    padding-bottom: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0 !important;
}

.accounts ul li a:hover {
    color: #d73e1f;
}

.social ul li a:hover {
    color: black;
}

.flot_nav li:last-child a:hover {
    color: black;
}

.BuyNow_Button.BuyNow_Button2 a:hover {
    color: black;
    text-decoration: none;
}

button.single_add_to_cart_button:hover {
    color: black !important;
}

.join_btn:hover {
    background-color: #d83e1f;
    background-image: none;
}

.checkout-button {
    background: #d83e1f none repeat scroll 0 0 !important;
    border-radius: 0 !important;
}

input.place_order {
    background: #d83e1f none repeat scroll 0 0 !important;
    border-radius: 0 !important;
}

input.place_order:hover {
    background: black !important;

}

.hover_button {
    background: #d83e1f none repeat scroll 0 0;
    color: #fff;
    display: inline-block;
    font-family: Raleway-Regular;
    font-size: 14px;
    padding: 5px 15px;
}

h1.heading {
    font-size: 28px;
    margin: 0 0 15px !important;
}

div.con_frm {
    width: auto;
}

a {
    color: #d83e1f;
}

a:hover, a:focus {
    color: #fb694f;
    text-decoration: underline;
}

.input-text {
    border: 1px solid #ccc;
    padding: 0.618em 1em;
}

h3, .h3 {
    font-size: 24px;
}

.home .woocommerce-message {
    left: 13%;
    margin: 0 auto;
    position: fixed;
    top: 50%;
    width: 75%;
    z-index: 2147483647;
}

select {
    border: 1px solid #ccc;
    padding: 0.458em 1em;
}

#coupon_code {
    float: none;
    padding: 9px;
    width: 300px;
}

ins {
    text-decoration: none;
}

* {
    text-decoration: none !important;
}

.woocommerce-thankyou-order-received {
    font-size: 26px;
    margin-bottom: 15px;
}

h2, .h2 {
    font-size: 24px;
    margin-bottom: 15px;
}

.page-id-47 .new_letter, .page-id-48 .new_letter {
    display: none;
}

.page-id-47 .coupon {
    display: none;
}

h1, .h1 {
    font-size: 36px;
}

.div404 {
    text-align: center;
}

#buynow .pro_time_head > style, #buynow .pro_time_head > script {
    display: none;
}

.pro_time_head #main_countedown_1 .time_left {
    background-color: transparent;
    border-radius: 0;
    color: white;
    font-family: inherit;
    font-size: 16px;
    padding: 0;
    display: inline;
}

.pro_time_head #main_countedown_1 .time_description {
    color: white;
    font-family: inherit;
    font-size: 15px;
    display: inline;
    margin-left: 5px;
}

.pro_time_head #main_countedown_1 .element_conteiner {
    min-width: inherit;
}

#buynow .pro_time_head > * {
    display: inline-block;
}

.pro_time_head #main_countedown_1 .countdown {
    margin: 0
}

.mc4wp-alert.mc4wp-notice {
    color: white;
}

.ctct-form-errorMessage {
    font-size: 16px;
    line-height: 1.5;
    margin-bottom: -20px !important;
    color: #ffffff !important;
    position: relative;
    top: 60px;
}

.ctct-custom-form .ctct-form-required:before {
    content: "\2217";
    position: absolute;
    top: 0 !important;;
    left: 0 !important;
}

.wpcf7-response-output {
    color: #d73e1f !important;
}

@media only screen and (max-width: 480px) {
    .html5gallery-elem-1 > iframe {
        height: 150px !important;
        width: auto !important;
    }
}

.divider-inline {
    border-top-color: #d73e1f;
    border-top-width: 3px;
    width: 30%;
    display: block;
    position: relative;
    border-top-style: solid;
}

.titlehead {
    margin-top: 25px;
    font-family: Raleway-Bold;
    font-size: 35px;
    color: #d73e1f;
    text-transform: uppercase;

}

.jCountdown .label {
    display: block !important;
}

#small_countdown {
    position: relative;
    text-align: center;
}

#large_countdown {
    position: relative;
    /*text-align: center;*/
}


@media screen and (max-width: 1000px) {
    #small_countdown {
        left: 0px;
    }

    #large_countdown {
        left: -10000px;
        display: none;
    }
}

@media screen and (min-width: 1000px) {
    #small_countdown {
        left: -10000px;
        display: none;
    }

    #large_countdown {
        left: 0px;
    }
}

#select-donation{color:#000;width:75px;font-size:12px;}
.blog-container{max-width:1200px;margin:50px auto;}
.main-loop{width:65%;float:left;margin-right:5%;}
#sidebar-wrapper{width:30%;float:left;margin-bottom:-1px;}
.blog-container footer{width:100%;float:left;margin: 15px 0;}
.blog-container .entry-title{margin-top:0px;font-size:30px;color: #fb694f}
.blog-container  .entry-content{
font-size: 13px;
}
.nav-next {
	float: right;
}
.main-loop article {
	padding: 20px;
	margin-bottom: 50px;
	background: #f8f8f866;
    box-shadow: 4px 4px 8px 0px #ccc;
}
.blog-container .entry-summary, .blog-container .entry-meta {
 font-size: 14px;
}
.sidebar  aside{
	text-align: center;
	margin: 0 10px 50px 10px;
}

.single_page  .entry-header, .single_page  .main-content{
    width:100%;
}
.single_page  .main-content {
    padding: 20px 0;
  	font-size: 16px;
}
.single_page .entry-content {
  	font-size: 16px;
}
.single_page .entry-content p{
  	margin: 0 0 10px;
}
div._42ef._8u {
	display: none;
}
.nav-single {
	padding: 20px;
}



@media screen and (max-width: 678px) {
    .main-loop{width:100%;}
	#sidebar-wrapper{width:100%;}
}
@media screen and (max-width: 400px) {
   .blog-container .entry-header{width:100%;}
   .blog-container .main-content{
    width:100%;
    float: right;
    padding: 25px 0px 15px 0px;
}
}
