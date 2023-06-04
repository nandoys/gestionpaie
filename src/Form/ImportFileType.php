<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', DropzoneType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '2m',
                        'maxSizeMessage' => 'Le fichier est trop lourd ({{ size }} {{ suffix }}). La taille maximale est {{ limit }} {{ suffix }}',
                        'extensions' => [
                            'xlsx',
                            'xls'
                        ],
                        'notFoundMessage' => "Le fichier n'a pas pu être trouvé",
                        'notReadableMessage' => "Le fichier n'est pas lisible",
                        'extensionsMessage' => 'Fichier au format {{ extension }} est invalide. Veuillez uploader un fichier au format {{ extensions }}',
                        'uploadErrorMessage' => "Le fichier n'a pas pu être uploadé."
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'csrf_protection' => false,
        ]);
    }
}
