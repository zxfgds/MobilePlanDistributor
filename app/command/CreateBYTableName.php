<?php

namespace app\command;

use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CreateBYTableName 类
 * 基于指定表名创建模型、控制器和逻辑类
 */
class CreateBYTableName extends Command
{
    // 设置默认命令名称和描述
    protected static $defaultName = 'generate:resources';
    protected static $defaultDescription = 'CreateBYTableName';
    
    /**
     * 配置命令行参数
     *
     * @return void
     */
    protected function configure(): void
    {
        // 添加名为 'table' 的可选参数，用于指定表名
        $this->addArgument('table', InputArgument::OPTIONAL, '指定表名');
    }
    
    /**
     * 执行命令的主要逻辑
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 获取输入参数中的表名，并将其转换为单数形式和 StudlyCase 格式
        $name       = $input->getArgument('table');
        $name       = Str::singular($name);
        $nameStudly = Str::studly($name);
        
        // 为指定的表名创建一个新的模型类
        shell_exec('php webman make:model ' . $nameStudly);
        
        // 读取并替换模板文件中的占位符
        $logicStub          = $this->replaceStubContent('Logic.stub', $nameStudly);
        $controllerStub     = $this->replaceStubContent('Controller.stub', $nameStudly);
        $httpControllerStub = $this->replaceStubContent('HttpController.stub', $nameStudly);
        
        // 将替换后的模板内容写入到对应的类文件中
        file_put_contents(app_path('controller/admin/' . $nameStudly . 'Controller.php'), $controllerStub);
        file_put_contents(app_path('controller/http/' . $nameStudly . 'Controller.php'), $httpControllerStub);
        file_put_contents(app_path('logic/' . $nameStudly . 'Logic.php'), $logicStub);
        
        // 输出完成消息
        $output->writeln('CreateBYTableName 命令执行完成');
        return self::SUCCESS;
    }
    
    /**
     * 从存储路径中读取模板文件，并替换占位符
     *
     * @param string $stubFileName
     * @param string $nameStudly
     *
     * @return string
     */
    protected function replaceStubContent(string $stubFileName, string $nameStudly): string
    {
        $stubContent = file_get_contents(base_path('storage/development/stubs/' . $stubFileName));
        return str_replace('DUMMY_NAME', $nameStudly, $stubContent);
    }
}
