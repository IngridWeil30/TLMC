<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadGroupeData extends Fixture
{

    /**
     *
     * @var ContainerInterface
     */
    private $container;

    private $tabGroupes = [
        'Groupe' => [
            'Groupe-1' => [
                'setNom' => 'Nouvelles',
                'setDate' => '2018-02-23 17:53:00',
                'setDisabled' => '1'
            ],
            'Groupe-2' => [
                'setNom' => 'Inscription',
                'setDate' => '2018-03-05 12:12:00',
                'setDisabled' => '0'
            ]
        ]
    ];

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

		//$groupesArray = $this->container->getParameter('Groupe');
		$groupesArray = $this->tabGroupes['Groupe'];
		
		foreach ($groupesArray as $name => $object) {
            $groupe = new Groupe();

            foreach ($object as $key => $val) {
                
                if($key == 'setDate')
                {
                    $val = new \DateTime($val);
                }
                
                $groupe->{$key}($val);
            }

            $manager->persist($groupe);
            $this->addReference($name, $groupe);
        }
        $manager->flush();
    }
}