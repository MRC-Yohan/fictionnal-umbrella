<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number_of_player;

    /**
     * @ORM\OneToMany(targetEntity=Monitoring::class, mappedBy="game")
     */
    private $monitorings;

    /**
     * @ORM\ManyToMany(targetEntity=user::class)
     */
    private $player;

    public function __construct()
    {
        $this->monitorings = new ArrayCollection();
        $this->player = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNumberOfPlayer(): ?int
    {
        return $this->number_of_player;
    }

    public function setNumberOfPlayer(?int $number_of_player): self
    {
        $this->number_of_player = $number_of_player;

        return $this;
    }

    /**
     * @return Collection|Monitoring[]
     */
    public function getMonitorings(): Collection
    {
        return $this->monitorings;
    }

    public function addMonitoring(Monitoring $monitoring): self
    {
        if (!$this->monitorings->contains($monitoring)) {
            $this->monitorings[] = $monitoring;
            $monitoring->setGame($this);
        }

        return $this;
    }

    public function removeMonitoring(Monitoring $monitoring): self
    {
        if ($this->monitorings->removeElement($monitoring)) {
            // set the owning side to null (unless already changed)
            if ($monitoring->getGame() === $this) {
                $monitoring->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getPlayer(): Collection
    {
        return $this->player;
    }

    public function addPlayer(user $player): self
    {
        if (!$this->player->contains($player)) {
            $this->player[] = $player;
        }

        return $this;
    }

    public function removePlayer(user $player): self
    {
        $this->player->removeElement($player);

        return $this;
    }
}
