<?php

namespace App\Controller;

use App\Application\UseCase\CreateBooking\CreateBookingCommand;
use App\Application\UseCase\CreateBooking\CreateBookingUseCase;
use App\Form\BookingType;
use App\Form\ContactType;
use App\Form\Model\BookingFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request, CreateBookingUseCase $createBookingUseCase): Response
    {
        $bookingFormData = new BookingFormData();
        $bookingForm = $this->createForm(BookingType::class, $bookingFormData)
            ->add('submit', SubmitType::class, ['label' => 'Zarezerwuj']);

        $contactForm = $this->createForm(ContactType::class)
            ->add('submit', SubmitType::class, ['label' => 'Wyslij']);

        $bookingForm->handleRequest($request);
        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {
            $command = new CreateBookingCommand(
                (string) $bookingFormData->name,
                (string) $bookingFormData->email,
                \DateTimeImmutable::createFromInterface($bookingFormData->date),
                (int) $bookingFormData->guests
            );
            $booking = $createBookingUseCase->execute($command);

            $this->addFlash(
                'success',
                sprintf(
                    'Rezerwacja przyjeta dla %s na %s.',
                    $booking->name(),
                    $booking->date()->format('Y-m-d')
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
