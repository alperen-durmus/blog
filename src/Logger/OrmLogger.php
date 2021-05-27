<?php


namespace App\Logger;

use App\Entity\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class OrmLogger extends AbstractProcessingHandler
{
    protected function write(array $record)
    {
//        dd($record);
        $log = new Log();
    }

}