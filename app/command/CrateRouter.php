<?php

namespace app\command;

use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CrateRouter extends Command
{
    protected static $defaultName        = 'create:router';
    protected static $defaultDescription = 'CrateRouter';
    
    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }
    
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $routeFile = config_path('route.php');
        if (!file_exists($routeFile)) {
            die("Route file not found");
        }
        
        $routeContent = file_get_contents($routeFile);
        $startMarker  = '//ADMIN_START';
        $endMarker    = '//ADMIN_END';
        
        $startPos = strpos($routeContent, $startMarker);
        $endPos   = strpos($routeContent, $endMarker);
        
        if ($startPos === FALSE || $endPos === FALSE) {
            die("Start or end marker not found");
        }
        
        $controllersPath = app_path('controller/admin');
        $controllerFiles = scandir($controllersPath);
        
        $routes = [];
        
        foreach ($controllerFiles as $controllerFile) {
            if (strpos($controllerFile, 'Controller.php') === FALSE) {
                continue;
            }
            $controllerName  = str_replace('Controller.php', '', $controllerFile);
            $controllerClass = "\\app\\controller\\admin\\{$controllerName}Controller";
            
            
            $routeName = Str::kebab((str_replace('Controller', '', $controllerName)));
            
            $routes[] = "Route::group('/{$routeName}', function () {\n";
            $routes[] = "    Route::any('/{action}', [{$controllerClass}::class, 'action']);\n";
            $routes[] = "});\n";
        }
        
        
        $backupPath = storagePath('backup/' . 'route_' . date("YmdHis") . '.php');
        if (!file_exists(dirname($backupPath))) {
            checkAndCreateDirectory(dirname($backupPath));
        }

        copy($routeFile, $backupPath);
//
        $newRouteContent = substr($routeContent, 0, $startPos + strlen($startMarker)) . "\n" . implode("\n", $routes) . "\n" . substr($routeContent, $endPos);
    
        file_put_contents($routeFile, $newRouteContent);
        
        return self::SUCCESS;
    }
    
}
