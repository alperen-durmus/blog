<?php


namespace App\MessageHandler;


use App\Entity\Blog;
use App\Message\NewsletterEmail;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterEmailHandler implements MessageHandlerInterface
{
    /**
     * @var MailerInterface
     */
    protected $mailer;
    protected $entityManager;

    /**
     * NewsletterEmailHandler constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NewsletterEmail $newsletterEmail)
    {
        $blog_id = $newsletterEmail->getBlogId();
        $blog = $this->getBlogById($blog_id);


        $subscriber_email = $newsletterEmail->getSubscriberEmail();

        $email = (new Email())
            ->from('aalperendurmuss@gmail.com')
            ->to($subscriber_email)
            ->subject('Yeni blog yayında')
            ->html("{$blog->getTitle()} başlıklı yeni yazımızı okumak için  <a href='https://127.0.0.1:8000/post/{$blog_id}'>buraya</a> tıklayabilirsin.");

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            dump($exception);
        }
    }

    public function getBlogById($blog_id) {
        return $this->entityManager->getRepository(Blog::class)->find($blog_id);

    }
}
