<?php
	class db {
		public static function connect($_) {
			$_['con'] = 'mysql:host='.$_['db_host'].';dbname='.$_['db_name'].';';
			try {
				$con = new PDO($_['con'],$_['db_user'],$_['db_pass']); // mysql
			} catch(PDOException $e) {
				die ('<h1>Could not connect to database.</h1>'); // Exit, displaying an error message
			}
			return $con;
		}
		public static function close($statement) {
			return $statement->closeCursor();
		}
		public static function error($statement) {
			$status = $statement->errorCode();
			if($status !== '00000') {
				die('<h1>Database error.</h1>');
			}
		}
		public static function query($_, $query, $values) {
			$statement = self::connect($_)->prepare($query);
			if(is_array($values)) {
				$statement->execute($values);
			} else {
				$statement->execute(array($values));
			}
			self::error($statement);
			self::close($statement);

			return true;
		}
		public static function getRow($_, $query, $values) {
			$statement = self::connect($_)->prepare($query);
			if(is_array($values)) {
				$statement->execute($values);
			} else {
				$statement->execute(array($values));
			}
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			self::error($statement);
			self::close($statement);

			return $result;
		}
		public static function rowExists($_, $query, $values) {
			$statement = self::connect($_)->prepare($query);
			if(is_array($values)) {
				$statement->execute($values);
			} else {
				$statement->execute(array($values));
			}
			self::error($statement);
			self::close($statement);

			$count = $statement->fetchColumn(); // investigate switching to rowCount instead of fetchColumn
			if ($count !== '0') {
				return true;
			} else {
				return false;
			}
		}
	}
