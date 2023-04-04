<?php
require_once '../database.php';

$res = $_GET['res'] ?? null;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if ($res === 'binhluan') {
            add_binhluan($objConn);
        }
        break;

    case 'PUT':
        if ($res === 'binhluan' && isset($_GET['id'])) {
            update_comment($objConn, $_GET['id']);
        }
        break;

    case 'GET':
        if ($res === 'binhluan' && isset($_GET['id'])) {
            get_comments($objConn, $_GET['id']);
        }
        break;

    case 'DELETE':
        if ($res === 'binhluan' && isset($_GET['id'])) {
            delete_comment($objConn, $_GET['id']);
        }
        break;

    default:
        http_response_code(405); // Phương thức không được phép
        die('Phương thức HTTP không được hỗ trợ');
}


function add_binhluan($objConn) {
    $id_truyen = isset($_REQUEST['id_truyen']) ? $_REQUEST['id_truyen'] : null;
    $id_user = isset($_REQUEST['id_user']) ? $_REQUEST['id_user'] : null;
    $noi_dung = isset($_REQUEST['noi_dung']) ? $_REQUEST['noi_dung'] : null;
    $ngay_gio = date('Y-m-d H:i:s');

    if (!$id_truyen || !$id_user || !$noi_dung) {
        die("Thiếu thông tin bình luận");
    }

    
    try {
        $stmt = $objConn->prepare("INSERT INTO `tb_binh_luan` (`id_truyen`, `id_user`, `noi_dung`, `ngay_gio`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_truyen, $id_user, $noi_dung, $ngay_gio]);

        echo "Bình luận được thêm thành công!";
    } catch (PDOException $e) {
        die('Lỗi khi thêm bình luận vào CSDL: ' . $e->getMessage());
    }
}



function update_comment($objConn, $id) {
    $noi_dung = isset($_REQUEST['noi_dung']) ? $_REQUEST['noi_dung'] : null;

    if (!$noi_dung) {
        die("Thiếu thông tin bình luận");
    }

    try {
        $stmt = $objConn->prepare("UPDATE `tb_binh_luan` SET `noi_dung` = ? WHERE `id` = ?");
        $stmt->execute([$noi_dung, $id]);

        echo "Bình luận được cập nhật thành công!";
    } catch (PDOException $e) {
        die('Lỗi khi cập nhật bình luận vào CSDL: ' . $e->getMessage());
    }
}



function get_comments($objConn, $id) {
    try {
        $stmt = $objConn->prepare("SELECT * FROM `tb_binh_luan` WHERE `id_truyen` = ?");
        $stmt->execute([$id]);

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($comments) > 0) {
            echo json_encode($comments);
        } else {
            echo "Không tìm thấy bình luận cho truyện có ID là $id";
        }
    } catch (PDOException $e) {
        die('Lỗi khi truy vấn CSDL: ' . $e->getMessage());
    }
}



function delete_comment($objConn, $id) {
    try {
        $stmt = $objConn->prepare("DELETE FROM `tb_binh_luan` WHERE `id` = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "Bình luận đã được xoá thành công!";
        } else {
            echo "Không tìm thấy bình luận có ID là $id";
        }
    } catch (PDOException $e) {
        die('Lỗi khi xoá bình luận từ CSDL: ' . $e->getMessage());
    }
}
?>