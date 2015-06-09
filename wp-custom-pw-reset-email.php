<?php
/*
    Plugin Name: Custom Password Reset Email
    Version: 1.0
    Author: Vital
    Author URI: http://vtldesign.com
    License: GPLv2

    Copyright 2015  VITAL DESIGN  (email : info@vtldesign.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'Do not access this file directly.' );


/**
 * Customize password reset email from address
 * @return string Custom email address
 */
function vital_custom_password_email_from() {

    $email = 'donotreply@example.com';
    $email = is_email($email);

    return $email;

}


/**
 * Customize password reset email from name
 * @return string Custom email from name
 */
function vital_custom_password_email_from_name() {

    $name = 'Custom From Name';
    $name = esc_attr($name);

    return $name;

}


/**
 * Customize password reset email subject text
 * @param  string $old_subject Default WordPress subject
 * @return string              Custom email subject
 */
function vital_custom_password_email_subject( $old_subject ) {

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $subject = sprintf( __('%s Password Reset'), $blogname );

    return $subject;
}


/**
 * Customize password reset email body
 * @param  string $old_message Default WordPress message
 * @param  string $key         WordPress nonce
 * @return string              Custom email body
 */
function vital_custom_password_email_body( $old_message, $key ) {

    $user_data = '';

    // If no value is posted, return false
    if ( ! isset( $_POST['user_login'] ) ) {
            return '';
    }

    // Fetch user information from user_login
    if ( strpos( $_POST['user_login'], '@' ) ) {

        $user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );

    } else {

        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);

    }

    if ( ! $user_data ) {
        return '';
    }

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    // Setting up message for retrieve password
    $message = "Looks like you want to reset your password? Sweet!\n\n";
    $message .= "Please click on this link:\n";
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\n\n";
    $message .= "Love,\n";
    $message .= "Vital";

    // Return completed message for retrieve password
    return $message;

}

add_filter( 'wp_mail_from', 'vital_custom_password_email_from' );
add_filter( 'wp_mail_from_name', 'vital_custom_password_email_from_name' );
add_filter( 'retrieve_password_title', 'vital_custom_password_email_subject', 10, 1 );
add_filter( 'retrieve_password_message', 'vital_custom_password_email_body', 10, 2 );
?>