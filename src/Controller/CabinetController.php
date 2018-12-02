<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 01.12.2018
 * Time: 12:27
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CabinetController extends Controller
{
    /**
     * @Route("/cabinet", name="cabinet")
     */
    public function cabinet()
    {
        $user = $this->getUser();

        return $this->render('cabinet.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/get-prize", name="get_prize", methods={"POST"}, options={"expose": true})
     */
    public function getPrize()
    {
        $prizeService = $this->get('casexe_task.prize_service');
        return new JsonResponse($prizeService->getPrize());
    }

    /**
     * @Route("/reject-prize", name="reject_prize", methods={"POST"}, options={"expose": true})
     */
    public function rejectPrize(Request $request)
    {
        $prizeService = $this->get('casexe_task.prize_service');
        $prizeId = $request->request->get('id');
        return new JsonResponse($prizeService->rejectPrize((int)$prizeId));
    }
}