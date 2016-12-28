<?php

namespace Interpro\Scalar;

use Interpro\Core\Taxonomy\Collections\ManifestsCollection;

class ConfigInterpreter
{
    public function interpretConfig(array $config)
    {
        $manifests = new ManifestsCollection();

        $family = 'scalar';

        foreach($config as $name => $descr)
        {
            $man = new \Interpro\Core\Taxonomy\Manifests\CTypeManifest($family, $name, [], []);

            $manifests->addManifest($man);
        }

        return $manifests;
    }
}
