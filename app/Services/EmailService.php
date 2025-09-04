<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\View;

class EmailService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer()
    {
        $this->mail->isSMTP();
        $this->mail->Host = env('MAIL_HOST', 'smtp.gmail.com');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = env('MAIL_USERNAME');
        $this->mail->Password = env('MAIL_PASSWORD');
        $this->mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
        $this->mail->Port = env('MAIL_PORT', 587);
        $this->mail->isHTML(true);
    }

    public function sendEmail(array $params): array
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->setFrom($params['from'], env('MAIL_FROM_NAME', 'Mailer API'));

            foreach ($params['to'] as $email) {
                $this->mail->addAddress($email);
            }

            $this->mail->Subject = $params['subject'];

            if (!empty($params['html_content'])) {
                $this->mail->Body = $params['html_content'];
            } elseif (!empty($params['template_id'])) {
                $template = $this->loadTemplate($params['template_id'], $params['data']);
                $this->mail->Body = $template;
            } else {
                throw new Exception('Either html_content or template_id must be provided');
            }

            $result = $this->mail->send();

            return [
                'sent' => $result,
                'recipients' => $params['to'],
                'subject' => $params['subject'],
                'timestamp' => now()
            ];

        } catch (Exception $e) {
            throw new \Exception('Email sending failed: ' . $e->getMessage());
        }
    }

    private function loadTemplate(string $templateId, array $data = []): string
    {
        $templatePath = "emails.templates.{$templateId}";
        
        if (!View::exists($templatePath)) {
            throw new Exception("Template '{$templateId}' not found");
        }

        return View::make($templatePath, $data)->render();
    }
}