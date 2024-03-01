<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    private $cache;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, TagAwareCacheInterface $cache)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->cache = $cache;

    }

    public function getRandomValueInArray(array $array)
    {

        if (is_array($array) === true) {
            // Get random key from array

            $random_key = array_rand($array);

            $random_value = $array[$random_key];

            return $random_value;
        }

        return throw new Exception("This is not an array !");
        
    }

    public function load(ObjectManager $manager): void
    {
        $user = new Customer();
        $user->setEmail("user@test.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));

        $manager->persist($user);

        $userAdmin = new Customer();
        $userAdmin->setEmail("admin@test.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));

        $manager->persist($userAdmin);

        // Create 20 products
        for ($i = 0; $i < 20; $i++) {
            $product = new Product;

            // Model

            $model = [
                'Samswing Galactic 8',
                'BileMo Vxpire 56',
                'Vanilla Zora +',
                'Zokia lite 7',
            ];

            $product->setModel($this->getRandomValueInArray($model));

            // Color

            $color = [
                'Black',
                'Orange',
                'White',
                'Purple'
            ];

            $product->setColor($this->getRandomValueInArray($color));

            // Operating system

            $operation_system = [
                'Android 12',
                'Android 13',
                'IOS 12',
                'IOS 13'
            ];

            $product->setOperatingSystem($this->getRandomValueInArray($operation_system));

            // SIM type

            $sim_type = [
                'SIM 12',
                'SIM Vray',
                'SIM V35.2',
                'SIM 4-78-ProType'
            ];

            $product->setSimType($this->getRandomValueInArray($sim_type));

            // Number of SIMs

            $number_sims = [
                1,
                2,
                3,
                4
            ];

            $product->setNumberOfSims($this->getRandomValueInArray($number_sims));

            // Processor

            $processor = [
                'AI15 Chip',
                'ContiVchip 45',
                'Minizio 89v',
                'Vellv5'
            ];

            $product->setProcessor($this->getRandomValueInArray($processor));

            // Processor details

            $processor_detail = [
                'Bionic',
                'Metalic',
                'Plastic',
                'Vine'
            ];

            $product->setProcessorDetails($this->getRandomValueInArray($processor_detail));

            // Battery (mAh)

            $battery = [
                '2815 mAh',
                '3200 mAh',
                '4500 mAh',
                '7800 mAh'
            ];

            $product->setBattery($this->getRandomValueInArray($battery));

            // Quick charge

            $quick_charge = [
                false,
                true
            ];

            $product->setQuickCharge($this->getRandomValueInArray($quick_charge));

            // Screen size (cm)

            $screen_size = [
                "15.5",
                "17.2",
                "10.5",
                "14.3"
            ];

            $product->setScreenSize($this->getRandomValueInArray($screen_size));

            // Screen resolution

            $screen_resolution = [
                "1920x1080",
                "720x108",
                "1920x720",
                "1440x480"
            ];

            $product->setScreenResolution($this->getRandomValueInArray($screen_resolution));

            // Network

            $network = [
                "5G",
                "4G",
                "5G+",
                "Edge"
            ];

            $product->setNetwork($this->getRandomValueInArray($network));

            // Bluetooth

            $bluetooth = [
                5.2,
                7.3,
                8.0,
                4.4
            ];

            $product->setBluetooth($this->getRandomValueInArray($bluetooth));

            // Wifi standard

            $wifi_standard = [
                "Wifi 5Ve",
                "Wifi 6.0 + V15",
                "Wifi 7.0 -v",
                "Wifi 6.2"
            ];

            $product->setWifiStandard($this->getRandomValueInArray($wifi_standard));

            // Internal memory (GB)

            $internal_memory = [
                500,
                400,
                200,
                100
            ];

            $product->setInternalMemory($this->getRandomValueInArray($internal_memory));

            // RAM memory

            $ram_memory = [
                128,
                64,
                32,
                16
            ];

            $product->setRamMemory($this->getRandomValueInArray($ram_memory));

            // Camera resolution (MegaPix)

            $camera_resolution = [
                128,
                64,
                32,
                16
            ];

            $product->setCameraResolution($this->getRandomValueInArray($camera_resolution));

            // Water resistant

            $water_resistant = [
                "IP6",
                "IP7",
                "IPV7",
                "IPV8"
            ];

            $product->setWaterResistant($this->getRandomValueInArray($water_resistant));

            // Dust resistant

            $dust_resistant = [
                true,
                false
            ];

            $product->setDustResistant($this->getRandomValueInArray($dust_resistant));

            // Shock resistance

            $shock_resistant = [
                true,
                false
            ];

            $product->setShockResistance($this->getRandomValueInArray($shock_resistant));

            // Repairability index / 10

            $repairability_index = [
                4.5,
                7.5,
                8.2,
                10
            ];

            $product->setRepairabilityIndex($this->getRandomValueInArray($repairability_index));

            // Made in

            $made_in = [
                "China",
                "France",
                "Italie",
                "Canada"
            ];

            $product->setMadeIn($this->getRandomValueInArray($made_in));

            // Brand

            $brand = [
                "BileMo",
                "Pear",
                "Mitosoft",
                "Wunder"
            ];

            $product->setBrand($this->getRandomValueInArray($brand));

            // Product height (mm)

            $height = [
                250.2,
                245.0,
                125.25,
                130.0
            ];

            $product->setProductHeight($this->getRandomValueInArray($height));

            // Product width (mm)

            $width = [
                250.2,
                245.0,
                125.25,
                130.0
            ];

            $product->setProductWidth($this->getRandomValueInArray($width));

            // Product thickness (mm)

            $thick = [
                2.2,
                2.5,
                3.0,
                2.1
            ];

            $product->setProductThickness($this->getRandomValueInArray($thick));

            // Net weight (g)

            $weight = [
                500.0,
                575.0,
                577.2,
                245.2
            ];

            $product->setNetWeight($this->getRandomValueInArray($weight));

            $manager->persist($product);

            $this->cache->invalidateTags(['ProductsCache']);
        }

        $manager->flush();
        
    }
}
