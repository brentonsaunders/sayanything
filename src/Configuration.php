<?php
class Configuration {
    private $dbHost = null;
    private $dbName = null;
    private $dbUsername = null;
    private $dbPassword = null;

    public function __construct(
        $dbHost,
        $dbName,
        $dbUsername,
        $dbPassword
    ) {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
    }

    public function getDbHost() {
        return $this->dbHost;
    }

    public function getDbName() {
        return $this->dbName;
    }

    public function getDbUsername() {
        return $this->dbUsername;
    }

    public function getDbPassword() {
        return $this->dbPassword;
    }

    public function setDbHost($dbHost) {
        $this->dbHost = $dbHost;
    }

    public function setDbName($dbName) {
        $this->dbName = $dbName;
    }

    public function setDbUsername($dbUsername) {
        $this->dbUsername;
    }

    public function setDbPassword($dbPassword) {
        $this->dbPassword = $dbPassword;
    }
}