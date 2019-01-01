<?php

namespace App\Controller;

use App\Entity\Court;
use App\Entity\Tournament;
use App\Form\CourtType;
use App\Repository\CourtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourtController extends AbstractController
{
    /**
     * @Route("/courts/add-new", name="courts-add-json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addCourt(Request $request)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $tournament = $entityManager->find(Tournament::class, $content->tournamentId);
        $court = new Court();
        $court->setName($content->name);
        $court->setTournament($tournament);

        $entityManager->persist($court);
        $entityManager->flush();

        return $this->json(array('id' => $court->getId()));
    }

    /**
     * @Route("/courts/get-list", name="courts-get-list-json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCourtsList(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Court::class);
        $content = json_decode($request->getContent());
        $courts = $repository->findBy(array('tournament' => $content->tournamentId));
        $resultCourt = array();

        /** @var Court $tournament */
        foreach ($courts as $court) {
            $courtArray = array();
            $courtArray['id'] = $court->getId();
            $courtArray['name'] = $court->getName();
            $courtArray['tournamentId'] = $court->getTournament()->getId();
            $resultCourt[] = $courtArray;
        }

        return $this->json($resultCourt);
    }

    /**
     * @Route("/players/delete", name="player-delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteTournament(Request $request)
    {
        $content = json_decode($request->getContent());
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $manager = $this->getDoctrine()->getManager();
        $player = $repository->find($content->id);

        $manager->remove($player);
        $manager->flush();

        return $this->json(json_encode(array('success' => true)));
    }
}
