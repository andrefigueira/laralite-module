<?php

namespace Modules\Laralite\Constant;

use InvalidArgumentException;

abstract class AbstractConstant
{
    public const PRIMARY_KEY = null;

    /**
     * @param string $conditionTypeName The name of the constant
     * @param bool $fromCamelCase Optional flag to convert the constant name from camelCase to CONSTANT_FORMAT.
     * @return mixed The value of the constant.
     * @throws InvalidArgumentException if the constant does not exist.
     */
    public static function getByName(string $conditionTypeName, bool $fromCamelCase = false)
    {
        $constants = self::getConstants();

        if ($fromCamelCase) {
            $conditionTypeName = static::camelCaseToConstant($conditionTypeName);
        }

        if (!isset($constants[$conditionTypeName])) {
            throw new InvalidArgumentException(
                'Wrong ' . self::getReflection()->getShortName() . ' name: ' . $conditionTypeName
            );
        }

        return $constants[$conditionTypeName];
    }

    /**
     * @param mixed $constValue The value of the constant.
     * @param bool $toCamelCase Optional flag to convert the constant to camelCase format.
     * @return string
     * @throws InvalidArgumentException if the constant value doesn't exist.
     */
    public static function getByValue($constValue, bool $toCamelCase = false): string
    {
        $constants = self::getConstants();
        foreach ($constants as $name => $value) {
            if ($value === $constValue) {
                if ($toCamelCase) {
                    return static::constantToCamelCase($name);
                }
                return $name;
            }
        }
        throw new InvalidArgumentException(
            'Wrong ' . self::getReflection()->getShortName() . ' value: ' . $constValue
        );
    }

    /**
     * Method to get an array of all constants defined and their values.
     *
     * @param bool $toCamelCase Optional flag to get all constants in camelCase format.
     * @return array
     */
    public static function getConstants(bool $toCamelCase = false): array
    {
        $constants = self::getReflection()->getConstants();
        unset($constants['PRIMARY_KEY']);

        if (!$toCamelCase) {
            return $constants;
        }

        $camelCaseConstants = [];
        foreach ($constants as $key => $value) {
            $camelCaseConstants[static::constantToCamelCase($key)] = $value;
        }
        return $camelCaseConstants;
    }

    /**
     * Method to get an array of all constants defined and their values as a collection.
     *
     * @return array
     */
    public static function getConstantsList(): array
    {
        $constants = self::getReflection()->getConstants();
        unset($constants['PRIMARY_KEY']);

        $camelCaseConstants = [];
        foreach ($constants as $key => $value) {
            $camelCaseConstants[] = [
                'id' => $value,
                'name' => static::constantToTitleCase($key),
            ];
        }
        return $camelCaseConstants;
    }

    /**
     * @param array $values
     * @param bool $toCamelCase
     * @return array
     */
    public static function getConstantsByValues(array $values, bool $toCamelCase = false): array
    {
        return array_intersect(static::getConstants($toCamelCase), $values);
    }

    /**
     * Get an array of values indexed numerically.
     *
     * @return array
     */
    public static function getConstantValues(): array
    {
        return array_values(static::getConstants());
    }

    /**
     * Method to get an array of all constant names.
     *
     * @param bool $toCamelCase Optional switch to get constant names in camelCase format.
     * @return array
     */
    public static function getConstantNames(bool $toCamelCase = false): array
    {
        return array_keys(self::getConstants($toCamelCase));
    }

    /**
     * @return \ReflectionClass
     */
    protected static function getReflection(): \ReflectionClass
    {
        return new \ReflectionClass(static::class);
    }

    /**
     * Takes a CONSTANT_FORMAT string, and converts it to camelCase i.e. constantFormat.
     *
     * @param string $key
     * @return string
     */
    public static function constantToCamelCase(string $key): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($key)))));
    }

    /**
     * Takes a CONSTANT_FORMAT string, and converts it to camelCase i.e. constantFormat.
     *
     * @param string $key
     * @return string
     */
    public static function constantToTitleCase(string $key): string
    {
        return ucwords(str_replace('_', ' ', strtolower($key)));
    }

    /**
     * Takes a camelCase format and converts it to constant format i.e. CAMEL_CASE
     *
     * @param string $string
     * @return string
     */
    public static function camelCaseToConstant(string $string): string
    {
        return strtoupper(ltrim(preg_replace('/[A-Z]/', '_$0', $string), '_'));
    }

    /**
     * @return string|null
     */
    public static function getPrimaryKey(): ?string
    {
        return static::PRIMARY_KEY;
    }
}