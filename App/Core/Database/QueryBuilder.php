<?php

namespace App\Core\Database;

class QueryBuilder
{
    private $query;

    public function __construct(
        private Database $database
    ) {
    }

    private function receiveHandle()
    {
        return $this->database->getConnector();
    }


}
