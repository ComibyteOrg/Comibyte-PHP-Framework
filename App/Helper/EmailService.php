<?php

namespace App\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        
        // Configure based on environment
        $this->configureMailer();
        
        $this->fromEmail = $_ENV['MAIL_FROM_EMAIL'] ?? 'noreply@tgnbulletin.com';
        $this->fromName = $_ENV['MAIL_FROM_NAME'] ?? 'The Gospel of Now';
    }

    /**
     * Configure the mailer based on environment variables
     */
    private function configureMailer()
    {
        $mailDriver = $_ENV['MAIL_DRIVER'] ?? 'smtp';

        try {
            if ($mailDriver === 'smtp') {
                $this->mailer->isSMTP();
                $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io';
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
                $this->mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
                $this->mailer->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
                $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 587;
            } else {
                // Use PHP's mail() function
                $this->mailer->isMail();
            }

            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            error_log('Mailer configuration error: ' . $e->getMessage());
        }
    }

    /**
     * Send a newsletter email to a subscriber
     *
     * @param string $toEmail Recipient email
     * @param string $subject Email subject
     * @param string $htmlContent HTML email content
     * @param string $plainTextContent Plain text fallback
     * @return bool True if sent successfully
     */
    public function sendNewsletter($toEmail, $subject, $htmlContent, $plainTextContent = '')
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->setFrom($this->fromEmail, $this->fromName);
            $this->mailer->addAddress($toEmail);
            $this->mailer->Subject = $subject;
            $this->mailer->isHTML(true);
            $this->mailer->Body = $htmlContent;
            
            if (!empty($plainTextContent)) {
                $this->mailer->AltBody = $plainTextContent;
            }

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Email send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk emails to multiple subscribers
     *
     * @param array $emails Array of email addresses
     * @param string $subject Email subject
     * @param string $htmlContent HTML email content
     * @param string $plainTextContent Plain text fallback
     * @return array Array with 'sent' and 'failed' counts
     */
    public function sendBulkNewsletter($emails, $subject, $htmlContent, $plainTextContent = '')
    {
        $sent = 0;
        $failed = 0;

        foreach ($emails as $email) {
            if ($this->sendNewsletter($email, $subject, $htmlContent, $plainTextContent)) {
                $sent++;
            } else {
                $failed++;
            }
        }

        return ['sent' => $sent, 'failed' => $failed];
    }

    /**
     * Generate HTML email template for new blog post
     */
    public static function generateBlogPostEmail($post)
    {
        $excerpt = Helper::excerpt($post['content'], 200);
        $postUrl = $_ENV['APP_URL'] ?? 'https://tgnbulletin.com';
        $postUrl .= '/post/' . $post['id'];

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0a1a2f; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .footer { background-color: #0a1a2f; color: white; padding: 15px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; }
                .btn { display: inline-block; background-color: #d4af37; color: #0a1a2f; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
                h2 { color: #0a1a2f; }
                .meta { color: #666; font-size: 14px; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>New Blog Post</h1>
                </div>
                <div class='content'>
                    <h2>{$post['title']}</h2>
                    <div class='meta'>
                        By {$post['author_name']} | " . date('F j, Y', strtotime($post['created_at'])) . "
                    </div>
                    <p>{$excerpt}</p>
                    <a href='{$postUrl}' class='btn'>Read Full Post</a>
                </div>
                <div class='footer'>
                    <p>You received this email because you subscribed to our newsletter.</p>
                    <p><a href='{$postUrl}' style='color: #d4af37;'>View in browser</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Generate HTML email template for new devotional
     */
    public static function generateDevotionalEmail($devotional)
    {
        $excerpt = Helper::excerpt($devotional['content'], 200);
        $appUrl = $_ENV['APP_URL'] ?? 'https://tgnbulletin.com';
        $devotionalUrl = $appUrl . '/devotional/' . $devotional['id'];

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0a1a2f; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .footer { background-color: #0a1a2f; color: white; padding: 15px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; }
                .btn { display: inline-block; background-color: #d4af37; color: #0a1a2f; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
                h2 { color: #0a1a2f; }
                .meta { color: #666; font-size: 14px; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Daily Devotional</h1>
                </div>
                <div class='content'>
                    <h2>{$devotional['title']}</h2>
                    <div class='meta'>
                        By {$devotional['author_name']} | " . date('F j, Y', strtotime($devotional['created_at'])) . "
                    </div>
                    <p>{$excerpt}</p>
                    <a href='{$devotionalUrl}' class='btn'>Read Full Devotional</a>
                </div>
                <div class='footer'>
                    <p>You received this email because you subscribed to our newsletter.</p>
                    <p><a href='{$devotionalUrl}' style='color: #d4af37;'>View in browser</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Generate HTML email template for new book
     */
    public static function generateBookEmail($book)
    {
        $appUrl = $_ENV['APP_URL'] ?? 'https://tgnbulletin.com';
        $bookUrl = $appUrl . '/books';

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0a1a2f; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .footer { background-color: #0a1a2f; color: white; padding: 15px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; }
                .btn { display: inline-block; background-color: #d4af37; color: #0a1a2f; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
                h2 { color: #0a1a2f; }
                .meta { color: #666; font-size: 14px; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>New Book Available</h1>
                </div>
                <div class='content'>
                    <h2>{$book['title']}</h2>
                    <div class='meta'>
                        By {$book['writer_name']} | ₦" . number_format($book['price'], 2) . "
                    </div>
                    <p>{$book['description']}</p>
                    <a href='{$bookUrl}' class='btn'>Browse Our Books</a>
                </div>
                <div class='footer'>
                    <p>You received this email because you subscribed to our newsletter.</p>
                    <p><a href='{$bookUrl}' style='color: #d4af37;'>View in browser</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Generate HTML email template for new event
     */
    public static function generateEventEmail($event)
    {
        $appUrl = $_ENV['APP_URL'] ?? 'https://tgnbulletin.com';
        $eventUrl = $appUrl . '/events';

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0a1a2f; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .footer { background-color: #0a1a2f; color: white; padding: 15px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; }
                .btn { display: inline-block; background-color: #d4af37; color: #0a1a2f; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
                h2 { color: #0a1a2f; }
                .meta { color: #666; font-size: 14px; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Upcoming Event</h1>
                </div>
                <div class='content'>
                    <h2>{$event['title']}</h2>
                    <div class='meta'>
                        📅 " . date('F j, Y g:i A', strtotime($event['event_date'])) . " | 📍 {$event['location']}
                    </div>
                    <p>{$event['description']}</p>
                    <a href='{$eventUrl}' class='btn'>View All Events</a>
                </div>
                <div class='footer'>
                    <p>You received this email because you subscribed to our newsletter.</p>
                    <p><a href='{$eventUrl}' style='color: #d4af37;'>View in browser</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
