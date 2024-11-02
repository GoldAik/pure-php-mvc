{% load_layout "posts/_inc/base" %}

{% open title %} Posts {% close title %}

{% open content %}

    <?php if(isset($data['success'])) { ?>

        <script>

            <?php if($data['success'] === true) { ?>
                alert('Post was added');
            <?php } ?>

            <?php if($data['success'] === false) { ?>
                alert('Something went wrong');
            <?php } ?>

        </script>

    <?php } ?>

    <?php $content = $data['content'] ?? '' ?>

    <h1>POSTS</h1>

    <form action="" method="post">

        <textarea name="content"><?= htmlspecialchars($content) ?></textarea>

        <button type="submit"> Save </button>

        <?= App\Utils\CSRFTokenHandler::HTML() ?>
    </form>


{% close content %}
