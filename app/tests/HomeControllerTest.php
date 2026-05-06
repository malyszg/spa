<?php

namespace App\Tests;

use App\Entity\Booking;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsAvailable(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSame(1, $crawler->filter('h1:contains("Apartament i pobyt")')->count());
    }

    public function testBookingIsStoredInDatabase(): void
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->resetDatabase($entityManager);

        $client->request('GET', '/');

        $client->submitForm('Zarezerwuj', [
            'booking[name]' => 'Jan Kowalski',
            'booking[email]' => 'jan@example.com',
            'booking[date]' => (new \DateTimeImmutable('+1 day'))->format('Y-m-d'),
            'booking[guests]' => 2,
        ]);

        self::assertResponseRedirects('/');
        $client->followRedirect();

        $savedBooking = $entityManager->getRepository(Booking::class)->findOneBy([
            'email' => 'jan@example.com',
        ]);

        self::assertNotNull($savedBooking);
        self::assertSame('Jan Kowalski', $savedBooking->getName());
    }

    private function resetDatabase(EntityManagerInterface $entityManager): void
    {
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}
