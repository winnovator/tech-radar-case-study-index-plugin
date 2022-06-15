<?php
if (!defined('ABSPATH')) {
    wp_die();
}

/**
 * Wtr_Csi_Admin_Mailer
 */
class Wtr_Csi_Admin_Mailer {    
    /**
     * csi_mailer
     *
     * @param  mixed $to
     * @return void
     */
    public function csi_mailer($to) {
        $subject = 'Uw case studie is geaccepteerd!';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $body = '';

        $body .= '<p>Geachte Inzender,</p>';
        $body .= '<p>Uw case studie is geaccepteerd!</p>';
        $body .= '<p>Hartelijk dank.</p>';
        $body .= '<p>Met vriendelijke groet,</p>';
        $body .= '<p>Windesheim Technology Radar</p>';

        wp_mail($to, $subject, $body, $headers);
    }
}