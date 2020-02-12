<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeRepository")
 */
class Time
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $minutes;

    /**
     * @ORM\Column(type="integer")
     */
    private $secondes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinutes(): ?int
    {
        return $this->minutes;
    }

    public function setMinutes(int $minutes): self
    {
        $this->minutes = $minutes;

        return $this;
    }

    public function getSecondes(): ?int
    {
        return $this->secondes;
    }

    public function setSecondes(int $secondes): self
    {
        $this->secondes = $secondes;

        return $this;
    }
}
