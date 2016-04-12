<?php

namespace LoneWolfAppBundle\Controller;

use LoneWolfAppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class HomeController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        $hasHero = false;
        $hasStory = false;
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        if ($user->getHero() != null) {
            $userHero = $user->getHero();
            $hasHero = true;
            if ($userHero->getCurrentStory() != null) {
                $hasStory = true;
            }
        }
        //name of story
        //stat of hero in medaillon
        return $this->render(':Layouts:homepage.html.twig', [
            'hasHero' => $hasHero,
            'hasStory' => $hasStory,
        ]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/resume", name="current_screen")
     */
    public function currentAction()
    {
        //find the best screen to redirect
    }

}
