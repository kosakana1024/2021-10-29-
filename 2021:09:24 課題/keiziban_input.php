<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易掲示板</title>
</head>

<body>
    <form action="create_file.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>簡易掲示板（入力画面）</legend>
            <a href="keiziban_read.php">一覧画面</a>
            <a href="keiziban_logout.php">logout</a>
            <div>
                名前: <input type="text" name="named">
            </div>
            <div>
                投稿: <textarea name="contents" id="" cols="30" rows="10"></textarea>
            </div>
            <div>
                <input type="file" name="upfile" accept="image/*"capture="camera">
            </div>
            <div>
                <button>投稿する</button>
            </div>
        </fieldset>
    </form>
</body>

</html>