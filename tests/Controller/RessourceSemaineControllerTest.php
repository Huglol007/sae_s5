<?php

namespace App\Tests\Controller;

use App\Entity\RessourceSemaine;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RessourceSemaineControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $ressourceSemaineRepository;
    private string $path = '/ressource/semaine/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->ressourceSemaineRepository = $this->manager->getRepository(RessourceSemaine::class);

        foreach ($this->ressourceSemaineRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RessourceSemaine index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'ressource_semaine[semaine]' => 'Testing',
            'ressource_semaine[cm]' => 'Testing',
            'ressource_semaine[td]' => 'Testing',
            'ressource_semaine[tp]' => 'Testing',
            'ressource_semaine[ds]' => 'Testing',
            'ressource_semaine[sae]' => 'Testing',
            'ressource_semaine[ressource]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->ressourceSemaineRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new RessourceSemaine();
        $fixture->setSemaine('My Title');
        $fixture->setCm('My Title');
        $fixture->setTd('My Title');
        $fixture->setTp('My Title');
        $fixture->setDs('My Title');
        $fixture->setSae('My Title');
        $fixture->setRessource('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RessourceSemaine');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new RessourceSemaine();
        $fixture->setSemaine('Value');
        $fixture->setCm('Value');
        $fixture->setTd('Value');
        $fixture->setTp('Value');
        $fixture->setDs('Value');
        $fixture->setSae('Value');
        $fixture->setRessource('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ressource_semaine[semaine]' => 'Something New',
            'ressource_semaine[cm]' => 'Something New',
            'ressource_semaine[td]' => 'Something New',
            'ressource_semaine[tp]' => 'Something New',
            'ressource_semaine[ds]' => 'Something New',
            'ressource_semaine[sae]' => 'Something New',
            'ressource_semaine[ressource]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ressource/semaine/');

        $fixture = $this->ressourceSemaineRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getSemaine());
        self::assertSame('Something New', $fixture[0]->getCm());
        self::assertSame('Something New', $fixture[0]->getTd());
        self::assertSame('Something New', $fixture[0]->getTp());
        self::assertSame('Something New', $fixture[0]->getDs());
        self::assertSame('Something New', $fixture[0]->getSae());
        self::assertSame('Something New', $fixture[0]->getRessource());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new RessourceSemaine();
        $fixture->setSemaine('Value');
        $fixture->setCm('Value');
        $fixture->setTd('Value');
        $fixture->setTp('Value');
        $fixture->setDs('Value');
        $fixture->setSae('Value');
        $fixture->setRessource('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/ressource/semaine/');
        self::assertSame(0, $this->ressourceSemaineRepository->count([]));
    }
}
