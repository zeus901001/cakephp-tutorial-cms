<?php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(): void
    {
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    public function view($slug = null): void
    {
        $article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();
        $this->set(compact('article'));
    }

    public function add(): void
    {
        $article = $this->Articles->newEmptyEntity();

        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->user_id = 1;
            if ($this->Articles->save($article)) {
                $this->Flash->success(__("Your article has been saved."));
                $this->redirect(['action' => 'index']);
                return;
            }
            $this->Flash->error(__('Unable to add your article.'));
        }

        $tags = $this->Articles->Tags->find('list')->all();

        $this->set(compact('tags', 'article'));
    }

    public function edit($slug): void
    {
        $article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                $this->redirect(['action' => 'index']);
                return;
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        $tags = $this->Articles->Tags->find('list')->all();

        $this->set(compact('tags', 'article'));
    }

    public function delete($slug): void
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            $this->redirect(['action' => 'index']);
            return;
        }
    }

    public function tags(): void
    {
        $tags = $this->request->getParam('pass');
        $articles = $this->Articles->find('tagged', compact('tags'))->all();
        $this->set(compact('articles', 'tags'));
    }
}