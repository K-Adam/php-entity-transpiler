<?php

namespace EntityTranspiler\UserInterface;

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use EntityTranspiler\Framework;

class CommandLine extends CLI {

    protected function setup(Options $options)
    {
        $options->setHelp('Php entity transpiler');

        $options->registerOption('config', 'config file', 'c', 'path');

    }

    protected function main(Options $options)
    {
        $framework = Framework::create(require($options->getOpt('config')));
        $framework->execute();
        $this->info("Success!");
    }

}
