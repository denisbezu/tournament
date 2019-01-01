<?php

namespace App\Controller;

use App\Entity\Court;
use App\Entity\Player;
use App\Entity\TMatch;
use App\Entity\Tournament;
use App\Service\MatchService;
use App\Utils\CourtViewType;
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
     * @param MatchService $matchService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getMatchesList(Request $request, MatchService $matchService)
    {
        $content = json_decode($request->getContent());
        if ($content->court == '') {
            return $this->json(array());
        }

        $court = $this->getDoctrine()->getManager()->find(Court::class, $content->court);
        $matches = $court->getTMatches();
        $resultMatch = $matchService->getMatchesAsArray($matches);

        return $this->json($resultMatch);
    }

    /**
     * @Route("/matches/get-list/tournament", name="matches-get-all-list-json")
     * @param Request $request
     * @param MatchService $matchService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getMatchesListByTournament(Request $request, MatchService $matchService)
    {
        $content = json_decode($request->getContent());
        $repository = $this->getDoctrine()->getRepository(TMatch::class);

        if ($content->tournament == '') {
            return $this->json(array());
        }
        $tournament = $this->getDoctrine()->getManager()->find(Tournament::class, $content->tournament);
        $matches = $repository->findBy(
            array('tournament' => $tournament->getId())
        );

        $resultMatch = $matchService->getMatchesAsArray($matches);

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

    /**
     * @Route("/matches/delete", name="match-delete")
     */
    public function deleteMatch(Request $request)
    {
        $content = json_decode($request->getContent());
        $repository = $this->getDoctrine()->getRepository(TMatch::class);
        $manager = $this->getDoctrine()->getManager();
        $match = $repository->find($content->id);

        $manager->remove($match);
        $manager->flush();

        return $this->json(json_encode(array('success' => true)));
    }

    /**
     * @Route("/matches/position-update", name="match-position-up")
     * @param Request $request
     * @param MatchService $matchService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function positionUpdate(Request $request, MatchService $matchService)
    {
        $content = json_decode($request->getContent());
        $match = $this->getDoctrine()->getRepository(TMatch::class)->find($content->id);
        $repository = $this->getDoctrine()->getRepository(TMatch::class);

        if ($match != null) {
            $content->position == 'up' ? $matchService->positionUp($match) : $matchService->positionDown($match);
        }

        if ($content->type == CourtViewType::ALL) {
            $matches = $repository->findBy(
                array('tournament' => $match->getTournament()->getId())
            );
        } else {
            $matches = $match->getCourt()->getTMatches();
        }
        $matchesResult = $matchService->getMatchesAsArray($matches);

        return $this->json($matchesResult);
    }
}
