<?php

class Prescription {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        echo "\nNew Prescription object created.\n";
    }

    public function insert($visitID, $medicine, $quantity, $dose, $refillable) {
        $sql = "INSERT INTO prescriptions (visitID, medicine, quantity, dose, refillable) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isisi", $visitID, $medicine, $quantity, $dose, $refillable);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function update($prescriptionID, $visitID, $medicine, $quantity, $dose, $refillable) {
        $sql = "UPDATE prescriptions SET visitID=?, medicine=?, quantity=?, dose=?, refillable=? WHERE prescriptionID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isisis", $visitID, $medicine, $quantity, $dose, $refillable, $prescriptionID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function delete($prescriptionID) {
        $sql = "DELETE FROM prescriptions WHERE prescriptionID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $prescriptionID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function selectAll() {
        $sql = "SELECT * FROM prescriptions";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            $prescriptions = array();
            while ($row = $result->fetch_assoc()) {
                $prescriptions[] = $row;
            }
            return $prescriptions;
        } else {
            return false;
        }
    }
    
    public function selectByDoctor($doctorID) {
    $sql = "SELECT prescriptions.*, patients.firstName, patients.lastName
            FROM prescriptions
            INNER JOIN visits ON prescriptions.visitID = visits.visitID
            INNER JOIN patients ON visits.patientID = patients.patientID
            WHERE visits.doctorID=?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $doctorID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $prescriptions = array();
        while ($row = $result->fetch_assoc()) {
            $prescriptions[] = $row;
        }
        return $prescriptions;
    } else {
        return false;
    }
}


    public function selectOne($prescriptionID) {
        $sql = "SELECT * FROM prescriptions WHERE prescriptionID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $prescriptionID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

}
