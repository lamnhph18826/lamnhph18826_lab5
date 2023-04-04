<?php
require_once '../database.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($_GET['res']) && $_GET['res'] == 'users') {
            add_user($objConn);
        }
        break;

    case 'PUT':
        if (isset($_GET['res']) && $_GET['res'] == 'users' && isset($_GET['id'])) {
            update_user($objConn);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['res']) && $_GET['res'] == 'users') {
            delete_user($objConn);
        }
        break;

    case 'GET':
        if (isset($_GET['res']) && $_GET['res'] == 'users') {
            if (isset($_GET['id'])) {
                // Tìm kiếm người dùng
                find_users_id($objConn);
            } else {
                // Lấy danh sách người dùng
                find_users($objConn);
            }
        }
        break;
    default:
        http_response_code(405); // Phương thức không được phép
        die('Phương thức HTTP không được hỗ trợ');
}


function add_user($objConn) {
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
    $passwd = isset($_REQUEST['passwd']) ? $_REQUEST['passwd'] : null;
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $fullname = isset($_REQUEST['fullname']) ? $_REQUEST['fullname'] : null;

    if (!$username || !$passwd || !$email || !$fullname) {
        die("Thiếu thông tin người dùng");
    }

    try {
        $stmt = $objConn->prepare("INSERT INTO `tb_user` (`username`, `passwd`, `email`, `fullname`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $passwd, $email, $fullname]);

        echo "User added successfully!";
    } catch (PDOException $e) {
        die('Error adding user to database: ' . $e->getMessage());
    }
}


function update_user($objConn) {
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
    $passwd = isset($_REQUEST['passwd']) ? $_REQUEST['passwd'] : null;
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $fullname = isset($_REQUEST['fullname']) ? $_REQUEST['fullname'] : null;

    if (!$id || !$username || !$passwd || !$email || !$fullname) {
        die("Thiếu thông tin người dùng");
    }

    try {
        $sql_str = "UPDATE `tb_user` SET `username`=?, `passwd`=?, `email`=?, `fullname`=? WHERE `id`=?";
        $stmt = $objConn->prepare($sql_str);

        $stmt->execute([$username, $passwd, $email, $fullname, $id]);

        if ($stmt->rowCount() > 0) {
            echo "Cập nhật thông tin người dùng thành công!";
        } else {
            echo "Cập nhật thất bại!";
        }

    } catch (PDOException $e) {
        die('Lỗi khi cập nhật CSDL do: ' . $e->getMessage());
    }
}

    
    function delete_user($objConn) {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$id) {
            die("Thiếu thông tin người dùng cần xoá");
        }
        
        try {
            $stmt = $objConn->prepare("DELETE FROM `tb_user` WHERE `id`=?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
            echo "Xoá người dùng thành công!";
            } else {
            echo "Không tìm thấy người dùng để xoá!";
            }

        } catch (PDOException $e) {
        die('Lỗi khi xoá người dùng: ' . $e->getMessage());
        }

        } 

        function find_users($objConn) {
            try {
                $stmt = $objConn->query("SELECT * FROM `tb_user`");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode($users);
                } catch (PDOException $e) {
                die('Lỗi khi lấy danh sách người dùng từ CSDL: ' . $e->getMessage());
                } catch (Exception $e) {
                die('Lỗi không xác định: ' . $e->getMessage());
                }
        
            }
        

        //
        function find_users_id($objConn) {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            
                if (!$id) {
                die("Thiếu thông tin người dùng cần tìm kiếm");
                }
            
                try {
                $stmt = $objConn->prepare("SELECT * FROM `tb_user` WHERE `id`=?");
                $stmt->execute([$id]);
            
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if ($user) {
                    header('Content-Type: application/json');
                    echo json_encode($user);
                } else {
                    echo "Không tìm thấy người dùng";
                }
                } catch (PDOException $e) {
                die('Lỗi khi tìm kiếm người dùng: ' . $e->getMessage());
                }
            }
            

//  ?>
