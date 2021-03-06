<?php
namespace App\Form;

use App\Entity\ProduitSpecialite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Specialite;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\SpecialiteRepository;

class ProduitSpecialiteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $opt_spe = array(
            'label' => 'Spécialité',
            'class' => Specialite::class,
            'choice_label' => 'service',
            'disabled' => $options['disabled_specialite'],
            'query_builder' => function (SpecialiteRepository $sr) {
                return $sr->createQueryBuilder('s')
                    ->innerJoin('App:Etablissement', 'e', 'WITH', 's.etablissement = e.id')
                    ->andWhere('s.disabled = 0')
                    ->orderBy('e.nom, s.service', 'ASC');
            },
            'group_by' => function (Specialite $specialite) {
                return $specialite->getEtablissement()->getNom();
            }
        );

        if ($options['avec_specialite']) {
            $builder->add('specialite', EntityType::class, $opt_spe);
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
            'data_class' => ProduitSpecialite::class,
            'label_submit' => 'Valider',
            'avec_bouton' => true,
            'avec_specialite' => true,
            'avec_produit' => true,
            'disabled_specialite' => false,
            'disabled_produit' => false
        ]);
    }
}
