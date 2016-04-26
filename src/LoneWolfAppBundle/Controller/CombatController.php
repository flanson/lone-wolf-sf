<?php

namespace LoneWolfAppBundle\Controller;

use LoneWolf\Characteristics;
use LoneWolf\Combat as LoneWolfCombat;
use LoneWolf\CombatSkill;
use LoneWolf\DiceResult;
use LoneWolf\Endurance;
use LoneWolfAppBundle\Entity\Combat;
use LoneWolfAppBundle\Entity\Enemy;
use LoneWolfAppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LoneWolf\Enemy as LoneWolfEnemy;
use LoneWolf\Hero as LoneWolfHero;

/**
 * Hero controller.
 *
 * @Route("/combat")
 */
class CombatController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="combat")
     * @Method({"GET"})
     */
    public function combatAction()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you don\'t have a Hero : %s', 'combat'));
            return $this->redirectToRoute('homepage');
        }
        $userEnemy = $userHero->getCurrentEnemy();
        if ($userEnemy == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t combat without an enemy'));
            return $this->redirectToRoute('enemy_creation');
        }

        $combatRatio = $userHero->getCombatSkill() - $userEnemy->getCombatSkill();
        if ($combatRatio > 11) {
            $combatRatio = 11;
        }
        if ($combatRatio < -11) {
            $combatRatio = -11;
        }

        $abandonCombatForm = $this->createAbandonForm($this->generateUrl('abandon_combat'));
        $abandonAdventureForm = $this->createAbandonForm($this->generateUrl('abandon_adventure'));

        return $this->render(
            ':Layouts:combat.html.twig',
            [
                'combatRatio'   => $combatRatio,
                'hero'          => $userHero,
                'enemy'         => $userEnemy,
                'abandonAdventureForm' => $abandonAdventureForm->createView(),
                'abandonCombatForm' => $abandonCombatForm->createView(),
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/enemy/{id}", name="combat_enemy")
     * @Method({"GET"})
     * @param Enemy $enemy
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function combatEnemyAction(Enemy $enemy = null)
    {
        if ($enemy == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This enemy don\'t exist. Please fight a real enemy !'));
            return $this->redirectToRoute('continue_adventure');
        }
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you don\'t have a Hero : %s', 'combat'));
            return $this->redirectToRoute('homepage');
        }
        $userEnemy = $userHero->getCurrentEnemy();
        if ($userEnemy != null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You already have an enemy ! Fight him or flee like a coward before going to a new fight'));
            return $this->redirectToRoute('continue_adventure');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $currentEnemy = new Combat();
        $currentEnemy->setEnemy($enemy);
        $userHero->setCurrentEnemy($currentEnemy);
        $entityManager->persist($currentEnemy);
        $entityManager->persist($userHero);
        $entityManager->flush();

        return $this->redirectToRoute('combat');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="combat_launch_dice")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function combatLaunchDiceAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you don\'t have a Hero : %s', 'launch dice'));
            return $this->redirectToRoute('homepage');
        }
        $userEnemy = $userHero->getCurrentEnemy();
        if ($userEnemy == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t combat without an enemy'));
            return $this->redirectToRoute('enemy_creation');
        }
        $userAdventure = $userHero->getCurrentAdventure();
        if ($userAdventure == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t combat outside an adventure, please select one.'));
            return $this->redirectToRoute('new_adventure');
        }
        $diceValue = $request->get('diceValue');
        if ($diceValue < 0 || $diceValue > 9 ) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Wrong dice value : %d. You\'re you have use the right kind of dice ? Value should be between 0 and 9', $diceValue));
            return $this->redirectToRoute('combat');
        }

        //Launch Dice
        $heroPreviousLife = $userHero->getLife();
        $enemyPreviousLife = $userEnemy->getLife();
        $combatHero = new LoneWolfHero(
            new Characteristics(
                new CombatSkill($userHero->getCombatSkill()),
                new Endurance($userHero->getLife())
            )
        );
        $combatEnemy = new LoneWolfEnemy(
            new Characteristics(
                new CombatSkill($userEnemy->getCombatSkill()),
                new Endurance($userEnemy->getLife())
            )
        );
        $combat = new LoneWolfCombat($combatHero, $combatEnemy);
        $combat->rolledDice(new DiceResult($diceValue));

        //Get dice Result (hit hero/ hit enemy)
        $heroHitDamage = $userHero->getLife() - $combatHero->getLife();
        $enemyHitDamage = $userEnemy->getLife() - $combatEnemy->getLife();
        $combatRatio = $userHero->getCombatSkill() - $userEnemy->getCombatSkill();
        if ($combatRatio > 11) {
            $combatRatio = 11;
        }
        if ($combatRatio < -11) {
            $combatRatio = -11;
        }
        $userHero->setLife($combatHero->getLife());
        $userEnemy->setLife($combatEnemy->getLife());

        $entityManager = $this->getDoctrine()->getManager();
        if ($userHero->getLife() < 1) {
            $userHero->setCurrentEnemy(null);
            $entityManager->persist($userHero);
            $entityManager->flush();
            return $this->redirectToRoute('hero_dead');
        }
        if ($userEnemy->getLife() < 1) {
            $userHero->setCurrentEnemy(null);
            if ($userEnemy->getEnemy() != null) {
                $currentEtape = $userAdventure->getLastEtape();
                $currentEtape->addEnemyDefeated($userEnemy->getEnemy());
            }
            $entityManager->persist($userHero);
            $entityManager->persist($currentEtape);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('Enemy defeated !'));
            return $this->redirectToRoute('continue_adventure');
        }
        $entityManager->persist($userHero);
        $entityManager->persist($userEnemy);
        $entityManager->flush();

        $abandonCombatForm = $this->createAbandonForm($this->generateUrl('abandon_combat'));
        $abandonAdventureForm = $this->createAbandonForm($this->generateUrl('abandon_adventure'));

        return $this->render(
            ':Layouts:combat.html.twig',
            [
                'heroPreviousLife'  => $heroPreviousLife,
                'heroHitDamage'     => $heroHitDamage,
                'enemyPreviousLife' => $enemyPreviousLife,
                'enemyHitDamage'    => $enemyHitDamage,
                'diceValue'         => $diceValue,
                'combatRatio'       => $combatRatio,
                'hero'              => $userHero,
                'enemy'             => $userEnemy,
                'abandonAdventureForm' => $abandonAdventureForm->createView(),
                'abandonCombatForm' => $abandonCombatForm->createView(),
            ]
        );
    }

    /**
     * Creates a new Hero entity.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/new/combat", name="enemy_creation")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $enemy = new Combat();
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you don\'t have a Hero : %s', 'create enemy'));
            return $this->redirectToRoute('homepage');
        }
        $userEnemy = $userHero->getCurrentEnemy();
        if ($userEnemy != null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You already have a enemy, Fight him before asking for a new enemy : %s', $userEnemy->getName()));
            return $this->redirectToRoute('combat');
        }
        $form = $this->createForm('LoneWolfAppBundle\Form\CombatCreateType', $enemy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userHero->setCurrentEnemy($enemy);
            $enemy->setLifeToMax();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enemy);
            $entityManager->persist($userHero);
            $entityManager->flush();

            return $this->redirectToRoute('combat');
        }

        return $this->render(
            ':Layouts:enemy.edition.html.twig',
            [
                'newEnemy' => true,
                'enemy' => $enemy,
                'edit_form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Hero entity.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/current_enemy/edit", name="enemy_edition")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you don\'t have a Hero : %s', 'edit enemy'));
            return $this->redirectToRoute('homepage');
        }
        $userEnemy = $userHero->getCurrentEnemy();
        if ($userEnemy == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t edit a enemy you don\'t have'));
            return $this->redirectToRoute('homepage');
        }

        $editForm = $this->createForm('LoneWolfAppBundle\Form\CombatType', $userEnemy);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userEnemy);
            $entityManager->flush();

            return $this->redirectToRoute('combat');
        }

        return $this->render(
            ':Layouts:enemy.edition.html.twig',
            [
                'newEnemy'      => false,
                'enemy'         => $userEnemy,
                'edit_form'     => $editForm->createView(),
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/abandon", name="abandon_combat")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @throws \Exception
     */
    public function abandonAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception("The user Should be an instance of LoneWolfAppBundle\\Entity\\User");
        }
        $userHero = $user->getHero();
        if ($userHero == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('This action is not permitted when you don\'t have a Hero : %s', 'combat'));
            return $this->redirectToRoute('homepage');
        }
        $userEnemy = $userHero->getCurrentEnemy();
        if ($userEnemy == null) {
            $this->get('session')->getFlashBag()->add('error', sprintf('You can\'t abandon a combat you didn\'t even started!'));
            return $this->redirectToRoute('combat');
        }
        $form = $this->createAbandonForm($this->generateUrl('abandon_combat'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userHero->setCurrentEnemy(null);
            $entityManager->persist($userHero);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('info', sprintf('Coward !'));
        } else {
            $this->get('session')->getFlashBag()->add('error', sprintf('Could\'nt abandon Fight. Form not valid or not submitted'));
        }
        return $this->redirectToRoute('continue_adventure');
    }

    /**
     * Creates a form to abandon an adventure.
     *
     * @param string $action
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

}
