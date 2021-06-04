<?php


namespace App\Message;


class NewsletterEmail
{
    private $blog_id;
    private $subscriber_email;

    /**
     * NewsletterEmail constructor.
     * @param int $blog_id
     * @param string $subscriber_email
     */
    public function __construct(int $blog_id, string  $subscriber_email)
    {
        $this->blog_id = $blog_id;
        $this->subscriber_email = $subscriber_email;
    }

    /**
     * @return mixed
     */
    public function getSubscriberEmail(): string
    {
        return $this->subscriber_email;
    }

    /**
     * @return int
     */
    public function getBlogId(): int
    {
        return $this->blog_id;
    }
}