{% load_layout "posts/_inc/base" %}

{% open title %} Posts {% close title %}

{% open content %}
    <h1>POSTS</h1>

    <?php $posts = $data['posts'] ?? [] ?>

    <?php foreach($posts as $post) { ?>
        <div class="post">
            <span class="id">
                <?= htmlspecialchars($post->id ?? '') ?>
            </span>
            <span class="content">
                <?= htmlspecialchars($post->content ?? '') ?>
            </span>
            <span class="user-id">
                <?= htmlspecialchars($post->user_id ?? '') ?>
            </span>
        </div>
    <?php } ?>

{% close content %}
