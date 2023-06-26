<?php

class Manager
{
    protected function dbConnect()
    {
        // NOTE: set this to false to use local database
        $USE_PLANETSCALE = false;

        if ($USE_PLANETSCALE) {
            $HOST = getenv("PLANETSCALE_DB_HOST");
            $DATABASE = getenv("PLANETSCALE_DB");
            $USERNAME = getenv("PLANETSCALE_DB_USERNAME");
            $PASSWORD = getenv("PLANETSCALE_DB_PASSWORD");
            // This is for Mac and Vercel
            $SSL_CERT = getenv("PLANETSCALE_SSL_CERT_PATH") ? getenv("PLANETSCALE_SSL_CERT_PATH") : "/etc/ssl/cert.pem";
            // On Windows, download this to your C:\Temp folder - https://curl.se/ca/cacert-2023-05-30.pem   
            // then comment the $SSL_CERT above and uncomment the one below
            // $SSL_CERT = getenv("PLANETSCALE_SSL_CERT_PATH") ? getenv("PLANETSCALE_SSL_CERT_PATH") : "C:\\temp\\cacert-2023-05-30.pem";
            $OPTIONS = array(
                PDO::MYSQL_ATTR_SSL_CA => $SSL_CERT,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"'
            );
        } else {
            $HOST = "localhost";
            $DATABASE = "batch20_project";
            $USERNAME = "root";
            $PASSWORD = "";
            $OPTIONS = array();
        }

        $db = new PDO("mysql:host=$HOST;dbname=$DATABASE;charset=utf8", $USERNAME, $PASSWORD, $OPTIONS);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $db;
    }
}
