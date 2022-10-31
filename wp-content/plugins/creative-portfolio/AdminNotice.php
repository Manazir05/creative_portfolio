<?php


class AdminNotice {

    private const NOTICE_MSG   = 'Error';
    private const NOTICE_CLASS = 'notice-warning';

    public function __construct()
    {
        //
    }

    public static function displayError( $message = null )
    {
        self::updateNotice($message, 'notice-error');
    }

    public static function displayWarning( $message = null )
    {
        self::updateNotice($message, 'notice-warning');
    }

    public static function displayInfo( $message = null )
    {
        self::updateNotice($message, 'notice-info');
    }

    public static function displaySuccess( $message = null )
    {
        self::updateNotice($message, 'notice-success');
    }

    protected static function updateNotice( $message , $noticeLevel ) {

        $default_msg   = self::NOTICE_MSG;
        $default_class = self::NOTICE_CLASS;

        if ( !empty($message) ) {
            echo "<div class='notice {$noticeLevel} is-dismissible'><p>{$message}</p></div>";
        } else {
            echo "<div class='notice {$default_class} is-dismissible'><p>{$default_msg}</p></div>";
        }        
    }
    
}


?>