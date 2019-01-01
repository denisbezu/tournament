<?php

namespace App\Controller;

use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class TournamentController extends AbstractController
{
    /**
     * @Route("/tournament", name="tournament")
     */
    public function index()
    {
        return $this->render('tournament/index.html.twig', [
            'controller_name' => 'TournamentController',
        ]);
    }

    /**
     * @Route("/tournaments/list", name="tournaments")
     */
    public function list()
    {
        return $this->render('tournament/index.html.twig', [
            'controller_name' => 'TournamentController',
        ]);
    }

    /**
     * @Route("/tournaments/add", name="tournament-add")
     */
    public function add()
    {
        return $this->render('tournament/index.html.twig', [
            'controller_name' => 'TournamentController',
        ]);
    }

    /**
     * @Route("/tournaments/edit/{id}", name="tournament-edit")
     */
    public function edit()
    {
        return $this->render('tournament/index.html.twig', [
            'controller_name' => 'TournamentController',
        ]);
    }

    /**
     * @Route("/tournaments/add-new", name="tournament-add-json")
     */
    public function addTournament(Request $request)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $tournament = new Tournament();
        $tournament->setName($content->name);
        $tournament->setDateStart(new \DateTime('@'.strtotime($content->date_start)));
        $tournament->setDateEnd(new \DateTime('@'. strtotime($content->date_end)));

        $entityManager->persist($tournament);
        $entityManager->flush();

        return $this->json(json_encode(array('id' => $tournament->getId())));
    }

    /**
     * @Route("/tournaments/update", name="tournament-update-json")
     */
    public function updateTournament(Request $request)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $tournament = $entityManager->find(Tournament::class, $content->id);
        $tournament->setName($content->name);
        $tournament->setDateStart(new \DateTime('@'.strtotime($content->date_start)));
        $tournament->setDateEnd(new \DateTime('@'. strtotime($content->date_end)));

        $entityManager->persist($tournament);
        $entityManager->flush();

        return $this->json(json_encode(array('id' => $tournament->getId())));
    }

    /**
     * @Route("/tournaments/get-list", name="tournament-get-list-json")
     */
    public function getTournamentsList()
    {
        $repository = $this->getDoctrine()->getRepository(Tournament::class);
        $tournaments = $repository->findAll();
        $resultTournaments = array();

        /** @var Tournament $tournament */
        foreach ($tournaments as $tournament) {
            $tournamentArray = array();
            $tournamentArray['id'] = $tournament->getId();
            $tournamentArray['name'] = $tournament->getName();
            $tournamentArray['date_start'] = $tournament->getDateStart()->format('d-m-Y');
            $tournamentArray['date_end'] = $tournament->getDateEnd()->format('d-m-Y');
            $resultTournaments[] = $tournamentArray;
        }

        return $this->json(json_encode($resultTournaments));
    }

    /**
     * @Route("/tournaments/delete", name="tournament-delete")
     */
    public function deleteTournament(Request $request)
    {
        $content = json_decode($request->getContent());
        $repository = $this->getDoctrine()->getRepository(Tournament::class);
        $manager = $this->getDoctrine()->getManager();
        $tournament = $repository->find($content->id);

        $manager->remove($tournament);
        $manager->flush();

        return $this->json(json_encode(array('success' => true)));
    }
}
