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
    $message = $_POST['message'];
    wp_mail($_POST['to'], $_POST['subject'], $_POST['message']);
} else {
    $to = get_option('admin_email');
    $subject = 'Very cool subject';
    $message = "Hi,\nthis is your first message from WP.\n\nHave a nice day.";
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
        <input type="email" name="to" value="<?php echo esc_attr($to)?>" placeholder="Email address">
        <br>
        <input type="text" name="subject" value="<?php echo esc_attr($subject)?>" placeholder="Subject">
        <br>
        <textarea name="message" placeholder="Message..."><?php echo esc_html($message)?></textarea>
        <br>
        <button>Submit</button>
        
    </form>
    
</div>

