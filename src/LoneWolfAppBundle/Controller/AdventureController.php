<?php

namespace LoneWolfAppBundle\Controller;

use LoneWolfAppBundle\Entity\Chapter;
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
    public function adventureListAction()
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
        $userStory = $userHero->getCurrentStory();
        if ($userStory != null) {
            $currentChapter = $userHero->getCurrentChapter();
            if ($currentChapter == null) {
                $this->get('session')->getFlashBag()->add('error', sprintf('You already have a story, you where redirected to your current story : %s', $userStory->getName()));
                return $this->redirectToRoute('continue_adventure');
            }
            $this->get('session')->getFlashBag()->add('error', sprintf('You already have a story, you where redirected to your current story : %s, chapter #%d', $userStory->getName(), $currentChapter));
            return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentChapter));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $storyList = $entityManager->getRepository('LoneWolfAppBundle:Story')->findAll();

        // replace this example code with whatever you need
        return $this->render(
            ':Layouts:story.selection.html.twig',
            [
                'storyList' => $storyList,
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
        $userStory = $userHero->getCurrentStory();
        if ($userStory != null) {
            $currentChapter = $userHero->getCurrentChapter();
            if ($currentChapter == null) {
                $this->get('session')->getFlashBag()->add('error', sprintf('You already have a story, you where redirected to your current story : %s', $userStory->getName()));
                return $this->redirectToRoute('continue_adventure');
            }
            $this->get('session')->getFlashBag()->add('error', sprintf('You already have a story, you where redirected to your current story : %s, chapter #%d', $userStory->getName(), $currentChapter));
            return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentChapter));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $userHero->setCurrentStory($story);
        $userHero->setCurrentChapter(1);
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
        $userHero->getCurrentStory();
        $userStory = $userHero->getCurrentStory();
        if ($userStory == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t continue a story you don\'t have, please select one.'));
            return $this->redirectToRoute('new_adventure');
        }
        $currentChapter = $userHero->getCurrentChapter();
        if ($currentChapter != null) {
            $this->get('session')->getFlashBag()->add('success', sprintf('Welcome back to your story :%s, chapter #%d', $userStory->getName(), $currentChapter));
        }
        if ($currentChapter == null) {
            $currentChapter = 1;
            $entityManager = $this->getDoctrine()->getManager();
            $userHero->setCurrentChapter($currentChapter);
            $entityManager->persist($userHero);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('Welcome to your new story : %s !', $userStory->getName()));
        }
        return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentChapter));
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function abandonAdventureAction()
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
        $userHero->getCurrentStory();
        $userStory = $userHero->getCurrentStory();
        if ($userStory == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t abandon a story you don\'t have.'));
            return $this->redirectToRoute('homepage');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $userHero->setCurrentStory(null);
        $userHero->setCurrentChapter(null);
        $entityManager->persist($userHero);
        $entityManager->flush();
        $this->get('session')->getFlashBag()->add('info', sprintf('Coward !'));
        return $this->redirectToRoute('homepage');
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
        $userStory = $userHero->getCurrentStory();
        if ($userStory == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t travel when you don\'t have a story, please select one.'));
            return $this->redirectToRoute('new_adventure');
        }
        $currentChapter = $userHero->getCurrentChapter();
        if ($currentChapter == null) {
            $currentChapter = 1;
            $this->get('session')->getFlashBag()->add('success', sprintf('Welcome to your new story : %s !', $userStory->getName()));
        }
        if ($chapterId < 1 || $chapterId > 350) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Wrong chapter id, you where redirected to your current chapter #%d', $userStory->getName(), $currentChapter));
            return $this->redirectToRoute('travel_to_chapter', array('chapterId' => $currentChapter));
        }
        $currentChapter = $chapterId;
        $this->get('session')->getFlashBag()->add('success', sprintf('You have travel to chapter #%d', $currentChapter));
        $entityManager = $this->getDoctrine()->getManager();
        $userHero->setCurrentChapter($chapterId);
        $entityManager->persist($userHero);
        $entityManager->flush();
        // replace this example code with whatever you need
        return $this->render(
            ':Layouts:chapter.html.twig',
            [
                'hero'      => $userHero,
                'story'     => $userStory,
                'chapter'   => $currentChapter,
            ]
        );
    }
}
