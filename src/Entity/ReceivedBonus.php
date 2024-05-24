<?php


namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ReceivedBonusRepository")]
class ReceivedBonus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $clientId;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Bonus")]
    #[ORM\JoinColumn(nullable: false)]
    private Bonus $bonus;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $receivedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }

    public function setBonus(Bonus $bonus): void
    {
        $this->bonus = $bonus;
    }

    public function getReceivedAt(): DateTimeInterface
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(DateTimeInterface $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
    }
}
