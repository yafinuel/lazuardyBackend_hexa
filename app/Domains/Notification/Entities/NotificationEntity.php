<?php

namespace App\Domains\Notification\Entities;

use DateTime;

class NotificationEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected int $id,
        protected string $type,
        protected string $notifiableType,
        protected int $notifiableId,
        protected string $title,
        protected string $body,
        protected ?string $data,
        protected ?DateTime $readAt,
        protected ?DateTime $createdAt,
    ) {}

    public function getId(): ?string { return $this->id; }
    public function getType(): string { return $this->type; }
    public function getTitle(): string { return $this->title; }
    public function getBody(): string { return $this->body; }
    public function getData(): string { return $this->data; }
    
    public function isRead(): bool 
    {
        return !is_null($this->readAt);
    }
}
