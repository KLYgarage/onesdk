<?php
namespace One;

use One\Model\Article;

class FactoryArticle
{
    public function create(array $data)
    {
        $title = isset($data['title']) ? $data['title'] : '';
        $body = isset($data['body']) ? $data['body'] : '';
        $source = isset($data['source']) ? $data['source'] : '';
        $uniqueId = isset($data['unique_id']) ? $data['unique_id'] : '';
        $typeId = isset($data['type_id']) ? $data['type_id'] : null;
        $categoryId = isset($data['category_id']) ? $data['category_id'] : null;
        $reporter = isset($data['reporter']) ? $data['reporter'] : '';
        $lead = isset($data['lead']) ? $data['lead'] : '';
        $reporter = isset($data['reporter']) ? $data['reporter'] : '';
        $tags = isset($data['tags']) ? $data['tags'] : '';
        $publishedAt = isset($data['published_at']) ? $data['published_at'] : null;
        $identifier = isset($data['identifier']) ? $data['identifier'] : null;

        return new Article(
            $title,
            $body,
            $source,
            $uniqueId,
            $typeId,
            $categoryId,
            $reporter,
            $lead,
            $reporter,
            $tags,
            $publishedAt,
            $identifier
        );
    }
}
