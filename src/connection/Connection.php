<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\connection;


interface Connection
{
    /**
     * @param string $query
     * @return mixed
     */
    public function query(string $query);
}