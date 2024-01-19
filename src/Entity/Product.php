<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The model is required and cannot be null")]
    private ?string $model = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "The color is required and cannot be null")]
    private ?string $color = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The operating system is required and cannot be null")]
    private ?string $operating_system = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The sim type is required and cannot be null")]
    private ?string $sim_type = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The number of sims cards is required and cannot be null")]
    private ?int $number_of_sims = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The processsor name is required and cannot be null")]
    private ?string $processor = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The processor details is required and cannot be null")]
    private ?string $processor_details = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "The battery capacity is required and cannot be null")]
    private ?string $battery = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The quick charge is required and cannot be null")]
    private ?bool $quick_charge = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The screen size is required and cannot be null")]
    private ?float $screen_size = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "The screen resolution is required and cannot be null")]
    private ?string $screen_resolution = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "The network is required and cannot be null")]
    private ?string $network = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The bluetooth is required and cannot be null")]
    private ?float $bluetooth = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The wifi standard is required and cannot be null")]
    private ?string $wifi_standard = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The internal memory is required and cannot be null")]
    private ?int $internal_memory = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The ram memory is required and cannot be null")]
    private ?int $ram_memory = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The camera resolution is required and cannot be null")]
    private ?int $camera_resolution = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "The water resistant is required and cannot be null")]
    private ?string $water_resistant = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The dust resistant is required and cannot be null")]
    private ?bool $dust_resistant = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The shock resistance is required and cannot be null")]
    private ?bool $shock_resistance = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The brand is required and cannot be null")]
    private ?string $brand = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "The made in label is required and cannot be null")]
    private ?string $made_in = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The repairability index is required and cannot be null")]
    private ?float $repairability_index = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The product height is required and cannot be null")]
    private ?float $product_height = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The width is required and cannot be null")]
    private ?float $product_width = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The thickness is required and cannot be null")]
    private ?float $product_thickness = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The net weight is required and cannot be null")]
    private ?float $net_weight = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operating_system;
    }

    public function setOperatingSystem(string $operating_system): static
    {
        $this->operating_system = $operating_system;

        return $this;
    }

    public function getSimType(): ?string
    {
        return $this->sim_type;
    }

    public function setSimType(string $sim_type): static
    {
        $this->sim_type = $sim_type;

        return $this;
    }

    public function getNumberOfSims(): ?int
    {
        return $this->number_of_sims;
    }

    public function setNumberOfSims(int $number_of_sims): static
    {
        $this->number_of_sims = $number_of_sims;

        return $this;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    public function setProcessor(string $processor): static
    {
        $this->processor = $processor;

        return $this;
    }

    public function getProcessorDetails(): ?string
    {
        return $this->processor_details;
    }

    public function setProcessorDetails(string $processor_details): static
    {
        $this->processor_details = $processor_details;

        return $this;
    }

    public function getBattery(): ?string
    {
        return $this->battery;
    }

    public function setBattery(string $battery): static
    {
        $this->battery = $battery;

        return $this;
    }

    public function isQuickCharge(): ?bool
    {
        return $this->quick_charge;
    }

    public function setQuickCharge(bool $quick_charge): static
    {
        $this->quick_charge = $quick_charge;

        return $this;
    }

    public function getScreenSize(): ?float
    {
        return $this->screen_size;
    }

    public function setScreenSize(float $screen_size): static
    {
        $this->screen_size = $screen_size;

        return $this;
    }

    public function getScreenResolution(): ?string
    {
        return $this->screen_resolution;
    }

    public function setScreenResolution(string $screen_resolution): static
    {
        $this->screen_resolution = $screen_resolution;

        return $this;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(string $network): static
    {
        $this->network = $network;

        return $this;
    }

    public function getBluetooth(): ?float
    {
        return $this->bluetooth;
    }

    public function setBluetooth(float $bluetooth): static
    {
        $this->bluetooth = $bluetooth;

        return $this;
    }

    public function getWifiStandard(): ?string
    {
        return $this->wifi_standard;
    }

    public function setWifiStandard(string $wifi_standard): static
    {
        $this->wifi_standard = $wifi_standard;

        return $this;
    }

    public function getInternalMemory(): ?int
    {
        return $this->internal_memory;
    }

    public function setInternalMemory(int $internal_memory): static
    {
        $this->internal_memory = $internal_memory;

        return $this;
    }

    public function getRamMemory(): ?int
    {
        return $this->ram_memory;
    }

    public function setRamMemory(int $ram_memory): static
    {
        $this->ram_memory = $ram_memory;

        return $this;
    }

    public function getCameraResolution(): ?int
    {
        return $this->camera_resolution;
    }

    public function setCameraResolution(int $camera_resolution): static
    {
        $this->camera_resolution = $camera_resolution;

        return $this;
    }

    public function getWaterResistant(): ?string
    {
        return $this->water_resistant;
    }

    public function setWaterResistant(string $water_resistant): static
    {
        $this->water_resistant = $water_resistant;

        return $this;
    }

    public function isDustResistant(): ?bool
    {
        return $this->dust_resistant;
    }

    public function setDustResistant(bool $dust_resistant): static
    {
        $this->dust_resistant = $dust_resistant;

        return $this;
    }

    public function isShockResistance(): ?bool
    {
        return $this->shock_resistance;
    }

    public function setShockResistance(bool $shock_resistance): static
    {
        $this->shock_resistance = $shock_resistance;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getMadeIn(): ?string
    {
        return $this->made_in;
    }

    public function setMadeIn(string $made_in): static
    {
        $this->made_in = $made_in;

        return $this;
    }

    public function getRepairabilityIndex(): ?float
    {
        return $this->repairability_index;
    }

    public function setRepairabilityIndex(float $repairability_index): static
    {
        $this->repairability_index = $repairability_index;

        return $this;
    }

    public function getProductHeight(): ?float
    {
        return $this->product_height;
    }

    public function setProductHeight(float $product_height): static
    {
        $this->product_height = $product_height;

        return $this;
    }

    public function getProductWidth(): ?float
    {
        return $this->product_width;
    }

    public function setProductWidth(float $product_width): static
    {
        $this->product_width = $product_width;

        return $this;
    }

    public function getProductThickness(): ?float
    {
        return $this->product_thickness;
    }

    public function setProductThickness(float $product_thickness): static
    {
        $this->product_thickness = $product_thickness;

        return $this;
    }

    public function getNetWeight(): ?float
    {
        return $this->net_weight;
    }

    public function setNetWeight(float $net_weight): static
    {
        $this->net_weight = $net_weight;

        return $this;
    }
}
