<?php
    class db
    {
        public static function connect($_)
        {
            $con = 'mysql:host='.$_['db_host'].';dbname='.$_['db_name'].';';
            try {
                if ($con = new PDO($con, $_['db_user'],$_['db_pass'])) { // mysql
                    core::debug($_, 'db::connect - opened database connection');
                }
            } catch(PDOException $e) {
                die ('<h1>Could not connect to database.</h1>'); // Exit, displaying an error message
            }
            return $con;
        }

        public static function close($_, $statement)
        {
            //if ($statement = null) {
            if ($result = $statement->closeCursor()) {
                core::debug($_, 'db::close - closed database connection');
                return true;
            } else {
                core::debug($_, 'db::close - failed to close database connection');
                return false;
            }
        }

        public static function error($_, $statement)
        {
            $result = $statement->errorCode();
            if ($result !== '00000') {
                core::debug($_, $result);
                core::debug($_, $statement->errorInfo());
                die('<h1>Database error.</h1>');
            }
        }

        public static function arrayPrep($_, $values)
        {
            // Prepare $values to ensure it gets passed to PDO as an array, and report on what's happened for debugging purposes
            if ($values !== null) {
                core::debug($_, 'db::arrayPrep - $values is not null');
                if (!is_array($values)) {
                    core::debug($_, 'db::arrayPrep - $values is not an array');
                    if ($values = array($values)) {
                        core::debug($_, 'db::arrayPrep - $values converted to an array');
                        core::debug($_, $values);
                    } else {
                        core::debug($_, 'db::arrayPrep - $values could not be converted to an array');
                    }
                } else {
                    core::debug($_, 'db::arrayPrep - $values is an array');
                    core::debug($_, $values);
                }
            } else {
                core::debug($_, 'db::arrayPrep - $values is null');
            }

            return $values;
        }
        public static function execute($_, $statement, $values)
        {
            if ($values == null) {
                if ($statement->execute()) {
                    core::debug($_, 'db::execute - $statement->execute (null $values) returned true');
                } else {
                    core::debug($_, 'db::execute - $statement->execute (null $values) returned false');
                }
            } elseif (is_array($values)) {
                if ($statement->execute($values)) {
                    core::debug($_, 'db::execute - $statement->execute (array $values) returned true');
                } else {
                    core::debug($_, 'db::execute - $statement->execute (array $values) returned false');
                }
            }

            return $statement;
        }
        public static function selectAll($_, $query, $values = null)
        {
            try {
                core::debug($_, 'db::selectAll - $query: '.$query.' $values:');
                core::debug($_, $values);

                $values = self::arrayPrep($_, $values);

                $con = self::connect($_);
                $statement = $con->prepare($query);
                $statement = self::execute($_, $statement, $values);

                if($result = $statement->fetchAll(PDO::FETCH_ASSOC)) {
                    core::debug($_, 'db::selectAll - $statement->fetchAll returned true');
                    core::debug($_, $result);
                } else {
                    core::debug($_, 'db::selectAll - $statement->fetchAll returned false');
                    $result = false;
                }
            } catch (PDOException $e) {
                core::error($_, $e->getMessage());
                //print "Error!: " . $e->getMessage() . "<br/>";
            }

            self::error($_, $statement);
            self::close($_, $statement);
            return $result;
        }
        public static function selectRow($_, $query, $values)
        {
            try {
                core::debug($_, 'db::selectRow - $query: '.$query.' $values:');
                core::debug($_, $values);

                $values = self::arrayPrep($_, $values);

                $con = self::connect($_);
                $statement = $con->prepare($query);
                $statement = self::execute($_, $statement, $values);

                if($result = $statement->fetch(PDO::FETCH_ASSOC)) {
                    core::debug($_, 'db::selectRow - $statement->fetch returned true');
                    core::debug($_, $result);
                } else {
                    core::debug($_, 'db::selectRow - $statement->fetch returned false');
                    $result = false;
                }
            } catch (PDOException $e) {
                core::error($_, $e->getMessage());
            }

            self::error($_, $statement);
            self::close($_, $statement);
            return $result;
        }
        public static function rowExists($_, $query, $values)
        {
            try {
                core::debug($_, 'db::rowExists - $query: '.$query.' $values:');
                core::debug($_, $values);

                $values = self::arrayPrep($_, $values);

                $con = self::connect($_);
                $statement = $con->prepare($query);
                $statement = self::execute($_, $statement, $values);

                if($count = $statement->fetchColumn()) {
                    if ($count !== '0') {
                        $result = true;
                    } else {
                        $result = false;
                    }
                } else {
                    core::debug($_, 'db::rowExists - $statement->fetchColumn returned false');
                    $result = false;
                }
            } catch (PDOException $e) {
                core::error($_, $e->getMessage());
            }

            self::error($_, $statement);
            self::close($_, $statement);
            return $result;
        }
        public static function insertRow($_, $query, $values)
        {
            try {
                core::debug($_, 'db::insert - $query: '.$query.' $values:');
                core::debug($_, $values);

                $values = self::arrayPrep($_, $values);

                $con = self::connect($_);
                $statement = $con->prepare($query);
                if ($statement = self::execute($_, $statement, $values)) {
                    $result = true;
                } else {
                    core::debug($_, 'db::insert - $statement->Execute returned false');
                    $result = false;
                }
            } catch (PDOException $e) {
                core::error($_, $e->getMessage());
            }

            self::error($_, $statement);
            self::close($_, $statement);
            return $result;
        }
    }
