<?php

namespace Lazzier\Services;

use Lazzier\Contracts\TaskContract;
use Lazzier\Contracts\TaskExecutorContract;
use Lazzier\Contracts\TaskSchedulerContract;
use Lazzier\Exceptions\IncompleteTaskException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskExecutor
 * @package Lazzier\Services
 */
class TaskExecutor implements TaskExecutorContract
{
    /**
     * @var TaskSchedulerContract
     */
    protected $scheduler;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var
     */
    protected $stats;

    public function __construct(TaskSchedulerContract $scheduler, OutputInterface $output)
    {
        $this->scheduler = $scheduler;
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): void
    {
        $this->handleTasks(
            'before release install',
            $this->scheduler->tasksToRunBefore()
        );

        $this->handleTasks(
            'release install',
            $this->scheduler->tasksToRun()
        );

        $this->handleTasks(
            'after release install',
            $this->scheduler->tasksToRunAfter()
        );
    }

    /**
     * @param string $section
     * @param array $tasks
     */
    protected function handleTasks(string $section, array $tasks)
    {
        $this->output->writeln('<headline>' . $section . '</headline>');

        foreach ($tasks as $task) {
            try {
                $status = $task->invoke();
                $this->printStatus($task, $status);
            } catch (IncompleteTaskException $e) {
                $this->printStatus($task, $status);
                $this->printFailureAnalysis($task, $e->getMessage());
                die('Exiting routine!');
            }
        }

        $this->output->writeln('');
    }

    /**
     * @param TaskContract $task
     * @param bool $status
     */
    protected function printStatus(TaskContract $task, bool $status)
    {
        $type = $status ? 'info' : 'error';
        $symbol = $status ? '✔' : '!';
        $this->output->writeln(
            sprintf('    <%1$s>[%2$s]: %3$s</%1$s>', $type, $symbol, $task->name())
        );
    }

    /**
     * @param TaskContract $task
     * @param IncompleteTaskException $e
     */
    protected function printFailureAnalysis(TaskContract $task, string $errorMsg)
    {
        $this->output->writeln('<error>' . str_repeat('*', strlen($errorMsg) + 12) . '</error>');
        $this->output->writeln('<error>' . '*     ' . $errorMsg . '     *' . '</error>');
        $this->output->writeln('<error>' . str_repeat('*', strlen($errorMsg) + 12) . '</error>');
        $this->output->writeln('');
        $this->output->writeln('<comment>' . $task->name() . ' - ' . $task->desc() .  '</comment>');

        $params = $task->params();
        foreach ($params as $key => $value) {
            $this->output->writeln('    <comment>- ' . $key . ' => ' . $value .  '</comment>');
        }
    }
}
