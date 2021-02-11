<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Region;
use App\Entity\Specialite;
use App\Entity\Tag;

class ImportCsvCommand extends Command
{
    protected static $defaultName = 'app:import:specialites';
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Importe des spécialités depuis un fichier CSV.')
            ->addArgument('importPath', InputArgument::REQUIRED, 'Le path absolu du fichier d\'import.')
            ->addArgument('picturePath', InputArgument::REQUIRED, 'Le path absolu du dossier des images.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importPath = $input->getArgument('importPath');
        $picturePath = $input->getArgument('picturePath');
        
        if(!file_exists($importPath) || !is_dir($picturePath)) {
            $output->writeln('L\'un des paramêtres renseignés n\'est pas correct !');
            return Command::FAILURE;
        }
        
        $em = $this->container->get('doctrine')->getManager();
        $tagRepository = $this->container->get('doctrine')->getRepository(Tag::class);

        $output->writeln([
            '======================',
            'Import des specialites',
            '======================',
            '',
        ]);

        // supprime les entrées existantes dans la bdd
        $em->createQuery('DELETE App:Specialite p')->execute();
        $em->createQuery('DELETE App:Tag p')->execute();

        // $em
        //     ->createNativeQuery("ALTER TABLE specialite AUTO_INCREMENT = 1", new ResultSetMapping())
        //     ->execute();

        // $em
        //     ->createNativeQuery("ALTER TABLE tag AUTO_INCREMENT = 1", new ResultSetMapping())
        //     ->execute();

        $handle = fopen($importPath, "r");
        if ($handle) {
            fgets($handle); // retire la 1ère ligne contenant les noms de colonnes
            while (($line = fgets($handle)) !== false) {
                $row = str_replace('"','',$line);
                $array = explode(';',$row);
                $label = array_shift($array);
                $picture = array_pop($array);
                $tags = $array;

                $specialite = new Specialite();
                $specialite->setLibelle($label);
                $specialite->setImage($picture);
                
                $em->persist($specialite);

                foreach ($tags as $tagRow) {
                    $tagArray = explode(',', $tagRow);
                    foreach ($tagArray as $tag) {
                        $tag = trim($tag);
                        if(empty($tag)) continue;
                        $existTag = $tagRepository->findOneBy(['libelle' => $tag]);
                        if(is_null($existTag)) {
                            $newTag = new Tag();
                            $newTag->setLibelle($tag);
                            $newTag->addSpecialite($specialite);

                            $em->persist($newTag);
                        }
                        else {
                            $existTag->addSpecialite($specialite);
                        }
                    }
                }

                $em->flush();
            }

            fclose($handle);
        } else {
            $output->writeln('Le fichier renseigné n\'est pas lisible !');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}