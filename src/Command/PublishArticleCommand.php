<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishArticleCommand extends Command
{
    protected static $defaultName = 'app:publish-article';
    protected static $defaultDescription = 'publie les articles "A publier"';

    private $articleRepository;
    private $manager;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $manager, string $name = null)
    {
        $this->articleRepository = $articleRepository;
        $this->manager = $manager;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription($this->getDescription())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $articles = $this->articleRepository->findBy([
            'state' => 'a publier'
        ]);

        foreach ($articles as $article){
            $article->setState('publie');
        }

        $this->manager->flush();

        $io->success(count($articles) . ' Articles publiés');

        return Command::SUCCESS;
    }
}
