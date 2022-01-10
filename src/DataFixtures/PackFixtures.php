<?php

namespace App\DataFixtures;

use App\Entity\DetailsPack;
use App\Entity\FilePicture;
use App\Entity\Pack;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class PackFixtures extends Fixture implements FixtureGroupInterface
{
    private string $packTargetDirectory;

    public function __construct(string $packDirectory)
    {
        $this->packTargetDirectory = $packDirectory;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $packs = $this->getPack();
        $details = $this->getDetails();
        foreach ($packs as $value) {
            $file = new File($this->packTargetDirectory.DIRECTORY_SEPARATOR.$value['image']);
            $filePicture = $this->getInstanceFilePicture($file);
            $manager->persist($filePicture);

            $pack = $this->getPackInstance($value, $filePicture);

            $manager->persist($pack);

            $n = 0;
            while (true) {
                $detailsPack = $this->getDetailPack($details[$n], $pack);
                $manager->persist($detailsPack);
                $n++;
                if ($n === 3) {
                    break;
                }
            }
        }

        $manager->flush();
    }

    /**
     * @param array       $packs
     * @param FilePicture $filePicture
     *
     * @return Pack
     */
    private function getPackInstance(array $packs, FilePicture $filePicture): Pack
    {
        $pack = new Pack();
        $pack
            ->setTitle($packs['title'])
            ->setDescription($packs['description'])
            ->setPrice($packs['price'])
            ->setDays($packs['days'])
            ->setPriceByDay($packs['priceByDay'])
            ->setImage($filePicture)
        ;

        return $pack;
    }

    /**
     * @param File $file
     *
     * @return FilePicture
     */
    private function getInstanceFilePicture(File $file): FilePicture
    {
        return (new FilePicture())
            ->setFile($file)
            ->setFileName($file->getFilename())
       ;
    }

    /**
     * @param string $detail
     * @param Pack   $pack
     *
     * @return DetailsPack
     */
    private function getDetailPack(string $detail, Pack $pack): DetailsPack
    {
        return (new DetailsPack())
            ->setDescription($detail)
            ->setPack($pack)
            ;
    }

    /**
     * @return array[]
     */
    private function getPack(): array
    {
        return [
            [
                'title' => 'Pack argent',
                'description' => 'Vos annonces seront placées en tête de liste automatiquement tous les jours (pendant 7 jours) pour une meilleure visibilité.',
                'price' => '1000',
                'days' => 7,
                'priceByDay' => '50 F cfa/Jour',
                'image' => 'argent.png'
            ],
            [
                'title' => 'Pack Or',
                'description' => 'Vos annonces seront placées en tête de liste automatiquement tous les jours (pendant 14  jours) tout étant en surbrillance avec un arrière-plan de couleur orange, pour une plus grande  visibilité.',
                'price' => '5000',
                'days' => 14,
                'priceByDay' => '100 F cfa/Jour',
                'image' => 'or.png'
            ],
            [
                'title' => 'Pack VIP',
                'description' => 'Mettez en avant vos annonces dans la section VIP, un emplacement exclusif. Vos annonces seront placées en tête de liste automatiquement tous les jours (pendant 14 jours) pour une  plus grande visibilité.',
                'price' => '10 000',
                'days' => 14,
                'priceByDay' => '100 F cfa/Jour',
                'image' => 'vip.png'
            ]
        ];
    }

    /**
     * @return array
     */
    private function getDetails(): array
    {
        return [
            'Phasellus sit amet nisl pulvinar, feugiat tortor non, porttitor tortor. Vestibulum ante ipsum primis in faucibus orci luctus.',
            'Duis at tortor vitae est lobortis feugiat. Quisque sed maximus mi. Ut dictum sapien turpis, sed finibus nisi facilisis sit amet.',
            'Maecenas volutpat elit odio. Aliquam et nibh porttitor, placerat metus quis, rutrum mauris. Nunc ut imperdiet augue. Sed in fermentum lorem.'
        ];
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['packs'];
    }


}
