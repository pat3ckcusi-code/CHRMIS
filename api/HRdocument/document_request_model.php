<?php
class DocumentRequestModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all requests (for HR / Front Office Clerk)
    public function getAllRequests() {
        $sql = "
            SELECT 
                r.id,
                r.EmpNo,
                r.document_type,
                r.purpose,
                r.requested_on,
                r.status,
                r.hr_notes AS remarks,
                e.Fname,
                e.Lname,
                e.EMail,
                e.Dept
            FROM document_requests r
            LEFT JOIN i e ON e.EmpNo = r.EmpNo
            ORDER BY r.requested_on DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single request by ID
    public function getRequestById($id) {
        $sql = "
            SELECT 
                r.id,
                r.EmpNo,
                r.document_type,
                r.purpose,
                r.requested_on,
                r.status,
                r.hr_notes AS remarks,
                e.Fname,
                e.Lname,
                e.Dept
            FROM document_requests r
            LEFT JOIN i e ON e.EmpNo = r.EmpNo
            WHERE r.id = :id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update request status
    public function updateStatus($id, $status, $note = null) {
        $sql = "UPDATE document_requests SET status = :status, hr_notes = :note WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':note' => $note,
            ':id' => $id
        ]);
    }


    // Create new request
    public function createRequest($empNo, $documentType, $purpose) {
        $sql = "INSERT INTO document_requests (EmpNo, document_type, purpose, requested_on, status) 
                VALUES (:empNo, :document_type, :purpose, NOW(), 'Requested')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':empNo' => $empNo,
            ':document_type' => $documentType,
            ':purpose' => $purpose
        ]);
    }

    public function logRequestAction($requestId, $actionBy, $action, $remarks = null) {
        $sql = "INSERT INTO document_requests_logs 
                (request_id, action_by, action, action_on, remarks)
                VALUES (:request_id, :action_by, :action, NOW(), :remarks)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':request_id' => $requestId,
            ':action_by' => $actionBy,
            ':action' => $action,
            ':remarks' => $remarks
        ]);
    }

    // Fetch single request WITH employee email (for email notifications)
    public function getRequestWithEmployeeEmail($id)
    {
        $sql = "
            SELECT 
                r.id,
                r.EmpNo,
                r.document_type,
                r.status,
                r.hr_notes AS remarks,
                e.Fname,
                e.Lname,
                e.EMail,
                e.Dept
            FROM document_requests r
            LEFT JOIN i e ON e.EmpNo = r.EmpNo
            WHERE r.id = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
