<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Prompt implements ResourceInterface
{
    private ?int $id = null;

    private ?string $text = null;

    private ?string $code = null;

    private ?string $structure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(string $structure): void
    {
        $this->structure = $structure;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
