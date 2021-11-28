<?php
declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Send Mail
 *
 * Class SendMail
 */
class SendMail
{
    protected const MESSAGE_EXCEPTION_TRANSPORT = 'Erreur lors de l\'envoi du mail: %s';

    protected $mailer;
    protected $logger;
    protected $senderTo;
    protected $senderName;

    public function __construct(
        MailerInterface $mailer,
        LoggerInterface $logger,
        string $senderTo,
        string $senderName
    )
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->senderTo = $senderTo;
        $this->senderName = $senderName;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $tplPath
     * @param array|null $context
     */
    public function verifyAccountUser(string $to, string $subject, string $tplPath, ?array $context = [])
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address($this->senderTo, $this->senderName))
                ->to($to)
                ->subject($subject)
                ->htmlTemplate($tplPath)
                ->context($context)
            ;
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(sprintf(self::MESSAGE_EXCEPTION_TRANSPORT, $e->getMessage()), ['exception' => $e]);
        }
    }
}
