<?php
namespace App\Form;

use App\Entity\ProduitEtablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Etablissement;
use App\Entity\Produit;
use App\Repository\EtablissementRepository;

class ProduitEtablissementType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['avec_etablissement']) {
            $builder->add('etablissement', EntityType::class, array(
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'query_builder' => function (EtablissementRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('App:Specialite', 's', 'WITH', 's.etablissement = e.id')
                        ->andWhere('s.id IS NULL')
                        ->andWhere('e.disabled = 0')
                        ->orderBy('e.nom', 'ASC');
                },
                'disabled' => $options['disabled_etablissement']
            ));
        }

        if ($options['avec_produit']) {
            $builder->add('produit', EntityType::class, array(
                'class' => Produit::class,
                'choice_label' => 'titre',
                'disabled' => $options['disabled_produit']
            ));
        }
        $builder->add('quantite', IntegerType::class, array(
            'label' => 'Quantité'
        ));

        if ($options['avec_bouton']) {
            $builder->add('save', SubmitType::class, array(
                'label' => $options['label_submit'],
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProduitEtablissement::class,
            'label_submit' => 'Valider',
            'avec_bouton' => true,
            'avec_etablissement' => true,
            'avec_produit' => true,
            'disabled_etablissement' => false,
            'disabled_produit' => false,
            'query_specialite' => null
        ]);
    }
}
