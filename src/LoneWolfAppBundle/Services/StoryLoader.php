<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 24/04/2016
 * Time: 11:18
 */

namespace LoneWolfAppBundle\Services;

use Doctrine\ORM\EntityManager;
use LoneWolfAppBundle\Component\CampaignList;
use LoneWolfAppBundle\Component\CombatInfo;
use LoneWolfAppBundle\Entity\Campaign;
use LoneWolfAppBundle\Entity\Chapter;
use LoneWolfAppBundle\Entity\Enemy;
use LoneWolfAppBundle\Entity\Story;
use Symfony\Component\Console\Output\OutputInterface;

class StoryLoader
{
    const BATCH_SAVE_LENGTH = 25;

    /**
     * @var CampaignList
     */
    private $campaignList;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var OutputInterface
     */
    private $outputInterface;

    /**
     * StoryLoader constructor.
     * @param array $campaignList
     * @param EntityManager $entityManager
     */
    public function __construct(
        array $campaignList,
        EntityManager $entityManager
    ) {
        $this->campaignList = new CampaignList($campaignList);
        $this->entityManager = $entityManager;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutputInterface(OutputInterface $output)
    {
        $this->outputInterface = $output;
    }

    /**
     * @param array $subCampaignList
     */
    public function loadCampaignList($subCampaignList = [])
    {
        if ($this->campaignList == null) {
            return;
        }
        $this->campaignList->applyToCampaignList($this, 'saveFullStory', $subCampaignList);
    }

    /**
     * @param string $storyKey
     */
    public function saveFullStory($storyKey)
    {
        //$this->outputInterface->writeln();
        $campaign = $this->campaignList->getCampaign($storyKey);
        if ($campaign == null) {
            return;
        }
        $story = $this->saveStory($storyKey, $campaign);
        $chaptersArray = $this->campaignList->getChapterList($storyKey);
        $combatList = $this->campaignList->getCombatList($storyKey);
        $chapterListChunk = array_chunk($chaptersArray, self::BATCH_SAVE_LENGTH, true);
        foreach ($chapterListChunk as $chapterList) {
            $this->saveChapterBatch($chapterList, $combatList, $story);
        }
        $this->saveDeadEndList($story, $storyKey);
    }

    /**
     * @param Story $story
     * @param string $storyKey
     */
    private function saveDeadEndList($story, $storyKey)
    {
        $deadEndList = $this->campaignList->getDeadEndList($storyKey);
        foreach (array_keys($deadEndList) as $deadEndChapterId) {
            $chapter = new Chapter();
            $chapter->setChapterValue(intval($deadEndChapterId));
            $chapter->setStory($story);
            $story->addChapter($chapter);
            $this->entityManager->persist($chapter);
        }
        $this->entityManager->persist($story);
        $this->entityManager->flush();
    }

    /**
     * @param $combatList
     * @param $chapterId
     * @param Chapter $chapter
     */
    private function saveChapterCombatList($combatList, $chapterId, $chapter)
    {
        foreach ($combatList[$chapterId] as $combatEnemy) {
            $enemy = new Enemy();
            $enemy->setName(CombatInfo::getNameFromCombatInfoArray($combatEnemy));
            $enemy->setCombatSkill(CombatInfo::getCombatSkillFromCombatInfoArray($combatEnemy));
            $enemy->setEnduranceMax(CombatInfo::getEnduranceFromCombatInfoArray($combatEnemy));
//            $enemy->setName($combatEnemy['name']);
//            $enemy->setCombatSkill(intval($combatEnemy['COMBAT SKILL']));
//            $enemy->setEnduranceMax(intval($combatEnemy['ENDURANCE']));
            $enemy->setChapter($chapter);
            $chapter->addEnemy($enemy);
            $this->entityManager->persist($enemy);
        }
    }

    /**
     * @param string $storyKey
     * @param Campaign $campaign
     * @return Story
     */
    private function saveStory($storyKey, $campaign)
    {
        $storyName = $this->campaignList->getStoryName($storyKey);
        $story = new Story();
        $story->setName($storyName);
        $story->setCampaign($campaign);
        $story->setPosition($this->campaignList->getStoryPosition($storyKey));
        $this->entityManager->persist($campaign);
        $this->entityManager->persist($story);
        $this->entityManager->flush();
        return $story;
    }

    /**
     * @param $chapterId
     * @param $chapterDirections
     * @param $combatList
     * @param Story $story
     */
    private function saveChapter($chapterId, $chapterDirections, $combatList, $story)
    {
        $chapter = new Chapter();
        $chapter->setChapterValue(intval($chapterId));
        $chapter->setDirections($chapterDirections);
        if (key_exists($chapterId, $combatList)) {
            $this->saveChapterCombatList($combatList, $chapterId, $chapter);
        }
        $chapter->setStory($story);
        $story->addChapter($chapter);
        $this->entityManager->persist($chapter);
    }

    /**
     * @param $chapterList
     * @param $combatList
     * @param Story $story
     */
    private function saveChapterBatch($chapterList, $combatList, $story)
    {
        foreach ($chapterList as $chapterId => $chapterDirections) {
            $this->saveChapter($chapterId, $chapterDirections, $combatList, $story);
        }
        $this->entityManager->persist($story);
        $this->entityManager->flush();
    }
}
