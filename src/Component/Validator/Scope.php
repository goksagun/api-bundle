<?php

namespace Goksagun\ApiBundle\Component\Validator;

enum Scope: string
{
    case REQUEST = 'request';
    case QUERY = 'query';
    case ATTRIBUTES = 'attributes';
    case HEADERS = 'headers';
}
