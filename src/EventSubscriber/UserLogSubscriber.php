<?php

namespace App\EventSubscriber;


use App\Entity\Log;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class UserLogSubscriber implements EventSubscriber
{
    private  $logger;
    private $session;
    private $user;
    public function __construct(LoggerInterface $ormLogger, SessionInterface $session)
    {
        $this->logger = $ormLogger;
        $this->session = $session;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        if (!$this->is_entity_log($args)) {
            $table = $args->getEntityManager()->getClassMetadata(get_class($args->getEntity()))->getTableName();
            $user = $this->getUser();
            $datetime = new \DateTime();

            $this->logger->info("Added new {table} by {username} at {datetime}", [
                "table" => $table,
                "username" => $user->getUsername(),
                "datetime" => $datetime->format("Y-m-d H:i:s"),
                "action" => "add"
            ]);
        };
    }

    public function postRemove(LifecycleEventArgs $args) {

        if (!$this->is_entity_log($args)) {
            $table = $args->getEntityManager()->getClassMetadata(get_class($args->getEntity()))->getTableName();
            $user = $this->getUser();
            $datetime = new \DateTime();

            $this->logger->info("Deleted {table} by {username} at {datetime}", [
                "table" => $table,
                "username" => $user->getUsername(),
                "datetime" => $datetime->format("Y-m-d H:i:s"),
                "action" => "delete"
            ]);
        };
    }

    public function postUpdate(LifecycleEventArgs $args) {

        if (!$this->is_entity_log($args)) {
            $table = $args->getEntityManager()->getClassMetadata(get_class($args->getEntity()))->getTableName();
            $user = $this->getUser();
            $datetime = new \DateTime();

            $this->logger->info("Updated {table} by {username} at {datetime}", [
                "table" => $table,
                "username" => $user->getUsername(),
                "datetime" => $datetime->format("Y-m-d H:i:s"),
                "action" => "update"
            ]);
        };
    }

    /**
     * @param $args
     * @return bool
     */
    private function is_entity_log($args): bool
    {
        if ($args->getEntity() instanceof Log) {
            return true;
        }
        return false;
    }

    /**
     * @return User|null
     */
    private function getUser(): ?User
    {
        $user = "";
        if ($this->session->has('_security_main')) {
            /** @var AbstractToken $security */
            $security = unserialize($this->session->get("_security_main"));
            /** @var User $user */
            $user = $security->getUser();
        }
        return $user;
    }
}
