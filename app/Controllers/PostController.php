<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\RedirectResponse;
use Somecode\Framework\Http\Response;
use Somecode\Framework\Session\SessionInterface;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
        private SessionInterface $session
    ) {
    }

    public function index(): Response
    {
        $posts = $this->service->all();
        return $this->render('index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function show(int $id): Response
    {
        $post = $this->service->findOrFail($id);

        return $this->render('posts.html.twig', [
            'post' => $post,
        ]);
    }

    public function create(): Response
    {
        return $this->render('post_create.html.twig', []);
    }

    public function store()
    {
        $post = Post::create(
            $this->request->input('title'),
            $this->request->input('body'),
        );
        $post = $this->service->save($post);

        $this->request->getSession()->setFlash('success', 'Post created successfully');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
