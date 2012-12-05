<?php
	class db {
		public static function connect($_) {
			$_['con'] = 'mysql:host='.$_['db_host'].';dbname='.$_['db_name'].';';
			try {
				$con = new PDO($_['con'],$_['db_user'],$_['db_pass']); // mysql
			} catch(PDOException $e) {
				die ('Could not connect to database.'); // Exit, displaying an error message
			}
			return $con;
		}
		public static function close($statement) {
			return $statement->closeCursor();
		}
		public static function query($_, $query, $values) {
			$statement = self::connect($_)->prepare($query);
			if(is_array($values)) {
				$statement->execute($values);
			} else {
				$statement->execute(array($values));
			}
			self::close($statement);
		}
		public static function getRow($_, $query, $values) {
			$statement = self::connect($_)->prepare($query);
			if(is_array($values)) {
				$statement->execute($values);
			} else {
				$statement->execute(array($values));
			}
			$result = $statement->fetch(PDO::FETCH_ASSOC);
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
			$count = $statement->fetchColumn(); // investigate switching to rowCount instead of fetchColumn
			self::close($statement);
			if ($count !== '0') {
				return true;
			} else {
				return false;
			}
		}
	}
