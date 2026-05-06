<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booking = new Booking();
        $bookingForm = $this->createForm(BookingType::class, $booking)
            ->add('submit', SubmitType::class, ['label' => 'Zarezerwuj']);

        $contactForm = $this->createForm(ContactType::class)
            ->add('submit', SubmitType::class, ['label' => 'Wyslij']);

        $bookingForm->handleRequest($request);
        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash(
                'success',
                sprintf(
                    'Rezerwacja przyjeta dla %s na %s.',
                    (string) $booking->getName(),
                    $booking->getDate()?->format('Y-m-d')
                )
            );

            return $this->redirectToRoute('app_home');
        }

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactData = $contactForm->getData();

            $this->addFlash(
                'success',
                sprintf('Dziekujemy %s, wiadomosc zostala wyslana.', $contactData['name'])
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'booking_form' => $bookingForm->createView(),
            'contact_form' => $contactForm->createView(),
        ]);
    }
}
