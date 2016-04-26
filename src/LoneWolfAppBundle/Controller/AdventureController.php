<?php

namespace LoneWolfAppBundle\Controller;

use LoneWolfAppBundle\Entity\Adventure;
use LoneWolfAppBundle\Entity\Chapter;
use LoneWolfAppBundle\Entity\Etape;
use LoneWolfAppBundle\Entity\Story;
use LoneWolfAppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Hero controller.
 *
 * @Route("/adventure")
 */
class AdventureController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="new_adventure")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function storyListAction()
    {
        // TODO !!! change !!! manage otherwise ... this,is so awful
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', 'new story'));
            return $this->redirectToRoute('homepage');
        }
        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure != null) {
            $userStory = $userAdventure->getStory();
            $currentChapter = $userAdventure->getLastEtape()->getChapterValue();
//            if ($currentChapter == null) {
//                $this->get('session')->getFlashBag()->add('error', sprintf('You already have a story, you where redirected to your current story : %s', $userStory->getName()));
//                return $this->redirectToRoute('continue_adventure');
//            }
            $this->get('session')->getFlashBag()->add('error', sprintf('You already have a story, you where redirected to your current story : %s, chapter #%d', $userStory->getName(), $currentChapter));
            return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentChapter));
        }

        $entityManager = $this->getDoctrine()->getManager();
//        $storyList = $entityManager->getRepository('LoneWolfAppBundle:Story')->findAll();
        $campaignList = $entityManager->getRepository('LoneWolfAppBundle:Campaign')->findAll();

        // replace this example code with whatever you need
        return $this->render(
            ':Layouts:story.selection.html.twig',
            [
//                'storyList' => $storyList,
                'campaignList' => $campaignList,
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/select/{id}", name="select_adventure")
     * @param Story $story
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adventureSelectionAction(Story $story)
    {
        // TODO !!! change !!! manage otherwise ... this,is so awful
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', 'select story'));
            return $this->redirectToRoute('homepage');
        }
        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure != null) {
            $userStory = $userAdventure->getStory();
            $currentEtape = $userAdventure->getLastEtape();
//            $currentChapter = $userHero->getCurrentChapter();
//            if ($currentEtape == null) {
//                $this->get('session')->getFlashBag()->add('error', sprintf('You already have a adventure, you where redirected to your current story : %s', $userStory->getName()));
//                return $this->redirectToRoute('continue_adventure');
//            }
            $this->get('session')->getFlashBag()->add('error', sprintf('You already have a adventure, you where redirected to your current adventure : %s, chapter #%d', $userStory->getName(), $currentEtape->getChapterValue()));
            return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentEtape->getChapterValue()));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $newAdventure = new Adventure();
        $newAdventure->setStory($story);
        $firstEtape = new Etape();
        $firstEtape->setAdventure($newAdventure);
        $firstEtape->setChapterValue(1);
        $newAdventure->setLastEtape($firstEtape);
        $userHero->setCurrentAdventure($newAdventure);
//        $userHero->setCurrentStory($story);
//        $userHero->setCurrentChapter(1);
        $entityManager->persist($newAdventure);
        $entityManager->persist($firstEtape);
        $entityManager->persist($userHero);
        $entityManager->flush();
        $this->get('session')->getFlashBag()->add('success', sprintf('Welcome to your new story : %s !', $story->getName()));
        return $this->redirectToRoute('travel_to_chapter', array('chapterId' => 1));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/continue", name="continue_adventure")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function continueAction()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', 'new story'));
            return $this->redirectToRoute('homepage');
        }
        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t continue a story you don\'t have, please select one.'));
            return $this->redirectToRoute('new_adventure');
        }
//        $userStory = $userAdventure->getStory();
        $currentChapter = $userAdventure->getLastEtape()->getChapterValue();
//        if ($currentChapter != null) {
//            $this->get('session')->getFlashBag()->add('success', sprintf('Welcome back to your story :%s, chapter #%d', $userStory->getName(), $currentChapter));
//        }
//        if ($currentChapter == null) {
//            $firstEtape = new Etape();
//            $firstEtape->setAdventure($userAdventure);
//            $firstEtape->setChapterValue(1);
//            $userAdventure->setLastEtape($firstEtape);
//            $entityManager = $this->getDoctrine()->getManager();
//            $userHero->setCurrentChapter($currentChapter);
//            $entityManager->persist($userHero);
//            $entityManager->flush();
//            $this->get('session')->getFlashBag()->add('success', sprintf('Welcome to your new story : %s !', $userStory->getName()));
//        }
        return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentChapter));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/farniente/", name="farniente")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @throws \Exception
     */
    public function gainOneLifeAction()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', 'abandon adventure'));
            return $this->redirectToRoute('homepage');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $heroLife = $userHero->getLife();
        if ($heroLife < $userHero->getEnduranceMax()) {
            $heroLife++;
            $userHero->setLife($heroLife);
            $entityManager->persist($userHero);
            $entityManager->flush();
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('You gain 1 life !'));

        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure == null) {
//            $userStory = $userAdventure->getStory();
            return $this->redirectToRoute('homepage');
        }
        return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $userAdventure->getLastEtape()->getChapterValue()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/select/chapter/", name="select_chapter")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @throws \Exception
     */
    public function chapterSelectionAction(Request $request)
    {
        $destinationId = $request->get('destination');
        //TODO : Check if destination is really an integer...
        return $this->chapterAction($destinationId);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/abandon", name="abandon_adventure")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @throws \Exception
     */
    public function abandonAdventureAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', 'abandon adventure'));
            return $this->redirectToRoute('homepage');
        }
        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t abandon a story you don\'t have.'));
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createAbandonForm($this->generateUrl('abandon_adventure'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userHero->setCurrentEnemy(null);
            $userHero->setCurrentAdventure(null);
            $entityManager->persist($userHero);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('info', sprintf('Coward ! You just abandoned your adventure'));
        } else {
            $this->get('session')->getFlashBag()->add('error', sprintf('Could\'nt abandon adventure. Form not valid or not submitted'));
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to abandon an adventure.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createAbandonForm($action)
    {
        return $this->createFormBuilder()
            ->setAction($action)
            ->setMethod('POST')
            ->getForm()
            ;
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/chapter/{chapterId}", name="travel_to_chapter")
     * @param integer $chapterId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function chapterAction($chapterId)
    {
        // TODO !!! change !!! manage otherwise ... this,is so awful
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', 'select story'));
            return $this->redirectToRoute('homepage');
        }
        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t travel when you don\'t have a story, please select one.'));
            return $this->redirectToRoute('new_adventure');
        }
        $userStory = $userAdventure->getStory();
        $currentEtape = $userAdventure->getLastEtape();
//        if ($currentChapter == null) {
//            $currentChapter = 1;
//            $this->get('session')->getFlashBag()->add('success', sprintf('Welcome to your new story : %s !', $userStory->getName()));
//        }
        if ($chapterId < 1 || $chapterId > 350) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Wrong chapter id, you where redirected to your current chapter #%d', $userStory->getName(), $currentEtape->getChapterValue()));
            return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentEtape->getChapterValue()));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $currentChapter = $entityManager->getRepository('LoneWolfAppBundle:Chapter')->findOneBy([
            'story' => $userStory->getId(),
            'chapterValue' => $chapterId,
        ]);

        if (count($currentChapter) < 1) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Wrong chapter id %d, you where redirected to the home', $chapterId));
            return $this->redirectToRoute('homepage');
        }

        $this->get('session')->getFlashBag()->add('success', sprintf('You have travel to chapter #%d', $currentChapter->getChapterValue()));
        //change only if last etape is <> from current Chapter
        if ($chapterId != $currentEtape->getChapterValue()) {
            $entityManager = $this->getDoctrine()->getManager();
            $newEtape = new Etape();
            $newEtape->setAdventure($userAdventure);
            $newEtape->setChapterValue($chapterId);
            $userAdventure->addEtape($newEtape);
            $entityManager->persist($newEtape);
            $entityManager->persist($userAdventure);
            $entityManager->flush();
        }

        $abandonCombatForm = $this->createAbandonForm($this->generateUrl('abandon_combat'));
        $abandonAdventureForm = $this->createAbandonForm($this->generateUrl('abandon_adventure'));

        // replace this example code with whatever you need
        return $this->render(
            ':Layouts:chapter.html.twig',
            [
                'hero'      => $userHero,
                'story'     => $userStory,
                'chapter'   => $currentChapter,
                'etape'     => $currentEtape,
                'abandonAdventureForm' => $abandonAdventureForm->createView(),
                'abandonCombatForm' => $abandonCombatForm->createView(),
            ]
        );
    }
}
