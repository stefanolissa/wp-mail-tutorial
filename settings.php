<?php
/*
 * This settings page DOES NOT implement security best practicies, is ONLY to be used
 * on a test system otherwise protected.
 * DO NOT use this file as a start point for real administrative panels!
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_POST = stripslashes_deep($_POST);
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $message_text = $_POST['message_text'];
    $message_html = $_POST['message_html'];
    if (isset($_POST['btn_text'])) {
        wp_mail($to, $subject, $message_text);
    } else if (isset($_POST['btn_html'])) {
        wp_mail($to, $subject, $message_html, array('Content-Type: text/html'));
    }
} else {
    $to = get_option('admin_email');
    $subject = 'Very cool subject';
    $message_text = "Hi,\nthis is your first message from WP.\n\nHave a nice day.";
    $message_html = "<p>Hi,</p>\n<p>this is strong>your first message</strong> from WP.</p>\n<p>Have a nice day.</p>";
}
?>
<style>
    .wrap input, .wrap textarea {
        width: 100%;
    }
    .wrap textarea {
        height: 250px;
    }   
</style>
<div class="wrap">
    <h2>WP Mail Tutorial</h2>
    <form method="post">
        <input type="email" name="to" value="<?php echo esc_attr($to) ?>" placeholder="Email address">
        <br>
        <input type="text" name="subject" value="<?php echo esc_attr($subject) ?>" placeholder="Subject">

        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    Text version<br>
                    <textarea name="message_text" placeholder="Message..."><?php echo esc_html($message_text) ?></textarea>
                    <br>
                    <button name="btn_text">Send text version</button>
                </td>
                <td style="width: 50%">
                    HTML version<br>
                    <textarea name="message_html" placeholder="Message..."><?php echo esc_html($message_html) ?></textarea>
                    <br>
                    <button name="btn_html">Send HTML version</button>
                </td>
            </tr>
        </table>


    </form>

</div>

