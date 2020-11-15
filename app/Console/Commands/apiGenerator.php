<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class apiGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Api CRUD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $import = Str::of('use App.Http.Controllers.'.$name.'Controller;')->replace('.', chr(92));
        $this->controller($name);
        $this->model($name); 
        $this->service($name); 
        $fp = fopen(base_path('routes/api.php'), 'a');
        fwrite($fp,
            "\n
    $import \n
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('" . Str::plural(strtolower($name)) . "', [{$name}Controller::class, 'index']);
        Route::post('" . Str::plural(strtolower($name)) . "', [{$name}Controller::class, 'store']);
        Route::get('" . Str::plural(strtolower($name)) . "/{id}', [{$name}Controller::class, 'show']);
        Route::patch('" . Str::plural(strtolower($name)) . "/{id}', [{$name}Controller::class, 'update']);
        Route::delete('" . Str::plural(strtolower($name)) . "/{id}', [{$name}Controller::class, 'delete']);
        Route::delete('" . Str::plural(strtolower($name)) . "/{id}/permanent', [{$name}Controller::class, 'deletePermanent']);
        Route::patch('" . Str::plural(strtolower($name)) . "/{id}/restore', [{$name}Controller::class, 'restore']);
        Route::get('" . Str::plural(strtolower($name)) . "-withtrashed', [{$name}Controller::class, 'withtrashed']);
        Route::get('" . Str::plural(strtolower($name)) . "-onlytrashed', [{$name}Controller::class, 'onlytrashed']);
    });");
            fclose($fp);  
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function controller($name)
    {
        $controllerTemplate = str_replace([
            '{{modelName}}',
            '{{modelNamePlural}}',
            '{{modelNameSingular}}'
        ],
        [
            $name,
            strtolower(Str::plural($name)),
            strtolower($name)
        ],
        $this->getStub('Controller'));     
        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function model($name)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}', '{{modelNamePlural}}'],
            [$name, strtolower(Str::plural($name))],
            $this->getStub('Model')
        );
        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
    }

    protected function service($name)
    {
        $serviceTemplate = str_replace([
            '{{modelName}}',
            '{{modelNamePlural}}',
            '{{modelNameSingular}}'
        ],
        [
            $name,
            strtolower(Str::plural($name)),
            strtolower($name)
        ],
        $this->getStub('Service'));     
        file_put_contents(app_path("/Services/{$name}Service.php"), $serviceTemplate);
    }
}
