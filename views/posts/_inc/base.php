<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/media/css/posts/base.css">

    <?php if(file_exists(VIEW_PATH .'/posts/_inc/metadata.php')) include_once VIEW_PATH .'/posts/_inc/metadata.php' ?>

    {% open head %}
    {% close head %}
    
    <title>{% open title %} MVC {% close title %}</title>
</head>

<body>
    {% open header %}
    <?php if(file_exists(VIEW_PATH .'/posts/_inc/header.php')) include_once VIEW_PATH .'/posts/_inc/header.php' ?>
    {% close header %}


    {% open content %}
        <main id="main" class="main">
            <h1>Can be overwritten</h1>
        </main>
    {% close content %}

    {% open footer %}
    <?php if(file_exists(VIEW_PATH .'/posts/_inc/footer.php')) include_once VIEW_PATH .'/posts/_inc/footer.php' ?>
    {% close footer %}

</body>
</html>