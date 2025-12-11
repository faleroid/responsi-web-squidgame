<?php
class UserModel
{
    private $conn;
    public function __construct($db_connection)
    {
        $this->conn = $db_connection;
    }
    public function findUser($email, $username = '')
    {
        $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            die("Error preparing statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $user;
    }
    public function createNewUser($username, $email, $password)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            die("Error preparing statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password_hash);
        $result = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        return $result;
    }
}