<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$search = '';
?>
<div class="search-holder">
	<button class="close-btn"></button>
    <form role="search" method="GET" action="<?php echo get_permalink( get_page_by_path( 'search' ) ) ?>">
		<?php
		if ( is_page() && get_queried_object()->post_name == 'search' ) {
			if ( isset( $_GET['search'] ) ) {
				$search = $_GET['search'];
			} else {
				$search = '';
			}
		}
		?>
        <input type="search" name="search" id="" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search for products">
        <input type="submit" value="Search">
    </form>
</div>