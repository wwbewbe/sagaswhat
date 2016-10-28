<?php
// add_option
add_option('tic-comment-end');
add_option('tic-distance');
add_option('event-distance');
add_option('park-distance');

// update_option
if ($_REQUEST['tic-comment-end']) update_option('tic-comment-end', $_REQUEST['tic-comment-end']);
if ($_REQUEST['tic-distance']) update_option('tic-distance', $_REQUEST['tic-distance']);
if ($_REQUEST['event-distance']) update_option('event-distance', $_REQUEST['event-distance']);
if ($_REQUEST['park-distance']) update_option('park-distance', $_REQUEST['event-distance']);
?>

<div id="icon-options-general" class="icon32"></div>

<h2>Site Settings</h2>

<form method="post" action="admin.php?page=site_settings">

    <table class="form-table">
	<tr valign="top">
        <th scope="row"><label for="tic-comment-end">TIC comment in end of post</label></label></th>
        <td><input name="tic-comment-end" type="text" value="<?php echo get_option('tic-comment-end'); ?>" class="regular-text">
    </tr>
	<tr valign="top">
        <th scope="row"><label for="tic-distance">TIC distance</label></label></th>
        <td><input name="tic-distance" type="text" value="<?php echo get_option('tic-distance'); ?>" class="regular-text">
    </tr>
	<tr valign="top">
        <th scope="row"><label for="event-distance">Event distance</label></label></th>
        <td><input name="event-distance" type="text" value="<?php echo get_option('event-distance'); ?>" class="regular-text">
    </tr>
	<tr valign="top">
        <th scope="row"><label for="park-distance">Park distance</label></label></th>
        <td><input name="park-distance" type="text" value="<?php echo get_option('park-distance'); ?>" class="regular-text">
    </tr>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="Save">
    </p>

</form>
