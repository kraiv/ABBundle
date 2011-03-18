<?php

namespace AB\ABBundle\Tests\Mock;

use AB\ABBundle\TestSuite\ABErrorUnavailableVersion;

use AB\ABBundle\TestSuite\ABErrorNoVersionSet;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;

class MockTestSuite implements ABTestSuiteInterface
{

    private $uid = null;

    private $scores = array();

    private $replace = array();

    public function __construct($uid, array $versions)
    {
        $this->uid = $uid;
        foreach ($versions as $version) {
            $this->replace[$version] = array();
            $this->scores[$version] = 0;
        }
    }

    public function addReplace($version, array $replace)
    {
        if (!in_array($version, $this->getAvailableVersions())) {
            throw new ABErrorUnavailableVersion();
        }

        $this->replace[$version] = array_merge($this->replace[$version], $replace);
    }

    public function getUID()
    {
        return $this->uid;
    }

    public function getAvailableVersions()
    {
        return array_keys($this->scores);
    }

    public function getResource($version, $resource)
    {
        if (!in_array($version, $this->getAvailableVersions())) {
            throw new ABErrorUnavailableVersion();
        }

        return @$this->replace[$version][$resource] ?: $resource;
    }

    public function addScore($version, $points = +1)
    {
        if (!in_array($version, $this->getAvailableVersions())) {
            throw new ABErrorUnavailableVersion();
        }

        $this->scores[$version] += $points;
    }

    public function getScores()
    {
        return $this->scores;
    }

}