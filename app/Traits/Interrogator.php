<?php

namespace Lazzier\Traits;

/**
 * Trait Interrogator
 * @package Lazzier\Traits
 */
trait Interrogator
{
    protected $command;

    /**
     * @return array
     */
    protected function helperTaskOptions()
    {
        return array_sort([
            'command_line',
            'customs',
            'link_current',
            'link_files',
            'make_dirs',
            'copy_files',
            'share_dirs',
            'unpack_artifact',
        ]);
    }

    /**
     * @return array
     */
    protected function taskClassOptions()
    {
        $tasks = array_reduce(scandir(BASE_PATH . '/app/Tasks'), function ($list, $file) {
            if (!in_array($file, ['.', '..', 'BaseTask.php'])) {
                $list[] = str_replace('.php', '', $file);
            }

            return $list;
        }, []);

        return array_sort($tasks);
    }

    /**
     * @param string $className
     * @return array
     */
    public function getTaskParams(string $className): array
    {
        $parameters = (new \ReflectionClass('Lazzier\\Tasks\\' . $className))
            ->getConstructor()
            ->getParameters();

        return array_map(function ($arg) {
            return [
                'name' => $arg->name,
                'default' => $arg->isDefaultValueAvailable() ? $arg->getDefaultValue() : null,
                'required' => !($arg->isDefaultValueAvailable() || $arg->allowsNull()),
            ];
        }, $parameters);
    }

    /**
     * @param string $section
     * @return bool
     */
    protected function shouldAddNewTask(string $section)
    {
        return $this->command->confirm(
            sprintf(config('questions.add_task.question'), $section),
            config('questions.add_task.default')
        );
    }

    /**
     * @param string $section
     * @param callable $questions
     * @return array
     */
    protected function askWhile(string $section, callable $questions): array
    {
        $task = [$section => []];

        do {
            $task[$section][] = $questions();
            $addTask = $this->shouldAddNewTask($section);
        } while ($addTask);

        return $task;
    }

    /**
     * @param string $questionKey
     * @param string|null $section
     * @return string
     */
    protected function askFor(string $questionKey, string $section = null): string
    {
        $prefix = !empty($section) ? "[$section] " : "";

        return $this->command->ask(
            sprintf(config("questions.$questionKey.question"), $prefix),
            config("questions.$questionKey.default")
        );
    }

    /**
     * @param string $taskName
     * @param array $parameters
     * @return array
     */
    protected function askTaskParams(string $taskName, array $parameters): array
    {
        $task = ['task' => $taskName, 'args' => []];

        foreach ($parameters as $arg) {
            ['name' => $name, 'default' => $default, 'required' => $required] = $arg;
            $question = sprintf(config("questions.enter_param.question"), $taskName, $name);

            $task['args'][$name] = $this->command->ask($question, $required ? null : $default);
        }

        return $task;
    }

    /**
     * @param string $section
     * @param array $choices
     * @return array
     */
    public function selectTaskForSection(string $section, array $choices)
    {
        $selections = $this->command->select(
            'Select type of steps for ' . $section,
            $choices,
            false
        );

        return !empty($selections) ? $selections[0] : null;
    }
}
