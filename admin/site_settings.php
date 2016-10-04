<?php
// add_option
add_option('tic-info');

// update_option
if ($_REQUEST['tic-info']) update_option('tic-info', $_REQUEST['tic-info']);
?>

<div id="icon-options-general" class="icon32"></div>

<h2>Site Settings</h2>

<form method="post" action="admin.php?page=site_settings">

    <table class="form-table">
    <tr valign="top">
        <th scope="row"><label for="tic-info">TIC related information</label></label></th>
        <td><input name="tic-info" type="text" value="<?php echo get_option('tic-info'); ?>" class="regular-text">
    </tr>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="Save">
    </p>

</form>
