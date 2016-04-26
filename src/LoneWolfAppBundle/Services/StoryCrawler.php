<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 24/04/2016
 * Time: 11:19
 */

namespace LoneWolfAppBundle\Services;

use LoneWolfAppBundle\Component\CampaignList;
use LoneWolfAppBundle\Component\CombatInfo;
use Symfony\Component\DomCrawler\Crawler;

class StoryCrawler
{
    const HTML_FOLDER = './src/LoneWolfAppBundle/Resources/HtmlStories/';
    const HTML_FILE_NME = '.html';
    /**
     * @var array
     */
    private $storyArray = [];
    private $combatArray = [];
    private $combatCompareArray = [];
    private $endingCheckArray = [];
    /**
     * @var int
     */
    private $currentChapter = 0;

    /**
     * @var CampaignList
     */
    private $campaignList;

    /**
     * StoryLoader constructor.
     * @param array $campaignList
     */
    public function __construct(
        array $campaignList
    ) {
        $this->campaignList = new CampaignList($campaignList);
    }

    public function crawlCampaignList($subCampaignList = [])
    {
        if ($this->campaignList == null) {
            return;
        }
        $this->campaignList->applyToCampaignList($this, 'crawlStory', $subCampaignList);
    }

    public function crawlStory($storyKey)
    {
        $this->initCrawlStory();
        $html = file_get_contents(self::HTML_FOLDER . $storyKey . self::HTML_FILE_NME);
        $crawler = new Crawler($html);
        $crawler->filter('.numbered')->children()
            ->each(function (Crawler $node) use ($crawler) {
                $this->updateCurrentChapter($node);
                $this->updateChapterInfo($node);
                $this->updateCombatInfo($node);
            });
        $this->addEndingChapter();
        $this->endingCheckArray = $this->getEndingArray();
        $this->campaignList->saveDeadEndList($storyKey, $this->endingCheckArray);
        $this->campaignList->saveCombatList($storyKey, $this->combatArray, $this->combatCompareArray);
        $this->campaignList->saveChapterList($storyKey, $this->storyArray);
    }

    /**
     * @param Crawler $node
     */
    public function updateCurrentChapter(Crawler $node)
    {
        $chapterValue = $this->getChapterValue($node);
        if ($chapterValue != '') {
            $this->currentChapter = $chapterValue;
        }
    }

    /**
     * @param Crawler $node
     */
    public function updateChapterInfo(Crawler $node)
    {
        $destinationValue = $this->getDestinationValue($node);
        if ($destinationValue != '') {
            $this->storyArray[$this->currentChapter][] = $destinationValue;
            $tempArray = $this->storyArray[$this->currentChapter];
            natsort($tempArray);
            $this->storyArray[$this->currentChapter] = array_values($tempArray);
        }
    }

    /**
     */
    public function addEndingChapter()
    {
        $this->storyArray[350][] = 'fin';
    }

    /**
     * @param Crawler $node
     */
    public function updateCombatInfo(Crawler $node)
    {
        $combatTextValue = $this->getCombatNode($node);
        $combatInfo = $this->getCombatInfo($combatTextValue);
        if ($combatInfo != null) {
            $this->combatArray[$this->currentChapter][] = $combatInfo->toArray();
            $this->combatCompareArray[$this->currentChapter][] = $combatInfo->toCompareArray();
        }
    }

    /**
     * @param Crawler $node
     * @return string
     */
    public function getChapterValue(Crawler $node)
    {
        if ($node->attr('class') == 'choice') {
            return '';
        }
        if ($node->attr('align') != 'center') {
            return '';
        }
        $chapterNode = $node->filter('a[name^=sect]');
        if (count($chapterNode) > 0) {
            return $chapterNode->text();
        }
        return '';
    }

    /**
     * @param Crawler $node
     * @return string
     */
    public function getDestinationValue(Crawler $node)
    {
        if ($node->attr('class') != 'choice') {
            return '';
        }
        $urlNode = $node->filter('a');
        if (count($urlNode) < 1) {
            return '';
        }
        $destinationValue = '';
        $urlNode->each(function (Crawler $node) use (&$destinationValue) {
            $validDestinationValue = $this->getValidDestinationValue($node);
            if ($validDestinationValue != '') {
                $destinationValue = $validDestinationValue;
            }
        });
        return $destinationValue;
    }

    /**
     * @param Crawler $node
     * @return string
     */
    public function getCombatNode(Crawler $node)
    {
        if ($node->attr('class') != 'combat') {
            return '';
        }
        $nodeText = $node->text();
        if (strpos($nodeText, 'COMBAT') === false) {
            return '';
        }
        if (strpos($nodeText, 'SKILL') === false) {
            return '';
        }
        if (strpos($nodeText, 'ENDURANCE') === false) {
            return '';
        }
        return $nodeText;
    }

    /**
     * @param string $combatTextValue
     * @return CombatInfo|null
     */
    public function getCombatInfo($combatTextValue)
    {
        if (preg_match('/^(.*): COMBAT SKILL (\d*)   ENDURANCE (\d*)$/', $combatTextValue, $matches) !== 1) {
            return null;
        }
        return new CombatInfo($matches[1], $matches[2], $matches[3]);
    }

    /**
     * @param Crawler $urlNode
     * @return string
     */
    public function getValidDestinationValue(Crawler $urlNode)
    {
        $url = $urlNode->attr('href');
        $urlParsed = parse_url($url);
        if (!isset($urlParsed['fragment']) || substr($urlParsed['fragment'], 0, 4) !== 'sect') {
            return '';
        }
        $nodeText = $urlNode->text();
        $destValueFromHref = substr($urlParsed['fragment'], 4);
        $destValueFromText = $this->findChapterInText($nodeText);
        if ($destValueFromHref === $destValueFromText) {
            return $destValueFromHref;
        }
        return '';
    }

    /**
     * @param string $nodeText
     * @return mixed|string
     */
    public function findChapterInText($nodeText)
    {
        if (preg_match_all('/\D*(\d+)/', $nodeText, $matches) === false) {
            return '';
        }
        if (count($matches) < 2 || count($matches[1]) < 1) {
            return '';
        }
        return array_pop($matches[1]);
    }

    /**
     * @return array
     */
    private function getEndingArray()
    {
        $storyArray = $this->storyArray;
        $iterator = 1;
        $endingCheckArray = [];
        while ($iterator < 350) {
            if (!array_key_exists($iterator, $storyArray)) {
                $endingCheckArray[$iterator] = "Your life and your quest end here.";
            }
            $iterator++;
        }
        return $endingCheckArray;
    }

    private function initCrawlStory()
    {
        $this->currentChapter = 0;
        $this->storyArray = [];
        $this->combatArray = [];
        $this->combatCompareArray = [];
        $this->endingCheckArray = [];
    }
}
