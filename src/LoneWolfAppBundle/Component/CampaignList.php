<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 24/04/2016
 * Time: 12:09
 */

namespace LoneWolfAppBundle\Component;

use LoneWolfAppBundle\Entity\Campaign;

class CampaignList
{
    const SAVE_JSON_FOLDER = './src/LoneWolfAppBundle/Resources/SavedStories/';
    const COMPARE_JSON_FOLDER = './src/LoneWolfAppBundle/Resources/CompareStories/HtmlStories/';
    const COMPARE_JS_JSON_FOLDER = './src/LoneWolfAppBundle/Resources/CompareStories/JsStories/';
    const COMBAT_FILE_NAME = 'combat.json';
    const DEAD_END_FILE_NAME = 'ending.json';
    const CHAPTER_FILE_NAME = 'story.json';
    const COMBAT_JS_FILE_NAME = 'combat_js.json';
    const DEAD_END_JS_FILE_NAME = 'ending_js.json';
    const CHAPTER_JS_FILE_NAME = 'story_js.json';

    /**
     * @var array
     */
    private $campaignStoryList = [];
    private $campaignNameList = [];

    /**
     * CampaignList constructor.
     * @param array $campaignList
     */
    public function __construct(array $campaignList)
    {
        $this->setCampaignList($campaignList);
    }

    /**
     * @param $object
     * @param string $functionName
     * @param array $subCampaignList.
     */
    public function applyToCampaignList($object, $functionName, $subCampaignList = [])
    {
        if (!method_exists($object, $functionName)) {
            return;
        }
        $campaignList = $this->getCampaignList();
        if (!empty($subCampaignList)) {
            $campaignList = $this->getSubCampaignList($campaignList, $subCampaignList);
        }
        foreach ($campaignList as $campaignKey => $storyList) {
            $this->applyToCampaign($object, $functionName, $storyList, $campaignKey);
        }
    }

    /**
     * @return array
     */
    private function getCampaignList()
    {
        return $this->campaignStoryList;
    }

    /**
     * @param string $storyKey
     * @return string
     */
    public function getStoryName($storyKey)
    {
        $storyNameList =  $this->getCampaignList();
        $storyIdArray = explode('_', $storyKey);
        if (!isset($storyIdArray[0]) || !isset($storyIdArray[1])) {
            return '';
        }
        $campaignId = $storyIdArray[0];
        $campaignStoryId = $storyIdArray[1];
        if (key_exists($campaignId, $storyNameList)
            && key_exists($campaignStoryId, $storyNameList[$campaignId]) ) {
            return $storyNameList[$campaignId][$campaignStoryId];
        }
        return '';
    }

    /**
     * @param string $storyKey
     * @return Campaign|null
     */
    public function getCampaign($storyKey)
    {
        $campaignKey = $this->getCampaignKey($storyKey);
        $campaignList = $this->campaignNameList;
        if (key_exists($campaignKey, $campaignList)) {
            return $campaignList[$campaignKey];
        }
        return null;
    }

    /**
     * @param string $storyKey
     * @return int
     */
    public function getCampaignKey($storyKey)
    {
        $storyIdArray = explode('_', $storyKey);
        if (!isset($storyIdArray[0])) {
            return 0;
        }
        return $storyIdArray[0];
    }

    /**
     * @param string $storyKey
     * @return int
     */
    public function getStoryPosition($storyKey)
    {
        $storyIdArray = explode('_', $storyKey);
        if (!isset($storyIdArray[1])) {
            return 0;
        }
        return $storyIdArray[1];
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getChapterList($storyKey)
    {
        return $this->fileGetArray(self::SAVE_JSON_FOLDER . $storyKey . self::CHAPTER_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCompareChapterList($storyKey)
    {
        return $this->fileGetArray(self::COMPARE_JSON_FOLDER . $storyKey . self::CHAPTER_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCompareJsChapterList($storyKey)
    {
        return $this->fileGetArray(self::COMPARE_JS_JSON_FOLDER . $storyKey . self::CHAPTER_JS_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getDeadEndList($storyKey)
    {
        return $this->fileGetArray(self::SAVE_JSON_FOLDER . $storyKey . self::DEAD_END_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCompareDeadEndList($storyKey)
    {
        return $this->fileGetArray(self::COMPARE_JSON_FOLDER . $storyKey . self::DEAD_END_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCompareJsDeadEndList($storyKey)
    {
        return $this->fileGetArray(self::COMPARE_JS_JSON_FOLDER . $storyKey . self::DEAD_END_JS_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCombatList($storyKey)
    {
        return $this->fileGetArray(self::SAVE_JSON_FOLDER . $storyKey . self::COMBAT_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCompareCombatList($storyKey)
    {
        return $this->fileGetArray(self::COMPARE_JSON_FOLDER . $storyKey . self::COMBAT_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @return mixed
     */
    public function getCompareJsCombatList($storyKey)
    {
        return $this->fileGetArray(self::COMPARE_JS_JSON_FOLDER . $storyKey . self::COMBAT_JS_FILE_NAME);
    }

    /**
     * @param string $storyKey
     * @param array $data
     */
    public function saveChapterList($storyKey, $data)
    {
        $this->saveJson(self::SAVE_JSON_FOLDER . $storyKey . self::CHAPTER_FILE_NAME, $data);
        $this->saveJson(self::COMPARE_JSON_FOLDER . $storyKey . self::CHAPTER_FILE_NAME, $data);
    }

    /**
     * @param string $storyKey
     * @param array $data
     */
    public function saveDeadEndList($storyKey, $data)
    {
        $this->saveJson(self::SAVE_JSON_FOLDER . $storyKey . self::DEAD_END_FILE_NAME, $data);
        $this->saveJson(self::COMPARE_JSON_FOLDER . $storyKey . self::DEAD_END_FILE_NAME, $data);
    }

    /**
     * @param string $storyKey
     * @param array $data
     * @param $dataCompare
     */
    public function saveCombatList($storyKey, $data, $dataCompare)
    {
        $this->saveJson(self::SAVE_JSON_FOLDER . $storyKey . self::COMBAT_FILE_NAME, $data);
        $this->saveJson(self::COMPARE_JSON_FOLDER . $storyKey . self::COMBAT_FILE_NAME, $dataCompare);
    }

    /**
     * @param string $fileName
     * @return mixed
     */
    public function fileGetArray($fileName)
    {
        $json = file_get_contents($fileName);
        $data = json_decode($json, true);
        return $data;
    }

    /**
     * @param string $fileName
     * @param array $data
     */
    public function saveJson($fileName, $data)
    {
        $file = fopen($fileName, 'w');
        fwrite($file, json_encode($data));
        fclose($file);
    }

    /**
     * @param array $campaignArray
     * @param string $campaignKey
     */
    private function addCampaignInfo($campaignArray, $campaignKey)
    {
        if (!isset($campaignArray['name']) || !isset($campaignArray['story_list'])) {
            return;
        }
        $campaign = new Campaign();
        $campaign->setName($campaignArray['name']);
        $campaign->setPosition($campaignKey);
        $this->campaignNameList[$campaignKey] = $campaign;
        $this->campaignStoryList[$campaignKey] = $campaignArray['story_list'];
    }

    /**
     * @param array $campaignList
     */
    private function setCampaignList(array $campaignList)
    {
        foreach ($campaignList as $campaignKey => $campaign) {
            $this->addCampaignInfo($campaign, $campaignKey);
        }
    }

    /**
     * @param $object
     * @param string $functionName
     * @param array $storyList
     * @param string $campaignKey
     */
    private function applyToCampaign($object, $functionName, $storyList, $campaignKey)
    {
        foreach (array_keys($storyList) as $storyKey) {
            $storyId = $campaignKey . '_' . $storyKey;
            $args = [$storyId];
            call_user_func_array(array($object, $functionName), $args);
        }
    }

    /**
     * @param $campaignList
     * @param $subCampaignList
     * @return array
     */
    private function getSubCampaignList($campaignList, $subCampaignList)
    {
        foreach ($campaignList as $campaignKey => $storyList) {
            if (!key_exists($campaignKey, $subCampaignList)) {
                unset($campaignList[$campaignKey]);
                continue;
            }
            $campaignList[$campaignKey] = $this->getSubStoryList($storyList, $subCampaignList[$campaignKey]);
        }
        return $campaignList;
    }
    /**
     * @param $storyList
     * @param $subStoryList
     * @return array
     */
    private function getSubStoryList($storyList, $subStoryList)
    {
        foreach (array_keys($storyList) as $storyKey) {
            if (!in_array($storyKey, $subStoryList)) {
                unset($storyList[$storyKey]);
            }
        }
        return $storyList;
    }
}
