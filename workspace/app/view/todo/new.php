<?php

    require_once('./../../controller/TodoController.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new TodoController();
        $controller->new();
        exit;
    }

    $title = '';
    $detail = '';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
        }

        if (isset($_GET['detail'])) {
            $detail = $_GET['detail'];
        }

    }

    session_start();
    $error_msgs = $_SESSION['error_msgs'];
    unset($_SESSION['error_msgs']);

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規作成</title>
</head>
<body>
    <h1>新規作成</h1>
    <form action="./new.php" method="post">
        <div>
            <div>タイトル</div>
            <input name="title" type="text" value="<?php echo $title; ?>">
        </div>
        <div>
            <div>詳細</div>
            <textarea name="detail"><?php echo $detail; ?></textarea>
        </div>
        <button type="submit">登録</button>
    </form>
    <?php if($error_msgs): ?>
        <div>
            <ul>
                <?php foreach ($error_msgs as $error_msg): ?> 
                    <li><?php echo $error_msg; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</body>
</html>