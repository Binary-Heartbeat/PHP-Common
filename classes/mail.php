<?php
    class mail
    {
        public static function send($to, $subject, $message, $from)
        {
            if (self::validate($to, $subject, $message, $from)) {
                mail($to, $subject, $message, $from);
            }
        }
        private static function validate($to, $subject, $message, $from)
        {
            ;
        }
    }
