<?php
require_once('./../../controller/TodoController.php');

$controller = new TodoController;
$todo_list = $controller->index();

session_start();
$error_msgs = $_SESSION['error_msgs'];
unset($_SESSION['error_msgs']);

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODOリスト</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./../../public/css/main.css">
</head>
<body>
    <h1>TODOリスト</h1>
    <div><a href="./new.php">新規作成</a></div>
    <?php if($todo_list): ?>
    <ul class="list-group">
        <?php foreach($todo_list as $todo):?>
        <li class="list-group-item">
            <label class="checkbox-label">
                <input type="checkbox" class="todo-checkbox" data-id="<?php echo $todo['id']; ?>" <?php if($todo['status']): ?>checked<?php endif; ?>>
            </label>
            <a href="./detail.php?todo_id=<?php echo $todo['id']; ?>"><?php echo $todo['title']; ?></a>: <span class="status"><?php echo $todo['display_status']; ?></span>
            <div class="delete-btn" data-id="<?php echo $todo['id']; ?>">
                <button>削除</button>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
    <?php else: ?>
        <p>データなし</p>
    <?php endif; ?>
    
    <?php if($error_msgs): ?>
        <div>
            <ul>
                <?php foreach ($error_msgs as $error_msg): ?> 
                    <li><?php echo $error_msg; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <script src="./../../public/js/jquery-3.5.1.min.js"></script>
    <script>
        $(".delete-btn").click(function() {
            let todo_id = $(this).data('id');

            if (confirm("削除しますがよろしいですか？ id:" + todo_id)) {
                $(".delete-btn").prop("disabled", true);
                let data = {};
                data.todo_id = todo_id;

                $.ajax({
                    url: './delete.php',
                    type: 'post',
                    data: data
                }).then(
                    function(data) {
                        let json = JSON.parse(data);
                        console.log("success", json);
                        if (json.result == 'success') {
                            window.location.href = "./index.php";
                        } else {
                            console.log("failed to delete.");
                            alert("failed to delete.");
                            $(".delete-btn").prop("disabled", false);
                        }
                    },
                    function() {
                        console.log("fail");
                        alert("fail");
                        $(".delete-btn").prop("disabled", false);
                    }
                );                
            }
        });

        $('.todo-checkbox').change(function() {
            let todo_id = $(this).data('id');
            let data = {};
            data.todo_id = todo_id;

            $.ajax({
                url: './update_status.php',
                type: 'post',
                data: data
            }).then(
                function(data) {
                    let json = JSON.parse(data);
                    console.log("success", json);
                    if (json.result == 'success') {
                        console.log("success");

                        let text = $(this).parent().parent().find('.status').text();
                        console.log(text);

                        if (text == '完了') {
                            text = '未完了';
                        } else if(text == '未完了') {
                            text = '完了';
                        }

                        $(this).parent().parent().find('.status').text(text);

                    } else {
                        console.log("failed to update status.");
                        alert("failed to update status.");
                    }
                    
                }.bind(this),
                function() {
                    console.log("fail");
                    alert("fail to ajax.");
                }
            );                
            
        });
    </script>
</body>
</html>