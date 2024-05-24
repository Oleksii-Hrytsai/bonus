<?php


namespace App\Controller;

use App\Service\BonusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BonusController extends AbstractController
{
    public function __construct(private BonusService $bonusService)
    {
    }

    #[Route('/api/bonuses', methods: ['GET'])]
    public function getBonuses(Request $request): Response
    {
        $id = $request->query->getInt('id');
        $isEmailVerified = $request->query->getBoolean('isEmailVerified');
        $isBirthday = $request->query->getBoolean('isBirthday');

        $bonuses = $this->bonusService->getBonuses($id, $isEmailVerified, $isBirthday);

        return $this->json(array_map(function ($bonus) {
            return [
                'id' => $bonus->getBonus()->getId(),
                'receivedAt' => $bonus->getReceivedAt()->format('Y-m-d H:i:s'),
            ];
        }, $bonuses));
    }

    #[Route('/api/received-bonuses', methods: ['GET'])]
    public function getReceivedBonuses(Request $request): Response
    {
        $id = $request->query->getInt('id');
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $bonuses = $this->bonusService->getReceivedBonuses($id, $page, $limit);

        return $this->json(array_map(function ($receivedBonus) {
            return [
                'title' => $receivedBonus->getBonus()->getTitle(),
                'reward' => $receivedBonus->getBonus()->getReward(),
                'receivedAt' => $receivedBonus->getReceivedAt()->format('Y-m-d H:i:s'),
            ];
        }, $bonuses));
    }
}
