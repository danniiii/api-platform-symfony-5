<?php

declare(strict_types=1);

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    /**
     * @return mixed|null
     */
    public static function getField(Request $request, string $fieldName, bool $isArray = false, bool $isRequired = true)
    {
        $requestData = json_decode($request->getContent(), true);

        if ($isArray) {
            $arrayData = self::arrayFlatten($requestData);

            foreach ($arrayData as $key => $value) {
                if ($fieldName === $key) {
                    return $value;
                }
            }
            if ($isRequired) {
                throw new BadRequestHttpException(sprintf('Field %s not found', $fieldName));
            }

            return null;
        }

        if (array_key_exists($fieldName, $requestData)) {
            return $requestData[$fieldName];
        }

        if ($isRequired) {
            throw new BadRequestHttpException(sprintf('Field %s not found', $fieldName));
        }

        return null;
    }

    public static function arrayFlatten(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::arrayFlatten($value));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
