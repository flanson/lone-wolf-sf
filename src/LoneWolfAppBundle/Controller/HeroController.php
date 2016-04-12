<?php

namespace LoneWolfAppBundle\Controller;

use LoneWolfAppBundle\Entity\Hero;
use LoneWolfAppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Hero controller.
 *
 * @Route("/hero")
 */
class HeroController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/life", name="hero_life")
     */
    public function lifeAction()
    {
        // replace this example code with whatever you need
        return $this->render(':Layouts:hero.life.management.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/dead", name="hero_dead")
     */
    public function deadAction()
    {
        // replace this example code with whatever you need
        return $this->render(':Layouts:dead.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * Creates a new Hero entity.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="hero_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $hero = new Hero();
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero != null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have a Hero : %s', 'new'));
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm('LoneWolfAppBundle\Form\HeroType', $hero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setHero($hero);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hero);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(':Layouts:hero.edition.html.twig', array(
            'newHero'       => true,
            'hero'          => $hero,
            'edit_form'     => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Hero entity.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/edit", name="hero_edition")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        $hero = $this->getUserHero();
        $response = $this->redirectIfNoHero($hero, 'edit');
        if ($response) {
            return $response;
        }
        $deleteForm = $this->createDeleteForm($hero);
        $editForm = $this->createForm('LoneWolfAppBundle\Form\HeroType', $hero);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hero);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(':Layouts:hero.edition.html.twig', array(
            'newHero'       => false,
            'hero'          => $hero,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Hero entity.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/delete", name="hero_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $hero = $this->getUserHero();
        $response = $this->redirectIfNoHero($hero, 'delete');
        if ($response) {
            return $response;
        }
        $form = $this->createDeleteForm($hero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setHero(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->remove($hero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a Hero entity.
     *
     * @param Hero $hero The Hero entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hero $hero)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hero_delete', array('id' => $hero->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @return Hero
     * @throws \Exception
     */
    private function getUserHero()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $hero = $user->getHero();
        return $hero;
    }

    /**
     * @param $hero
     * @param $action
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectIfNoHero($hero, $action)
    {
        if ($hero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you have no Hero : %s', $action));
            return $this->redirectToRoute('homepage');
        }
    }
}
