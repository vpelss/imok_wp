# IMOK for Wordpress : work in progress

The idea for this program came to me after a relative had passed away and was not found for 7 days.

Click here to learn [About this program](https://github.com/vpelss/imok_wp/blob/master/imok.md#about)

You can try it at: https://imok.emogic.com

-------------------------------------

## Benefits

IMOK is web based app. Any device can be used. Smart phone, PC, etc.

It was designed to be a fail safe alert system. You or your phone can be out of commission and the alert will still be sent.
By having the customer report to a server that he is OK, the server can send out the alert if you do not report in by a set time.

## Install

- This was designed as a web app to work on a stand alone Wordpress install and may conflict with existing pages.
For example, anyone who registers for a new account on your site will be asked to fill in the IMOK settings page.
It will set the Wordpress main page to IMOK.
To avoid site conflicts, you can easily create another Wordpress installation on your web host under a sub directory, or a subdomain, as I have done. https://imok.emogic.com
- Download and place all files under your Wordpress installation at \wp-content\plugins\imok_wp
- Activate the imok plugin. It will create multiple pages unless they exist; 'IMOK Log In', 'IMOK Logged In', 'IMOK Settings', etc
- If suitable, it is recommended to set the page 'IMOK' as your main page
- Set permalinks to 'Post name'. 'Plain' will work but css may break
- Create a header and footer as required and set on the IMOK pages
- There is a very simple menu on each page, but you will likely want to remove it and set your own and place it in the header
- Set up a cron job to run wp-cron.php on your wordpress insatllation at least every hour. eg: wget -qO- https://yoursite.com/imok/wp-cron.php &> /dev/null or better(as web access cron may be disabled): 	/usr/local/bin/php /home/xx/imok.emogic.com/wp-cron.php
- Create an account and test
- You may change the page(s) URL slugs (you may need to change button href's), but you cannot change the page(s) name. If you do the script will break
- You may edit the pages, but if you remove the [shortcode](s) you may break some of the page functions
- enable users to register accounts in wp
- disable posts in wp. set all registrations to subscribers
- check that the IMOK send email is a valid email adress to send from, found in settings

## Liabilty

This program is subject to change and no assumption of reliability can be assumed.
This is a proof of concept script. Don't risk your life on it.

## To Do

- success sounds (asterix/calendar alert) fail sounds (device failed to connect)
- imok_test buttton and message 
- accounts not checked in and how long. delete option??? legal aspect?
- alert to text
- alert to social media
- audio alarm. bypass dom interaction?
- no audio alarm option in settings
- programaticly set no comments
- programaticly set no posts
- programaticly set no media
- pay system
- more emails inline text, but have an email limit
- security benefit (nonces, etc) to commands going through : /wp-admin/admin-post.php. basicly action button would be a form that would trigger an action. but formatting gets messy with forms
