<?php
/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2016-04-01
 */

$yml = <<<YML
# behat.yml

default:
    autoload:
        '': %paths.base%/tests/behat/contexts
    suites:
        default:
          paths: [ %paths.base%/tests/behat ]
          contexts:
            - FeatureContext
    extensions:
        Behat\MinkExtension:
            base_url: https://develop.vagrant.dev
            browser_name: 'chrome'
            selenium2:
              wd_host: "http://selenium.dev:4444/wd/hub"

firefox:
    extensions:
        Behat\MinkExtension:
          browser_name: 'firefox'

chrome:
    extensions:
        Behat\MinkExtension:
          browser_name: 'chrome'

YML;

return [
    'name' => 'behat',
    'validation' =>[
        'files' =>[
            'behat.yml'
        ]
    ],
    'init' => [
        'dirs' => [
            './tests/behat'
        ],
        'files' => [
            'behat.yml' => $yml
        ]
    ],
    'ideHelperClasses' => [
        'LinusShops\Contexts\Web'
    ]
];
