<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /**
     * @Route("/player", name="player")
     */
    public function index()
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

    /**
     * @Route("/players/list", name="players")
     */
    public function list()
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

    /**
     * @Route("/players/add", name="players-add")
     */
    public function add()
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

    /**
     * @Route("/players/edit/{id}", name="players-edit")
     */
    public function edit()
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

    /**
     * @Route("/players/add-new", name="players-add-json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addPlayer(Request $request)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $player = new Player();
        $player->setFirstname($content->firstname);
        $player->setLastname($content->lastname);
        $player->setSurname($content->surname);
        $player->setPhone($content->phone);
        if (!empty($content->birthday)) {
            $player->setBirthday(new \DateTime('@'. strtotime($content->birthday)));
        }

        $entityManager->persist($player);
        $entityManager->flush();

        return $this->json(json_encode(array('id' => $player->getId())));
    }

    /**
     * @Route("/players/update", name="player-update-json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function updatePlayer(Request $request)
    {
        $content = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $player = $entityManager->find(Player::class, $content->id);
        $player->setFirstname($content->firstname);
        $player->setLastname($content->lastname);
        $player->setSurname($content->surname);
        $player->setPhone($content->phone);
        if (!empty($content->birthday)) {
            $player->setBirthday(new \DateTime('@'. strtotime($content->birthday)));
        } else {
            $player->setBirthday(null);
        }
        $entityManager->persist($player);
        $entityManager->flush();

        return $this->json(json_encode(array('id' => $player->getId())));
    }

    /**
     * @Route("/players/get-list", name="players-get-list-json")
     */
    public function getPlayersList()
    {
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $players = $repository->findAll();
        $resultPlayers = array();

        /** @var Player $tournament */
        foreach ($players as $player) {
            $playerArray = array();
            $playerArray['id'] = $player->getId();
            $playerArray['firstname'] = $player->getFirstname();
            $playerArray['lastname'] = $player->getLastname();
            $playerArray['surname'] = $player->getSurname();
            $playerArray['phone'] = $player->getPhone();
            $playerArray['birthday'] = $player->getBirthday() == null ? '' : $player->getBirthday()->format('d-m-Y');
            $resultPlayers[] = $playerArray;
        }

        return $this->json(json_encode($resultPlayers));
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
