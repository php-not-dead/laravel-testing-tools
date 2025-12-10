<?php

declare(strict_types=1);

namespace Tests\Helper;

use Illuminate\Support\Facades\File;

class ComposerLockBuilder
{
    private const string SOURCE_COMPOSER_LOCATION = 'tests/Fixtures/composer.lock.source';
    private const string TEMPORARY_COMPOSER_LOCATION = '/tmp/composer.lock';

    private array $fixturePackages;

    /**
     * @param array<array{name:string, version:string}> $packages
     * @param array<array{name:string, version:string}> $packagesDev
     * @return string
     */
    public function build(array $packages, array $packagesDev): string
    {
        $fixtureJson = File::get(self::SOURCE_COMPOSER_LOCATION);
        $fixture = json_decode($fixtureJson, true, 512, JSON_THROW_ON_ERROR);

        $composerLock = $fixture;
        $composerLock['packages'] = $this->generatePackagesInfo($packages, $fixture);
        $composerLock['packages-dev'] = $this->generatePackagesInfo($packagesDev, $fixture);
        $composerLock['stability-flags'] = $this->generateStabilityFlags($composerLock);

        $json = json_encode($composerLock, JSON_THROW_ON_ERROR);

        file_put_contents(self::TEMPORARY_COMPOSER_LOCATION, $json);

        return self::TEMPORARY_COMPOSER_LOCATION;
    }

    /**
     * @param array{name: 'string', version: 'string'} $packages
     * @return array
     */
    private function generatePackagesInfo(array $packages, array $fixture): array
    {
        if (empty($packages) === true) {
            return [];
        }

        if (isset($this->fixturePackages) === false) {
            $this->generateFixturePackages($fixture);
        }

        $result = [];
        foreach ($packages as $package) {
            $packageInfo = array_key_exists($package['name'], $this->fixturePackages) === true
                ? $this->fixturePackages[$package['name']]
                : $this->fixturePackages[array_rand($this->fixturePackages)];

            $packageInfo['name'] = $package['name'];
            $packageInfo['version'] = $package['version'];

            $result[] = $packageInfo;
        }

        return $result;
    }

    private function generateStabilityFlags(array $composerLock): array
    {
        $allPackages = array_merge($composerLock['packages'], $composerLock['packages-dev']);
        $unstablePackages = [];
        foreach ($allPackages as $package) {
            $pass = preg_match('/^v?\d{1,2}\.\d{1,4}(?:\.\d{1,4})?$/', $package['version']) === 1;
            if ($pass === true) {
                continue;
            }

            $unstablePackages[$package['name']] = 20;
        }

        return $unstablePackages;
    }

    private function generateFixturePackages(array $fixture): void
    {
        $allPackages = array_merge($fixture['packages'], $fixture['packages-dev']);
        foreach ($allPackages as $package) {
            $this->fixturePackages[$package['name']] = $package;
        }
    }
}
