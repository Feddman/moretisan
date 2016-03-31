<?php

namespace Sven\Moretisan\Commands;

use Illuminate\Console\Command;
use Sven\Moretisan\MakeView\ViewCreator;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view
                           {name : The name of the view to create. Use resource for resourceful views}
                           {--extends= : What \'master\' view should be extended?}
                           {--sections= : A comma-separated list of sections to create.}
                           {--directory=resources/views/ : The directory where your views are stored.}
                           {--extension=blade.php : What file extension should the view have?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $view = new ViewCreator(
            base_path($this->option('directory'))
        );


        // check if view is made with resource keyword
        // example: make:view products/resource or make:view products.resource
        if ( strpos( $this->argument('name'), 'resource' ) ) {
            // resource items to create
            $types = ['edit', 'index', 'create', 'show'];
            foreach($types as $type) {
                // change resource into type.
                $filename = str_replace('resource', $type, $this->argument('name'));
                $view = $view->create(
                    $filename,
                    $this->option('extension')
                );
            }

        } else {
            $view = $view->create(
                $this->argument('name'),
                $this->option('extension')
            );
        }



        if ( ! is_null( $this->option('extends') )) {
            $view = $view->extend($this->option('extends'));
        }

        if ( ! is_null( $this->option('sections') )) {
            $view->sections(
                explode(',', $this->option('sections'))
            );
        }

        return $this->info(printf(
            "Successfully created the view '%s'!",
            $this->argument('name')
        ));
    }
}
