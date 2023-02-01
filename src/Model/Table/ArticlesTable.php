<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;
use Cake\ORM\Query;

class ArticlesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');

        $this->belongsToMany('Tags', ['joinTable' => 'articles_tags', 'dependent' => true]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title')->minLength('title', 5)->maxLength('title', 255)
            ->notEmptyString('body')->minLength('body', 10);

        return $validator;
    }

    public function beforeSave(EventInterface $event, $entity, $options): void
    {
        if ($entity->tag_string)
            $entity->tags = $this->_buildTags($entity->tag_string);

        $sluggedTitle = Text::slug($entity->title);
        $entity->slug = substr($sluggedTitle, 0, 255);
    }

    public function findTagged(Query $query, array $options)
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title', 'Articles.body',
            'Articles.published', 'Articles.created', 'Articles.modified', 'Articles.slug'
        ];

        $query = $query->select($columns)->distinct($columns);
        if (empty($options['tags']))
            $query->leftJoinWith('Tags')->where(['Tags.title IS' => null]);
        else
            $query->innerJoinWith('Tags')->where(['Tags.title IN' => $options['tags']]);

        return $query->group(['Articles.id']);
    }

    protected function _buildTags($tagString)
    {
        $newTags = array_map('trim', explode(',', $tagString));
        $newTags = array_filter($newTags);
        $newTags = array_unique($newTags);

        $out = [];
        $tags = $this->Tags->find()->where(['Tags.title IN' => $newTags])->all();
        foreach ($tags as $tag) {
            $out[] = $tag;
        }
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }

        return $out;
    }
}