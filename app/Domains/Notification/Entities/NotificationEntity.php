<?php

namespace App\Domains\Notification\Entities;

use DateTime;

class NotificationEntity implements \JsonSerializable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected string $id,
        protected string $type,
        protected string $notifiableType,
        protected int $notifiableId,
        protected string $title,
        protected string $body,
        protected ?array $data,
        protected ?DateTime $readAt,
        protected ?DateTime $createdAt,
    ) {}

    public function getId(): ?string { return $this->id; }
    public function getType(): string { return $this->type; }
    public function getTitle(): string { return $this->title; }
    public function getBody(): string { return $this->body; }
    public function getData(): ?array { return $this->data; }
    
    public function isRead(): bool 
    {
        return !is_null($this->readAt);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'notifiable_type' => $this->notifiableType,
            'notifiable_id' => $this->notifiableId,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'read_at' => $this->readAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'is_read' => $this->isRead(),
        ];
    }
}
