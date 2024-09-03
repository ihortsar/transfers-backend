<?php

class User
{
    public $id;
    public $email;
    public $password;
    public $name;
    public  $birthday;
    public $phone_number;
    public  $user_type;
    public $created_at;


    public static function authenticate($conn, $email, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user === false) {
                error_log("User not found for email: $email");
                return false;
            }
            if (password_verify($password, $user['password'])) {
                unset($user['password']);
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'authenticated',
                        'user' => $user
                    ]
                );
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }


    public static function create($conn)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data) {
            try {
                $stmt = $conn->prepare('INSERT INTO users (name, email, phone_number, user_type, password, created_at) 
                                    VALUES (:name, :email, :phone_number, :user_type, :password, :created_at)');
                $created_at = date('Y-m-d H:i:s');
                $hash = password_hash($data['password'], PASSWORD_DEFAULT);

                $stmt->bindParam(':name', $data['name']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':phone_number', $data['phone_number']);
                $stmt->bindParam(':user_type', $data['user_type']);
                $stmt->bindParam(':password', $hash);
                $stmt->bindParam(':created_at', $created_at);
                if ($stmt->execute()) {
                    $id = $conn->lastInsertId();
                    echo json_encode(['status' => 'success', 'message' => 'User created successfully.', 'user_id' => $id]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to create user.']);
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Database error.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        }
    }
}
