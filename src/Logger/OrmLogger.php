<?php


namespace App\Logger;

use App\Entity\Log;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class OrmLogger extends AbstractProcessingHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function write(array $record)
    {
        $context = $record['context'];

        $user = $this->em->getRepository(User::class)->findOneBy(["username" => $context['username']]);

        $log = new Log();
        $log->setAction($context['action']);
        $log->setCreatedAt($record['datetime']);
        $log->setDetail($this->__formatter($record['message'], $context));
        $log->setUser($user);

        $this->em->persist($log);
        $this->em->flush();

    }

    /**
     * log içerisindeki recordun içinde gelen contextteki keyleri {} arasına alır,
     *
     * @param $context
     * @return array|false
     */

    private function __modify_keys($context)
    {
        $keys = array_map(function ($k){
            return '{'.$k.'}';
        }, array_keys($context));

        return array_combine($keys, array_values($context));
    }

    private function __formatter($message, $context) {
        $context = $this->__modify_keys($context);
        return str_replace(array_keys($context), array_values($context), $message);
    }
}