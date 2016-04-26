<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 24/04/2016
 * Time: 22:28
 */

namespace LoneWolfAppBundle\Component;


class CombatInfo
{
    const NAME_PROPERTY_KEY = 'name';
    const COMBAT_SKILL_PROPERTY_KEY = 'COMBAT SKILL';
    const ENDURANCE_PROPERTY_KEY = 'ENDURANCE';
    private $name;
    private $truncatedName;
    private $combatSkill;
    private $endurance;

    /**
     * CombatInfo constructor.
     * @param $name
     * @param $combatSkill
     * @param $endurance
     */
    public function __construct($name, $combatSkill, $endurance)
    {
        $this->name = $name;
        $this->truncatedName = $this->truncateMonsterName($name);
        $this->combatSkill = $combatSkill;
        $this->endurance = $endurance;
    }

    public function truncateMonsterName($monsterName)
    {
        if (strlen($monsterName) > 14) {
            $monsterName = substr($monsterName, 0, 11) . "...";
        }
        return $monsterName;
    }

    public function toArray()
    {
        return [
            self::NAME_PROPERTY_KEY => $this->name,
            self::COMBAT_SKILL_PROPERTY_KEY => $this->combatSkill,
            self::ENDURANCE_PROPERTY_KEY => $this->endurance,
        ];
//        return [
//            'name' => $this->name,
//            'COMBAT SKILL' => $this->combatSkill,
//            'ENDURANCE' => $this->combatSkill,
//        ];
    }

    public function toCompareArray()
    {
        return [
            self::NAME_PROPERTY_KEY => $this->truncatedName,
            self::COMBAT_SKILL_PROPERTY_KEY => $this->combatSkill,
            self::ENDURANCE_PROPERTY_KEY => $this->endurance,
        ];
//        return [
//            'name' => $this->truncatedName,
//            'COMBAT SKILL' => $this->combatSkill,
//            'ENDURANCE' => $this->combatSkill,
//        ];
    }

    /**
     * @param array $combatInfoArray
     * @return string
     */
    public static function getNameFromCombatInfoArray($combatInfoArray)
    {
        if (isset($combatInfoArray[self::NAME_PROPERTY_KEY])) {
            return $combatInfoArray[self::NAME_PROPERTY_KEY];
        }
        return '';
    }

    /**
     * @param array $combatInfoArray
     * @return int
     */
    public static function getCombatSkillFromCombatInfoArray($combatInfoArray)
    {
        if (isset($combatInfoArray[self::COMBAT_SKILL_PROPERTY_KEY])) {
            return intval($combatInfoArray[self::COMBAT_SKILL_PROPERTY_KEY]);
        }
        return 0;
    }

    /**
     * @param array $combatInfoArray
     * @return int
     */
    public static function getEnduranceFromCombatInfoArray($combatInfoArray)
    {
        if (isset($combatInfoArray[self::ENDURANCE_PROPERTY_KEY])) {
            return intval($combatInfoArray[self::ENDURANCE_PROPERTY_KEY]);
        }
        return 0;
    }
}