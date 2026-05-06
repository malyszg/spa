<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Imie i nazwisko',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: 120),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Data rezerwacji',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual('today'),
                ],
            ])
            ->add('guests', IntegerType::class, [
                'label' => 'Liczba osob',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(min: 1, max: 12),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
