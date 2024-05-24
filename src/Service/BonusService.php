<?php

namespace App\Service;

use App\Entity\Bonus;
use App\Entity\ReceivedBonus;
use App\Repository\BonusRepository;
use App\Repository\ReceivedBonusRepository;
use Doctrine\ORM\EntityManagerInterface;

class BonusService
{
    private $bonusRepository;
    private $receivedBonusRepository;
    private $entityManager;

    public function __construct(
        BonusRepository $bonusRepository,
        ReceivedBonusRepository $receivedBonusRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->bonusRepository = $bonusRepository;
        $this->receivedBonusRepository = $receivedBonusRepository;
        $this->entityManager = $entityManager;
    }

    public function getBonuses(int $clientId, bool $isEmailVerified, bool $isBirthday): array
    {
        $bonuses = $this->bonusRepository->findAll();
        $receivedBonuses = [];

        foreach ($bonuses as $bonus) {
            if (
                $this->canReceiveBonus($clientId, $bonus, $isEmailVerified, $isBirthday) &&
                !$this->hasAlreadyReceivedBonus($clientId, $bonus)
            ) {
                $receivedBonus = new ReceivedBonus();
                $receivedBonus->setClientId($clientId);
                $receivedBonus->setBonus($bonus);
                $receivedBonus->setReceivedAt(new \DateTime());
                $this->entityManager->persist($receivedBonus);
                $receivedBonuses[] = $receivedBonus;
            }
        }

        $this->entityManager->flush();
        return $receivedBonuses;
    }

    public function getReceivedBonuses(int $clientId, int $page, int $limit): array
    {
        return $this->receivedBonusRepository->findByClientId($clientId, $page, $limit);
    }

    private function canReceiveBonus(int $clientId, Bonus $bonus, bool $isEmailVerified, bool $isBirthday): bool
    {
        if (strpos($bonus->getReward(), 'Smile') !== false) {
            return $isEmailVerified || $isBirthday;
        }
        return true;
    }

    private function hasAlreadyReceivedBonus(int $clientId, Bonus $bonus): bool
    {
        return $this->receivedBonusRepository->findOneBy(['clientId' => $clientId, 'bonus' => $bonus]) !== null;
    }
}
