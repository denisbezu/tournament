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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    protected $matchRepository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(TMatchRepository $matchRepository, EntityManagerInterface $entityManager)
    {
        $this->matchRepository = $matchRepository;
        $this->entityManager = $entityManager;
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

    public function positionUp(TMatch $match)
    {
        if ($match->getPosition() == 1) {
            return;
        }

        /** @var TMatch $upperMatch */
        $upperMatch = $this->matchRepository->findOneBy(
            array(
                'court' => $match->getCourt()->getId(),
                'position' => $match->getPosition() - 1
            )
        );
        if ($upperMatch != null) {
            $upperMatch->setPosition($match->getPosition());
            $this->entityManager->persist($upperMatch);
        }
        $match->setPosition($match->getPosition() - 1);
        $this->entityManager->persist($match);

        $this->entityManager->flush();
    }

    public function positionDown(TMatch $match)
    {
        /** @var TMatch $downMatch */
        $downMatch = $this->matchRepository->findOneBy(
            array(
                'court' => $match->getCourt()->getId(),
                'position' => $match->getPosition() + 1
            )
        );
        if ($downMatch != null) {
            $downMatch->setPosition($match->getPosition());
            $this->entityManager->persist($downMatch);
        }
        $match->setPosition($match->getPosition() + 1);
        $this->entityManager->persist($match);

        $this->entityManager->flush();
    }

    public function getMatchesAsArray($matches)
    {
        $resultMatch = array();

        /** @var TMatch $match */
        foreach ($matches as $match) {
            $matchArray = array();
            $matchArray['id'] = $match->getId();
            $matchArray['court'] = $match->getCourt()->getId();
            $matchArray['tournament'] = $match->getTournament()->getId();
            $matchArray['player1'] = $match->getPlayer1()->getId();
            $matchArray['player2'] = $match->getPlayer2()->getId();
            $matchArray['winner'] = $match->getWinner() == null ? '' : $match->getWinner()->getId();
            $matchArray['score'] = $match->getScore();
            $matchArray['position'] = $match->getPosition();
            $resultMatch[] = $matchArray;
        }

        return $resultMatch;
    }
}