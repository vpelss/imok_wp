
<?php
/*
 * Template Name: Contact Form Template
 * Template Post Type: page
 */

get_header();

?>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <h3>Congrats! Your Form Submitted Successfully.</h3>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <h3>Sorry! Unable to submit the form.</h3>
    </div>
<?php endif; ?>

<form name="contact_form" method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" enctype="multipart/form-data" autocomplete="off" accept-charset="utf-8">

    <div>
        <label>
            Full Name
            <input type="text" name="contact_full_name" required="">
        </label>
    </div>

    <div>
        <label>
            Email
            <input type="email" name="contact_email" required="">
        </label>
    </div>

    <input type="hidden" name="action" value="contact_form">

    <input type="hidden" name="base_page" value="<?php echo home_url( $wp->request ); ?>">

    <div>
        <button type="submit" name="submit_btn">
            Submit
        </button>
    </div>

</form>
<!-- new registeration -->

<?php

get_footer();

?>
