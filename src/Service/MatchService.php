<?php
/**
 * Created by PhpStorm.
 * User: denys
 * Date: 30.12.18
 * Time: 16:13
 */

namespace App\Service;


use App\Entity\TMatch;
use App\Repository\TMatchRepository;
use Doctrine\Common\Collections\Collection;

class MatchService
{
    protected $matchRepository;

    public function __construct(TMatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    public function setMatchPosition(TMatch $tMatch)
    {
        $matches = $tMatch->getCourt()->getTMatches();
        $maxPosition = 0;
        foreach ($matches as $match) {
            if ($tMatch->getId() == $match->getId()) {
                continue;
            }
            if ($match->getPosition() > $maxPosition) {
                $maxPosition = $match->getPosition();
            }
        }


        return $maxPosition + 1;
    }
}