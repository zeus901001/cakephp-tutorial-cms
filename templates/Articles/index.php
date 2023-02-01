<h1>Articles</h1>
<?=$this->Html->link('Add Article', ['action' => 'add'])?>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article): ?>
            <tr>
                <td><?=$this->Html->link($article->title, ['action' => 'view', $article->slug])?></td>
                <td><?=$article->created->format('Y-M-d H:i:s')?></td>
                <td><?=$article->modified->format('Y-M-d H:i:s')?></td>
                <td><?=$this->Html->link('Edit', ['action' => 'edit', $article->slug])?></td>
                <td><?=$this->Form->postLink('Delete', ['action' => 'delete', $article->slug], ['confirm' => 'Are you sure ?'])?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>