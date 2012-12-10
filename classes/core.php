<?php
	class core {
		public static function debug($debug, $message) {
			if($debug['debug']) {echo '<br/> Debug: '.$message.PHP_EOL;}
		}

		public static function error($_, $level, $message) {
			if($level==1) {	$prefix=''; }
			elseif($level==2) { $prefix='Arcfolder, Fatal Error: '; }
		}
		public static function log($_,$text,$type,$user) {
			$query = "INSERT INTO ".$_['table_prefix']."logs(LogType,LogUser,LogText,LogDateTime) VALUES(?,?,?,NOW());";
			$statement=db::connect($_)->prepare($query);
			$statement->execute(array($type,$user,$text));
			db::close($statement);
			/* Type key for common::log()
			1 - register
			2 - login
			3 - logout
			4 - cookie/token renewed
			5 - expired cookie/token destroyed
			*/
		}
		public static function config($_) {
			$statement = db::connect($_);
			foreach($statement->query('SELECT * FROM '.$_['table_prefix'].'settings') as $row) {
				$_[$row['SetName']] = $row['SetValue'];
			}
			db::error($statement);
			//db::close($statement);
			return $_;
		}
	}
