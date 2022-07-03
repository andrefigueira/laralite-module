<?php

namespace Modules\Laralite\Http\Requests;

use Closure;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Laralite\Filters\Condition;

class FilterableRequest extends FormRequest implements FilterableInterface
{
    protected array $filteredData = [];

    public function getFilteredData(): array
    {
        return $this->filteredData;
    }

    /**
     * @throws Exception
     */
    public function filterData()
    {
        if (!$filters = $this->filters()) {
            return;
        }

        $this->filteredData = $this->getInputSource()->all();
        foreach ($filters as $key => $filter) {
            $filterArray = is_string($filter) ? explode('|', $filter) : $filter;
            $keys = explode('.', $key);
            $this->processValue($keys, $this->filteredData, $filterArray);
        }

        $this->merge($this->filteredData);
    }

    /**
     * @param $value
     * @param $filters
     * @return mixed
     * @throws Exception
     */
    private function processFilters($value, $filters)
    {
        foreach ($filters as $key => $item) {
            $item = is_string($item) ? explode(':', $item) : $item;
            $arguments = [];

            $arguments[] = $value;
            if (!is_callable($item[0])) {
                throw new Exception('invalid filter rule: ' . $item[0]);
            }

            if (count($item) > 1) {
                $secondaryArgs = explode(',', $item[1]);
                foreach ($secondaryArgs as $secondaryArg) {
                    $arguments[] = $secondaryArg;
                }
            }
            $filterResult = $item[0](...$arguments);
            $value = $filterResult;
        }

        return $value;
    }

    /**
     * @param array $paths
     * @param array $value
     * @param array $filters
     * @throws Exception
     */
    private function processValue(array $paths, array &$value, array $filters): void
    {
        if (!$this->conditionMet($filters)) {
            return;
        }
        $nextValue = null;
        $count = count($paths);
        foreach ($paths as $i => $path) {
            if ($path === '*') {
                $newPaths = array_splice($paths, $i+1, count($paths));
                foreach ($value as $num => &$childValues) {
                    $this->processValue($newPaths, $childValues, $filters);
                    break;
                }
            }

            if (isset($value[$path]) && $i < $count-1) {
                $value = &$value[$path];
                continue;
            }

            if (isset($value[$path]) && $i === $count-1) {
                $value[$path] = $this->processFilters($value[$path], $filters);
            }
        }
    }

    private function conditionMet(array &$filters): bool
    {
        if (isset($filters[0]) && !$filters[0] instanceof Condition) {
            return true;
        }
        $condition = $filters[0];
        array_shift($filters);
        return $condition->filterIf();
    }

    /**
     * set/return a nested array value
     *
     * @param array $array the array to modify
     * @param array $path  the path to the value
     * @param mixed $value (optional) value to set
     *
     * @return array|string|int|object
     */
    private function arrayPath(array &$array, $path = array(), &$value = null)
    {
        $ref = &$array;
        foreach ($path as $key) {
            if (!is_array($ref)) {
                $ref = array();
            }
            $ref = &$ref[$key];
        }
        $prev = $ref;
        if (null !== $value) {
            $ref = $value;
        }
        return $prev;
    }

    /**
     * @throws Exception
     */
    public function prepareForValidation(): void
    {
        $this->filterData();
    }

    public function filters(): array
    {
        return [];
    }

    /**
     * @param Closure $callback
     * @return Condition
     */
    protected function getConditionRule(Closure $callback): Condition
    {
        return new Condition($callback);
    }
}