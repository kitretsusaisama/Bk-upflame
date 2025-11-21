<?php

namespace App\Domains\Notification\Services;

use App\Domains\Notification\Repositories\NotificationRepository;
use App\Domains\Notification\Repositories\NotificationTemplateRepository;
use Illuminate\Support\Str;

class NotificationService
{
    protected $notificationRepository;
    protected $notificationTemplateRepository;

    public function __construct(
        NotificationRepository $notificationRepository,
        NotificationTemplateRepository $notificationTemplateRepository
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->notificationTemplateRepository = $notificationTemplateRepository;
    }

    public function send($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        if (isset($data['template_name'])) {
            $template = $this->notificationTemplateRepository->findByName(
                $data['template_name'],
                $data['tenant_id']
            );
            
            if ($template) {
                $data['template_id'] = $template->id;
                $data['subject'] = $template->subject;
                $data['body'] = $this->replaceVariables($template->body, $data['variables'] ?? []);
                $data['channel'] = $data['channel'] ?? $template->channel;
            }
            
            unset($data['template_name']);
            unset($data['variables']);
        }
        
        return $this->notificationRepository->create($data);
    }

    public function getNotificationById($id)
    {
        return $this->notificationRepository->findById($id);
    }

    public function getNotificationsByRecipient($recipientUserId, $limit = 20)
    {
        return $this->notificationRepository->findByRecipient($recipientUserId, $limit);
    }

    public function getPendingNotifications($limit = 100)
    {
        return $this->notificationRepository->findPending($limit);
    }

    public function markAsSent($notificationId)
    {
        return $this->notificationRepository->update($notificationId, [
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsFailed($notificationId, $reason)
    {
        return $this->notificationRepository->update($notificationId, [
            'status' => 'failed',
            'failure_reason' => $reason
        ]);
    }

    protected function replaceVariables($template, $variables)
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        return $template;
    }
}
