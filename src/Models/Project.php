<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
#[ORM\Table(name: 'projects')]
class Project
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int | null $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $project_name;

    #[ORM\Column(type: 'text')]
    private string $materials;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $workers_count = 0;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, options: ['default' => '0.0'])]
    private string $budget = '0.0';

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeInterface $deadline_start = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeInterface $deadline_finish = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(name:'create_at', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_project_name(): string
    {
        return $this->project_name;
    }

    public function set_project_name(string $in_project_name): void
    {
        $this->project_name = $in_project_name;
    }

    public function get_materials(): string
    {
        return $this->materials;
    }

    public function set_materials(string $in_materials): void
    {
        $this->materials = $in_materials;
    }

    public function get_workers_count(): int
    {
        return $this->workers_count;
    }

    public function set_workers_count(int $in_workers): void
    {
        $this->workers_count = $in_workers;
    }

    public function get_budget(): string
    {
        return $this->budget;
    }

    public function set_budget(string $in_budget): void
    {
        $this->budget = $in_budget;
    }

    public function get_deadline_start(): ?\DateTimeInterface
    {
        return $this->deadline_start;
    }

    public function set_deadline_start(?\DateTimeInterface $in_start): void
    {
        $this->deadline_start = $in_start;
    }

    public function get_deadline_finish(): ?\DateTimeInterface
    {
        return $this->deadline_finish;
    }

    public function set_deadline_finish(?\DateTimeInterface $in_finish): void
    {
        $this->deadline_finish = $in_finish;
    }

    public function get_place() : ?string
    {
        return $this->place;
    }

    public function set_place(?string $in_place) : void
    {
        $this->place = $in_place;
    }

    public function get_created_at() : DateTimeImmutable
    {
        return $this->created_at;
    }
}

?>
