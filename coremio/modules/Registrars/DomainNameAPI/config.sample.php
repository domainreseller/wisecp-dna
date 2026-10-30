<?php

return [
    'meta'     => [
        'name'    => 'DomainNameAPI',
        'version' => '4.0.5',
        'logo'    => 'logo.png',
    ],
    'settings' => [
        'whois-types'      => true,
        'dns-record-types' => [
            'A',
            'AAAA',
            'ALIAS',
            'CAA',
            'CNAME',
            'DS',
            'MX',
            'NAPTR',
            'PTR',
            'SRV',
            'TLSA',
            'TXT',
        ],
        'username'         => '',
        'password'         => '',
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
