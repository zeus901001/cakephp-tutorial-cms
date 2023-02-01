<h1><?=h($article->title)?></h1>
<p><?=h($article->body)?></p>
<p><b>Tags:</b> <?=h($article->tag_string)?></p>
<p><small>Created: <?=$article->created->format('Y-M-d H:i:s')?></small></p>
<p><small>Modified: <?=$article->modified->format('Y-M-d H:i:s')?></small></p>
<p><?=$this->Html->link('Edit', ['action' => 'edit', $article->slug])?></p>