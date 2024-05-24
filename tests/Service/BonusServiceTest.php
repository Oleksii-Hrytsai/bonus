<?php

namespace App\Tests\Service;

use App\Entity\Bonus;
use App\Repository\BonusRepository;
use App\Repository\ReceivedBonusRepository;
use App\Service\BonusService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class BonusServiceTest extends TestCase
{
    private $bonusRepository;
    private $receivedBonusRepository;
    private $entityManager;
    private $bonusService;

    protected function setUp(): void
    {
        $this->bonusRepository = $this->createMock(BonusRepository::class);
        $this->receivedBonusRepository = $this->createMock(ReceivedBonusRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->bonusService = new BonusService(
            $this->bonusRepository,
            $this->receivedBonusRepository,
            $this->entityManager
        );
    }

    public function testGetBonuses()
    {
        $bonus = new Bonus();
        $bonus->setId(1);
        $bonus->setTitle('Test Bonus');
        $bonus->setReward('10 Smile');

        $this->bonusRepository->method('findAll')->willReturn([$bonus]);
        $this->receivedBonusRepository->method('findOneBy')->willReturn(null);

        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $bonuses = $this->bonusService->getBonuses(1, true, false);

        $this->assertCount(1, $bonuses);
        $this->assertEquals(1, $bonuses[0]->getBonus()->getId());
    }
}
