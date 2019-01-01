<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourtRepository")
 */
class Court
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="courts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TMatch", mappedBy="court")
     */
    private $tMatches;

    public function __construct()
    {
        $this->tMatches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|TMatch[]
     */
    public function getTMatches(): Collection
    {
        return $this->tMatches;
    }

    public function addTMatch(TMatch $tMatch): self
    {
        if (!$this->tMatches->contains($tMatch)) {
            $this->tMatches[] = $tMatch;
            $tMatch->setCourt($this);
        }

        return $this;
    }

    public function removeTMatch(TMatch $tMatch): self
    {
        if ($this->tMatches->contains($tMatch)) {
            $this->tMatches->removeElement($tMatch);
            // set the owning side to null (unless already changed)
            if ($tMatch->getCourt() === $this) {
                $tMatch->setCourt(null);
            }
        }

        return $this;
    }
}
