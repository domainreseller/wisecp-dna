<?php

return [
    'meta'     => [
        'name'    => 'DomainNameAPI',
        'version' => '1.18.10',
        'logo'    => 'logo.png',
    ],
    'settings' => [
        'whois-types'      => true,
        'username'         => 'user.domainnameapi',
        'password'         => '********',
        'test-mode'        => 0,
        'whidden-amount'   => 0,
        'whidden-currency' => 4,
        'cost-currency'    => 4,
        'adp'              => true,
        'exclude'          => '',
        'dom-cache-ttl'    => 1024,
        'periodic-sync'    => false,
        'sync-count'       => 5,
        'sync-delay'       => 86400
    ],
];
