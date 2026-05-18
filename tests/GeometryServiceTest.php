<?php

namespace App\Tests;

use App\Services\GeometryService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Activité 1 : Testez la classe GeometryService
 * Cette exercice est simple et a pour but de vous familiariser avec l'écriture de tests unitaires pour des méthodes de calcul.
 * La classe GeometryService contient plusieurs méthodes qui calculent des aires et des volumes pour différentes formes géométriques.
 * Votre tâche est d'écrire des tests unitaires pour chacune de ces méthodes
 */
class GeometryServiceTest extends KernelTestCase
{
    private GeometryService $geoService;
    
    public function testCalculateSquareArea() : void
    {
        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $squareArea = $this->geoService->calculateSquareArea(5);
        $this->assertEquals(25,$squareArea,"La surface d'un carré de coté 5 doit être égal à 25");
    }

    // Remplissez les test restants :)

    public function testCalculateCircleArea() : void{

        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $circlearea = $this->geoService->calculateCircleArea(5);
        $result = pi() * pow(5, 2);

        $this->assertEquals($result, $circlearea, "l'aire d'un cercle de 5 de rayon est de 78.5");
    }


    public function testCalculateRectangleArea() : void{
        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $rectanglearea = $this->geoService->calculateRectangleArea(5, 2);
        $this->assertEquals(10, $rectanglearea, "l'aire d'un rectangle de 5 de large et 2 de haut est de 10");
    }


    public function testCalculateTriangleArea() : void{
        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $trianglearea = $this->geoService->calculateTriangleArea(5, 2);
        $this->assertEquals(5, $trianglearea, "l'aire d'un triangle de 5 de large et 2 de haut est de 10");
    }
    public function testCalculateCubeVolume() : void{
        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $cubevolume = $this->geoService->calculateCubeVolume(10);
        $this->assertEquals(1000, $cubevolume, "le volume d'un cube de 10 est de 1000");
    }
    public function testCalculateCylinderVolume() : void{
        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $cylindervolume = $this->geoService->calculateCylinderVolume(5, 10);
        $result = pi() * pow(5, 2) * 10;

        $this->assertEquals($result, $cylindervolume, "le volume d'un cylindre de 5 de rayon et 10 de hauteur est de 785");
    }
    public function testCalculateConeVolume() : void{
        self::bootKernel();
        $this->geoService = static::getContainer()->get(GeometryService::class);

        $conevolume = $this->geoService->calculateConeVolume(5, 10);
        $result = (1/3) * pi() * pow(5, 2) * 10;

        $this->assertEquals($result, $conevolume, "le volume d'un cone de 5 de rayon et 10 de hauteur est de 1000");
    }
}
