<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 12/04/2016
 * Time: 22:09
 */

namespace LoneWolfAppBundle\Command;


//use Symfony\Component\EventDispatcher\Event;
use Composer\Script\Event;

class HerokuDatabase
{

    public static function populateEnvironment(Event $event)
    {
        $url = getenv("DATABASE_URL");

        if ($url) {
            $url = parse_url($url);
            putenv("DATABASE_HOST={$url['host']}");
            putenv("DATABASE_USER={$url['user']}");
            putenv("DATABASE_PASSWORD={$url['pass']}");
            $database = substr($url['path'], 1);
            putenv("DATABASE_NAME={$database}");
        }

        $inputOutput = $event->getIO();

        $inputOutput->write("DATABASE_URL=".getenv("DATABASE_URL"));
    }
}