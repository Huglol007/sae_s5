<?php

namespace App\Tests\Controller;

use App\Entity\Creneau;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreneauControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/creneau/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Creneau::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Creneau index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'creneau[date]' => 'Testing',
            'creneau[duree]' => 'Testing',
            'creneau[matiere]' => 'Testing',
            'creneau[enseignant]' => 'Testing',
            'creneau[ressource]' => 'Testing',
            'creneau[promotion]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Creneau();
        $fixture->setDate('My Title');
        $fixture->setDuree('My Title');
        $fixture->setMatiere('My Title');
        $fixture->setEnseignant('My Title');
        $fixture->setRessource('My Title');
        $fixture->setPromotion('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Creneau');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Creneau();
        $fixture->setDate('Value');
        $fixture->setDuree('Value');
        $fixture->setMatiere('Value');
        $fixture->setEnseignant('Value');
        $fixture->setRessource('Value');
        $fixture->setPromotion('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'creneau[date]' => 'Something New',
            'creneau[duree]' => 'Something New',
            'creneau[matiere]' => 'Something New',
            'creneau[enseignant]' => 'Something New',
            'creneau[ressource]' => 'Something New',
            'creneau[promotion]' => 'Something New',
        ]);

        self::assertResponseRedirects('/creneau/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getDuree());
        self::assertSame('Something New', $fixture[0]->getMatiere());
        self::assertSame('Something New', $fixture[0]->getEnseignant());
        self::assertSame('Something New', $fixture[0]->getRessource());
        self::assertSame('Something New', $fixture[0]->getPromotion());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Creneau();
        $fixture->setDate('Value');
        $fixture->setDuree('Value');
        $fixture->setMatiere('Value');
        $fixture->setEnseignant('Value');
        $fixture->setRessource('Value');
        $fixture->setPromotion('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/creneau/');
        self::assertSame(0, $this->repository->count([]));
    }
}
