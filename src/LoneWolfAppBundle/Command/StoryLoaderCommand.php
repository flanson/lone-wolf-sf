<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 12/04/2016
 * Time: 22:09
 */

namespace LoneWolfAppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StoryLoaderCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('lonewolf:story:load')
            ->setDescription('Load story stored in json')
            ->addArgument(
                'storyList',
                InputArgument::OPTIONAL,
                'List of story you want to apply ex 1:1,4,7_2:1,5_3:6,8,7'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storyListOptionText = $input->getArgument('storyList');
        $subCampaignList = [];
        if (!empty($storyListOptionText)) {
            $campaignArray = explode('_', $storyListOptionText);
            foreach ($campaignArray as $campaignText) {
                $campaignInfo = explode(':', $campaignText);
                $subCampaignList[$campaignInfo[0]] = explode(',', $campaignInfo[1]);
            }
        }
        $storyLoader = $this->getContainer()->get('lone_wolf_app.story_loader');
        $storyLoader->loadCampaignList($subCampaignList);
    }
}