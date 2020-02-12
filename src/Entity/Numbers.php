<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NumbersRepository")
 */
class Numbers
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
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $length;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?int
    {
        return $this->content;
    }

    public function setContent(int $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }
}
