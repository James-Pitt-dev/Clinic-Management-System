<?php

class Patient {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        echo "\nNew Patient object created.\n";
    }

    public function insert($firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber, $role) {
        $sql = "INSERT INTO patients (firstName, lastName, email, phone, homeAddress, healthCardNumber, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssis", $firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function update($patientID, $firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber) {
        $sql = "UPDATE patients SET firstName=?, lastName=?, email=?, phone=?, homeAddress=?, healthCardNumber=? WHERE patientID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssis", $firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber, $patientID);

        //SQL statement to update email in patient_login table
   $sql2 = "UPDATE patient_login SET email=? WHERE patientID=?";
   $stmt2 = $this->db->prepare($sql2);
   $stmt2->bind_param("si", $email, $patientID);

   if ($stmt->execute() && $stmt2->execute()) {
       return true;
   } else {
       return false;
   }
   $stmt->close();
   $stmt2->close();
}
    //     if ($stmt->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    //     $stmt->close();
    // }

    public function delete($patientID) {
        $sql = "DELETE FROM patients WHERE patientID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function selectAll() {
        $sql = "SELECT * FROM patients";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            $patients = array();
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
            return $patients;
        } else {
            return false;
        }
    }

    public function selectOne($patientID) {
        $sql = "SELECT * FROM patients WHERE patientID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function isApproved($patientID) {
        $sql = "SELECT approval FROM patients WHERE patientID=? AND approval=1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function selectAllApproved() {
    $sql = "SELECT * FROM patients WHERE approval = 1";
    $result = $this->db->query($sql);
    if ($result->num_rows > 0) {
        $patients = array();
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        return $patients;
    } else {
        return false;
    }
}


    public function generateTopMedicinesReport() {
    $sql = "SELECT Medicine, COUNT(*) AS NumPrescriptions FROM PRESCRIPTIONS GROUP BY Medicine ORDER BY NumPrescriptions DESC LIMIT 3";
    $result = $this->db->query($sql);
    if ($result->num_rows > 0) {
        $medicines = array();
        while ($row = $result->fetch_assoc()) {
            $medicines[] = $row;
        }
        return $medicines;
    } else {
        return false;
    }
}

}
