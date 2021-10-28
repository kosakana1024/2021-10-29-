<?php
session_start();
include("functions.php");
check_session_id();

if (
    !isset($_POST['named']) || $_POST['named'] == '' ||
    !isset($_POST['contents']) || $_POST['contents'] == ''
    ) {
    echo json_encode(["error_msg" => "no input"]);
    exit();
}

$named = $_POST['named'];
$contents = $_POST['contents'];


// データの確認
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {

    // 情報の取得
    $uploaded_file_name = $_FILES['upfile']['name'];
    $temp_path  = $_FILES['upfile']['tmp_name'];
    $directory_path = 'upload/';

    // ファイル名の準備
    $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
    $unique_name = date('YmdHis').md5(session_id()) . '.' . $extension;
    $save_path = $directory_path . $unique_name;

    // 今回は画面に表示しないので権限の変更までで終了
    if (is_uploaded_file($temp_path)) {
        if (move_uploaded_file($temp_path, $save_path)) {
            chmod($save_path, 0644);




            $pdo = connect_to_db();

            $sql = 'INSERT INTO keiziban_table(id, named, contents, image, created_at, updated_at) VALUES(NULL, :named, :contents, :image, sysdate(), sysdate())';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':named', $named, PDO::PARAM_STR);
            $stmt->bindValue(':contents', $contents, PDO::PARAM_STR);
            $stmt->bindValue(':image', $save_path, PDO::PARAM_STR);
            $status = $stmt->execute();

            if ($status == false) {
                $error = $stmt->errorInfo();
                echo json_encode(["error_msg" => "{$error[2]}"]);
                exit();
            } else {
                header("Location:keiziban_input.php");
                exit();
            }
        } else {
            exit('Error:アップロードできませんでした');
        }
        } else {
        exit('Error:画像がありません');
        }
    } else {
        exit('Error:画像が送信されていません');
    }

