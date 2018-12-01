<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 01.12.2018
 * Time: 12:27
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CabinetController extends AbstractController
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
}