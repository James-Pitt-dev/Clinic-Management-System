<?php
class Staff {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        echo "\nNew Staff object created.\n";
    }

    //Staff create.
    public function insert($firstName, $lastName, $email, $phone, $role) {
        $sql = "INSERT INTO staff (firstName, lastName, email, phone, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    //Select individual staff
    public function selectOne($staffID) {
        $sql = "SELECT * FROM staff WHERE StaffID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $staffID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function update($staffID, $firstName, $lastName, $email, $phone) {
        $sql = "UPDATE staff SET FirstName=?, LastName=?, Email=?, Phone=? WHERE StaffID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $phone, $staffID);

        $sql2 = "UPDATE staff_login SET email=? WHERE staffID=?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("si", $email, $staffID);

        if ($stmt->execute() && $stmt2->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
        $stmt2->close();
     }

     //We can just have a link to patientRegister page. Method not needed.
    public function insertPatient($firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO patients (firstName, lastName, email, phone, homeAddress, healthCardNumber) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber);
        if ($stmt->execute()) {
            $patientID = $stmt->insert_id;
            $sql2 = "INSERT INTO patient_login (patientID, password, email) VALUES (?, ?, ?)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bind_param("iss", $patientID, $hashedPassword, $email);
            if ($stmt2->execute()) {
                return true;
            } else {
                return false;
            }
            $stmt2->close();
        } else {
            return false;
        }
        $stmt->close();
    }

    //We can just have a link to doctorRegister page. Method not needed.
    public function insertDoctor($firstName, $lastName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO doctors (firstName, lastName, email) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $firstName, $lastName, $email);
        if ($stmt->execute()) {
            $doctorID = $stmt->insert_id;
            $sql2 = "INSERT INTO doctor_login (doctorID, password, email) VALUES (?, ?, ?)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bind_param("iss", $doctorID, $hashedPassword, $email);
            if ($stmt2->execute()) {
                return true;
            } else {
                return false;
            }
            $stmt2->close();
        } else {
            return false;
        }
        $stmt->close();
    }

    public function deleteAccount($accountType, $accountID) {
      $sql = "DELETE FROM $accountType WHERE {$accountType}ID=?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $accountID);
    if ($stmt->execute()) {
        // If account deleted successfully, delete corresponding entry in login table
        $loginTable = $accountType."_login";
        $sql2 = "DELETE FROM $loginTable WHERE $accountType"."ID=?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("i", $accountID);
        if ($stmt2->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt2->close();
    } else {
        return false;
    }
    $stmt->close();
}

    public function approvePatientAccount($patientID) {
        $sql = "UPDATE patients SET approved=1 WHERE patientID=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function createReport($startDate, $endDate) {
        $sql = "SELECT patients.firstName, patients.lastName, clinical_records.date, clinical_records.doctorID, clinical_records.diagnosis, clinical_records.prescription FROM clinical_records INNER JOIN patients ON clinical_records.patientID=patients.patientID WHERE clinical_records.date BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $records = array();
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
            return $records;
        } else {
            return false;
        }
        $stmt->close();
    }
  }
 ?>
