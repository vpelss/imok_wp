# IMOK for Wordpress : work in progress

The idea for this program came to me after a relative had passed away and was not found for 7 days.

Click here to learn [About this program](https://github.com/vpelss/imok_wp/blob/master/imok.md#about)

You can try it at: https://www.emogic.com/imok/

-------------------------------------

## Benefits

IMOK is web based app. Any device can be used. Smart phone, PC, etc.

It was designed to be a fail safe alert system. You or your phone can be out of commision and the alert will still be sent.
By having the customer report to a server that he is ok, the server can send out the alert if you do not report in.

## Install

- This was designed to work alone on a Wordpress install and may conflict with existing pages. You can easily create another Wordpress installation on your web host under a sub directory as I have done. https://www.emogic.com/imok/
- Download and place all files under your Wordpress installation at \wp-content\plugins\imok
- Acivate the imok plugin. It will create 4 pages unless they exist; 'IMOK Log In', 'IMOK Logged In', 'IMOK Redirector', 'IMOK Settings'
- Set the page 'IMOK Redirector' as your main page
- Create a header and footer as required an set on the 4 pages
- set up a cron job to run at least every hour. eg: wget -qO- https://emogic.com/imok/wp-cron.php &> /dev/null
- create an account and test

## Liabilty

This program is subject to change and no assumption of reliability can be assumed.
This is a proof of concept script. Don't risk your life on it.

## To Do

- alert to text
- alert to social media
