<?php

declare(strict_types=1);

namespace ApiSpec\Commands;

use ApiSpec\Builders\BuilderInterface;
use Illuminate\Console\Command;

class AggregateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apispec:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate all generated specs';

    /**
     * The apispec builder instance.
     *
     * @var \ApiSpec\Builders\BuilderInterface|mixed
     */
    private BuilderInterface $builder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->builder = app()->make(BuilderInterface::class);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->builder->setApp(app())->aggregate();
    }
}
