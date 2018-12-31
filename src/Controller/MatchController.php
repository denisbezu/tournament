<?php

namespace App\Controller;

use App\Entity\Court;
use App\Entity\Player;
use App\Entity\TMatch;
use App\Entity\Tournament;
use App\Service\MatchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match/add/{tournament}/{court}", name="match-add-new")
     */
    public function add()
    {
        return $this->render('match/index.html.twig', [
            'controller_name' => 'MatchController',
        ]);
    }

    /**
     * @Route("/match/edit/{id}", name="match-edit")
     */
    public function edit()
    {
        return $this->render('match/index.html.twig', [
            'controller_name' => 'MatchController',
        ]);
    }

    /**
     * @Route("/matches/add-new", name="matches-add-json")
     * @param Request $request
     * @param MatchService $matchService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addMatch(Request $request, MatchService $matchService)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $tournament = $entityManager->find(Tournament::class, $content->tournament);
        $court = $entityManager->find(Court::class, $content->court);
        $player1 = $entityManager->find(Player::class, $content->player1);
        $player2 = $entityManager->find(Player::class, $content->player2);
        $winner = $entityManager->find(Player::class, $content->winner);
        $match = new TMatch();
        $match->setTournament($tournament);
        $match->setCourt($court);
        $match->setPlayer1($player1);
        $match->setPlayer2($player2);
        $match->setScore($content->score);
        $match->setWinner($winner);
        $match->setPosition($matchService->setMatchPosition($match));
        $entityManager->persist($match);
        $entityManager->flush();

        return $this->json(array('id' => $match->getId()));
    }

    /**
     * @Route("/matches/get-list", name="matches-get-list-json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getMatchesList(Request $request)
    {
        $content = json_decode($request->getContent());
        if ($content->court == '') {
            return $this->json(array());
        }
        $court = $this->getDoctrine()->getManager()->find(Court::class, $content->court);
        $matches = $court->getTMatches();
        $resultMatch = array();

        /** @var Court $tournament */
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

        return $this->json($resultMatch);
    }

    /**
     * @Route("/matches/update", name="match-update-json")
     */
    public function updateMatch(Request $request, MatchService $matchService)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $match = $entityManager->find(TMatch::class, $content->id);
        $tournament = $entityManager->find(Tournament::class, $content->tournament);
        $court = $entityManager->find(Court::class, $content->court);
        $player1 = $entityManager->find(Player::class, $content->player1);
        $player2 = $entityManager->find(Player::class, $content->player2);
        $winner = $entityManager->find(Player::class, $content->winner);

        $match->setTournament($tournament);
        $match->setPlayer1($player1);
        $match->setPlayer2($player2);
        $match->setScore($content->score);
        $match->setWinner($winner);
        if ($match->getCourt()->getId() != $content->court) {
            $match->setCourt($court);
            $match->setPosition(0);
            $match->setPosition($matchService->setMatchPosition($match));
        }

        $entityManager->persist($match);
        $entityManager->flush();

        return $this->json(json_encode(array('id' => $tournament->getId())));
    }
}
