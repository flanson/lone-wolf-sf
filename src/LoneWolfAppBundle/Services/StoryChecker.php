<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 24/04/2016
 * Time: 11:19
 */

namespace LoneWolfAppBundle\Services;

use LoneWolfAppBundle\Component\CampaignList;

class StoryChecker
{

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

    /**
     * @param array $subCampaignList
     */
    public function compareCampaignList($subCampaignList = [])
    {
        if ($this->campaignList == null) {
            return;
        }
        $this->campaignList->applyToCampaignList($this, 'compareResults', $subCampaignList);
    }


    public function fileGetArray($fileName)
    {
        $json = file_get_contents($fileName);
        $data = json_decode($json, true);
        return $data;
    }

    public function compareResults($storyId)
    {
        $this->compareResult(
            'Chapter',
            $storyId
        );
        $this->compareResult(
            'DeadEnd',
            $storyId
        );
        $this->compareResult(
            'Combat',
            $storyId
        );
    }

    /**
     * @param string $type
     * @param string $storyId
     */
    public function compareResult($type, $storyId)
    {
        if (!in_array($type, ['Chapter', 'Combat', 'DeadEnd'])) {
            printf('This type doesn\'t exist : %s' . PHP_EOL, $type, $storyId);
            return;
        }
        $getCompareList = 'getCompare' .  $type . 'List';
        $getCompareJsList = 'getCompareJs' .  $type . 'List';
        $dataArray1 = $this->campaignList->$getCompareList($storyId);
        $dataArray2 = $this->campaignList->$getCompareJsList($storyId);
        if ($dataArray1 !== $dataArray2) {
            printf('The comparison of story %s was wrong for type %s' . PHP_EOL, $storyId, $type);
        }
//        $this->assertEquals(
//            $this->fileGetArray($fileName1),
//            $this->fileGetArray($fileName2),
//            sprintf("Compare %s with %s", $fileName1, $fileName2)
//        );
    }
}