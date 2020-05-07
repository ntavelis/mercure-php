<?php

declare(strict_types=1);

namespace Ntavelis\Mercure;

class QueryBuilder
{
    private $message;

    public function __construct(\JsonSerializable $message)
    {
        $this->message = $message;
    }

    public function __toString(): string
    {
        return $this->buildQueryString($this->message->jsonSerialize());
    }

    private function buildQueryString(array $data, ?string $keyToUse = null): string
    {
        $queryParameters = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $queryParameters[] = $this->buildQueryString($value, $key);
                continue;
            }
            if ($value !== null) {
                $queryParameters[] = $this->urlEncodeKeyValue($keyToUse ?? $key, $value);
                continue;
            }
        }
        return $this->implodeQueryParameters($queryParameters);
    }

    private function urlEncodeKeyValue(string $key, string $value): string
    {
        return sprintf('%s=%s', $key, urlencode($value));
    }

    private function implodeQueryParameters(array $parts): string
    {
        return implode('&', $parts);
    }
}
