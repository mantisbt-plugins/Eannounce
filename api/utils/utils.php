<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as phpmailerException;

define('PHPMAILER_METHOD', "phpMailer_method");
define('MAIL_HEADER', "headers");
define('FROM_EMAIL', "from_email");

/**
 * File aggregating static methods
 */

/**
 * Function logging messages in the plugin's tmp/logs folder
 * 
 * @param string $message
 * @param boolean $datetime_prefix
 */
function eannounce_log($message, $datetime_prefix = true){
    
    // Creating folder if necessary
    $log_dir = "plugins/" . plugin_get_current() . "/tmp/logs";
    // Check if folder exists
    if(! file_exists($log_dir)) {
        // Create folder, forcing creation with 3rd parameter
        mkdir($log_dir, 0777, true);
    }
    
    $log_file = $log_dir . "/log_" . date("Y-m-d") . ".txt";
    
    // Write in log file
    if($datetime_prefix) {
        $content = date(plugin_config_get('date_format')) . " : " . $message;
    } else {
        $content = $message;
    }
    
    // Open log file
    if(! $handle = fopen($log_file, 'ab')) {
        return false;
    }
    
    // Write, close file and return
    if(fwrite($handle, $content . "\n") === false){
        fclose($handle);
        return false;
    }
    
    fclose($handle);
    return true;
}

/**
 * Sets all DKIM fields if necessary
 * @param EmailData $p_mail
 * @return EmailData
 */
function set_dkim($p_mail){
    $t_mail = $p_mail;
    
    #apply DKIM settings
    if( config_get( 'email_dkim_enable' ) ) {
        $t_mail->DKIM_domain = config_get( 'email_dkim_domain' );
        $t_mail->DKIM_private = config_get( 'email_dkim_private_key_file_path' );
        $t_mail->DKIM_private_string = config_get( 'email_dkim_private_key_string' );
        $t_mail->DKIM_selector = config_get( 'email_dkim_selector' );
        $t_mail->DKIM_passphrase = config_get( 'email_dkim_passphrase' );
        $t_mail->DKIM_identity = config_get( 'email_dkim_identity' );
    }
    
    return $t_mail;
}

/**
 * Sets fields with the metadatas
 * @param EmailData $p_mail
 * @param EmailData $p_email_data
 * @return unknown
 */
function set_metadata( $p_mail, $p_email_data ){
    $t_mail = $p_mail;
    $t_email_data = $p_email_data;
    
    if( isset( $t_email_data->metadata['hostname'] ) ) {
        $t_mail->Hostname = $t_email_data->metadata['hostname'];
    }
    if( isset( $t_email_data->metadata['charset'] ) ) {
        $t_mail->Charset = $t_email_data->metadata['charset'];
    }
    if( isset( $t_email_data->metadata['priority'] ) ) {
        # Urgent = 1, Not Urgent = 5, Disable = 0
        $t_mail->Priority = $t_email_data->metadata['priority'];  
    }
    
    if( isset( $t_email_data->metadata[MAIL_HEADER] ) && is_array( $t_email_data->metadata[MAIL_HEADER] ) ) {
        foreach( $t_email_data->metadata[MAIL_HEADER] as $t_key => $t_value ) {
            switch( $t_key ) {
                case 'Message-ID':
                    # Note: hostname can never be blank here as we set metadata['hostname']
                    # in email_store() where mail gets queued.
                    if( !strchr( $t_value, '@' ) && !is_blank( $t_mail->Hostname ) ) {
                        $t_value = $t_value . '@' . $t_mail->Hostname;
                    }
                    $t_mail->set( 'MessageID', '<' . $t_value . '>' );
                    break;
                case 'In-Reply-To':
                    $t_mail->addCustomHeader( $t_key . ': <' . $t_value . '@' . $t_mail->Hostname . '>' );
                    break;
                default:
                    $t_mail->addCustomHeader( $t_key . ': ' . $t_value );
                    break;
            }
        }
    }
    return $t_mail;
}

/**
 * Sets SMTP configuration
 * 
 * @param EmailData $p_mail
 * @return EmailData
 */
function set_smtp_params( $p_mail ){
    $t_mail = $p_mail;
    
    $t_mail->isSMTP();
    
    # SMTP collection is always kept alive
    $t_mail->SMTPKeepAlive = true;
    
    if( !is_blank( config_get( 'smtp_username' ) ) ) {
        # Use SMTP Authentication
        $t_mail->SMTPAuth = true;
        $t_mail->Username = config_get( 'smtp_username' );
        $t_mail->Password = config_get( 'smtp_password' );
    }
    
    if( is_blank( config_get( 'smtp_connection_mode' ) ) ) {
        $t_mail->SMTPAutoTLS = false;
    }
    else {
        $t_mail->SMTPSecure = config_get( 'smtp_connection_mode' );
    }
    
    $t_mail->Port = config_get( 'smtp_port' );
    
    return $t_mail;
}

/**
 * Send mail overriding PhpMailer config
 * 
 * @param EmailData $p_email
 * @param string $p_subject Subject of the mail
 * @param string $p_body Body of the mail
 * @return boolean
 */
function send_mail($p_email, $p_subject, $p_body) {

    // Get global mailer
    global $g_phpMailer;
    
    if(is_null( $g_phpMailer )) {
        if( config_get( PHPMAILER_METHOD ) == PHPMAILER_METHOD_SMTP ) {
            register_shutdown_function( 'email_smtp_close' );
        }
        $g_phpMailer = new PHPMailer( true );
    }
    
    //Build email
    $t_mail = new EmailData();
    $t_mail->subject = $p_subject;
    $t_mail->body = $p_body;
    
    //Add recipients
    $i=0;
    while( $i < count( $p_email )) {
        $g_phpMailer->addBCC( $p_email[$i], '' );
        $i++;
    }
    
    $current_user_mail = user_get_email(auth_get_current_user_id());
    
    $t_cc = $current_user_mail;
    
    // Store temporarily original email sender address
    // and set current user address instead
    $original_sender = config_get(FROM_EMAIL);
    config_set(FROM_EMAIL, $current_user_mail);

    if( isset( $t_cc ) ) {
        $g_phpMailer->addCC( $t_cc );
    }
    $result = send_bcc_only($t_mail, $g_phpMailer);
    // Set original user back
    config_set(FROM_EMAIL, $original_sender);
    
    return $result;
}

/**
 * Allows to send emails with only bcc recipients
 * 
 * @param EmailData $p_email_data
 * @param string log_message
 * @return boolean
 */
function send_bcc_only( EmailData $p_email_data, $g_phpMailer ){
    
    $t_email_data = $p_email_data;
    
    $t_recipient = trim( $t_email_data->email );
    $t_subject = string_email( trim( $t_email_data->subject ) );
    $t_message = string_email_links( trim( $t_email_data->body ) );
    
    $t_debug_email = config_get_global( 'debug_email' );
    
    $t_log_msg = 'ERROR: Message could not be sent - ';
    
    $t_mail = $g_phpMailer;
    
    $t_lang = config_get_global( 'default_language' );
    if( 'auto' == $t_lang ) {
        $t_lang = config_get_global( 'fallback_language' );
    }
    $t_mail->setLanguage( lang_get( 'phpmailer_language', $t_lang ) );
    
    # Select the method to send mail
    switch( config_get( PHPMAILER_METHOD ) ) {
        case PHPMAILER_METHOD_MAIL:
            $t_mail->isMail();
            break;
            
        case PHPMAILER_METHOD_SENDMAIL:
            $t_mail->isSendmail();
            break;
            
        case PHPMAILER_METHOD_SMTP:
            $t_mail = set_smtp_params( $t_mail );
            break;
            
        default:
            break;
    }
    
    $t_mail = set_dkim($t_mail);
    $t_mail = set_metadata($t_mail, $t_email_data);
    
    # set email format to plain text
    $t_mail->isHTML( false );
    # set word wrap to 80 characters
    $t_mail->WordWrap = 80;              
    
    $t_mail->Host = config_get( 'smtp_host' );
    $t_mail->From = config_get( 'from_email' );
    $t_mail->Sender = config_get( 'return_path_email' );
    $t_mail->FromName = config_get( 'from_name' );
    $t_mail->AddCustomHeader( 'Auto-Submitted:auto-generated' );
    $t_mail->AddCustomHeader( 'X-Auto-Response-Suppress: All' );
    
    $t_mail->Encoding   = 'quoted-printable';
    
    if( !empty( $t_debug_email ) ) {
        $t_message = 'To: ' . $t_recipient . "\n\n" . $t_message;
        $t_recipient = $t_debug_email;
        log_event(LOG_EMAIL_VERBOSE, "Using debug email '$t_debug_email'");
    }
    
    $t_mail->Subject = $t_subject;
    $t_mail->Body = make_lf_crlf( $t_message );
    
    try {
        $t_success = $t_mail->send();
        if( $t_success ) {
            $t_success = true;
            
            if( $t_email_data->email_id > 0 ) {
                email_queue_delete( $t_email_data->email_id );
            }
        } else {
            # We should never get here, as an exception is thrown after failures
            log_event( LOG_EMAIL, $t_log_msg . $t_mail->ErrorInfo );
            $t_success = false;
        }
    }
    catch ( phpmailerException $e ) {
        log_event( LOG_EMAIL, $t_log_msg . $t_mail->ErrorInfo );
        $t_success = false;
    }
    
    $t_mail->clearAllRecipients();
    $t_mail->clearAttachments();
    $t_mail->clearReplyTos();
    $t_mail->clearCustomHeaders();
    
    return $t_success;
}
