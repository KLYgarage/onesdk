<?php
namespace One;

class Factory
{
    public function Uri($string)
    {
        return createUriFromString($string);
    }
    public function Article($data)
    {
        return createArticleFromArray($data);
    }
}
