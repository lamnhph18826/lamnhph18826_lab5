<?php
require_once '../database.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_GET['res']) && $_GET['res'] == 'truyen') {
            add_truyen($objConn);
        }
        break;

    case 'PUT':
        if (isset($_GET['res']) && $_GET['res'] == 'truyen' && isset($_GET['id'])) {
            update_truyen($objConn);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['res']) && $_GET['res'] == 'truyen') {
            delete_truyen($objConn);
        }
        break;

    case 'GET':
        if (isset($_GET['res']) && $_GET['res'] == 'truyen') {
            if (isset($_GET['id'])) {
                // Tìm kiếm truyện
                find_truyen_id($objConn);
            } else {
                // Lấy danh sách truyện
                find_truyen($objConn);
            }
        }
        break;
    default:
        http_response_code(405); // Phương thức không được phép
        die('Phương thức HTTP không được hỗ trợ');
}


function add_truyen($objConn) {
    $ten_truyen = isset($_REQUEST['ten_truyen']) ? $_REQUEST['ten_truyen'] : null;
    $tac_gia = isset($_REQUEST['tac_gia']) ? $_REQUEST['tac_gia'] : null;
    $nam_xb = isset($_REQUEST['nam_xb']) ? $_REQUEST['nam_xb'] : null;
    $anh_bia = isset($_REQUEST['anh_bia']) ? $_REQUEST['anh_bia'] : null;

    if (!$ten_truyen || !$tac_gia || !$nam_xb || !$anh_bia) {
        die("Thiếu thông tin truyện");
    }

    
    try {
        $stmt = $objConn->prepare("INSERT INTO `tb_truyen` (`ten_truyen`, `tac_gia`, `nam_xb`, `anh_bia`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ten_truyen, $tac_gia, $nam_xb, $anh_bia]);

        echo "User added successfully!";
    } catch (PDOException $e) {
        die('Error adding user to database: ' . $e->getMessage());
    }
}


function update_truyen($objConn) {
    $ten_truyen = isset($_REQUEST['ten_truyen']) ? $_REQUEST['ten_truyen'] : null;
    $tac_gia = isset($_REQUEST['tac_gia']) ? $_REQUEST['tac_gia'] : null;
    $nam_xb = isset($_REQUEST['nam_xb']) ? $_REQUEST['nam_xb'] : null;
    $anh_bia = isset($_REQUEST['anh_bia']) ? $_REQUEST['anh_bia'] : null;

    if (!$ten_truyen || !$tac_gia || !$nam_xb || !$anh_bia) {
        die("Thiếu thông tin truyện");
    }

    try {
        $sql_str = "UPDATE `tb_truyen` SET `ten_truyen`=?, `tac_gia`=?, `nam_xb`=?, `anh_bia`=? WHERE `id`=?";
        $stmt = $objConn->prepare($sql_str);

        $stmt->execute([$ten_truyen, $tac_gia, $nam_xb, $anh_bia, $_REQUEST['id']]);

        if ($stmt->rowCount() > 0) {
            echo "Cập nhật thông tin truyện thành công!";
        } else {
            echo "Cập nhật thất bại!";
        }

    } catch (PDOException $e) {
        die('Lỗi khi cập nhật CSDL do: ' . $e->getMessage());
    }
}

    
    function delete_truyen($objConn) {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$id) {
            die("Thiếu thông tin truyện cần xoá");
        }
        
        try {
            $stmt = $objConn->prepare("DELETE FROM `tb_truyen` WHERE `id`=?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
            echo "Xoá truyện thành công!";
            } else {
            echo "Không tìm thấy truyện để xoá!";
            }

        } catch (PDOException $e) {
        die('Lỗi khi xoá truyện: ' . $e->getMessage());
        }

        } 

        function find_truyen($objConn) {
            try {
                $stmt = $objConn->query("SELECT * FROM `tb_truyen`");
                $comics = $stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode($comics);
                } catch (PDOException $e) {
                die('Lỗi khi lấy danh sách người dùng từ CSDL: ' . $e->getMessage());
                } catch (Exception $e) {
                die('Lỗi không xác định: ' . $e->getMessage());
                }
        
            }

        //
        function find_truyen_id($objConn) {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            
                if (!$id) {
                die("Thiếu thông tin truyện cần tìm kiếm");
                }
            
                try {
                $stmt = $objConn->prepare("SELECT * FROM `tb_truyen` WHERE `id`=?");
                $stmt->execute([$id]);
            
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if ($user) {
                    header('Content-Type: application/json');
                    echo json_encode($user);
                } else {
                    echo "Không tìm thấy truyện";
                }
                } catch (PDOException $e) {
                die('Lỗi khi tìm kiếm truyện: ' . $e->getMessage());
                }
            }
            

//  ?>
