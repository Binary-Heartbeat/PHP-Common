<?php
    class core
    {
        public static function debug($debug, $message)
        {
            if ($debug['debug']) {
                if (is_array($message)) {
                    new dBug( $message );
                    return true;
                } else {
                    echo '<br/> Debug: '.$message.PHP_EOL;
                    return true;
                }
            } else {
                return false;
            }
        }

        public static function error($_, $message, $type = null) // TODO: change $level to '$level = 1', update all error function calls
        {
            if ($type == null) {
                $prefix='';
            }
            elseif ($type == 'warn') {
                $prefix='Arcfolder, Fatal Error: ';
            }
            elseif ($type == 'fatal') {
                die('<h1>Fatal error: '.$message.'</h1>');
            }
        }
        public static function log($_, $text, $type, $user)
        {
            db::insertRow(
                $_,
                "INSERT INTO ".$_['table_prefix']."logs(LogType,LogUser,LogText,LogDateTime) VALUES(?,?,?,NOW());",
                array($type, $user, $text)
            );
            /* Type key for common::log()
            1 - register
            2 - login
            3 - logout
            4 - cookie/token renewed
            5 - expired cookie/token destroyed
            */
        }
        public static function config($_)
        {
            core::debug($_, 'Querying database for settings');
            if (
                $result = db::selectAll(
                    $_,
                    "SELECT * FROM ".$_['table_prefix']."settings"
                )
            ) {
                core::debug($_, 'Writing settings to $_');
                foreach($result as $row) {
                    $_[$row['SetName']] = $row['SetValue'];
                }
                $config = $_;
                $config['db_host'] = 'nope';
                $config['db_user'] = 'nope';
                $config['db_pass'] = 'nope';
                $config['db_name'] = 'nope';
                core::debug($_, $config);
                return $_;
            } else {
                core::error($_, 'Failed to retrieve settings from database.', 'fatal');
            }



/*            $statement = db::connect($_);
            foreach($statement->query('SELECT * FROM '.$_['table_prefix'].'settings') as $row) {
                $_[$row['SetName']] = $row['SetValue'];
            }
            db::error($statement);
            db::close($_, $statement);
            return $_;*/
        }
    }
