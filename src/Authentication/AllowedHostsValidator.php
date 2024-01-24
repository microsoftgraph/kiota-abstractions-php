<?php
/**
 * Copyright (c) Microsoft Corporation.  All Rights Reserved.
 * Licensed under the MIT License.  See License in the project root
 * for license information.
 */


namespace Microsoft\Kiota\Abstractions\Authentication;

/**
 * Class AllowedHostsValidator
 *
 * Maintains a list of valid hosts and allows authentication providers to check whether a host is valid before authenticating a request
 *
 * @package Microsoft\Kiota\Abstractions\Authentication
 * @copyright 2022 Microsoft Corporation
 * @license https://opensource.org/licenses/MIT MIT License
 * @link https://developer.microsoft.com/graph
 */
class AllowedHostsValidator
{
    /**
     * @var array<string, true> ["hostname" => true] Dictionary of allowed hosts for efficient lookups
     */
    private array $allowedHosts = [];

    /**
     * @param string[] $allowedHosts (containing scheme and host)
     */
    public function __construct(array $allowedHosts = [])
    {
        $this->setAllowedHosts($allowedHosts);
    }

    /**
     * @param string[] $hosts (containing scheme and host)
     */
    public function setAllowedHosts(array $hosts): void
    {
        foreach ($hosts as $host) {
            $newHost = $host;
            if (str_starts_with($host, "https://")) {
                $newHost = substr($host, 8);
            } else if (str_starts_with($host, 'http://')) {
                $newHost = substr($host, 7);
            }
            $newHost = strtolower(trim($newHost));
            if (!array_key_exists($newHost, $this->allowedHosts)) {
                $this->allowedHosts[$newHost] = true;
            }
        }
    }

    /**
     * @return string[]
     */
    public function getAllowedHosts(): array
    {
        return array_keys($this->allowedHosts);
    }

    /**
     * Returns true if no allowed hosts are specified OR if $url is valid & an allowed host
     * @param string $url
     * @return bool
     */
    public function isUrlHostValid(string $url): bool
    {
        return empty($this->allowedHosts) || array_key_exists($this->extractHost($url), $this->allowedHosts);
    }

    /**
     * Extracts the host name from $url
     *
     * @param string $url
     * @return string
     */
    private function extractHost(string $url): string
    {
        $urlParts = parse_url($url);
        if (!$urlParts) {
            throw new \InvalidArgumentException("$url is malformed");
        } else if (array_key_exists("host", $urlParts)) {
            return strtolower(trim( $urlParts["host"]));
        } else {
            throw new \InvalidArgumentException("$url must contain host");
        }
    }
}
