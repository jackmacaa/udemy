<?php
    /*
     * PDI DB class
     * connect to DB
     * create prepared statements
     * bind values
     * return rows and results
     */
    class Database
    {
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $dbname = DB_NAME;

        private $dbh;
        private $stmt;
        private $error;

        public function __construct()
        {
            // SET DSN
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            $options = array(
                // checks to see if already connection to save performance
                PDO::ATTR_PERSISTENT => true,
                // there are 3 error modes silent warning and exception
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            // Create PDO instance
            try {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $e) {
                // built in function to get error msg
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        // Prepare statement with query
        // dbh is the PDO instance from the constructor and prepare is a built in function to prevent SQL injection
        public function query($sql)
        {
            $this->stmt = $this->dbh->prepare($sql);
        }

        // Bind values
        public function bind($param, $value, $type = null)
        {
            // checking if they added a type and if not setting one
            if (is_null($type)) {
                // set to true always want it to run
                $type = match (true) {
                    is_int($value) => PDO::PARAM_INT,
                    is_bool($value) => PDO::PARAM_BOOL,
                    is_null($value) => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR,
                };
            }
            // built in bindvalue func
            $this->stmt->bindValue($param, $value, $type);
        }

        // Execute the prepared statement
        public function execute()
        {
            return $this->stmt->execute();
        }

        // Get results set as array of objects
        public function resultSet()
        {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        // Get single record as object
        public function single()
        {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        // Get row count
        public function rowCount()
        {
            // This is built in rowCount method
            return $this->stmt->rowCount();
        }
    }