<?php

class Visit {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        echo "\nNew Visit object created.\n";
    }

    public function createVisit($patientID, $doctorID, $details) {
        $date = date("Y-m-d");
        $sql = "INSERT INTO VISITS (PatientID, DoctorID, Date, Details) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiss", $patientID, $doctorID, $date, $details);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function selectOne($visitID) {
    $sql = "SELECT * FROM VISITS WHERE VisitID=?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $visitID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

    public function getAllVisits() {
        $sql = "SELECT * FROM VISITS";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            $visits = array();
            while ($row = $result->fetch_assoc()) {
                $visits[] = $row;
            }
            return $visits;
        } else {
            return false;
        }
    }

    public function getVisitsByPatient($patientID) {
        $sql = "SELECT * FROM VISITS WHERE PatientID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $visits = array();
            while ($row = $result->fetch_assoc()) {
                $visits[] = $row;
            }
            return $visits;
        } else {
            return false;
        }
    }

    public function getVisitsByDoctor($doctorID) {
        $sql = "SELECT * FROM VISITS WHERE DoctorID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $doctorID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $visits = array();
            while ($row = $result->fetch_assoc()) {
                $visits[] = $row;
            }
            return $visits;
        } else {
            return false;
        }
    }
}

?>
