<?php

class ApplicationModel
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function isSlotAvailable($positionId)
    {
        $stmt = $this->db->prepare("SELECT quota FROM positions WHERE id = ?");
        $stmt->execute([$positionId]);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row)
            return false;

        $quota = $row['quota'];

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE position_id = ? AND status = 'accepted'");
        $stmt->execute([$positionId]);
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $currentCount = $row[0];

        return $currentCount < $quota;
    }

    public function createApplication($data, $status)
    {
        $query = "INSERT INTO applications 
                  (user_id, position_id, debt_amount, reason_to_join, combat_skill, status) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        return $stmt->execute([
            $data['user_id'],
            $data['position_id'],
            $data['debt_amount'] ?? NULL,
            $data['reason'] ?? NULL,
            $data['combat_skill'] ?? NULL,
            $status
        ]);
    }

    public function hasActiveApplication($userId)
    {
        $stmt = $this->db->prepare("SELECT id FROM applications WHERE user_id = ? AND status IN ('pending', 'accepted')");
        $stmt->execute([$userId]);
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getApplication($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM applications WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$userId]);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getAllPendingApplications()
    {
        $query = "SELECT a.*, u.username, p.name as role_name 
                  FROM applications a
                  JOIN users u ON a.user_id = u.user_id
                  JOIN positions p ON a.position_id = p.id
                  WHERE a.status = 'pending'
                  ORDER BY a.created_at ASC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($applicationId, $newStatus)
    {
        $stmt = $this->db->prepare("UPDATE applications SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $applicationId]);
    }

    public function getStatusCounts()
    {
        // Initialize default counts
        $counts = [
            'total' => 0,
            'pending' => 0,
            'accepted' => 0, // Survivors
            'eliminated' => 0  // Eliminated
        ];

        $query = "SELECT status, COUNT(*) as count FROM applications GROUP BY status";
        $result = $this->db->query($query);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $count = (int) $row['count'];
                $counts['total'] += $count;

                if (isset($counts[$status])) {
                    $counts[$status] = $count;
                }
            }
        }

        return $counts;
    }

    public function getRoleCounts()
    {
        $query = "SELECT p.name as role_name, COUNT(a.id) as count 
                  FROM applications a
                  JOIN positions p ON a.position_id = p.id
                  GROUP BY p.name";

        $result = $this->db->query($query);
        $roleCounts = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $roleCounts[$row['role_name']] = (int) $row['count'];
            }
        }

        return $roleCounts;
    }

    public function getHistoryApplications()
    {
        $query = "SELECT a.*, u.username, p.name as role_name 
                  FROM applications a
                  JOIN users u ON a.user_id = u.user_id
                  JOIN positions p ON a.position_id = p.id
                  WHERE a.status IN ('accepted', 'eliminated')
                  ORDER BY a.created_at DESC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAcceptedApplications()
    {
        $query = "SELECT a.*, u.username, p.name as role_name 
                  FROM applications a
                  JOIN users u ON a.user_id = u.user_id
                  JOIN positions p ON a.position_id = p.id
                  WHERE a.status = 'accepted'
                  ORDER BY a.created_at DESC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}