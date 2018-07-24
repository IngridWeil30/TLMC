<?php
namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\SpecialiteEvenement;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Specialite;
use App\Entity\Evenement;

// use Symfony\Component\DependencyInjection\ContainerInterface;
class LoadSpecialiteEvenementData extends Fixture implements DependentFixtureInterface
{

    private $tabSpecialiteEvenement = [
        'SpecialiteEvenement' => [
            'SpecialiteEvenement-1' => [
                'setEvenement' => 'Evenement-1',
                'setSpecialite' => 'Specialite-1',
                'setStatut' => '1',
                'setDate' => '1991-09-30'
            ],
            'SpecialiteEvenement-2' => [
                'setEvenement' => 'Evenement-2',
                'setSpecialite' => 'Specialite-2',
                'setStatut' => '1',
                'setDate' => '1991-09-30'
            ]
        ]
    ];

    /*
     * public function __construct(ContainerInterface $container = null)
     * {
     * $this->container = $container;
     * }
     */
    public function load(ObjectManager $manager)
    {
        $specialiteEvenementArray = $this->tabSpecialiteEvenement['SpecialiteEvenement'];
        foreach ($specialiteEvenementArray as $name => $value) {
            $specialiteEvenement = new SpecialiteEvenement();
            $specialite = new Specialite();
            $evenement = new Evenement();

            foreach ($value as $key => $val) {
                if ($key == 'setDate') {
                    $val = new \DateTime($val);
                }

                if ($key == 'setEvenement') {
                    $evenement = $this->getReference($val);
                    $specialiteEvenement->{$key}($evenement);
                } elseif ($key == 'setSpecialite') {
                    $specialite = $this->getReference($val);
                    $specialiteEvenement->{$key}($specialite);
                } else {
                    $specialiteEvenement->{$key}($val);
                }
            }
            $manager->persist($specialiteEvenement);
            $this->addReference($name, $specialiteEvenement);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadSpecialiteData::class,
            LoadEvenementData::class
        );
    }
}