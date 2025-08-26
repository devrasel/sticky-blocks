<?php
// Handle custom CSS save action
if (isset($_POST['stky_save_custom_css'])) {
    if (!isset($_POST['stky_custom_css_nonce']) || !wp_verify_nonce($_POST['stky_custom_css_nonce'], 'stky_custom_css_action')) {
        wp_die('Security check failed!');
    }
    $custom_css = sanitize_textarea_field($_POST['stky_custom_css']);
    $updated = update_option('stky_custom_css', $custom_css);
    $old_custom_css = get_option('stky_custom_css', '');
    if ($updated) {
        set_transient('stky_admin_success_message', __('Custom CSS saved successfully!', 'sticky-blocks'), 30);
        wp_redirect(admin_url('admin.php?page=stickyblocks-custom-css'));
        exit;
    } elseif ($custom_css === $old_custom_css) {
        set_transient('stky_admin_success_message', __('Custom CSS already up to date!', 'sticky-blocks'), 30);
        wp_redirect(admin_url('admin.php?page=stickyblocks-custom-css'));
        exit;
    } else {
        set_transient('stky_admin_error_message', __('Error saving Custom CSS! Update failed.', 'sticky-blocks'), 30);
        wp_redirect(admin_url('admin.php?page=stickyblocks-custom-css'));
        exit;
    }
}
?>
<div class="wrap">
    <h2>Custom CSS</h2>
    <?php
    $error_message = get_transient('stky_admin_error_message');
    if ($error_message) {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($error_message) . '</p></div>';
        delete_transient('stky_admin_error_message');
    }

    $success_message = get_transient('stky_admin_success_message');
    if ($success_message) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($success_message) . '</p></div>';
        delete_transient('stky_admin_success_message');
    }
    ?>
    <form method="post">
        <?php wp_nonce_field('stky_custom_css_action', 'stky_custom_css_nonce'); ?>
        <table class="widefat fixed striped pages">
            <thead>
                <tr>
                    <td class="column">Add your custom CSS here</td>
                </tr>
            </thead>
            <tr>
                <td>
                    <div class="stky-custom-css-block">
                        <textarea name="stky_custom_css" rows="10" cols="50" class="large-text code"><?php echo esc_textarea(get_option('stky_custom_css', '')); ?></textarea>
                    </div>
                </td>
            </tr>
        </table>
        <p><input class="button button-primary" type="submit" name="stky_save_custom_css" value="Save Custom CSS" /></p>
    </form>
</div>